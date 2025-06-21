<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $recipe->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->input('comment'),
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
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return redirect()->route('recipes.show', $comment->recipe_id)->with('success', 'Komentarz został zaktualizowany.');
    }

    public function destroy(Comment $comment)
    {
        // Sprawdzenie, czy to autor komentarza
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $recipeId = $comment->recipe_id;

        $comment->is_visible = false;
        $comment->save();

        return redirect()->route('recipes.show', $recipeId)
            ->with('success', 'Komentarz został usunięty.');
    }

    public function myComments()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Dostęp zabroniony.');
        }

        // Pobierz komentarze zalogowanego użytkownika z powiązanym przepisem
        $comments = $user->comments()
            ->where('is_visible', true)
            ->with('recipe')
            ->latest()
            ->paginate(10);

        return view('comments.mine', compact('comments'));
    }
}
