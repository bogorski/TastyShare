<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // pobiera wszystkie kategorie
        return view('home.index', compact('categories'));
    }
}
