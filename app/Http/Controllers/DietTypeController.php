<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DietType;

class DietTypeController extends Controller
{
    public function index()
    {
        $dietTypes = DietType::where('is_visible', true)->get();
        return view('diettypes.index', compact('dietTypes'));
    }

    public function show(DietType $dietType)
    {
        $dietType->load(['recipes' => function ($query) {
            $query->where('is_visible', true);
        }]);
        return view('diettypes.show', compact('dietType'));
    }
}
