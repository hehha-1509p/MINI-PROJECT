<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DietController extends Controller
{
    public function diet_option()
    {
        return view('diet_option');
    }
}
