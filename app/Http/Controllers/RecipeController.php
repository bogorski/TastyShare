<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::all();

        return view('recipes.index', compact('recipes'));
    }

    public function show($id)
    {
        // Sprawdzenie, czy przepis istnieje
        $recipe = Recipe::findOrFail($id);

        $recipe->load(['user']);

        // Zwrócenie przepisu
        return view('recipes.show', compact('recipe'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $dietTypes = \App\Models\DietType::all();  // Pobierz typy diety

        return view('recipes.create', compact('categories', 'dietTypes'));
    }

    // Zapisz przepis do bazy
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'preparation_time' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'diet_types' => 'required|array', // ← zmienione z nullable
            'diet_types.*' => 'exists:diet_types,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = new Recipe();
        $recipe->title = $validated['title'];
        $recipe->description = $validated['description'] ?? null;
        $recipe->ingredients = $validated['ingredients'] ?? null;
        $recipe->instructions = $validated['instructions'] ?? null;
        $recipe->preparation_time = $validated['preparation_time'] ?? null;
        $recipe->image = $imagePath ? '/storage/' . $imagePath : null;
        $recipe->user_id = auth()->id();
        $recipe->save();

        // Podłącz kategorie i typy diety
        $recipe->categories()->sync($validated['categories']);
        if (!empty($validated['diet_types'])) {
            $recipe->dietTypes()->sync($validated['diet_types']);
        }

        return redirect()->route('recipes.show', $recipe->id)->with('success', 'Przepis dodany!');
    }
}
