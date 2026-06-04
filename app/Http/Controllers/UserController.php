<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function home()
    {
        // Force regenerate if diet plan exists but no meals
        if (session('diet_plan') && !session('meal_plan')) {
            app(DietController::class)->regenerateAllMeals();
        }

        $meals = app(DietController::class)->generateMeals();
        return view('home', compact('meals'));
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        User::create([
        'name' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        ]);

        return redirect('/login');
    }
}
