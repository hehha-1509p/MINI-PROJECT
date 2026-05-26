<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - {{ $query }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen py-6 px-4 sm:py-8 sm:px-6">

    <div class="max-w-7xl mx-auto">

 <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6 sm:mb-8">

    {{-- Title --}}
    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 break-words">
        Search Results for "<span class="text-orange-500">{{ $query }}</span>"
    </h1>

    {{-- Search Bar --}}
    <form action="/search" method="GET" class="w-full lg:w-[420px]">
        <div class="flex items-center bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">

            <input
                type="text"
                name="query"
                placeholder="Search food..."
                value="{{ $query }}"
                class="w-full px-4 py-3 text-gray-700 focus:outline-none"
            >

            <button
                type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 transition"
            >
                Search
            </button>
        </div>
    </form>

    {{-- Back Button --}}
    <a href="/"
       class="inline-flex items-center gap-2 text-blue-500 hover:text-blue-700 transition font-medium whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Homepage
    </a>

</div>

        {{-- Search Summary / Count --}}
        <div class="mb-6">
            <p class="text-gray-600 text-sm sm:text-base">
                Found <span class="font-bold text-orange-500">{{ $results->count() }}</span> result(s)
            </p>
        </div>

        @if($results->isEmpty())

            <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 text-center">
                <div class="text-6xl mb-4">🍽️</div>
                <p class="text-red-500 text-lg sm:text-xl font-semibold">
                    No food found.
                </p>
                <p class="text-gray-500 mt-2 text-sm sm:text-base">
                    Try searching with different keywords or browse our meal plans.
                </p>
                <a href="/" class="inline-block mt-6 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Browse Meal Plans
                </a>
            </div>

        @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($results as $item)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">

                        {{-- Card Header with Meal Category Badge --}}
                        <div class="bg-gradient-to-r from-orange-400 to-red-400 px-4 sm:px-6 py-3">
                            <span class="text-white text-xs font-semibold uppercase tracking-wide">
                                {{ $item->meal_category ?? 'Meal' }}
                            </span>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-4 sm:p-6">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 break-words">
                                {{ $item->item_name }}
                            </h2>

                            @if($item->estimated_main_ingredients)
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                        🥗 Main Ingredients
                                    </p>
                                    <p class="text-gray-700 text-sm sm:text-base break-words">
                                        {{ $item->estimated_main_ingredients }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500">Calories</p>
                                    <p class="text-lg font-bold text-orange-600">
                                        {{ $item->calories_min ?? 'N/A' }} kcal
                                    </p>
                                </div>

                                @if($item->food_type)
                                    <div class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                        {{ $item->food_type }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
