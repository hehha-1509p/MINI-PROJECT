<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diet & Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto">
        
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Diet Options</h1>
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            @foreach($dietPlans as $plan)
                <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-{{ $plan['color'] }}-500 hover:scale-105 transition-transform">
                    <span class="text-xs font-bold text-{{ $plan['color'] }}-600 uppercase bg-{{ $plan['color'] }}-50 px-2 py-1 rounded">{{ $plan['tag'] }}</span>
                    <h2 class="font-bold text-xl mt-3">{{ $plan['name'] }}</h2>
                    <p class="text-gray-500 text-sm mt-2">{{ $plan['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-200">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 underline decoration-blue-500">Daily Calorie Calculator</h2>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Your Weight (kg)</label>
                    <input type="number" id="weight" placeholder="e.g. 70" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activity Level</label>
                    <select id="activity" class="w-full p-3 border border-gray-300 rounded-lg outline-none">
                        <option value="1.2">Sedentary (No exercise)</option>
                        <option value="1.5">Moderate (Exercise 3x week)</option>
                        <option value="1.9">Athlete (Heavy exercise)</option>
                    </select>
                </div>
                <button onclick="calculate()" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black text-lg hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                    CALCULATE NOW
                </button>
                
                <div id="resultBox" class="hidden mt-6 p-6 bg-blue-50 border-2 border-blue-100 rounded-xl text-center">
                    <p class="text-blue-600 font-bold text-sm uppercase">Recommended Intake</p>
                    <div id="resultText" class="text-4xl font-black text-gray-800 mt-1"></div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function calculate() {
            const w = document.getElementById('weight').value;
            const a = document.getElementById('activity').value;
            if(w > 0) {
                const total = Math.round(w * 24 * a);
                document.getElementById('resultText').innerText = total + " kcal / day";
                document.getElementById('resultBox').classList.remove('hidden');
            } else {
                alert("Please enter a valid weight!");
            }
        }
    </script>
</body>
</html>