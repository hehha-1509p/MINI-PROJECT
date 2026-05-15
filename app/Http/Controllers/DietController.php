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

    // Save Diet
    public function saveCalorie(Request $request)
    {
        $sex = $request->sex;
        $weight = $request->weight;
        $height = $request->height;
        $age = $request->age;
        $activity = $request->activity;
        $goal = $request->goal;

        // BMR
        if ($sex == 'Male') {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
        } else {
            $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
        }

        $calories = $bmr * $activity;

        // Adjust based on goal
        if ($goal === 'lose') {
            $calories -= 300;
        }

        if ($goal === 'gain') {
            $calories += 300;
        }

        // Macronutrients
        $protein = ($calories * 0.3) / 4;
        $fat = ($calories * 0.25) / 9;
        $carbs = ($calories * 0.45) / 4;

        // SAVE SESSION
        session([
            'calories' => round($calories)
        ]);

        return back();
    }

    public function generate($day)
    {
        $calories = session('calories');

        // If user never calculate
        if (!$calories) {
            return redirect('/')->with('error', 'Please calculate calories first!');
        }

        // Split into 3 meals
        $mealCalories = [
            'breakfast' => $calories * 0.3,
            'lunch' => $calories * 0.4,
            'dinner' => $calories * 0.3,
        ];

        // Get foods based on calories range
        $breakfast = MenuItem::where('calories_min', '<=', $mealCalories['breakfast'])
            ->where('calories_max', '>=', $mealCalories['breakfast'])
            ->inRandomOrder()
            ->first();

        $lunch = MenuItem::where('calories_min', '<=', $mealCalories['lunch'])
            ->where('calories_max', '>=', $mealCalories['lunch'])
            ->inRandomOrder()
            ->first();

        $dinner = MenuItem::where('calories_min', '<=', $mealCalories['dinner'])
            ->where('calories_max', '>=', $mealCalories['dinner'])
            ->inRandomOrder()
            ->first();

        return view('generate', compact(
            'day',
            'calories',
            'breakfast',
            'lunch',
            'dinner'
        ));
    }
}
