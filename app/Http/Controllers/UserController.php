<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
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
}
