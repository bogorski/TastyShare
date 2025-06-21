<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        $latestRecipes = Recipe::latest()->take(6)->get();

        $latestComments = Comment::with(['user', 'recipe'])
            ->latest()
            ->take(3)
            ->get();

        $popularRecipes = Recipe::withAvg('ratings', 'rating')
            ->havingNotNull('ratings_avg_rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get();
        $categories = Category::all();

        return view('home.index', compact('categories', 'latestRecipes', 'latestComments', 'popularRecipes'));
    }
}
