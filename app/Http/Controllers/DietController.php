<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DietController extends Controller
{
    public function diet_option()
    {
        $dietPlans = [
            [
                'name' => '7 Days Balanced Diet',
                'description' => 'Maintain a consistent and balanced intake every day with proper nutrients.',
                'tag' => 'Consistency',
                'color' => 'green',
            ],
            [
                'name' => 'Weekday Balanced + Weekend Flex',
                'description' => 'Eat balanced on weekdays and allow slightly higher intake on weekends to stay sustainable.',
                'tag' => 'Flexible',
                'color' => 'blue',
            ],
        ];

        return view('diet_option', compact('dietPlans'));
    }
}