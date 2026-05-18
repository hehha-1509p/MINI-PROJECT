<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class DietController extends Controller
{
    // SAVE DIET PLAN (from /diet_option page)
    public function saveDiet(Request $request)
    {
        session(['diet_plan' => $request->diet]);
        $this->regenerateAllMeals();
        return redirect('/');
    }

    // Save PREFERRED DIET (from home page buttons)
    public function savePreferredDiet(Request $request)
    {
        session(['preferred_diet' => $request->diet]);
        $this->regenerateAllMeals();
        return response()->json(['status' => 'success']);
    }

    // SAVE CALORIES
    public function saveCalories(Request $request)
    {
        session(['calories' => $request->calories]);
        $this->regenerateAllMeals();
        return response()->json(['status' => 'success']);
    }

    public function regenerateAllMeals()
    {
        $calories = session('calories');
        $dietPlan = session('diet_plan');
        $preferredDiet = session('preferred_diet');

        if (!$calories || !$dietPlan) {
            return null;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $plan = [];

        foreach ($days as $day) {
            $isWeekend = in_array($day, ['Saturday', 'Sunday']);
            $currentCalories = $calories;

            if ($dietPlan === 'Weekday Balanced + Weekend Flex' && $isWeekend) {
                $currentCalories = $calories + 500;
            }

            $breakfastTarget = $currentCalories * 0.3;
            $lunchTarget = $currentCalories * 0.4;
            $dinnerTarget = $currentCalories * 0.3;

            $plan[$day] = [
                'breakfast' => $this->getMeal($preferredDiet, 'Breakfast', $breakfastTarget),
                'lunch' => $this->getMeal($preferredDiet, 'Lunch', $lunchTarget),
                'dinner' => $this->getMeal($preferredDiet, 'Dinner', $dinnerTarget),
            ];
        }

        session(['meal_plan' => $plan]);
        return $plan;
    }

    public function generateMeals()
    {
        if (session()->has('meal_plan')) {
            return session('meal_plan');
        }
        return $this->regenerateAllMeals();
    }

    public function regenerateDay(Request $request)
    {
        $day = $request->day;
        $calories = session('calories');
        $dietPlan = session('diet_plan');
        $preferredDiet = session('preferred_diet');

        if (!$calories || !$dietPlan) {
            return response()->json(['error' => 'Missing calories or diet plan'], 400);
        }

        $isWeekend = in_array($day, ['Saturday', 'Sunday']);
        $currentCalories = $calories;

        if ($dietPlan === 'Weekday Balanced + Weekend Flex' && $isWeekend) {
            $currentCalories = $calories + 500;
        }

        $breakfastTarget = $currentCalories * 0.3;
        $lunchTarget = $currentCalories * 0.4;
        $dinnerTarget = $currentCalories * 0.3;

        $mealPlan = session('meal_plan', []);

        $mealPlan[$day] = [
            'breakfast' => $this->getMeal($preferredDiet, 'Breakfast', $breakfastTarget),
            'lunch' => $this->getMeal($preferredDiet, 'Lunch', $lunchTarget),
            'dinner' => $this->getMeal($preferredDiet, 'Dinner', $dinnerTarget),
        ];

        session(['meal_plan' => $mealPlan]);

        return response()->json([
            'day' => $day,
            'meals' => [
                'breakfast' => $mealPlan[$day]['breakfast'] ? [
                    'id' => $mealPlan[$day]['breakfast']->id,
                    'item_name' => $mealPlan[$day]['breakfast']->item_name,
                ] : null,
                'lunch' => $mealPlan[$day]['lunch'] ? [
                    'id' => $mealPlan[$day]['lunch']->id,
                    'item_name' => $mealPlan[$day]['lunch']->item_name,
                ] : null,
                'dinner' => $mealPlan[$day]['dinner'] ? [
                    'id' => $mealPlan[$day]['dinner']->id,
                    'item_name' => $mealPlan[$day]['dinner']->item_name,
                ] : null,
            ]
        ]);
    }

    public function getIngredients(Request $request)
    {
        $day = $request->day;
        $mealPlan = session('meal_plan', []);

        if (!isset($mealPlan[$day])) {
            return redirect('/')->with('error', 'No meals found for this day');
        }

        $meals = $mealPlan[$day];
        session(['viewing_ingredients_day' => $day]);

        return view('ingredients', compact('meals', 'day'));
    }

    private function getMeal($preferredDiet, $mealType, $target)
    {
        // First attempt: Strict calorie range
        $query = MenuItem::where('meal_category', $mealType)
            ->whereBetween('calories_min_kcal', [$target - 100, $target + 100]);

        if ($preferredDiet === 'Vegetarian') {
            $query->where('food_type', 'Vegetarian');
        } elseif ($preferredDiet === 'Keto') {
            $query->where('carbs_max_g', '<=', 10);
        }

        $foodFilters = session('food_filters', []);
        if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
            $selectedFoods = $foodFilters['foods'];
            foreach ($selectedFoods as $food) {
                $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
            }
        }

        $meal = $query->inRandomOrder()->first();

        // Second attempt: No calorie restriction
        if (!$meal) {
            $query = MenuItem::where('meal_category', $mealType);

            if ($preferredDiet === 'Vegetarian') {
                $query->where('food_type', 'Vegetarian');
            } elseif ($preferredDiet === 'Keto') {
                $query->where('carbs_max_g', '<=', 10);
            }

            if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
                $selectedFoods = $foodFilters['foods'];
                foreach ($selectedFoods as $food) {
                    $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
                }
            }

            $meal = $query->inRandomOrder()->first();
        }

        // Third attempt: Remove diet filter
        if (!$meal && $preferredDiet !== 'Anything') {
            $query = MenuItem::where('meal_category', $mealType)
                ->whereBetween('calories_min_kcal', [$target - 100, $target + 100]);

            if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
                $selectedFoods = $foodFilters['foods'];
                foreach ($selectedFoods as $food) {
                    $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
                }
            }

            $meal = $query->inRandomOrder()->first();
        }

        // Fourth attempt: Any meal of this type
        if (!$meal) {
            $query = MenuItem::where('meal_category', $mealType);

            if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
                $selectedFoods = $foodFilters['foods'];
                foreach ($selectedFoods as $food) {
                    $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
                }
            }

            $meal = $query->inRandomOrder()->first();
        }

        return $meal;
    }

    private function regenerateMeal(Request $request)
    {
        $mealType = $request->meal_type;
        $day = $request->day;
        $calories = session('calories');
        $dietPlan = session('diet_plan');
        $preferredDiet = session('preferred_diet');

        if (!$calories || !$dietPlan) {
            return response()->json(['error' => 'Missing calories or diet plan'], 400);
        }

        $isWeekend = in_array($day, ['Saturday', 'Sunday']);
        $currentCalories = $calories;

        if ($dietPlan === 'Weekday Balanced + Weekend Flex' && $isWeekend) {
            $currentCalories = $calories + 500;
        }

        $target = match ($mealType) {
            'Breakfast' => $currentCalories * 0.3,
            'Lunch' => $currentCalories * 0.4,
            'Dinner' => $currentCalories * 0.3,
            default => $currentCalories * 0.3,
        };

        $newMeal = $this->getMeal($preferredDiet, $mealType, $target);

        $mealPlan = session('meal_plan', []);
        if (isset($mealPlan[$day])) {
            $mealKey = strtolower($mealType);
            $mealPlan[$day][$mealKey] = $newMeal;
            session(['meal_plan' => $mealPlan]);
        }

        return response()->json([
            'meal_type' => $mealType,
            'day' => $day,
            'meal' => $newMeal ? [
                'id' => $newMeal->id,
                'item_name' => $newMeal->item_name,
            ] : null
        ]);
    }

    public function diet_option()
    {
        if (!session('calories')) {
            return redirect('/')->with('error', 'Please calculate your calorie needs first');
        }

        $dietPlans = [
            [
                'name' => '7 Days Balanced Diet',
                'description' => 'Balanced daily nutrition with consistent portions',
                'tag' => 'Consistency',
            ],
            [
                'name' => 'Weekday Balanced + Weekend Flex',
                'description' => 'Strict weekday meals, flexible weekends (+500 calories)',
                'tag' => 'Flexible',
            ],
        ];

        return view('diet_option', compact('dietPlans'));
    }

    public function saveFoodFilters(Request $request)
    {
        session(['food_filters' => $request->filters]);
        $this->regenerateAllMeals();
        return response()->json(['status' => 'success']);
    }
}