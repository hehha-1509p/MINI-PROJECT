<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function home()
    {
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

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->with('error', 'Invalid email/username or password');
    }

    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'email.unique' => 'This email is already registered. Please use another email or login.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'username.required' => 'Please enter your username.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        User::create([
            'name' => $request->username,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
