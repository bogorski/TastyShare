<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('nazwa')->get();
        return view('ingredients.index', compact('ingredients'));
    }

    public function show(Ingredient $ingredient)
    {
        // Załaduj przepisy, które zawierają ten składnik
        $recipes = $ingredient->recipes()->with('ingredients')->paginate(10);
        return view('ingredients.show', compact('ingredient', 'recipes'));
    }
}
