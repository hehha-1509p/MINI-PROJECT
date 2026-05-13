<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food generation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Header --}}
    <div class="bg-white shadow p-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            🍽️ Meal Generator
        </h1>

        <a href="/" class="text-blue-500 hover:underline">
            ← Back Home
        </a>
    </div>

    {{-- Main Container --}}
    <div class="max-w-6xl mx-auto p-6">

        {{-- Day Title --}}
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800">
                Meal Plan for <span class="text-blue-600">{{ $day }}</span>
            </h2>
        </div>

        {{-- Calories --}}
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-green-600">
                Daily Calories: {{ $calories }} kcal
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Breakfast --}}
            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="font-bold text-xl mb-2">Breakfast</h2>
                <h3>{{ $breakfast->item_name ?? 'No food found' }}</h3>
                <p class="text-sm text-gray-500">
                    {{ $breakfast->notes ?? '' }}
                </p>
            </div>

            {{-- Lunch --}}
            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="font-bold text-xl mb-2">Lunch</h2>
                <h3>{{ $lunch->item_name ?? 'No food found' }}</h3>
                <p class="text-sm text-gray-500">
                    {{ $lunch->notes ?? '' }}
                </p>
            </div>

            {{-- Dinner --}}
            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="font-bold text-xl mb-2">Dinner</h2>
                <h3>{{ $dinner->item_name ?? 'No food found' }}</h3>
                <p class="text-sm text-gray-500">
                    {{ $dinner->notes ?? '' }}
                </p>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="mt-10 flex justify-center gap-4">

            <a href="/generate/{{ $day }}"
               class="bg-blue-500 text-white px-6 py-2 rounded-xl shadow hover:bg-blue-600 transition">
                🔄 Regenerate
            </a>

            <a href="/"
               class="bg-gray-300 text-gray-800 px-6 py-2 rounded-xl hover:bg-gray-400 transition">
                🏠 Home
            </a>
        </div>
    </div>
</body>
</html>
