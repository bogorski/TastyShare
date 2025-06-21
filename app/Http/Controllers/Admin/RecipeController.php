<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\DietType;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $query = Recipe::with(['user', 'ingredients']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $filter = $request->input('filter', 'all');

            $query->where(function ($q) use ($search, $filter) {
                switch ($filter) {
                    case 'title':
                        $q->where('title', 'like', "%{$search}%");
                        break;
                    case 'description':
                        $q->where('description', 'like', "%{$search}%");
                        break;
                    case 'instructions':
                        $q->where('instructions', 'like', "%{$search}%");
                        break;
                    case 'author':
                        $q->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                        break;
                    case 'ingredient':
                        $q->whereHas('ingredients', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                        break;
                    default: // "all"
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('instructions', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('ingredients', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
                }
            });
        }

        $recipes = $query->paginate(15)->withQueryString();

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $categories = Category::all();
        $dietTypes = DietType::all();
        $ingredients = Ingredient::all();

        return view('recipes.create', compact('categories', 'dietTypes', 'ingredients'));
    }

    // Zapisz przepis do bazy
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => ['required', 'array', 'unique_ingredients'],
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:20',
            'instructions' => 'required|string',
            'preparation_time' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'diet_types' => 'required|array',
            'diet_types.*' => 'exists:diet_types,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('image/recipes', 'public');
        }

        $recipe = new Recipe();
        $recipe->title = $validated['title'];
        $recipe->description = $validated['description'] ?? null;
        $recipe->instructions = $validated['instructions'] ?? null;
        $recipe->preparation_time = $validated['preparation_time'] ?? null;
        $recipe->image = $imagePath ? '/storage/' . $imagePath : null;
        $recipe->user_id = auth()->id();
        $recipe->save();

        // Podłącz kategorie i typy diety
        $recipe->categories()->sync($validated['categories']);
        $recipe->dietTypes()->sync($validated['diet_types']);


        foreach ($validated['ingredients'] as $ingredient) {
            $recipe->ingredients()->attach($ingredient['ingredient_id'], [
                'quantity' => $ingredient['quantity'],
                'unit' => $ingredient['unit'],
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('recipes.show', $recipe->id)->with('success', 'Przepis dodany!');
    }

    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        $ingredients = Ingredient::where('is_visible', true)->get();
        $dietTypes = DietType::all();

        $recipeIngredients = $recipe->ingredients->map(function ($item) {
            return [
                'ingredient_id' => $item->id,
                'quantity' => $item->pivot->quantity,
                'unit' => $item->pivot->unit,
            ];
        })->toArray();


        return view('admin.recipes.edit', compact('recipe', 'categories', 'dietTypes', 'ingredients', 'recipeIngredients'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => ['required', 'array', 'unique_ingredients'],
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|string|max:50',
            'ingredients.*.unit' => 'required|string|max:20',
            'instructions' => 'required|string',
            'preparation_time' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'diet_types' => 'required|array',
            'diet_types.*' => 'exists:diet_types,id',
            'is_visible' => 'required|boolean'
        ]);

        // Obsługa zdjęcia
        if ($request->hasFile('image')) {
            // Usuń stare zdjęcie jeśli istnieje
            if ($recipe->image && file_exists(public_path($recipe->image))) {
                unlink(public_path($recipe->image));
            }

            // Zapisz nowe zdjęcie
            $imagePath = $request->file('image')->store('image/recipes', 'public');
            $recipe->image = '/storage/' . $imagePath;
        }

        // Aktualizacja pól
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

        // Relacje wiele-do-wielu
        $recipe->categories()->sync($validated['categories']);
        $recipe->dietTypes()->sync($validated['diet_types']);

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
