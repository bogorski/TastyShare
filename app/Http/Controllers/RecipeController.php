<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\DietType;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecipeController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $diettypes = DietType::all();

        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $diettypeId = $request->input('diettype_id');
        $maxPreparationTime = $request->input('max_preparation_time');
        $minRating = $request->input('min_rating');

        $query = Recipe::with(['user', 'categories', 'dietTypes'])
            ->withAvg(['ratings' => function ($q) {
                $q->where('is_visible', true);
            }], 'rating')
            ->where('is_visible', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($categoryId) {
            // filtrujemy przepisy, które mają przypisaną kategorię
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        if ($diettypeId) {
            // filtrujemy przepisy, które mają przypisany typ diety
            $query->whereHas('dietTypes', function ($q) use ($diettypeId) {
                $q->where('id', $diettypeId);
            });
        }

        if ($maxPreparationTime) {
            $query->where('preparation_time', '<=', $maxPreparationTime);
        }

        if ($minRating) {
            $query->having('ratings_avg_rating', '>=', $minRating);
        }

        // Sortowanie
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        $validSorts = ['created_at', 'title', 'preparation_time', 'average_rating'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        if ($sort === 'average_rating') {
            // sortowanie wg średniej oceny, NULLy na końcu
            $query->orderByRaw('ratings_avg_rating IS NULL ASC')
                ->orderBy('ratings_avg_rating', $order);
        } else {
            $query->orderBy($sort, $order);
        }

        $recipes = $query->paginate(12)->withQueryString();

        return view('recipes.index', compact('recipes', 'categories', 'diettypes', 'sort', 'order'));
    }


    public function show($id)
    {
        $recipe = Recipe::with(['comments.user', 'ratings.user', 'dietTypes', 'categories', 'user'])->findOrFail($id);

        $userHasRated = false;
        $isAuthor = false;
        $myComment = null;
        $userRating = null;

        if (Auth::check()) {
            $userHasRated = $recipe->ratings->where('user_id', Auth::id())->isNotEmpty();
            $isAuthor = Auth::id() === $recipe->user_id;
            $myComment = $recipe->comments->where('user_id', Auth::id())->first();
            $userRating = $recipe->ratings->where('user_id', Auth::id())->first();
        }

        return view('recipes.show', compact('recipe', 'userHasRated', 'isAuthor', 'myComment', 'userRating'));
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
            'diet_types' => 'nullable|array',
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
        // Sprawdzenie, czy użytkownik jest autorem przepisu
        if (auth()->id() !== $recipe->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

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

        return view('recipes.edit', compact('recipe', 'categories', 'dietTypes', 'ingredients', 'recipeIngredients'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if (auth()->id() !== $recipe->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

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
        ]);

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

        $recipe->ingredients()->sync($pivotData);

        // Relacje wiele-do-wielu
        $recipe->categories()->sync($validated['categories']);
        $recipe->dietTypes()->sync($validated['diet_types']);

        return redirect()->route('recipes.show', $recipe)->with('success', 'Przepis został zaktualizowany.');
    }

    public function destroy(Recipe $recipe)
    {
        // Sprawdzenie, czy użytkownik jest autorem
        if (auth()->id() !== $recipe->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        // Ustaw is_visible na false
        $recipe->is_visible = false;
        $recipe->save();

        return redirect()->route('recipes.mine')
            ->with('success', 'Przepis został ukryty.');
    }

    public function myRecipes()
    {
        $recipes = auth()->user()
            ->recipes()
            ->where('is_visible', true)
            ->with(['categories', 'dietTypes'])
            ->latest()
            ->get();

        return view('recipes.mine', compact('recipes'));
    }
}
