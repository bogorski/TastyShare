<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;


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

        return view('admin.ingredients.index', compact('ingredients', 'search'));
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'is_visible' => 'boolean',
        ]);

        // Ustaw domyślnie is_visible na true, jeśli nie podano
        $data['is_visible'] = $data['is_visible'] ?? true;

        Ingredient::create($data);

        return redirect()->route('admin.ingredients.index')->with('success', 'Składnik został dodany.');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'is_visible' => 'required|boolean',
        ]);

        $ingredient->update($request->only(['name', 'is_visible']));

        return redirect()->route('admin.ingredients.index')->with('success', 'Składnik został zaktualizowany.');
    }



    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->is_visible = false;
        $ingredient->save();

        return redirect()->route('admin.ingredients.index')->with('success', 'Składnik został usunięty.');
    }
}
