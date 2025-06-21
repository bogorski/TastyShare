<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DietType;
use Illuminate\Http\Request;

class DietTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dietTypes = DietType::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(15);

        return view('admin.dietTypes.index', compact('dietTypes', 'search'));
    }

    public function create()
    {
        return view('admin.dietTypes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:diet_types,name',
            'image' => 'nullable|image|max:2048',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/diet_types', 'public');
            $data['image'] = $path;
        }

        // Ustaw domyślnie is_visible na true, jeśli nie podano
        $data['is_visible'] = $data['is_visible'] ?? true;

        DietType::create($data);

        return redirect()->route('admin.dietTypes.index')->with('success', 'Rodzja diety został dodany.');
    }

    public function edit(DietType $dietType)
    {
        return view('admin.dietTypes.edit', compact('dietType'));
    }

    public function update(Request $request, DietType $dietType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:diet_types,name,' . $dietType->id,
            'image' => 'nullable|image|max:2048',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('diet_types', 'public');
            $data['image'] = $path;
        }

        $dietType->update($data);

        return redirect()->route('admin.dietTypes.index')->with('success', 'Rodzaj diety został zaktualizowany.');
    }

    public function destroy($id)
    {
        $dietType = DietType::findOrFail($id);
        $dietType->is_visible = false;
        $dietType->save();

        return redirect()->route('admin.dietTypes.index')->with('success', 'Rodzaj diety został usunięty.');
    }
}
