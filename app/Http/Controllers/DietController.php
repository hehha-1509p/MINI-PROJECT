<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DietController extends Controller
{
    // SAVE DIET
    public function saveDiet(Request $request)
    {
        session(['diet_plan' => $request->diet]);  // Changed from 'selectedDiet' to 'diet_plan'
        $this->regenerateAllMeals();
        return redirect('/');
    }

    // Save PREFERRED DIET (from home page buttons) - This is just for filtering
    public function savePreferredDiet(Request $request)
    {
        session(['preferred_diet' => $request->diet]);  // New session variable
        $this->regenerateAllMeals();
        return response()->json(['status' => 'success']);
    }

    // SAVE CALORIES
    public function saveCalories(Request $request)
    {
        session(['calories' => $request->calories]);

        // Regenerate meals after calorie change
        $this->regenerateAllMeals();

        return response()->json([
            'status' => 'success'
        ]);
    }

    private function regenerateAllMeals()
    {
        $calories = session('calories');
        $dietPlan = session('diet_plan');  // From Diet Option page (7 Days Balanced or Weekend Flex)
        $preferredDiet = session('preferred_diet');  // From home page buttons (Anything, Keto, Vegetarian)

        if (!$calories || !$dietPlan) {  // Only need calories and diet plan, preferred diet is optional
            return null;
        }

        $breakfastTarget = $calories * 0.3;
        $lunchTarget = $calories * 0.4;
        $dinnerTarget = $calories * 0.3;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $plan = [];

        foreach ($days as $day) {
            // Apply WEEKEND FLEX logic if needed
            $isWeekend = in_array($day, ['Saturday', 'Sunday']);
            $currentBreakfastTarget = $breakfastTarget;
            $currentLunchTarget = $lunchTarget;
            $currentDinnerTarget = $dinnerTarget;

            if ($dietPlan === 'Weekday Balanced + Weekend Flex' && $isWeekend) {
                // Add 500 calories for weekend
                $currentBreakfastTarget = ($calories + 500) * 0.3;
                $currentLunchTarget = ($calories + 500) * 0.4;
                $currentDinnerTarget = ($calories + 500) * 0.3;
            }

            $plan[$day] = [
                'breakfast' => $this->getMeal($preferredDiet, 'Breakfast', $currentBreakfastTarget),
                'lunch' => $this->getMeal($preferredDiet, 'Lunch', $currentLunchTarget),
                'dinner' => $this->getMeal($preferredDiet, 'Dinner', $currentDinnerTarget),
            ];
        }

        session(['meal_plan' => $plan]);
        return $plan;
    }

    // MAIN GENERATION ENGINE (modified to use session)
    public function generateMeals()
    {
        // Check if we already have meals in session
        if (session()->has('meal_plan')) {
            return session('meal_plan');
        }

        // Otherwise generate new ones
        return $this->regenerateAllMeals();
    }

    // REGENERATE SINGLE DAY (NEW METHOD)
    public function regenerateDay(Request $request)
    {
        $day = $request->day;
        $calories = session('calories');
        $diet = session('selectedDiet');

        if (!$calories || !$diet) {
            return response()->json(['error' => 'Missing calories or diet'], 400);
        }

        $breakfastTarget = $calories * 0.3;
        $lunchTarget = $calories * 0.4;
        $dinnerTarget = $calories * 0.3;

        // Get current meal plan from session
        $mealPlan = session('meal_plan', []);

        // Regenerate meals for the specific day
        $mealPlan[$day] = [
            'breakfast' => $this->getMeal($diet, 'Breakfast', $breakfastTarget),
            'lunch' => $this->getMeal($diet, 'Lunch', $lunchTarget),
            'dinner' => $this->getMeal($diet, 'Dinner', $dinnerTarget),
        ];

        // Save back to session
        session(['meal_plan' => $mealPlan]);

        // Return the updated meals for this day
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

    // GET INGREDIENTS FOR A SPECIFIC DAY
    public function getIngredients(Request $request)
    {
        try {
            $day = $request->day;

            // Log for debugging
            \Log::info('getIngredients called', ['day' => $day]);

            $mealPlan = session('meal_plan', []);

            // Log the session data
            \Log::info('Current meal plan', ['plan' => $mealPlan]);

            if (!isset($mealPlan[$day])) {
                \Log::warning('No meals found for day: ' . $day);
                return redirect('/')->with('error', 'No meals found for this day');
            }

            $meals = $mealPlan[$day];

            // Store which day we're viewing
            session(['viewing_ingredients_day' => $day]);

            // For AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['redirect' => '/ingredients']);
            }

            return view('ingredients', compact('meals', 'day'));

        } catch (\Exception $e) {
            \Log::error('getIngredients error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getMeal($preferredDiet, $mealType, $target)
    {
        $query = MenuItem::where('meal_category', $mealType)
            ->whereBetween('calories_min_kcal', [$target - 100, $target + 100]);

        // Apply PREFERRED DIET filters (from home page buttons)
        if ($preferredDiet === 'Vegetarian') {
            $query->where('food_type', 'Vegetarian');
        } elseif ($preferredDiet === 'Keto') {
            $query->where('carbs_max_g', '<=', 10);
        }
        // 'Anything' means no filter

        // Apply food filter preferences from the widget - EXCLUDE checked items
        $foodFilters = session('food_filters', []);

        if (!empty($foodFilters) && isset($foodFilters['foods']) && count($foodFilters['foods']) > 0) {
            $selectedFoods = $foodFilters['foods'];
            foreach ($selectedFoods as $food) {
                $query->where('estimated_main_ingredients', 'not like', '%' . $food . '%');
            }
        }

        // Apply preference filters (Halal, Vegan, etc.)
        if (!empty($foodFilters) && isset($foodFilters['preferences']) && count($foodFilters['preferences']) > 0) {
            $preferences = $foodFilters['preferences'];
            foreach ($preferences as $pref) {
                switch ($pref) {
                    case 'Halal Only':
                        $query->where('estimated_main_ingredients', 'not like', '%pork%');
                        $query->where('estimated_main_ingredients', 'not like', '%alcohol%');
                        break;
                    case 'Vegan':
                        $query->where('food_type', '!=', 'Non-Vegetarian');
                        $query->where('estimated_main_ingredients', 'not like', '%milk%');
                        $query->where('estimated_main_ingredients', 'not like', '%cheese%');
                        $query->where('estimated_main_ingredients', 'not like', '%egg%');
                        break;
                    case 'Gluten-Free':
                        $query->where('estimated_main_ingredients', 'not like', '%wheat%');
                        $query->where('estimated_main_ingredients', 'not like', '%flour%');
                        break;
                    case 'Nut-Free':
                        $query->where('estimated_main_ingredients', 'not like', '%peanut%');
                        $query->where('estimated_main_ingredients', 'not like', '%almond%');
                        break;
                }
            }
        }

        $meal = $query->inRandomOrder()->first();

        // Fallback without calorie restriction
        if (!$meal) {
            $query = MenuItem::where('meal_category', $mealType);

            if ($preferredDiet === 'Vegetarian') {
                $query->where('food_type', 'Vegetarian');
            } elseif ($preferredDiet === 'Keto') {
                $query->where('carbs_max_g', '<=', 10);
            }

            $meal = $query->inRandomOrder()->first();
        }

        return $meal;
    }

    // REGENERATE SINGLE MEAL (keep for individual meal regeneration if needed)
    public function regenerateMeal(Request $request)
    {
        $mealType = $request->meal_type; // Breakfast, Lunch, or Dinner
        $day = $request->day;
        $calories = session('calories');
        $diet = session('selectedDiet');

        if (!$calories || !$diet) {
            return response()->json(['error' => 'Missing calories or diet'], 400);
        }

        $target = match ($mealType) {
            'Breakfast' => $calories * 0.3,
            'Lunch' => $calories * 0.4,
            'Dinner' => $calories * 0.3,
            default => $calories * 0.3,
        };

        $newMeal = $this->getMeal($diet, $mealType, $target);

        // Update session
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

    // DIET OPTION PAGE
    public function diet_option()
    {
        // Check if calories exist before showing diet options
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

    // Save food filters from the widget
    public function saveFoodFilters(Request $request)
    {
        session(['food_filters' => $request->filters]);

        // Regenerate meals to apply new exclude filters
        $this->regenerateAllMeals();

        return response()->json(['status' => 'success']);
    }
}
