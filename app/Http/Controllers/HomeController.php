<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Recipe;

class HomeController extends Controller
{
    public function index()
    {
        $latestRecipes = Recipe::latest()->take(6)->get();
        $categories = Category::all(); // pobiera wszystkie kategorie
        return view('home.index', compact('categories', 'latestRecipes'));
    }
}
