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
                            @if($meal->calories_min)
                            <div class="bg-blue-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-blue-600 font-semibold">Calories</span>
                                <p class="text-base sm:text-lg font-bold text-blue-800">{{ $meal->calories_min }} kcal</p>
                            </div>
                            @endif

                            @if($meal->protein_min)
                            <div class="bg-green-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-green-600 font-semibold">Protein</span>
                                <p class="text-base sm:text-lg font-bold text-green-800">{{ $meal->protein_min }} g</p>
                            </div>
                            @endif

                            @if($meal->carbs_min)
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-yellow-600 font-semibold">Carbs</span>
                                <p class="text-base sm:text-lg font-bold text-yellow-800">{{ $meal->carbs_min }} g</p>
                            </div>
                            @endif

                            @if($meal->fat_min)
                            <div class="bg-purple-50 rounded-lg p-3">
                                <span class="text-xs sm:text-sm text-purple-600 font-semibold">Fat</span>
                                <p class="text-base sm:text-lg font-bold text-purple-800">{{ $meal->fat_min }} g</p>
                            </div>
                            @endif
                        </div>

                        {{-- Health labels as chips --}}
                        @if($meal->health_label)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach(explode(',', $meal->health_label) as $label)
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ trim($label) }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Notes --}}
                        @if($meal->notes)
                            <div class="mt-4 text-xs sm:text-sm text-gray-500 italic">
                                📝 {{ $meal->notes }}
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
        <p>⚠️ Ingredients may vary based on preparation. Always check with the restaurant.</p>
    </div>
</div>

</body>
</html>
