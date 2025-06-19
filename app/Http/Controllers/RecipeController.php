<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\DietType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecipeController extends Controller
{
    // public function index(Request $request)
    // {
    //     $sort = $request->get('sort', 'created_at'); // domyślnie sortujemy po dacie
    //     $order = $request->get('order', 'desc');     // domyślnie malejąco

    //     // Pobierz wszystkie przepisy wraz z powiązanymi relacjami (np. użytkownik, kategorie)
    //     $recipes = Recipe::with(['user', 'categories', 'dietTypes'])
    //         ->orderBy($sort, $order)
    //         ->paginate(12)
    //         ->appends(['sort' => $sort, 'order' => $order]); // żeby linki paginacji pamiętały sortowanie

    //     return view('recipes.index', compact('recipes', 'sort', 'order'));
    // }
    // public function index(Request $request)
    // {
    //     $validSorts = ['created_at', 'title', 'preparation_time', 'average_rating'];
    //     $sort = $request->get('sort', 'created_at');
    //     $order = $request->get('order', 'desc');

    //     if (!in_array($sort, $validSorts)) {
    //         $sort = 'created_at';
    //     }
    //     if (!in_array($order, ['asc', 'desc'])) {
    //         $order = 'desc';
    //     }

    //     $query = Recipe::with(['user', 'categories', 'dietTypes']);

    //     if ($sort === 'average_rating') {
    //         $query = $query->withAvg('comments', 'rating')
    //             ->orderByRaw('comments_avg_rating IS NULL ASC')
    //             ->orderBy('comments_avg_rating', $order);
    //     } else {
    //         $query = $query->orderBy($sort, $order);
    //     }

    //     $recipes = $query->paginate(12)->withQueryString();

    //     return view('recipes.index', compact('recipes', 'sort', 'order'));
    // }

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
            ->withAvg('comments', 'rating');

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
            $query->having('comments_avg_rating', '>=', $minRating);
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
            $query->orderByRaw('comments_avg_rating IS NULL ASC')
                ->orderBy('comments_avg_rating', $order);
        } else {
            $query->orderBy($sort, $order);
        }

        $recipes = $query->paginate(12)->withQueryString();

        return view('recipes.index', compact('recipes', 'categories', 'diettypes', 'sort', 'order'));
    }


    public function show($id)
    {
        $recipe = Recipe::with(['comments.user', 'dietTypes', 'categories', 'user'])->findOrFail($id);

        $userHasCommented = false;
        $isAuthor = false;
        $myComment = null;

        if (Auth::check()) {
            $userHasCommented = $recipe->comments->where('user_id', Auth::id())->isNotEmpty();
            $isAuthor = Auth::id() === $recipe->user_id;
            $myComment = $recipe->comments->where('user_id', Auth::id())->first();
        }

        return view('recipes.show', compact('recipe', 'userHasCommented', 'isAuthor', 'myComment'));
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
            $imagePath = $request->file('image')->store('image/recipes', 'public');
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

    public function myRecipes()
    {
        $recipes = auth()->user()->recipes()->with(['categories', 'dietTypes'])->latest()->get();

        return view('recipes.mine', compact('recipes'));
    }

    public function edit(Recipe $recipe)
    {
        // Sprawdzenie, czy użytkownik jest autorem przepisu
        if (auth()->id() !== $recipe->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $categories = Category::all();
        $dietTypes = \App\Models\DietType::all();

        return view('recipes.edit', compact('recipe', 'categories', 'dietTypes'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        if (auth()->id() !== $recipe->user_id) {
            abort(403, 'To działanie jest niedozwolone.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
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
            'ingredients' => $validated['ingredients'],
            'instructions' => $validated['instructions'],
            'preparation_time' => $validated['preparation_time'],
        ]);

        // Relacje wiele-do-wielu
        $recipe->categories()->sync($validated['categories']);
        $recipe->dietTypes()->sync($validated['diet_types']);

        return redirect()->route('recipes.show', $recipe)->with('success', 'Przepis został zaktualizowany.');
    }

    public function destroy(Recipe $recipe)
    {
        // Tylko autor może usunąć przepis
        if ($recipe->user_id !== auth()->id()) {
            abort(403, 'Brak uprawnień.');
        }

        // Jeśli przepis ma zdjęcie – usuń plik
        if ($recipe->image && file_exists(public_path($recipe->image))) {
            unlink(public_path($recipe->image));
        }

        // Odłącz relacje wiele-do-wielu
        $recipe->categories()->detach();
        $recipe->dietTypes()->detach();

        // Usuń przepis
        $recipe->delete();

        return redirect()->route('recipes.mine')->with('success', 'Przepis został usunięty.');
    }
}
