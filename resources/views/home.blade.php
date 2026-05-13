<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-[280vh] relative">

<div id="homePage" class="h-full p-6">
  <h1 class="text-4xl font-bold mb-6">NomNomNom 🍽️</h1>

  <div class="flex justify-end items-center mb-6">
    <div class="absolute top-6 right-8 flex items-center space-x-4">
      <a href="/login" class="text-gray-600 hover:text-black font-medium">Log In</a>
      <a href="/register" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
    </div>
  </div>

  {{-- Preferred Diet --}}
  <h2 class="text-xl font-semibold mb-3 text-center">Preferred Diet</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <button onclick="selectDiet('Anything')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/anything.jpeg') }}" class="w-16 h-16 mb-2" alt="Anything">
      <span>Anything</span>
    </button>
    <button onclick="selectDiet('Keto')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/keto.jpeg') }}" class="w-16 h-16 mb-2" alt="Keto">
      <span>Keto</span>
    </button>
    <button onclick="selectDiet('Vegetarian')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/vegeterian.jpeg') }}" class="w-16 h-16 mb-2" alt="Vegetarian">
      <span>Vegetarian</span>
    </button>
  </div>

  {{-- NEW: Split Layout Container (No shared background box) --}}
  <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-6 w-full">

    {{-- Left Column: Saved Diet & Action Buttons --}}
    <div class="flex flex-col gap-4 mt-2">
      <p id="savedDiet" class="text-green-600 font-semibold text-lg m-0"></p>

      <button onclick="openCalculator()" class="text-blue-600 underline text-left cursor-pointer hover:text-blue-800 transition">Calorie Calculator →</button>

      <a href="/diet_option" class="bg-red-400 text-white px-6 py-2 rounded-xl shadow hover:bg-red-500 transition font-semibold w-max text-center">Diet Option</a>
    </div>

    {{-- Right Column: Frontend Macro Display (Standalone Box) --}}
    <div id="homeMacroResults" class="hidden p-5 bg-blue-50 rounded-2xl border border-blue-100 shadow-sm min-w-[300px]">
      <h3 class="font-bold text-blue-800 mb-3">Your Daily Targets:</h3>
      <p class="text-lg mb-3">Calories: <b id="displayKcal" class="text-blue-600"></b></p>
      <div class="flex justify-between gap-4 text-sm font-medium text-gray-700">
        <span>Pro: <span id="displayProtein"></span></span>
        <span>Fat: <span id="displayFat"></span></span>
        <span>Carb: <span id="displayCarbs"></span></span>
      </div>
    </div>
  </div>

  @if(session('selectedDiet'))
    <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5 text-center">
        Selected Diet:
        <strong>{{ session('selectedDiet') }}</strong>
    </div>
@endif

