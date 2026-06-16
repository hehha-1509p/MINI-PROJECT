<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ingredients - {{ $day }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto px-4 sm:px-6 py-4 sm:py-8 max-w-4xl">
    {{-- Header --}}
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 text-center sm:text-left">
            📋 Ingredients for {{ $day }}
        </h1>
        <a href="/" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-center w-full sm:w-auto">
            ← Back to Meal Plan
        </a>
    </div>

    @php
        $mealTimes = ['breakfast', 'lunch', 'dinner'];
    @endphp

    @foreach($mealTimes as $mealTime)
        @php
            $meal = $meals[$mealTime] ?? null;
            $mealName = ucfirst($mealTime);
        @endphp

        <div class="bg-white rounded-2xl shadow-lg mb-4 sm:mb-6 overflow-hidden">
            {{-- Meal header with gradient --}}
            <div class="bg-gradient-to-r from-orange-400 to-red-400 px-4 sm:px-6 py-3">
                <h2 class="text-xl sm:text-2xl font-bold text-white">{{ $mealName }}</h2>
            </div>

            <div class="p-4 sm:p-6">
                @if($meal)
                    <div class="mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 break-words">{{ $meal->item_name }}</h3>

                        @if($meal->estimated_main_ingredients)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="font-bold text-gray-700 mb-2">🥗 Main Ingredients:</h4>
                                <p class="text-gray-600 break-words">{{ $meal->estimated_main_ingredients }}</p>
                            </div>
                        @endif

                        {{-- Nutrition grid - responsive columns --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            {{-- Calories --}}
                            @if($meal->calories_min || $meal->calories_max)
                            <div class="bg-blue-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-blue-600 font-semibold">Calories</span>
                                <p class="text-base sm:text-lg font-bold text-blue-800">
                                    @if($meal->calories_min && $meal->calories_max && $meal->calories_min != $meal->calories_max)
                                        {{ $meal->calories_min }} - {{ $meal->calories_max }} kcal
                                    @elseif($meal->calories_min)
                                        {{ $meal->calories_min }} kcal
                                    @elseif($meal->calories_max)
                                        {{ $meal->calories_max }} kcal
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            @endif

                            {{-- Protein --}}
                            @if($meal->protein_min || $meal->protein_max)
                            <div class="bg-green-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-green-600 font-semibold">Protein</span>
                                <p class="text-base sm:text-lg font-bold text-green-800">
                                    @if($meal->protein_min && $meal->protein_max && $meal->protein_min != $meal->protein_max)
                                        {{ $meal->protein_min }} - {{ $meal->protein_max }} g
                                    @elseif($meal->protein_min)
                                        {{ $meal->protein_min }} g
                                    @elseif($meal->protein_max)
                                        {{ $meal->protein_max }} g
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            @endif

                            {{-- Carbs --}}
                            @if($meal->carbs_min || $meal->carbs_max)
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-yellow-600 font-semibold">Carbs</span>
                                <p class="text-base sm:text-lg font-bold text-yellow-800">
                                    @if($meal->carbs_min && $meal->carbs_max && $meal->carbs_min != $meal->carbs_max)
                                        {{ $meal->carbs_min }} - {{ $meal->carbs_max }} g
                                    @elseif($meal->carbs_min)
                                        {{ $meal->carbs_min }} g
                                    @elseif($meal->carbs_max)
                                        {{ $meal->carbs_max }} g
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            @endif

                            {{-- Fat --}}
                            @if($meal->fat_min || $meal->fat_max)
                            <div class="bg-purple-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-purple-600 font-semibold">Fat</span>
                                <p class="text-base sm:text-lg font-bold text-purple-800">
                                    @if($meal->fat_min && $meal->fat_max && $meal->fat_min != $meal->fat_max)
                                        {{ $meal->fat_min }} - {{ $meal->fat_max }} g
                                    @elseif($meal->fat_min)
                                        {{ $meal->fat_min }} g
                                    @elseif($meal->fat_max)
                                        {{ $meal->fat_max }} g
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>

                        {{-- Sodium Level --}}
                        @if($meal->sodium_level)
                        <div class="mt-3">
                            <span class="text-xs sm:text-sm font-semibold text-gray-600">Sodium Level:</span>
                            <span class="ml-2 text-xs sm:text-sm px-2 py-1 rounded-full
                                @if($meal->sodium_level == 'Low') bg-green-100 text-green-700
                                @elseif($meal->sodium_level == 'Medium') bg-yellow-100 text-yellow-700
                                @elseif($meal->sodium_level == 'High') bg-orange-100 text-orange-700
                                @elseif($meal->sodium_level == 'Very High') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif
                            ">{{ $meal->sodium_level }}</span>
                        </div>
                        @endif

                        {{-- Health labels as chips --}}
                        @if($meal->health_label)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach(explode(',', $meal->health_label) as $label)
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ trim($label) }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Pork-Free Status --}}
                        @if($meal->halal_status)
                            <div class="mt-3">
                                <span class="text-xs sm:text-sm font-semibold text-gray-600">Pork-Free Status:</span>
                                <span class="ml-2 text-xs sm:text-sm px-2 py-1 rounded-full
                                    @if($meal->halal_status == 'Pork-Free') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700
                                    @endif
                                ">{{ $meal->halal_status }}</span>
                            </div>
                        @endif

                        {{-- Price --}}
                        @if($meal->price_default_hot)
                            <div class="mt-3">
                                <span class="text-xs sm:text-sm font-semibold text-gray-600">Price:</span>
                                <span class="ml-2 text-sm font-bold text-gray-800">RM {{ number_format($meal->price_default_hot, 2) }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm sm:text-base">No meal selected for {{ $mealName }}</p>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Footer note --}}
    <div class="text-center text-gray-500 text-xs sm:text-sm mt-6 sm:mt-8 px-2">
        <p>⚠️ Ingredients and nutrition may vary based on preparation. Always check with the restaurant.</p>
    </div>
</div>

</body>
</html>
