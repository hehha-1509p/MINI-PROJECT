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

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">📋 Ingredients for {{ $day }}</h1>
        <a href="/" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
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

        <div class="bg-white rounded-2xl shadow-lg mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-400 to-red-400 px-6 py-3">
                <h2 class="text-2xl font-bold text-white">{{ $mealName }}</h2>
            </div>

            <div class="p-6">
                @if($meal)
                    <div class="mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $meal->item_name }}</h3>

                        @if($meal->estimated_main_ingredients)
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="font-bold text-gray-700 mb-2">🥗 Main Ingredients:</h4>
                                <p class="text-gray-600">{{ $meal->estimated_main_ingredients }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($meal->calories_min_kcal)
                            <div class="bg-blue-50 rounded-lg p-3">
                                <span class="text-sm text-blue-600 font-semibold">Calories</span>
                                <p class="text-lg font-bold text-blue-800">{{ $meal->calories_min_kcal }} - {{ $meal->calories_max_kcal }} kcal</p>
                            </div>
                            @endif

                            @if($meal->protein_min_g)
                            <div class="bg-green-50 rounded-lg p-3">
                                <span class="text-sm text-green-600 font-semibold">Protein</span>
                                <p class="text-lg font-bold text-green-800">{{ $meal->protein_min_g }} - {{ $meal->protein_max_g }} g</p>
                            </div>
                            @endif

                            @if($meal->carbs_min_g)
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <span class="text-sm text-yellow-600 font-semibold">Carbs</span>
                                <p class="text-lg font-bold text-yellow-800">{{ $meal->carbs_min_g }} - {{ $meal->carbs_max_g }} g</p>
                            </div>
                            @endif

                            @if($meal->fat_min_g)
                            <div class="bg-purple-50 rounded-lg p-3">
                                <span class="text-sm text-purple-600 font-semibold">Fat</span>
                                <p class="text-lg font-bold text-purple-800">{{ $meal->fat_min_g }} - {{ $meal->fat_max_g }} g</p>
                            </div>
                            @endif
                        </div>

                        @if($meal->health_label)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach(explode(',', $meal->health_label) as $label)
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">{{ trim($label) }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($meal->notes)
                            <div class="mt-4 text-sm text-gray-500 italic">
                                📝 {{ $meal->notes }}
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic">No meal selected for {{ $mealName }}</p>
                @endif
            </div>
        </div>
    @endforeach

    <div class="text-center text-gray-500 text-sm mt-8">
        <p>⚠️ Ingredients may vary based on preparation. Always check with the restaurant.</p>
    </div>
</div>

</body>
</html>