{{-- Food Filter --}}
<div id="foodFilterWidget" class="absolute top-[570px] right-8 bg-white p-4 rounded-2xl shadow-xl w-96 z-50">
    <h3 class="font-semibold leading-tight">Food Filter</h3>
    <span class="text-sm text-gray-600 block mb-3 -mt-1">(Randomly choose 21 if select all)</span>

  <div class="flex gap-2 mb-4">
    <button onclick="selectRandomFoods()" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
      Select All
    </button>
    <button onclick="unselectAllFoods()" class="bg-gray-400 text-white px-3 py-1 rounded text-sm hover:bg-gray-500 transition">
      Unselect All
    </button>
  </div>

  {{-- Food Filter --}}
  <div class="grid grid-cols-2 gap-4">
      <div class="mb-2">
        <h4 class="font-bold">Meat 🍖</h4>
        <label><input type="checkbox" value="Chicken" class="food-checkbox" data-category="Meat"> Chicken</label><br>
        <label><input type="checkbox" value="Beef" class="food-checkbox" data-category="Meat"> Beef</label><br>
        <label><input type="checkbox" value="Lamb" class="food-checkbox" data-category="Meat"> Lamb</label><br>
        <label><input type="checkbox" value="Pork" class="food-checkbox" data-category="Meat"> Pork</label><br>
        <label><input type="checkbox" value="Turkey" class="food-checkbox" data-category="Meat"> Turkey</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Seafood 🦐</h4>
        <label><input type="checkbox" value="Fish" class="food-checkbox" data-category="Seafood"> Fish</label><br>
        <label><input type="checkbox" value="Prawn" class="food-checkbox" data-category="Seafood"> Prawn</label><br>
        <label><input type="checkbox" value="Crab" class="food-checkbox" data-category="Seafood"> Crab</label><br>
        <label><input type="checkbox" value="Squid" class="food-checkbox" data-category="Seafood"> Squid</label><br>
        <label><input type="checkbox" value="Shellfish" class="food-checkbox" data-category="Seafood"> Shellfish</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Vegetables 🥦</h4>
        <label><input type="checkbox" value="Broccoli" class="food-checkbox" data-category="Vegetables"> Broccoli</label><br>
        <label><input type="checkbox" value="Carrot" class="food-checkbox" data-category="Vegetables"> Carrot</label><br>
        <label><input type="checkbox" value="Spinach" class="food-checkbox" data-category="Vegetables"> Spinach</label><br>
        <label><input type="checkbox" value="Mushroom" class="food-checkbox" data-category="Vegetables"> Mushroom</label><br>
        <label><input type="checkbox" value="Onion" class="food-checkbox" data-category="Vegetables"> Onion</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Carbs 🍞</h4>
        <label><input type="checkbox" value="Rice" class="food-checkbox" data-category="Carbs"> Rice</label><br>
        <label><input type="checkbox" value="Bread" class="food-checkbox" data-category="Carbs"> Bread</label><br>
        <label><input type="checkbox" value="Pasta" class="food-checkbox" data-category="Carbs"> Pasta</label><br>
        <label><input type="checkbox" value="Potato" class="food-checkbox" data-category="Carbs"> Potato</label><br>
        <label><input type="checkbox" value="Noodles" class="food-checkbox" data-category="Carbs"> Noodles</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Dairy 🧀</h4>
        <label><input type="checkbox" value="Milk" class="food-checkbox" data-category="Dairy"> Milk</label><br>
        <label><input type="checkbox" value="Cheese" class="food-checkbox" data-category="Dairy"> Cheese</label><br>
        <label><input type="checkbox" value="Yogurt" class="food-checkbox" data-category="Dairy"> Yogurt</label><br>
        <label><input type="checkbox" value="Butter" class="food-checkbox" data-category="Dairy"> Butter</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Nuts 🥜</h4>
        <label><input type="checkbox" value="Peanuts" class="food-checkbox" data-category="Nuts"> Peanuts</label><br>
        <label><input type="checkbox" value="Almonds" class="food-checkbox" data-category="Nuts"> Almonds</label><br>
        <label><input type="checkbox" value="Walnuts" class="food-checkbox" data-category="Nuts"> Walnuts</label>
      </div>

  </div>

  <div class="mt-2 pt-3 border-t border-gray-200">
    <h4 class="font-bold mb-1">Preferences & Allergies ☪️</h4>
    <div class="grid grid-cols-2 gap-x-4">
        <label><input type="checkbox" value="Halal Only" class="food-checkbox"> Halal Only</label>
        <label><input type="checkbox" value="Vegan" class="food-checkbox"> Vegan</label>
        <label><input type="checkbox" value="Gluten-Free" class="food-checkbox"> Gluten-Free</label>
        <label><input type="checkbox" value="Nut-Free" class="food-checkbox"> Nut-Free</label>
    </div>
  </div>
</div>

{{-- 7 Days Widget --}}
<div id="daysWidget" class="absolute top-[570px] left-8 right-[450px] w-auto bg-white p-6 rounded-2xl shadow-xl z-50">
  <h3 class="text-xl font-semibold mb-4">Meal Plan Days 📅</h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <!-- Monday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Monday</h4>

      <div id="meals-Monday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="mon-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="mon-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="mon-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Monday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Monday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Tuesday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Tuesday</h4>

      <div id="meals-Tuesday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="tue-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="tue-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="tue-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Tuesday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Tuesday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Wednesday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Wednesday</h4>

      <div id="meals-Wednesday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="wed-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="wed-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="wed-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Wednesday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Wednesday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Thursday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Thursday</h4>

      <div id="meals-Thursday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="thu-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="thu-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="thu-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Thursday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Thursday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Friday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Friday</h4>

      <div id="meals-Friday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="fri-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="fri-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="fri-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Friday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Friday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Saturday -->
    <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Saturday</h4>

      <div id="meals-Saturday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="sat-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="sat-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="sat-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Saturday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Saturday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>

    <!-- Sunday (Centered in the bottom row if on medium screens) -->
    <div class="md:col-start-2 bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full">
      <h4 class="text-lg font-bold text-center mb-2 border-b pb-1">Sunday</h4>

      <div id="meals-Sunday" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
            <span id="sun-breakfast" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Lunch</span>
            <span id="sun-lunch" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
        <div class="bg-white p-2 rounded border border-gray-200">
            <span class="text-xs text-gray-500 font-bold block">Dinner</span>
            <span id="sun-dinner" class="text-gray-800 italic">Awaiting plan...</span>
        </div>
      </div>

      <div class="flex gap-2 mt-auto">
        <button onclick="regenerateDay('Sunday')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
        <button onclick="viewIngredients('Sunday')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
      </div>
    </div>
  </div>
