<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        // Załóżmy, że pokazujesz kategorię z jej przepisami
        $category->load('recipes'); // lub inne relacje

        return view('categories.show', compact('category'));
    }
}
