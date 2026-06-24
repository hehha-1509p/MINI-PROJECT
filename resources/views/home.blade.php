<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .food-checkbox {
        display: none;
    }

    .checkbox-label {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 4px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.9rem;
    }

    .state-badge {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 4px;
        text-align: center;
        line-height: 20px;
        font-weight: bold;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .state-badge.include {
        background-color: #22c55e;
        color: white;
    }

    .state-badge.exclude {
        background-color: #ef4444;
        color: white;
    }

    .state-badge.none {
        background-color: #e5e7eb;
        color: #9ca3af;
    }

    .checkbox-label .label-text {
        color: #374151;
    }

    .checkbox-label .label-text.excluded {
        text-decoration: line-through;
        color: #9ca3af;
    }

    .preference-btn.active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .preference-btn.inactive {
        background-color: #e5e7eb;
        color: #6b7280;
        border-color: #d1d5db;
    }

    /* Toast animation */
    @keyframes bounceIn {
        0% { transform: translateX(-50%) scale(0.8); opacity: 0; }
        60% { transform: translateX(-50%) scale(1.05); opacity: 1; }
        100% { transform: translateX(-50%) scale(1); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounceIn 0.5s ease-out;
    }
  </style>
</head>
<body class="font-sans min-h-screen relative bg-fixed bg-cover bg-center bg-no-repeat"style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20231028/pngtree-fresh-and-calming-watercolor-texture-background-in-light-mint-pastel-green-image_13758848.png');">

<div id="homePage" class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-3xl sm:text-4xl font-bold">NomNomNom</h1>
            <img src="{{ asset('images/diet.png') }}" alt="NomNomNom Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
        </div>

        {{-- SEARCH BAR --}}
        <form action="{{ route('search') }}" method="GET"
            class="flex w-full max-w-2xl shadow-md rounded-xl overflow-hidden bg-white h-12">
            <input type="text" name="query" placeholder="Search food, ingredients, breakfast, lunch, dinner..." class="flex-grow p-3 text-base sm:text-lg focus:outline-none" required>
            <button type="submit" class="bg-green-500 text-white px-6 sm:px-8 hover:bg-green-600 transition font-semibold whitespace-nowrap">
                Search
            </button>
        </form>

        {{-- User Profile / Auth Section --}}
        <div class="flex items-center space-x-4 relative">
            @auth
                {{-- Logged In: Profile Picture Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-10 h-10 rounded-full bg-gray-300 hover:bg-gray-400 transition flex items-center justify-center overflow-hidden focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            {{-- Default avatar with first letter of username --}}
                            <span class="text-gray-700 font-bold text-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200 z-50">

                        {{-- Username display --}}
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        {{-- Logout button --}}
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Alpine.js for dropdown --}}
                <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
            @else
                {{-- Guest: Show Login and Sign Up --}}
                <a href="/login" class="text-gray-600 hover:text-black font-medium">Log In</a>
                <a href="/register" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
            @endauth
        </div>
    </div>

    {{-- Preferred Diet --}}
    <h2 class="text-xl font-semibold mb-3 text-center">Preferred Diet</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <button onclick="selectDiet('Manual Change')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
            <img src="{{ asset('images/anything.jpeg') }}" class="w-16 h-16 mb-2 mix-blend-multiply" alt="Anything">
            <span>Manual Change</span>
        </button>
        <button onclick="selectDiet('Keto')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
            <img src="{{ asset('images/keto.jpeg') }}" class="w-16 h-16 mb-2" alt="Keto">
            <span>Keto</span>
        </button>
        <button onclick="selectDiet('Vegetarian')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
            <img src="{{ asset('images/vegeterian.jpeg') }}" class="w-16 h-16 mb-2" alt="Vegetarian">
            <span>Vegetarian</span>
        </button>
    </div>

    {{-- Split Layout --}}
    <div class="flex flex-col lg:flex-row justify-between items-start mb-3 gap-6 w-full">
        <div class="flex flex-col gap-4 w-full lg:w-auto">
            <p id="savedDiet" class="text-green-600 font-semibold text-lg m-0"></p>
            <button onclick="openCalculator()" class="bg-blue-400 text-white px-6 py-2 rounded-xl shadow hover:bg-blue-500 transition font-semibold w-max text-center">Calorie Calculator</button>
            <a href="/diet_option"
                onclick="return checkDietOptionAccess(event)"
                class="bg-red-400 text-white px-6 py-2 rounded-xl shadow hover:bg-red-500 transition font-semibold w-max text-center">
                Diet Option
            </a>
        </div>

        <div id="homeMacroResults" class="p-5 bg-blue-50 rounded-2xl border border-blue-100 shadow-sm w-full lg:w-auto min-w-[300px]">
            <h3 class="font-bold text-blue-800 mb-3">Your Daily Targets:</h3>
            <p class="text-lg mb-3">Calories: <b id="displayKcal" class="text-blue-600">-</b></p>
            <div class="flex flex-wrap justify-between gap-4 text-sm font-medium text-gray-700">
                <span>Protein: <span id="displayProtein">-</span></span>
                <span>Fat: <span id="displayFat">-</span></span>
                <span>Carbohydrate: <span id="displayCarbs">-</span></span>
            </div>
        </div>
    </div>

        @if(session('diet_plan'))
            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5 text-center">
                Current Diet Plan: <strong>{{ session('diet_plan') }}</strong>
            </div>
        @endif

        {{-- Warning Messages --}}
        @auth
            @php
                $hasCalories = session('calories') !== null;
                $hasDietPlan = session('diet_plan') !== null;
            @endphp

            @if(!$hasCalories)
                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl mb-5 text-center border border-yellow-300">
                    <strong>⚠️ Please calculate your calorie needs first.</strong>
                    Click the <strong>"Calorie Calculator"</strong> button above to set your daily targets before choosing a diet option.
                </div>
            @endif

            @if($hasCalories && !$hasDietPlan)
                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-xl mb-5 text-center border border-yellow-300">
                    <strong>⚠️ Welcome!</strong> Please choose your meal plan by clicking the <strong>"Diet Option"</strong> button above.
                </div>
            @endif
        @endauth

    {{-- Food Filter & Meal Plan --}}
    <div class="flex flex-col xl:flex-row gap-6 relative">

        {{-- Food Filter Widget --}}
        <div class="bg-white p-4 rounded-2xl shadow-xl w-full xl:w-96 h-fit">
            <h3 class="font-semibold leading-tight">Food Filter</h3>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-2 rounded mb-3">
                <p class="text-xs text-gray-700">
                    Click once to include <span class="text-green-600 font-bold">✓</span>, twice to exclude <span class="text-red-600 font-bold">✗</span>, three times to clear.
                </p>
            </div>

            <div class="flex gap-2 mb-4">
                <button onclick="selectAllFoods()" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
                    Select All
                </button>
                <button onclick="unselectAllFoods()" class="bg-gray-400 text-white px-3 py-1 rounded text-sm hover:bg-gray-500 transition">
                    Unselect All
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto">
                <!-- Meat -->
                <div class="mb-2">
                    <h4 class="font-bold">Meat 🍖</h4>
                    <label>
                        <input type="checkbox" value="Chicken" class="food-checkbox" data-category="Meat" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Chicken</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Beef" class="food-checkbox" data-category="Meat" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Beef</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Lamb" class="food-checkbox" data-category="Meat" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Lamb</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Pork" class="food-checkbox" data-category="Meat" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Pork</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Duck" class="food-checkbox" data-category="Meat" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Duck</span>
                        </span>
                    </label>
                </div>

                <!-- Seafood -->
                <div class="mb-2">
                    <h4 class="font-bold">Seafood 🦐</h4>
                    <label>
                        <input type="checkbox" value="Fish" class="food-checkbox" data-category="Seafood" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Fish</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Prawn" class="food-checkbox" data-category="Seafood" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Prawn</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Crab" class="food-checkbox" data-category="Seafood" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Crab</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Squid" class="food-checkbox" data-category="Seafood" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Squid</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Shellfish" class="food-checkbox" data-category="Seafood" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Shellfish</span>
                        </span>
                    </label>
                </div>

                <!-- Vegetables -->
                <div class="mb-2">
                    <h4 class="font-bold">Vegetables 🥦</h4>
                    <label>
                        <input type="checkbox" value="Broccoli" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Broccoli</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Spinach" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Spinach</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Mushroom" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Mushroom</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Onion" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Onion</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Tofu" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Tofu</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Egg" class="food-checkbox" data-category="Vegetables" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Egg</span>
                        </span>
                    </label>
                </div>

                <!-- Carbs -->
                <div class="mb-2">
                    <h4 class="font-bold">Carbs 🍞</h4>
                    <label>
                        <input type="checkbox" value="Rice" class="food-checkbox" data-category="Carbs" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Rice</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Bread" class="food-checkbox" data-category="Carbs" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Bread</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Pasta" class="food-checkbox" data-category="Carbs" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Pasta</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Potato" class="food-checkbox" data-category="Carbs" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Potato</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Noodles" class="food-checkbox" data-category="Carbs" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Noodles</span>
                        </span>
                    </label>
                </div>

                <!-- Dairy -->
                <div class="mb-2">
                    <h4 class="font-bold">Dairy 🧀</h4>
                    <label>
                        <input type="checkbox" value="Milk" class="food-checkbox" data-category="Dairy" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Milk</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Cheese" class="food-checkbox" data-category="Dairy" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Cheese</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Yogurt" class="food-checkbox" data-category="Dairy" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Yogurt</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Butter" class="food-checkbox" data-category="Dairy" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Butter</span>
                        </span>
                    </label>
                </div>

                <!-- Nuts -->
                <div class="mb-2">
                    <h4 class="font-bold">Nuts 🥜</h4>
                    <label>
                        <input type="checkbox" value="Peanuts" class="food-checkbox" data-category="Nuts" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Peanuts</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Almonds" class="food-checkbox" data-category="Nuts" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Almonds</span>
                        </span>
                    </label><br>
                    <label>
                        <input type="checkbox" value="Walnuts" class="food-checkbox" data-category="Nuts" data-state="none">
                        <span class="checkbox-label">
                            <span class="state-badge none">□</span>
                            <span class="label-text">Walnuts</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="mt-2 pt-3 border-t border-gray-200">
                <h4 class="font-bold mb-1">Preferences & Allergies ☪️</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <button onclick="togglePreference('Pork-Free Only')"
                            class="preference-btn px-3 py-1 rounded text-sm font-medium transition border-2 border-gray-300 hover:border-blue-400 text-gray-600"
                            data-preference="Pork-Free Only">
                        Pork-Free Only
                    </button>
                    <button onclick="togglePreference('Vegan')"
                            class="preference-btn px-3 py-1 rounded text-sm font-medium transition border-2 border-gray-300 hover:border-blue-400 text-gray-600"
                            data-preference="Vegan">
                        Vegan
                    </button>
                    <button onclick="togglePreference('Gluten-Free')"
                            class="preference-btn px-3 py-1 rounded text-sm font-medium transition border-2 border-gray-300 hover:border-blue-400 text-gray-600"
                            data-preference="Gluten-Free">
                        Gluten-Free
                    </button>
                    <button onclick="togglePreference('Nut-Free')"
                            class="preference-btn px-3 py-1 rounded text-sm font-medium transition border-2 border-gray-300 hover:border-blue-400 text-gray-600"
                            data-preference="Nut-Free">
                        Nut-Free
                    </button>
                </div>
            </div>
        </div>

        {{-- Meal Plan Days Widget --}}
        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-xl flex-1 overflow-x-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4 mb-4">
                <h3 class="text-xl font-semibold">Meal Plan Days 📅</h3>
                <button onclick="regenerateAllDays()"
                        class="bg-purple-500 text-white px-4 py-2 rounded-xl hover:bg-purple-600 transition font-semibold text-sm sm:text-base whitespace-nowrap w-full sm:w-auto">
                    Regenerate All Days
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                    $colors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500', 'text-blue-500', 'text-purple-500', 'text-pink-500'];
                @endphp

                {{-- Row 1: Monday, Tuesday, Wednesday --}}
                @foreach(['Monday', 'Tuesday', 'Wednesday'] as $index => $day)
                    @php $dayIndex = array_search($day, $days); @endphp
                    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
                        <h4 class="text-lg font-bold text-center mb-2 border-b pb-1 {{ $colors[$dayIndex] }}">{{ $day }}</h4>

                        <div id="meals-{{ $day }}" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['breakfast']) && $meals[$day]['breakfast'] ? $meals[$day]['breakfast']->item_name : 'No meal' }}
                                </span>
                            </div>
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Lunch</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['lunch']) && $meals[$day]['lunch'] ? $meals[$day]['lunch']->item_name : 'No meal' }}
                                </span>
                            </div>
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Dinner</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['dinner']) && $meals[$day]['dinner'] ? $meals[$day]['dinner']->item_name : 'No meal' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-auto">
                            <button onclick="regenerateDay('{{ $day }}')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
                            <button onclick="viewIngredients('{{ $day }}')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
                        </div>
                    </div>
                @endforeach

                {{-- Row 2: Thursday, Friday, Saturday --}}
                @foreach(['Thursday', 'Friday', 'Saturday'] as $index => $day)
                    @php $dayIndex = array_search($day, $days); @endphp
                    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
                        <h4 class="text-lg font-bold text-center mb-2 border-b pb-1 {{ $colors[$dayIndex] }}">{{ $day }}</h4>

                        <div id="meals-{{ $day }}" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['breakfast']) && $meals[$day]['breakfast'] ? $meals[$day]['breakfast']->item_name : 'No meal' }}
                                </span>
                            </div>
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Lunch</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['lunch']) && $meals[$day]['lunch'] ? $meals[$day]['lunch']->item_name : 'No meal' }}
                                </span>
                            </div>
                            <div class="bg-white p-2 rounded border border-gray-200">
                                <span class="text-xs text-gray-500 font-bold block">Dinner</span>
                                <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['dinner']) && $meals[$day]['dinner'] ? $meals[$day]['dinner']->item_name : 'No meal' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-auto">
                            <button onclick="regenerateDay('{{ $day }}')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
                            <button onclick="viewIngredients('{{ $day }}')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
                        </div>
                    </div>
                @endforeach

                {{-- Row 3: Invisible, Sunday, Invisible --}}
                <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full opacity-0 invisible"></div>

                <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
                    @php $day = 'Sunday'; $dayIndex = array_search($day, $days); @endphp
                    <h4 class="text-lg font-bold text-center mb-2 border-b pb-1 {{ $colors[$dayIndex] }}">{{ $day }}</h4>

                    <div id="meals-{{ $day }}" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
                        <div class="bg-white p-2 rounded border border-gray-200">
                            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
                            <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['breakfast']) && $meals[$day]['breakfast'] ? $meals[$day]['breakfast']->item_name : 'No meal' }}
                            </span>
                        </div>
                        <div class="bg-white p-2 rounded border border-gray-200">
                            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
                            <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['lunch']) && $meals[$day]['lunch'] ? $meals[$day]['lunch']->item_name : 'No meal' }}
                            </span>
                        </div>
                        <div class="bg-white p-2 rounded border border-gray-200">
                            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
                            <span class="text-gray-800 break-words">
                                {{ isset($meals[$day]['dinner']) && $meals[$day]['dinner'] ? $meals[$day]['dinner']->item_name : 'No meal' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-auto">
                        <button onclick="regenerateDay('{{ $day }}')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
                        <button onclick="viewIngredients('{{ $day }}')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full opacity-0 invisible"></div>
            </div>

            {{-- Feedback Section --}}
            <div class="w-full flex justify-center mt-10 mb-5">
                <a href="https://forms.gle/7eqAqZ5cTTQLib2B9" target="_blank" class="bg-green-500 text-white px-8 py-3 rounded-xl shadow hover:bg-green-600 transition font-semibold text-center">
                    Feedback Form
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Calorie Calculator --}}
<div id="calculatorPage" class="hidden py-12 flex justify-center px-4">
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow w-full max-w-[400px]">
        <h2 class="text-2xl font-bold mb-4 text-center">Calorie Calculator</h2>

        <form id="calorieForm" onsubmit="event.preventDefault(); calculateCalories();">
        <input id="height" type="number" placeholder="Height (cm)" min="120" max="250" required class="w-full p-2 border rounded mb-2">
        <input id="weight" type="number" placeholder="Weight (kg)" min="25" max="250" required class="w-full p-2 border rounded mb-2">

        <select id="sex" class="w-full p-2 border rounded mb-2">
            <option>Male</option>
            <option>Female</option>
        </select>

        <select id="age" class="w-full p-2 border rounded mb-2">
            @for ($i = 18; $i <= 25; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <select id="activity" class="w-full p-2 border rounded mb-2">
            <option value="1.2">No Exercise</option>
            <option value="1.375">Light Exercise</option>
            <option value="1.725">Heavy Exercise</option>
        </select>

        <select id="goal" class="w-full p-2 border rounded mb-4">
            <option value="maintain">Maintain Weight</option>
            <option value="lose">Lose Weight</option>
            <option value="gain">Gain Weight</option>
        </select>

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition font-bold">Calculate</button>
        </form>

        <div id="result" class="mt-4 p-4 bg-gray-50 rounded text-gray-800 text-lg empty:hidden"></div>

        <button onclick="goHome()" class="w-full bg-red-400 text-white p-2 rounded hover:bg-red-500 transition font-bold mt-4">Back to Home Page</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Load persisted manual modification flag from localStorage
    let userManuallyModified = localStorage.getItem('userManuallyModified') === 'true';
    let currentDiet = localStorage.getItem('preferred_diet') || 'Anything';
    let activePreferences = [];

    // --- Toast Notification System ---
    function showToast(message, type = 'warning') {
        // Remove existing toast if any
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) {
            existingToast.remove();
        }

        const colors = {
            warning: 'bg-yellow-500',
            error: 'bg-red-500',
            success: 'bg-green-500',
            info: 'bg-blue-500'
        };

        const toast = document.createElement('div');
        toast.className = `custom-toast fixed top-4 left-1/2 transform -translate-x-1/2 z-50 ${colors[type] || 'bg-gray-700'} text-white px-6 py-3 rounded-xl shadow-lg text-center max-w-md w-full mx-4 animate-bounce-in`;
        toast.innerHTML = `
            <div class="flex items-center justify-between gap-4">
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 text-xl leading-none">×</button>
            </div>
        `;
        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    // --- Access Control Functions ---
    window.checkDietOptionAccess = function(event) {
        // Check if user is logged in
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const hasCalories = {{ session('calories') !== null ? 'true' : 'false' }};

        if (!isLoggedIn) {
            event.preventDefault();
            showToast('Please login before choosing diet option.', 'warning');
            return false;
        }

        if (!hasCalories) {
            event.preventDefault();
            showToast('Please calculate your calorie needs first using the Calorie Calculator.', 'warning');
            return false;
        }

        return true;
    };

    // --- Three-state checkbox handler ---
    function updateLabelDisplay(checkbox) {
        const label = checkbox.parentElement.querySelector('.checkbox-label');
        if (!label) return;

        const state = checkbox.dataset.state;
        const text = checkbox.value;

        let badge = label.querySelector('.state-badge');
        let textSpan = label.querySelector('.label-text');

        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'state-badge';
            label.prepend(badge);
        }

        if (!textSpan) {
            textSpan = document.createElement('span');
            textSpan.className = 'label-text';
            label.appendChild(textSpan);
        }

        badge.className = 'state-badge';
        if (state === 'include') {
            badge.classList.add('include');
            badge.textContent = '✓';
        } else if (state === 'exclude') {
            badge.classList.add('exclude');
            badge.textContent = '✗';
        } else {
            badge.classList.add('none');
            badge.textContent = '□';
        }

        textSpan.textContent = text;
        textSpan.className = 'label-text';
        if (state === 'exclude') {
            textSpan.classList.add('excluded');
        }
    }

    // Initialize all checkbox labels
    document.querySelectorAll('.food-checkbox').forEach(cb => {
        updateLabelDisplay(cb);
    });

    // --- Three-state click handler ---
    document.querySelectorAll('.food-checkbox').forEach(cb => {
        cb.addEventListener('click', function(e) {
            e.preventDefault();

            const currentState = this.dataset.state;
            let newState;

            if (currentState === 'none') {
                newState = 'include';
            } else if (currentState === 'include') {
                newState = 'exclude';
            } else {
                newState = 'none';
            }

            this.dataset.state = newState;
            updateLabelDisplay(this);

            userManuallyModified = true;
            localStorage.setItem('userManuallyModified', 'true');
            saveFoodFilters();
        });
    });

    // --- Preference Toggle ---
    window.togglePreference = function(pref) {
        const btn = document.querySelector(`[data-preference="${pref}"]`);
        const index = activePreferences.indexOf(pref);

        if (index > -1) {
            activePreferences.splice(index, 1);
            btn.classList.remove('active');
            btn.classList.add('inactive');

            // Clear the cross effect when unselecting
            if (pref === 'Vegan') {
                // Clear Meat
                document.querySelectorAll('.food-checkbox[data-category="Meat"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                    cb.disabled = false;
                    cb.parentElement.style.opacity = '1';
                    cb.parentElement.style.cursor = 'pointer';
                });
                // Clear Seafood
                document.querySelectorAll('.food-checkbox[data-category="Seafood"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                    cb.disabled = false;
                    cb.parentElement.style.opacity = '1';
                    cb.parentElement.style.cursor = 'pointer';
                });
            } else if (pref === 'Gluten-Free') {
                // Clear only Bread, Pasta, Noodles
                document.querySelectorAll('.food-checkbox[value="Bread"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                });
                document.querySelectorAll('.food-checkbox[value="Pasta"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                });
                document.querySelectorAll('.food-checkbox[value="Noodles"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                });
            } else if (pref === 'Nut-Free') {
                document.querySelectorAll('.food-checkbox[data-category="Nuts"]').forEach(cb => {
                    if (cb.dataset.state === 'exclude') {
                        cb.dataset.state = 'none';
                        updateLabelDisplay(cb);
                    }
                });
            }
        } else {
            activePreferences.push(pref);
            btn.classList.add('active');
            btn.classList.remove('inactive');
            applyPreferenceEffects(pref);
        }

        userManuallyModified = true;
        localStorage.setItem('userManuallyModified', 'true');
        saveFoodFilters();
    };

    function applyPreferenceEffects(pref) {
        if (pref === 'Vegan') {
            // Cross out (exclude) ALL Meat and Seafood checkboxes
            document.querySelectorAll('.food-checkbox[data-category="Meat"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
                // DO NOT disable - keep clickable
                cb.disabled = false;
                cb.parentElement.style.opacity = '1';
                cb.parentElement.style.cursor = 'pointer';
            });
            document.querySelectorAll('.food-checkbox[data-category="Seafood"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
                // DO NOT disable - keep clickable
                cb.disabled = false;
                cb.parentElement.style.opacity = '1';
                cb.parentElement.style.cursor = 'pointer';
            });
        } else if (pref === 'Gluten-Free') {
            // Exclude Bread (wheat-based)
            document.querySelectorAll('.food-checkbox[value="Bread"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
            });
            // Exclude Pasta (wheat-based)
            document.querySelectorAll('.food-checkbox[value="Pasta"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
            });
            // Exclude Noodles (wheat-based - note: rice noodles are fine, but we can't differentiate here)
            document.querySelectorAll('.food-checkbox[value="Noodles"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
            });
        } else if (pref === 'Nut-Free') {
            document.querySelectorAll('.food-checkbox[data-category="Nuts"]').forEach(cb => {
                cb.dataset.state = 'exclude';
                updateLabelDisplay(cb);
            });
        }
    }

    // --- Save Food Filters ---
    function saveFoodFilters() {
        const includedFoods = [];
        const excludedFoods = [];

        document.querySelectorAll('.food-checkbox').forEach(cb => {
            const state = cb.dataset.state;
            if (state === 'include') {
                includedFoods.push(cb.value);
            } else if (state === 'exclude') {
                excludedFoods.push(cb.value);
            }
        });

        fetch("/save-food-filters", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                filters: {
                    foods: includedFoods,
                    excluded: excludedFoods,
                    preferences: activePreferences
                }
            })
        });
    }

    // --- Restore Saved Filters ---
    function restoreSavedFilters() {
        fetch("/get-food-filters", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.filters) {
                // Reset all checkboxes
                document.querySelectorAll('.food-checkbox').forEach(cb => {
                    cb.dataset.state = 'none';
                    updateLabelDisplay(cb);
                });

                // Restore included foods
                if (data.filters.foods) {
                    document.querySelectorAll('.food-checkbox').forEach(cb => {
                        if (data.filters.foods.includes(cb.value)) {
                            cb.dataset.state = 'include';
                            updateLabelDisplay(cb);
                        }
                    });
                }

                // Restore excluded foods
                if (data.filters.excluded) {
                    document.querySelectorAll('.food-checkbox').forEach(cb => {
                        if (data.filters.excluded.includes(cb.value)) {
                            cb.dataset.state = 'exclude';
                            updateLabelDisplay(cb);
                        }
                    });
                }

                // Restore preferences
                if (data.filters.preferences) {
                    activePreferences = data.filters.preferences;
                    document.querySelectorAll('.preference-btn').forEach(btn => {
                        const pref = btn.dataset.preference;
                        if (activePreferences.includes(pref)) {
                            btn.classList.add('active');
                            btn.classList.remove('inactive');
                            if (pref !== 'Pork-Free Only') {
                                applyPreferenceEffects(pref);
                            }
                        } else {
                            btn.classList.remove('active');
                            btn.classList.add('inactive');
                        }
                    });
                }
            }
        });
    }

    // --- Select Diet ---
    window.selectDiet = function(diet) {
        currentDiet = diet;
        userManuallyModified = false;
        localStorage.setItem('userManuallyModified', 'false');
        localStorage.setItem('preferred_diet', diet);
        document.getElementById('savedDiet').innerText = "Saved Diet: " + diet;
        applyDietFilter(diet);

        fetch("/save-preferred-diet", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ diet: diet })
        });
    };

    // --- Apply Diet Filter ---
    const dietBlockMap = {
        Anything: [],
        Keto: ["Meat", "Seafood"],
        Vegetarian: ["Vegetables", "Dairy", "Nuts"]
    };

    function applyDietFilter(diet) {
        const allowedCategories = dietBlockMap[diet] || [];
        document.querySelectorAll('.food-checkbox').forEach(cb => {
            const category = cb.dataset.category;
            // Skip if disabled (Vegan case)
            if (cb.disabled) return;

            if (diet === "Anything") {
                cb.dataset.state = 'none';
                updateLabelDisplay(cb);
            } else if (allowedCategories.includes(category)) {
                cb.dataset.state = 'include';
                updateLabelDisplay(cb);
            } else {
                cb.dataset.state = 'none';
                updateLabelDisplay(cb);
            }
        });
        saveFoodFilters();
    }

    // --- Select All / Unselect All ---
    window.selectAllFoods = function() {
        document.querySelectorAll('.food-checkbox').forEach(cb => {
            cb.dataset.state = 'include';
            updateLabelDisplay(cb);
        });
        userManuallyModified = true;
        localStorage.setItem('userManuallyModified', 'true');
        saveFoodFilters();
    };

    window.unselectAllFoods = function() {
        document.querySelectorAll('.food-checkbox').forEach(cb => {
            cb.dataset.state = 'none';
            updateLabelDisplay(cb);
            cb.disabled = false; // Re-enable all
            cb.parentElement.style.opacity = '1';
            cb.parentElement.style.cursor = 'default';
        });
        // Also clear preferences
        activePreferences = [];
        document.querySelectorAll('.preference-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('inactive');
        });
        userManuallyModified = true;
        localStorage.setItem('userManuallyModified', 'true');
        saveFoodFilters();
    };

    // --- Regenerate Functions ---
    window.regenerateDay = async function(day) {
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const hasDietPlan = {{ session('diet_plan') ? 'true' : 'false' }};
        const hasCalories = {{ session('calories') !== null ? 'true' : 'false' }};

        if (!isLoggedIn) {
            showToast('Please login before regenerating meals.', 'warning');
            return;
        }

        if (!hasCalories) {
            showToast('Please calculate your calorie needs first.', 'warning');
            return;
        }

        if (!hasDietPlan) {
            showToast('Please choose a diet option before regenerating meals.', 'warning');
            return;
        }

        const buttons = document.querySelectorAll(`[onclick="regenerateDay('${day}')"]`);
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.textContent = 'Regenerating...';
        });

        try {
            const response = await fetch("/regenerate-day", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ day: day })
            });

            const data = await response.json();

            if (data.meals) {
                const mealsContainer = document.getElementById(`meals-${day}`);
                if (mealsContainer) {
                    const breakfastDiv = mealsContainer.children[0];
                    const lunchDiv = mealsContainer.children[1];
                    const dinnerDiv = mealsContainer.children[2];

                    if (breakfastDiv && data.meals.breakfast) {
                        breakfastDiv.querySelector('span:last-child').innerHTML = data.meals.breakfast.item_name;
                    }
                    if (lunchDiv && data.meals.lunch) {
                        lunchDiv.querySelector('span:last-child').innerHTML = data.meals.lunch.item_name;
                    }
                    if (dinnerDiv && data.meals.dinner) {
                        dinnerDiv.querySelector('span:last-child').innerHTML = data.meals.dinner.item_name;
                    }
                }
            }
        } catch (error) {
            console.error('Error regenerating day:', error);
            showToast('Failed to regenerate meals. Please try again.', 'error');
        } finally {
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.textContent = 'Regenerate';
            });
        }
    };

    window.regenerateAllDays = async function() {
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const hasDietPlan = {{ session('diet_plan') ? 'true' : 'false' }};
        const hasCalories = {{ session('calories') !== null ? 'true' : 'false' }};

        if (!isLoggedIn) {
            showToast('Please login before regenerating meals.', 'warning');
            return;
        }

        if (!hasCalories) {
            showToast('Please calculate your calorie needs first.', 'warning');
            return;
        }

        if (!hasDietPlan) {
            showToast('Please choose a diet option before regenerating meals.', 'warning');
            return;
        }

        const button = document.querySelector('[onclick="regenerateAllDays()"]');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'Regenerating...';

        try {
            const response = await fetch("/regenerate-all", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.status === 'success' && data.meals) {
                const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                days.forEach(day => {
                    const mealsContainer = document.getElementById(`meals-${day}`);
                    if (mealsContainer && data.meals[day]) {
                        const breakfastDiv = mealsContainer.children[0];
                        const lunchDiv = mealsContainer.children[1];
                        const dinnerDiv = mealsContainer.children[2];

                        if (breakfastDiv) {
                            const span = breakfastDiv.querySelector('span:last-child');
                            span.innerHTML = data.meals[day].breakfast ? data.meals[day].breakfast.item_name : 'No meal';
                        }
                        if (lunchDiv) {
                            const span = lunchDiv.querySelector('span:last-child');
                            span.innerHTML = data.meals[day].lunch ? data.meals[day].lunch.item_name : 'No meal';
                        }
                        if (dinnerDiv) {
                            const span = dinnerDiv.querySelector('span:last-child');
                            span.innerHTML = data.meals[day].dinner ? data.meals[day].dinner.item_name : 'No meal';
                        }
                    }
                });
                showToast('All meals regenerated successfully!', 'success');
            }
        } catch (error) {
            console.error('Error regenerating all days:', error);
            showToast('Failed to regenerate meals. Please try again.', 'error');
        } finally {
            button.disabled = false;
            button.textContent = originalText;
        }
    };

    // --- View Ingredients ---
    window.viewIngredients = async function(day) {
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        const hasDietPlan = {{ session('diet_plan') ? 'true' : 'false' }};
        const hasCalories = {{ session('calories') !== null ? 'true' : 'false' }};

        if (!isLoggedIn) {
            showToast('Please login before viewing ingredients.', 'warning');
            return;
        }

        if (!hasCalories) {
            showToast('Please calculate your calorie needs first.', 'warning');
            return;
        }

        if (!hasDietPlan) {
            showToast('Please choose a diet option before viewing ingredients.', 'warning');
            return;
        }

        try {
            const response = await fetch("/get-ingredients", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ day: day })
            });

            if (response.redirected || response.ok) {
                window.location.href = "/ingredients";
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Could not load ingredients page', 'error');
        }
    };

    // --- Calorie Calculator ---
    function loadMacroData() {
        const savedData = localStorage.getItem('macroData');
        if (savedData) {
            const parsedData = JSON.parse(savedData);
            document.getElementById('displayKcal').innerText = parsedData.kcal + ' kcal';
            document.getElementById('displayProtein').innerText = parsedData.protein;
            document.getElementById('displayFat').innerText = parsedData.fat;
            document.getElementById('displayCarbs').innerText = parsedData.carbs;
        } else {
            // Show dashes if no data
            document.getElementById('displayKcal').innerText = '-';
            document.getElementById('displayProtein').innerText = '-';
            document.getElementById('displayFat').innerText = '-';
            document.getElementById('displayCarbs').innerText = '-';
        }
    }

    function resetCalculator() {
        const resultDiv = document.getElementById('result');
        if (resultDiv) {
            resultDiv.innerHTML = "";
            resultDiv.classList.add('hidden');
        }
    }

    window.openCalculator = function() {
        resetCalculator();
        document.getElementById('homePage').classList.add('hidden');
        document.getElementById('calculatorPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    };

    window.goHome = function() {
        resetCalculator();
        document.getElementById('calculatorPage').classList.add('hidden');
        document.getElementById('homePage').classList.remove('hidden');
        loadMacroData();
        window.scrollTo(0, 0);
    };

    window.calculateCalories = function() {
        const weight = parseFloat(document.getElementById('weight').value);
        const height = parseFloat(document.getElementById('height').value);
        const age = parseFloat(document.getElementById('age').value);
        const activity = parseFloat(document.getElementById('activity').value);
        const sex = document.getElementById('sex').value;
        const goal = document.getElementById('goal').value;

        if (!weight || !height) return alert("Enter height & weight");

        let bmr = (sex === 'Male')
            ? 10 * weight + 6.25 * height - 5 * age + 5
            : 10 * weight + 6.25 * height - 5 * age - 161;

        let calories = bmr * activity;

        if (goal === 'lose') calories -= 300;
        if (goal === 'gain') calories += 300;

        const protein = (calories * 0.3) / 4;
        const fat = (calories * 0.25) / 9;
        const carbs = (calories * 0.45) / 4;

        const macroData = {
            kcal: calories.toFixed(0),
            protein: protein.toFixed(0) + 'g',
            fat: fat.toFixed(0) + 'g',
            carbs: carbs.toFixed(0) + 'g'
        };

        localStorage.setItem('macroData', JSON.stringify(macroData));

        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = `Daily Calories: <b>${calories.toFixed(0)} kcal</b><br>
            Protein: <b>${protein.toFixed(0)}g</b><br>
            Fat: <b>${fat.toFixed(0)}g</b><br>
            Carbs: <b>${carbs.toFixed(0)}g</b>`;
        resultDiv.classList.remove('hidden');

        fetch("/save-calories", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                calories: calories.toFixed(0)
            })
        })
        .then(response => response.json())
        .then(data => {
            // ✅ Reload the page to refresh the session state
            window.location.reload();
        })
        .catch(error => {
            console.error('Error saving calories:', error);
            // Still reload to show the updated state
            window.location.reload();
        });
    };
    // --- Initialize ---
    const savedPreferredDiet = localStorage.getItem('preferred_diet');
    if (savedPreferredDiet) {
        document.getElementById('savedDiet').innerText = "Saved Diet: " + savedPreferredDiet;
        currentDiet = savedPreferredDiet;

        if (!userManuallyModified) {
            applyDietFilter(savedPreferredDiet);
        } else {
            restoreSavedFilters();
        }
    }

    // Initialize preference buttons
    document.querySelectorAll('.preference-btn').forEach(btn => {
        btn.classList.add('inactive');
    });

    loadMacroData();

    window.togglePreference = togglePreference;
});

// Clear calories on logout (for when the user logs out)
document.addEventListener('logout', function() {
    localStorage.removeItem('macroData');
    loadMacroData();
});
</script>
</body>
</html>
