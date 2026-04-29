<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diet Options</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="max-w-2xl w-full text-center">

    <h1 class="text-3xl font-bold mb-10 text-gray-800">
        Diet Options
    </h1>

    <!-- DIET OPTIONS CENTERED -->
    <div class="grid gap-8">

        @foreach($dietPlans as $plan)
            <div class="bg-white p-8 rounded-2xl shadow-lg border-b-4 border-blue-500 hover:scale-105 transition-transform">
                
                <span class="text-xs font-bold text-blue-600 uppercase bg-blue-50 px-3 py-1 rounded-full">
                    {{ $plan['tag'] }}
                </span>

                <h2 class="font-bold text-2xl mt-4">
                    {{ $plan['name'] }}
                </h2>

                <p class="text-gray-500 text-sm mt-3">
                    {{ $plan['description'] }}
                </p>

            </div>
        @endforeach

    </div>

</div>

</body>
</html>