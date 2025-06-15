<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('recipes/{id}', [RecipeController::class, 'show']);
Route::get('recipes/', [RecipeController::class, 'index']);
