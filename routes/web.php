<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DietTypeController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\IngredientController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/diet-types', [DietTypeController::class, 'index'])->name('diettypes.index');
Route::get('/diet-types/{dietType}', [DietTypeController::class, 'show'])->name('dietTypes.show');
Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::post('/recipes/{recipe}/comments', [App\Http\Controllers\CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

Route::get('/moje-przepisy', [\App\Http\Controllers\RecipeController::class, 'myRecipes'])
    ->middleware('auth')
    ->name('recipes.mine');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/comments/mine', [CommentController::class, 'myComments'])->name('comments.mine');
});
Route::middleware('auth')->group(function () {
    Route::post('/recipes/{recipe}/ratings', [RatingController::class, 'store'])
        ->name('ratings.store');
    Route::get('/ratings/{rating}/edit', [RatingController::class, 'edit'])
        ->name('ratings.edit');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])
        ->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])
        ->name('ratings.destroy');
});
// Lista składników
Route::get('/ingredients', [IngredientController::class, 'index'])->name('ingredients.index');

// Przepisy z konkretnym składnikiem
Route::get('/ingredients/{ingredient}', [IngredientController::class, 'show'])->name('ingredients.show');
