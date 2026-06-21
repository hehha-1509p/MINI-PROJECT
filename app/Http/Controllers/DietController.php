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

    // SAVE PREFERRED DIET (from home page buttons)
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

        if (!$calories || !$dietPlan) {
            return null;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $plan = [];
        $usedMealIds = [];

        foreach ($days as $day) {
            $isWeekend = in_array($day, ['Saturday', 'Sunday']);
            $currentCalories = $calories;

            if ($dietPlan === 'Weekday Balanced + Weekend Flex' && $isWeekend) {
                $currentCalories = $calories + 500;
            }

            $breakfastTarget = $currentCalories * 0.3;
            $lunchTarget = $currentCalories * 0.4;
            $dinnerTarget = $currentCalories * 0.3;

            $breakfast = $this->getMeal('Breakfast', $breakfastTarget, $usedMealIds);
            if ($breakfast) {
                $usedMealIds[] = $breakfast->id;
            }

            $lunch = $this->getMeal('Lunch', $lunchTarget, $usedMealIds);
            if ($lunch) {
                $usedMealIds[] = $lunch->id;
            }

            $dinner = $this->getMeal('Dinner', $dinnerTarget, $usedMealIds);
            if ($dinner) {
                $usedMealIds[] = $dinner->id;
            }

            $plan[$day] = [
                'breakfast' => $breakfast,
                'lunch' => $lunch,
                'dinner' => $dinner,
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

        // Clear the day first
        $mealPlan[$day] = [
            'breakfast' => null,
            'lunch' => null,
            'dinner' => null,
        ];

        // Get new meals with proper exclusion
        $excludeIds = [];

        $breakfast = $this->getMeal('Breakfast', $breakfastTarget, $excludeIds);
        if ($breakfast) {
            $excludeIds[] = $breakfast->id;
            $mealPlan[$day]['breakfast'] = $breakfast;
        }

        $lunch = $this->getMeal('Lunch', $lunchTarget, $excludeIds);
        if ($lunch) {
            $excludeIds[] = $lunch->id;
            $mealPlan[$day]['lunch'] = $lunch;
        }

        $dinner = $this->getMeal('Dinner', $dinnerTarget, $excludeIds);
        if ($dinner) {
            $mealPlan[$day]['dinner'] = $dinner;
        }

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
            return response()->json(['error' => 'No meals found for this day'], 404);
        }

        session(['viewing_ingredients_day' => $day]);

        return response()->json(['status' => 'success']);
    }

    public function showIngredients()
    {
        $day = session('viewing_ingredients_day');
        $mealPlan = session('meal_plan', []);

        if (!$day || !isset($mealPlan[$day])) {
            return redirect('/')->with('error', 'No meals found for this day');
        }

        $meals = $mealPlan[$day];

        return view('ingredients', compact('meals', 'day'));
    }

    private function getMeal($mealType, $target, $excludeIds = [])
    {
        $foodFilters = session('food_filters', []);
        $includedFoods = $foodFilters['foods'] ?? [];
        $excludedFoods = $foodFilters['excluded'] ?? [];
        $preferences = $foodFilters['preferences'] ?? [];

        // If no ingredients selected, return null (no meal)
        if (empty($includedFoods)) {
            return null;
        }

        $dbMealCategory = $mealType;
        if ($mealType === 'Lunch' || $mealType === 'Dinner') {
            $dbMealCategory = 'Lunch/Dinner';
        }

        // First attempt: strict calorie range
        $query = MenuItem::where('meal_category', $dbMealCategory)
            ->whereNotIn('id', $excludeIds)
            ->whereBetween('calories_min', [$target - 100, $target + 100]);

        // OR logic for ALL selected ingredients (any of them)
        if (!empty($includedFoods)) {
            $query->where(function($q) use ($includedFoods) {
                foreach ($includedFoods as $food) {
                    $q->orWhere('ingredient_category', 'like', '%' . strtolower($food) . '%');
                }
            });
        }

        // Exclude: must NOT have any of these
        if (!empty($excludedFoods)) {
            foreach ($excludedFoods as $food) {
                $query->where('ingredient_category', 'not like', '%' . strtolower($food) . '%');
            }
        }

        // --- PREFERENCES FILTERS ---
        if (in_array('Pork-Free Only', $preferences)) {
            $query->where('halal_status', 'Pork-Free');
        }

        $meal = $query->inRandomOrder()->first();

        // Second attempt: no calorie restriction
        if (!$meal) {
            $query = MenuItem::where('meal_category', $dbMealCategory)
                ->whereNotIn('id', $excludeIds);

            if (!empty($includedFoods)) {
                $query->where(function($q) use ($includedFoods) {
                    foreach ($includedFoods as $food) {
                        $q->orWhere('ingredient_category', 'like', '%' . strtolower($food) . '%');
                    }
                });
            }

            if (!empty($excludedFoods)) {
                foreach ($excludedFoods as $food) {
                    $query->where('ingredient_category', 'not like', '%' . strtolower($food) . '%');
                }
            }

            // Pork-Free only
            if (in_array('Pork-Free Only', $preferences)) {
                $query->where('halal_status', 'Pork-Free');
            }

            $meal = $query->inRandomOrder()->first();
        }

        return $meal;
    }

    public function regenerateMeal(Request $request)
    {
        $mealType = $request->meal_type;
        $day = $request->day;
        $calories = session('calories');
        $dietPlan = session('diet_plan');

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

        $mealPlan = session('meal_plan', []);
        $mealKey = strtolower($mealType);

        $currentMealId = $mealPlan[$day][$mealKey]->id ?? null;
        $excludeIds = $currentMealId ? [$currentMealId] : [];

        $newMeal = $this->getMeal($mealType, $target, $excludeIds);

        if (isset($mealPlan[$day])) {
            $mealPlan[$day][$mealKey] = $newMeal;
            session(['meal_plan' => $mealPlan]);
        }

        return response()->json([
            'meal_type' => $mealType,
            'day' => $day,
            'meal' => $newMeal ? [
                'id' => $newMeal->id,
                'item_name' => $newMeal->item_name,
            ] : null,
        ]);
    }

    public function diet_option()
    {
        // ✅ ADD THIS - Check if user is logged in
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login before choosing a diet option.');
        }

        // ✅ Check if calories are calculated
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

        return response()->json(['status' => 'success']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = MenuItem::where('item_name', 'LIKE', "%{$query}%")
            ->orWhere('estimated_main_ingredients', 'LIKE', "%{$query}%")
            ->orWhere('ingredient_category', 'LIKE', "%{$query}%")
            ->orWhere('meal_category', 'LIKE', "%{$query}%")
            ->get();

        return view('search', compact('results', 'query'));
    }

    public function getFoodFilters(Request $request)
    {
        return response()->json([
            'filters' => session('food_filters', [
                'foods' => [],
                'preferences' => [],
            ]),
        ]);
    }

    public function regenerateAllDays(Request $request)
    {
        $mealPlan = $this->regenerateAllMeals();

        if (!$mealPlan) {
            return response()->json(['error' => 'Missing calories or diet plan'], 400);
        }

        return response()->json([
            'status' => 'success',
            'meals' => $mealPlan
        ]);
    }
}
