<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Diet option</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-10">Choose Your Diet Plan</h1>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($dietPlans as $plan)
                <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-{{ $plan['color'] }}-500">
                    <span class="text-xs font-bold uppercase text-{{ $plan['color'] }}-600 bg-{{ $plan['color'] }}-100 px-2 py-1 rounded">
                        {{ $plan['tag'] }}
                    </span>
                    
                    <h2 class="text-xl font-bold mt-4">{{ $plan['name'] }}</h2>
                    <p class="text-gray-600 mt-2 text-sm">{{ $plan['description'] }}</p>

                    @if($plan['name'] == 'Weekend Light Increase' && $isWeekend)
                        <div class="mt-4 p-2 bg-green-100 text-green-700 text-xs font-bold rounded text-center">
                            🚀 Boosted Intake Active Today!
                        </div>
                    @endif

                    <button class="w-full mt-6 bg-gray-800 text-white py-2 rounded-lg hover:bg-black transition">
                        Select Plan
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
