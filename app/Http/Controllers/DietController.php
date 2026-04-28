<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DietController extends Controller
{
    // Rename this from 'index' to 'diet_option' to match your route!
    public function diet_option()
    {
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
                // 'now()' helper is part of Laravel's Carbon library
                'status' => now()->isWeekend() ? 'Active Now' : 'Inactive'
            ]
        ];

       return view('diet_option', compact('dietPlans', 'isWeekend'));
    }
}