<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::paginate(15);
        return view('admin.recipes.index', compact('recipes'));
    }

    public function edit(Recipe $recipe)
    {
        $ingredients = Ingredient::where('is_visible', true)->get();

        $recipeIngredients = $recipe->ingredients->map(function ($item) {
            return [
                'ingredient_id' => $item->id,
                'quantity' => $item->pivot->quantity,
                'unit' => $item->pivot->unit,
            ];
        })->toArray();


        return view('admin.recipes.edit', compact('recipe', 'ingredients', 'recipeIngredients'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'preparation_time' => 'required|integer|min:1',
            'is_visible' => 'required|boolean',
            'ingredients' => 'array',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|string',
            'ingredients.*.unit' => 'required|string',
        ]);

        // Zapisujemy pola główne
        $recipe->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructions' => $validated['instructions'],
            'preparation_time' => $validated['preparation_time'],
            'is_visible' => $validated['is_visible'],
        ]);

        // Przygotowujemy dane do synchronizacji
        $pivotData = [];
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                $pivotData[$ingredient['ingredient_id']] = [
                    'quantity' => $ingredient['quantity'],
                    'unit' => $ingredient['unit'],
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Synchronizujemy relację
        $recipe->ingredients()->sync($pivotData);

        return redirect()
            ->route('admin.recipes.index')
            ->with('success', 'Przepis został zaktualizowany wraz ze składnikami.');
    }

    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->is_visible = false;
        $recipe->save();

        return redirect()->route('admin.recipes.index')->with('success', 'Przepis został usunięty.');
    }
}
