<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::all();

        return View('recipes.index', compact('recipes'));
    }

    public function show($id)
    {
        // Sprawdzenie, czy przepis istnieje
        $recipe = Recipe::findOrFail($id);

        $recipe->load(['user']);

        // Zwrócenie przepisu
        return view('recipes.show', compact('recipe'));
    }
}
