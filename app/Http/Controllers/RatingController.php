<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Recipe;
use App\Models\Rating;


class RatingController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Sprawdź czy użytkownik już dodał ocene
        $existingRating = $recipe->ratings()->where('user_id', auth()->id())->first();
        if ($existingRating) {
            return redirect()->back()->with('error', 'Możesz dodać tylko jedną ocenę do danego przepisu.');
        }

        $recipe->ratings()->create([
            'user_id' => auth()->id(),
            'rating' => $request->input('rating'),
        ]);

        return redirect()->back()->with('success', 'Dziękujemy za ocene!');
    }

    public function edit(Rating $rating)
    {
        if (auth()->id() !== $rating->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        return view('ratings.edit', compact('rating'));
    }

    public function update(Request $request, Rating $rating)
    {
        if (auth()->id() !== $rating->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $rating->update([
            'rating' => $request->rating,
        ]);

        return redirect()->route('recipes.show', $rating->recipe_id)->with('success', 'Ocena została zaktualizowana.');
    }

    public function destroy(Rating $rating)
    {
        // upewnij się, że to autor komentarza
        if (auth()->id() !== $rating->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $rating->is_visible = false;
        $rating->save();

        return redirect()->route('recipes.show', $rating->recipe_id)
            ->with('success', 'Ocena została usunięta.');
    }
}
