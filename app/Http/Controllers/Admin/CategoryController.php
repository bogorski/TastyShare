<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $categories = $query->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|max:2048',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/categories', 'public');
            $data['image'] = $path;
        }

        $data['is_visible'] = $data['is_visible'] ?? true;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategoria została dodana.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|max:2048',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/categories', 'public');
            $data['image'] = $path;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategoria została zaktualizowana.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->is_visible = false;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Komentarz został usunięty.');
    }
}
