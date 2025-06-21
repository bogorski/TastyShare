<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $ingredients = Ingredient::where('is_visible', true)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->get();

        return view('ingredients.index', compact('ingredients', 'search'));
    }

    public function show(Ingredient $ingredient)
    {
        $recipes = $ingredient->recipes()
            ->where('recipes.is_visible', true)
            ->with(['ingredients' => function ($query) {
                $query->where('ingredients.is_visible', true);
            }])
            ->paginate(10);

        return view('ingredients.show', compact('ingredient', 'recipes'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
        ]);

        Ingredient::create($data);

        return redirect()->route('ingredients.index')->with('success', 'Składnik został dodany.');
    }
}
