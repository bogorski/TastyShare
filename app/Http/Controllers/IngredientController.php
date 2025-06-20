<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::where('is_visible', true)->orderBy('name')->get();
        return view('ingredients.index', compact('ingredients'));
    }

    public function show(Ingredient $ingredient)
    {
        // Załaduj przepisy, które zawierają ten składnik
        $recipes = $ingredient->recipes()
            ->where('is_visible', true)
            ->with(['ingredients' => function ($query) {
                $query->where('is_visible', true);
            }])
            ->paginate(10);
        return view('ingredients.show', compact('ingredient', 'recipes'));
    }
}
