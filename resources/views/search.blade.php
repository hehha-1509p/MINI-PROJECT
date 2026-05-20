<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-7xl mx-auto">

        <h1 class="text-4xl font-bold mb-8">
            Search Results for "{{ $query }}"
        </h1>

        <a href="/"
           class="inline-block mb-6 text-blue-600 hover:underline">
           ← Back to Homepage
        </a>

        @if($results->isEmpty())

            <div class="bg-white p-8 rounded-2xl shadow">
                <p class="text-red-500 text-xl">
                    No food found.
                </p>
            </div>

        @else

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @foreach($results as $item)

                <div class="bg-white rounded-2xl shadow-lg p-6">

                    <h2 class="text-2xl font-bold mb-3">
                        {{ $item->item_name }}
                    </h2>

                    <p class="mb-2">
                        <strong>Category:</strong>
                        {{ $item->meal_category }}
                    </p>

                    <p class="mb-2">
                        <strong>Ingredients:</strong>
                        {{ $item->estimated_main_ingredients }}
                    </p>

                    <p>
                        <strong>Calories:</strong>
                        {{ $item->calories_min_kcal }} kcal
                    </p>

                </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
