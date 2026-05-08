<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
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

    // ADD THIS PART
    public function saveDiet(Request $request)
    {
        session([
            'selectedDiet' => $request->diet
        ]);

        return redirect('/');
    }

    // Food Generation
    public function generate($day)
    {
        $foods = MenuItem::inRandomOrder()->limit(5)->get();

        return view('generate', [
            'day' => $day,
            'foods' => $foods
        ]);
    }
}

