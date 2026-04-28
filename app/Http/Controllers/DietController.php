<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DietController extends Controller
{
    // This function will be for the page that ONLY shows diet options
    public function index()
    {
        $dietPlans = $this->getDietData();
        return view('diet_option', compact('dietPlans'));
    }

    // Helper function to keep your data in one place
    private function getDietData() {
        return [
            ['name' => 'Fully Balanced', 'description' => 'Equal nutrients daily.', 'tag' => 'Stable', 'color' => 'blue'],
            ['name' => 'Weekday Focus', 'description' => 'Strict Mon-Fri tracking.', 'tag' => 'Work Week', 'color' => 'green'],
            ['name' => 'Weekend Boost', 'description' => 'Light increase for Sat/Sun.', 'tag' => 'Flexible', 'color' => 'orange']
        ];
    }
}