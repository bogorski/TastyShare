<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;


class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::paginate(15);
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function edit(Ingredient $ingredient)
    {
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string',
            'is_visible' => 'required|boolean',
        ]);

        $ingredient->update($request->only(['name', 'is_visible']));

        return redirect()->route('admin.ingredients.index')->with('success', 'Komentarz zaktualizowany.');
    }



    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->is_visible = false;
        $ingredient->save();

        return redirect()->route('admin.ingredients.index')->with('success', 'Komentarz został usunięty.');
    }
}
