<?php

use App\Http\Controllers\DietController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [UserController::class, 'home']);
Route::get('/login', [UserController::class, 'login']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register', [UserController::class, 'store']);
Route::get('/diet_option', [DietController::class, 'diet_option']);
Route::post('/save-diet', [DietController::class, 'saveDiet']);
Route::post('/save-calories', [DietController::class, 'saveCalories']);
Route::post('/regenerate-day', [DietController::class, 'regenerateDay']);
Route::post('/regenerate-meal', [DietController::class, 'regenerateMeal']);
Route::post('/get-ingredients', [DietController::class, 'getIngredients']);
Route::get('/ingredients', [DietController::class, 'showIngredients']);
Route::post('/save-food-filters', [DietController::class, 'saveFoodFilters']);
Route::get('/get-food-filters', [DietController::class, 'getFoodFilters']);
Route::post('/save-preferred-diet', [DietController::class, 'savePreferredDiet']);
Route::get('/search', [DietController::class, 'search'])->name('search');
Route::post('/regenerate-all', [DietController::class, 'regenerateAllDays']);
