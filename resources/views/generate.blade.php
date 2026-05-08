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
            {{-- <p class="text-gray-500 mt-1">
                Randomly generated from your database
            </p> --}}
        </div>

        {{-- Food Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($foods as $food)
                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-5">

                    {{-- Food Name --}}
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">
                        {{ $food->item_name ?? 'Food Item' }}
                    </h3>

                    {{-- Description --}}
                    <p class="text-gray-500 text-sm mb-4">
                        {{ $food->notes ?? 'No description available' }}
                    </p>
                </div>
            @endforeach
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
