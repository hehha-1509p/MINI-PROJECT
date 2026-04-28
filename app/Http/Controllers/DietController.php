<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DietController extends Controller
{
    public function index()
    {
        // Defining your three specific features
        $plans = [
            'fully_balanced' => [
                'title' => 'Fully Balanced Diet',
                'description' => 'A consistent daily intake of proteins, carbs, and fats.',
                'status' => 'Stable'
            ],
            'weekday_balanced' => [
                'title' => 'Weekday Balanced Diet',
                'description' => 'Strict nutrition tracking from Monday to Friday.',
                'status' => 'Weekday Only'
            ],
            'weekend_increase' => [
                'title' => 'Weekend Light Increase',
                'description' => 'Strategically increase caloric intake for Saturday and Sunday.',
                'status' => now()->isWeekend() ? 'Active Now' : 'Inactive'
            ]
        ];

        // Return your existing view file
        return view('diet_option', compact('plans'));
    }
}