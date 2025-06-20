<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user', 'recipe')->paginate(15);
        return view('admin.comments.index', compact('comments'));
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