</div>

{{-- Frontend Macro Display --}}
  <div id="homeMacroResults" class="hidden mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100">
    <h3 class="font-bold text-blue-800 mb-1">Your Daily Targets:</h3>
    <p class="text-lg">Daily Calories: <b id="displayKcal" class="text-blue-600"></b></p>
    <div class="flex gap-4 text-sm font-medium text-gray-700">
      <span>Protein: <span id="displayProtein"></span></span>
      <span>Fat: <span id="displayFat"></span></span>
      <span>Carbs: <span id="displayCarbs"></span></span>
    </div>
  </div>
</div>

{{-- Calorie Calculator --}}
<div id="calculatorPage" class="hidden py-12 flex justify-center">
  <div class="bg-white p-8 rounded-2xl shadow w-[400px]">
    <h2 class="text-2xl font-bold mb-4 text-center">Calorie Calculator</h2>

    <form id="calorieForm" onsubmit="event.preventDefault(); calculateCalories();">
      <!-- Added min, max, and required attributes to enforce rules in HTML -->
      <input id="height" type="number" placeholder="Height (cm)" min="120" max="250" required class="w-full p-2 border rounded mb-2">
      <input id="weight" type="number" placeholder="Weight (kg)" min="25" max="250" required class="w-full p-2 border rounded mb-2">

      <select id="sex" class="w-full p-2 border rounded mb-2">
        <option>Male</option>
        <option>Female</option>
      </select>

      <select id="age" class="w-full p-2 border rounded mb-2">
        @for ($i = 18; $i <= 25; $i++)
          <option value="{{ $i }}">{{ $i }}</option>
        @endfor
      </select>

      <select id="activity" class="w-full p-2 border rounded mb-2">
        <option value="1.2">No Exercise</option>
        <option value="1.375">Light Exercise</option>
        <option value="1.725">Heavy Exercise</option>
      </select>

      <select id="goal" class="w-full p-2 border rounded mb-4">
        <option value="maintain">Maintain Weight</option>
        <option value="lose">Lose Weight</option>
        <option value="gain">Gain Weight</option>
      </select>

      <!-- Changed from type="button" to type="submit" and removed the onclick -->
      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition font-bold">Calculate</button>
    </form>

    <div id="result" class="mt-4 p-4 bg-gray-50 rounded text-gray-800 text-lg empty:hidden"></div>

    <button onclick="goHome()" class="mt-4 text-blue-600 underline bg-transparent border-none cursor-pointer block">← Back to Home Page</button>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // --- Diet Selection ---
    function selectDiet(diet) {
        localStorage.setItem('diet', diet);
        document.getElementById('savedDiet').innerText = "Saved Diet: " + diet;
        applyDietFilter(diet);
    }

    // --- Macro Display ---
    function loadMacroData() {
        const savedData = localStorage.getItem('macroData');
        if (savedData) {
            const parsedData = JSON.parse(savedData);

            document.getElementById('displayKcal').innerText = parsedData.kcal + ' kcal';
            document.getElementById('displayProtein').innerText = parsedData.protein;
            document.getElementById('displayFat').innerText = parsedData.fat;
            document.getElementById('displayCarbs').innerText = parsedData.carbs;

            document.getElementById('homeMacroResults').classList.remove('hidden');
        }
    }

    // --- Page Navigation ---
    function resetCalculator() {
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = "";
        resultDiv.classList.add('hidden');
    }

    function openCalculator() {
        resetCalculator();
        document.getElementById('homePage').classList.add('hidden');
        document.getElementById('calculatorPage').classList.remove('hidden');
        document.getElementById('foodFilterWidget').classList.add('hidden');
        window.scrollTo(0, 0);
    }

    function goHome() {
        resetCalculator();
        document.getElementById('calculatorPage').classList.add('hidden');
        document.getElementById('homePage').classList.remove('hidden');
        document.getElementById('foodFilterWidget').classList.remove('hidden');

        loadMacroData();
        window.scrollTo(0, 0);
    }

    // --- FOOD LIMIT ---
    const MAX_FOOD_OPTIONS = 21;

    document.querySelectorAll('.food-checkbox').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const checkedCount = document.querySelectorAll('.food-checkbox:checked').length;
            if (checkedCount > MAX_FOOD_OPTIONS) {
                e.target.checked = false;
                alert(`You can only select up to ${MAX_FOOD_OPTIONS} options.`);
            }
        });
    });

    function selectRandomFoods() {
        const checkboxes = Array.from(document.querySelectorAll('.food-checkbox'));
        checkboxes.forEach(cb => cb.checked = false);

        for (let i = checkboxes.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [checkboxes[i], checkboxes[j]] = [checkboxes[j], checkboxes[i]];
        }

        for (let i = 0; i < MAX_FOOD_OPTIONS && i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
    }

    function unselectAllFoods() {
        document.querySelectorAll('.food-checkbox').forEach(cb => cb.checked = false);
    }

    // --- CALORIES ---
    function calculateCalories() {
        const weight = parseFloat(document.getElementById('weight').value);
        const height = parseFloat(document.getElementById('height').value);
        const age = parseFloat(document.getElementById('age').value);
        const activity = parseFloat(document.getElementById('activity').value);
        const sex = document.getElementById('sex').value;
        const goal = document.getElementById('goal').value;

        if (!weight || !height) return alert("Enter height & weight");

        let bmr = (sex === 'Male')
            ? 10 * weight + 6.25 * height - 5 * age + 5
            : 10 * weight + 6.25 * height - 5 * age - 161;

        let calories = bmr * activity;

        if (goal === 'lose') calories -= 300;
        if (goal === 'gain') calories += 300;

        const protein = (calories * 0.3) / 4;
        const fat = (calories * 0.25) / 9;
        const carbs = (calories * 0.45) / 4;

        const macroData = {
            kcal: calories.toFixed(0),
            protein: protein.toFixed(0) + 'g',
            fat: fat.toFixed(0) + 'g',
            carbs: carbs.toFixed(0) + 'g'
        };

        localStorage.setItem('macroData', JSON.stringify(macroData));

        document.getElementById('result').innerHTML =
            `Daily Calories: <b>${calories.toFixed(0)} kcal</b><br>
             Protein: <b>${protein.toFixed(0)}g</b><br>
             Fat: <b>${fat.toFixed(0)}g</b><br>
             Carbs: <b>${carbs.toFixed(0)}g</b>`;

        document.getElementById('result').classList.remove('hidden');
    }

    // --- DIET FILTER MAP (IMPORTANT: must be BEFORE usage) ---
    const dietBlockMap = {
        Anything: [],
        Keto: ["Meat", "Seafood", "Carbs"],
        Vegetarian: ["Vegetables", "Dairy", "Nuts"]
    };

    function applyDietFilter(diet) {
        const blockedCategories = dietBlockMap[diet] || [];

        document.querySelectorAll('.food-checkbox').forEach(cb => {
            const category = cb.dataset.category;

            // ALWAYS reset first
            cb.checked = false;

            // Special case: Anything → do nothing else (stay all unchecked)
            if (diet === "Anything") return;

            // Other diets → tick allowed categories
            if (!blockedCategories.includes(category)) {
                cb.checked = true;
            }
        });
    }

    // --- INIT ---
    const savedDietMemory = localStorage.getItem('diet');

    if (savedDietMemory) {
        document.getElementById('savedDiet').innerText =
            "Saved Diet: " + savedDietMemory;

        applyDietFilter(savedDietMemory);
    }

    loadMacroData();

    // expose functions globally (IMPORTANT for onclick buttons)
    window.selectDiet = selectDiet;
    window.openCalculator = openCalculator;
    window.goHome = goHome;
    window.calculateCalories = calculateCalories;
    window.selectRandomFoods = selectRandomFoods;
    window.unselectAllFoods = unselectAllFoods;

});
</script>
</body>

<div class="max-w-4xl mx-auto">
</html>
