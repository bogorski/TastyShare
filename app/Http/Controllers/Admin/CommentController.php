<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $comments = Comment::with(['user', 'recipe'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('recipe', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                })->orWhere('comment', 'like', '%' . $search . '%');
            })
            ->paginate(15);

        return view('admin.comments.index', compact('comments', 'search'));
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'comment' => 'required|string',
            'is_visible' => 'required|boolean',
        ]);

        $comment->update($request->only(['comment', 'is_visible']));

        return redirect()->route('admin.comments.index')->with('success', 'Komentarz zaktualizowany.');
    }



    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->is_visible = false;
        $comment->save();

        return redirect()->route('admin.comments.index')->with('success', 'Komentarz został usunięty.');
    }
}
