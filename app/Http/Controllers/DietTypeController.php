<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DietType;

class DietTypeController extends Controller
{
    public function index()
    {
        $dietTypes = DietType::all();
        return view('diettypes.index', compact('dietTypes'));
    }

    public function show(DietType $dietType)
    {
        $dietType->load('recipes');
        return view('diettypes.show', compact('dietType'));
    }
}
