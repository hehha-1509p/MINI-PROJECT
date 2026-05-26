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

        if (!$calories || !$dietPlan) {
            return null;
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $plan = [];
        $usedMealIds = [];  // Prevent duplicates across the week

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
            if ($breakfast) $usedMealIds[] = $breakfast->id;

            $lunch = $this->getMeal('Lunch', $lunchTarget, $usedMealIds);
            if ($lunch) $usedMealIds[] = $lunch->id;

            $dinner = $this->getMeal('Dinner', $dinnerTarget, $usedMealIds);
            if ($dinner) $usedMealIds[] = $dinner->id;

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

        // Get current meal IDs to exclude from regeneration
        $excludeIds = [];
        if (isset($mealPlan[$day])) {
            if ($mealPlan[$day]['breakfast']) $excludeIds[] = $mealPlan[$day]['breakfast']->id;
            if ($mealPlan[$day]['lunch']) $excludeIds[] = $mealPlan[$day]['lunch']->id;
            if ($mealPlan[$day]['dinner']) $excludeIds[] = $mealPlan[$day]['dinner']->id;
        }

        $mealPlan[$day] = [
            'breakfast' => $this->getMeal('Breakfast', $breakfastTarget, $excludeIds),
            'lunch' => $this->getMeal('Lunch', $lunchTarget, $excludeIds),
            'dinner' => $this->getMeal('Dinner', $dinnerTarget, $excludeIds),
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

    private function getMeal($mealType, $target, $excludeIds = [])
    {
        // First attempt: Strict calorie range
        $query = MenuItem::where('meal_category', $mealType)
            ->whereNotIn('id', $excludeIds)
            ->whereBetween('calories_min', [$target - 100, $target + 100]);

        // ONLY use food filters - NO preferred diet filtering here
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
            $query = MenuItem::where('meal_category', $mealType)
                ->whereNotIn('id', $excludeIds);

            if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
                $selectedFoods = $foodFilters['foods'];
                foreach ($selectedFoods as $food) {
                    $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
                }
            }

            $meal = $query->inRandomOrder()->first();
        }

        // Third attempt: Any meal of this type (ignore calories)
        if (!$meal) {
            $query = MenuItem::where('meal_category', $mealType)
                ->whereNotIn('id', $excludeIds);

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

        // Exclude the current meal to get a different one
        $mealPlan = session('meal_plan', []);
        $currentMealId = $mealPlan[$day][strtolower($mealType)]->id ?? null;
        $excludeIds = $currentMealId ? [$currentMealId] : [];

        $newMeal = $this->getMeal($mealType, $target, $excludeIds);

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
    public function search(Request $request)
    {
     $query = $request->input('query');

    $results = MenuItem::where('item_name', 'LIKE', "%{$query}%")
        ->orWhere('estimated_main_ingredients', 'LIKE', "%{$query}%")
        ->orWhere('meal_category', 'LIKE', "%{$query}%")
        ->get();

    return view('search', compact('results', 'query'));
    }
}
