<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Recipe;
use App\Models\Comment;


class CommentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Sprawdź czy użytkownik już dodał komentarz
        $existingComment = $recipe->comments()->where('user_id', auth()->id())->first();
        if ($existingComment) {
            return redirect()->back()->with('error', 'Możesz dodać tylko jeden komentarz do danego przepisu.');
        }

        $recipe->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
        ]);

        return redirect()->back()->with('success', 'Dziękujemy za komentarz!');
    }

    public function edit(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $comment->update([
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        return redirect()->route('recipes.show', $comment->recipe_id)->with('success', 'Komentarz został zaktualizowany.');
    }

    public function destroy(Comment $comment)
    {
        // upewnij się, że to autor komentarza
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $recipeId = $comment->recipe_id;
        $comment->delete();

        return redirect()->route('recipes.show', $recipeId)
            ->with('success', 'Komentarz został usunięty.');
    }
}
