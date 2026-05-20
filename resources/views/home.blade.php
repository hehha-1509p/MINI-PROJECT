<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[url('{{ asset('images/homepage.jpeg') }}')] bg-cover bg-center bg-fixed bg-no-repeat font-sans min-h-screen relative">

<div id="homePage" class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
    <div class="flex items-center gap-3">
      <h1 class="text-3xl sm:text-4xl font-bold">NomNomNom</h1>
      <img src="{{ asset('images/diet.jpeg') }}" alt="NomNomNom Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
    </div>

    <div class="flex items-center space-x-4">
      <a href="/login" class="text-gray-600 hover:text-black font-medium">Log In</a>
      <a href="/register" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
    </div>
  </div>

  {{-- Preferred Diet --}}
  <h2 class="text-xl font-semibold mb-3 text-center">Preferred Diet</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <button onclick="selectDiet('Anything')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/anything.jpeg') }}" class="w-16 h-16 mb-2 mix-blend-multiply" alt="Anything">
      <span>Anything</span>
    </button>
    <button onclick="selectDiet('Keto')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/keto.jpeg') }}" class="w-16 h-16 mb-2" alt="Keto">
      <span>Keto</span>
    </button>
    <button onclick="selectDiet('Vegetarian')" class="dietBtn bg-[#ffdab9] p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/vegeterian.jpeg') }}" class="w-16 h-16 mb-2" alt="Vegetarian">
      <span>Vegetarian</span>
    </button>
  </div>

  {{-- Split Layout --}}
  <div class="flex flex-col lg:flex-row justify-between items-start mb-8 gap-6 w-full">
    <div class="flex flex-col gap-4 w-full lg:w-auto">
      <p id="savedDiet" class="text-green-600 font-semibold text-lg m-0"></p>
      <button onclick="openCalculator()" class="text-blue-600 underline text-left cursor-pointer hover:text-blue-800 transition">Calorie Calculator →</button>
      <a href="/diet_option" class="bg-red-400 text-white px-6 py-2 rounded-xl shadow hover:bg-red-500 transition font-semibold w-max text-center">Diet Option</a>
    </div>

    <div id="homeMacroResults" class="hidden p-5 bg-blue-50 rounded-2xl border border-blue-100 shadow-sm w-full lg:w-auto min-w-[300px]">
      <h3 class="font-bold text-blue-800 mb-3">Your Daily Targets:</h3>
      <p class="text-lg mb-3">Calories: <b id="displayKcal" class="text-blue-600"></b></p>
      <div class="flex flex-wrap justify-between gap-4 text-sm font-medium text-gray-700">
        <span>Pro: <span id="displayProtein"></span></span>
        <span>Fat: <span id="displayFat"></span></span>
        <span>Carb: <span id="displayCarbs"></span></span>
      </div>
    </div>
  </div>

  @if(session('diet_plan'))
    <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-5 text-center">
      Current Diet Plan: <strong>{{ session('diet_plan') }}</strong>
    </div>
  @endif

  {{-- Food Filter & Meal Plan --}}
  <div class="flex flex-col xl:flex-row gap-6 relative">

    {{-- Food Filter Widget (Now flows with content instead of absolute positioning) --}}
    <div class="bg-white p-4 rounded-2xl shadow-xl w-full xl:w-96 h-fit">
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

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto">
        <!-- Meat -->
        <div class="mb-2">
          <h4 class="font-bold">Meat 🍖</h4>
          <label><input type="checkbox" value="Chicken" class="food-checkbox" data-category="Meat"> Chicken</label><br>
          <label><input type="checkbox" value="Beef" class="food-checkbox" data-category="Meat"> Beef</label><br>
          <label><input type="checkbox" value="Lamb" class="food-checkbox" data-category="Meat"> Lamb</label><br>
          <label><input type="checkbox" value="Pork" class="food-checkbox" data-category="Meat"> Pork</label><br>
          <label><input type="checkbox" value="Turkey" class="food-checkbox" data-category="Meat"> Turkey</label>
        </div>

        <!-- Seafood -->
        <div class="mb-2">
          <h4 class="font-bold">Seafood 🦐</h4>
          <label><input type="checkbox" value="Fish" class="food-checkbox" data-category="Seafood"> Fish</label><br>
          <label><input type="checkbox" value="Prawn" class="food-checkbox" data-category="Seafood"> Prawn</label><br>
          <label><input type="checkbox" value="Crab" class="food-checkbox" data-category="Seafood"> Crab</label><br>
          <label><input type="checkbox" value="Squid" class="food-checkbox" data-category="Seafood"> Squid</label><br>
          <label><input type="checkbox" value="Shellfish" class="food-checkbox" data-category="Seafood"> Shellfish</label>
        </div>

        <!-- Vegetables -->
        <div class="mb-2">
          <h4 class="font-bold">Vegetables 🥦</h4>
          <label><input type="checkbox" value="Broccoli" class="food-checkbox" data-category="Vegetables"> Broccoli</label><br>
          <label><input type="checkbox" value="Carrot" class="food-checkbox" data-category="Vegetables"> Carrot</label><br>
          <label><input type="checkbox" value="Spinach" class="food-checkbox" data-category="Vegetables"> Spinach</label><br>
          <label><input type="checkbox" value="Mushroom" class="food-checkbox" data-category="Vegetables"> Mushroom</label><br>
          <label><input type="checkbox" value="Onion" class="food-checkbox" data-category="Vegetables"> Onion</label>
        </div>

        <!-- Carbs -->
        <div class="mb-2">
          <h4 class="font-bold">Carbs 🍞</h4>
          <label><input type="checkbox" value="Rice" class="food-checkbox" data-category="Carbs"> Rice</label><br>
          <label><input type="checkbox" value="Bread" class="food-checkbox" data-category="Carbs"> Bread</label><br>
          <label><input type="checkbox" value="Pasta" class="food-checkbox" data-category="Carbs"> Pasta</label><br>
          <label><input type="checkbox" value="Potato" class="food-checkbox" data-category="Carbs"> Potato</label><br>
          <label><input type="checkbox" value="Noodles" class="food-checkbox" data-category="Carbs"> Noodles</label>
        </div>

        <!-- Dairy -->
        <div class="mb-2">
          <h4 class="font-bold">Dairy 🧀</h4>
          <label><input type="checkbox" value="Milk" class="food-checkbox" data-category="Dairy"> Milk</label><br>
          <label><input type="checkbox" value="Cheese" class="food-checkbox" data-category="Dairy"> Cheese</label><br>
          <label><input type="checkbox" value="Yogurt" class="food-checkbox" data-category="Dairy"> Yogurt</label><br>
          <label><input type="checkbox" value="Butter" class="food-checkbox" data-category="Dairy"> Butter</label>
        </div>

        <!-- Nuts -->
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
          <label><input type="checkbox" value="Halal Only" class="preference-checkbox"> Halal Only</label>
          <label><input type="checkbox" value="Vegan" class="preference-checkbox"> Vegan</label>
          <label><input type="checkbox" value="Gluten-Free" class="preference-checkbox"> Gluten-Free</label>
          <label><input type="checkbox" value="Nut-Free" class="preference-checkbox"> Nut-Free</label>
        </div>
      </div>
    </div>

    {{-- Meal Plan Days Widget (Takes remaining width) --}}
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-xl flex-1 overflow-x-auto">
      <h3 class="text-xl font-semibold mb-4">Meal Plan Days 📅</h3>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $index => $day)
          @php
            $colors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500', 'text-blue-500', 'text-purple-500', 'text-pink-500'];
            $colStart = ($day == 'Sunday') ? 'md:col-start-2 lg:col-start-auto' : '';
          @endphp

          <div class="bg-gray-50 border border-gray-300 rounded-xl p-4 flex flex-col transition h-full {{ $colStart }}">
            <h4 class="text-lg font-bold text-center mb-2 border-b pb-1 {{ $colors[$index] }}">{{ $day }}</h4>

            <div id="meals-{{ $day }}" class="flex-grow flex flex-col gap-2 mb-3 text-sm">
              <div class="bg-white p-2 rounded border border-gray-200">
                <span class="text-xs text-gray-500 font-bold block">Breakfast</span>
                <span class="text-gray-800 break-words">
                  {{ $meals[$day]['breakfast']->item_name ?? 'No meal' }}
                </span>
              </div>
              <div class="bg-white p-2 rounded border border-gray-200">
                <span class="text-xs text-gray-500 font-bold block">Lunch</span>
                <span class="text-gray-800 break-words">
                  {{ $meals[$day]['lunch']->item_name ?? 'No meal' }}
                </span>
              </div>
              <div class="bg-white p-2 rounded border border-gray-200">
                <span class="text-xs text-gray-500 font-bold block">Dinner</span>
                <span class="text-gray-800 break-words">
                  {{ $meals[$day]['dinner']->item_name ?? 'No meal' }}
                </span>
              </div>
            </div>

            <div class="flex gap-2 mt-auto">
              <button onclick="regenerateDay('{{ $day }}')" class="flex-1 bg-blue-500 text-white text-xs py-2 rounded hover:bg-blue-600 transition font-semibold">Regenerate</button>
              <button onclick="viewIngredients('{{ $day }}')" class="flex-1 bg-orange-400 text-white text-xs py-2 rounded hover:bg-orange-500 transition font-semibold">Ingredients</button>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Feedback Section --}}
      <div class="w-full flex justify-center mt-10 mb-5">
        <a href="https://forms.gle/7eqAqZ5cTTQLib2B9" target="_blank" class="bg-green-500 text-white px-8 py-3 rounded-xl shadow hover:bg-green-600 transition font-semibold text-center">
          Feedback Form
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Calorie Calculator --}}
<div id="calculatorPage" class="hidden py-12 flex justify-center px-4">
  <div class="bg-white p-6 sm:p-8 rounded-2xl shadow w-full max-w-[400px]">
    <h2 class="text-2xl font-bold mb-4 text-center">Calorie Calculator</h2>

    <form id="calorieForm" onsubmit="event.preventDefault(); calculateCalories();">
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

      <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition font-bold">Calculate</button>
    </form>

    <div id="result" class="mt-4 p-4 bg-gray-50 rounded text-gray-800 text-lg empty:hidden"></div>

    <button onclick="goHome()" class="mt-4 text-blue-600 underline bg-transparent border-none cursor-pointer block">← Back to Home Page</button>
  </div>
</div>

<script>
// Your existing JavaScript remains exactly the same
document.addEventListener("DOMContentLoaded", function () {

    function selectDiet(diet) {
        localStorage.setItem('preferred_diet', diet);
        document.getElementById('savedDiet').innerText = "Saved Diet: " + diet;
        applyDietFilter(diet);

        fetch("/save-preferred-diet", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ diet: diet })
        }).then(() => {
            location.reload();
        });
    }

    window.regenerateDay = async function(day) {
        const buttons = document.querySelectorAll(`[onclick="regenerateDay('${day}')"]`);
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.textContent = 'Regenerating...';
        });

        try {
            const response = await fetch("/regenerate-day", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ day: day })
            });

            const data = await response.json();

            if (data.meals) {
                const mealsContainer = document.getElementById(`meals-${day}`);
                if (mealsContainer) {
                    const breakfastDiv = mealsContainer.children[0];
                    const lunchDiv = mealsContainer.children[1];
                    const dinnerDiv = mealsContainer.children[2];

                    if (breakfastDiv && data.meals.breakfast) {
                        breakfastDiv.querySelector('span:last-child').innerHTML = data.meals.breakfast.item_name;
                    }
                    if (lunchDiv && data.meals.lunch) {
                        lunchDiv.querySelector('span:last-child').innerHTML = data.meals.lunch.item_name;
                    }
                    if (dinnerDiv && data.meals.dinner) {
                        dinnerDiv.querySelector('span:last-child').innerHTML = data.meals.dinner.item_name;
                    }
                }
            }
        } catch (error) {
            console.error('Error regenerating day:', error);
            alert('Failed to regenerate meals. Please try again.');
        } finally {
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.textContent = 'Regenerate';
            });
        }
    };

    window.viewIngredients = async function(day) {
        try {
            const response = await fetch("/get-ingredients", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ day: day })
            });

            if (response.redirected || response.ok) {
                window.location.href = "/ingredients";
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Could not load ingredients page');
        }
    };

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

    function resetCalculator() {
        const resultDiv = document.getElementById('result');
        if (resultDiv) {
            resultDiv.innerHTML = "";
            resultDiv.classList.add('hidden');
        }
    }

    window.openCalculator = function() {
        resetCalculator();
        document.getElementById('homePage').classList.add('hidden');
        document.getElementById('calculatorPage').classList.remove('hidden');
        window.scrollTo(0, 0);
    };

    window.goHome = function() {
        resetCalculator();
        document.getElementById('calculatorPage').classList.add('hidden');
        document.getElementById('homePage').classList.remove('hidden');
        loadMacroData();
        window.scrollTo(0, 0);
    };

    const MAX_FOOD_OPTIONS = 21;

    document.querySelectorAll('.food-checkbox').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const checkedCount = document.querySelectorAll('.food-checkbox:checked').length;
            if (checkedCount > MAX_FOOD_OPTIONS) {
                e.target.checked = false;
                alert(`You can only select up to ${MAX_FOOD_OPTIONS} options.`);
            } else {
                saveFoodFilters();
            }
        });
    });

    function saveFoodFilters() {
        const selectedFoods = Array.from(document.querySelectorAll('.food-checkbox:checked')).map(cb => cb.value);
        const preferences = Array.from(document.querySelectorAll('.preference-checkbox:checked')).map(cb => cb.value);

        fetch("/save-food-filters", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                filters: { foods: selectedFoods, preferences: preferences }
            })
        });
    }

    window.selectRandomFoods = function() {
        const checkboxes = Array.from(document.querySelectorAll('.food-checkbox'));
        checkboxes.forEach(cb => cb.checked = false);

        for (let i = checkboxes.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [checkboxes[i], checkboxes[j]] = [checkboxes[j], checkboxes[i]];
        }

        for (let i = 0; i < MAX_FOOD_OPTIONS && i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
        saveFoodFilters();
    };

    window.unselectAllFoods = function() {
        document.querySelectorAll('.food-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.preference-checkbox').forEach(cb => cb.checked = false);
        saveFoodFilters();
    };

    window.calculateCalories = function() {
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

        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = `Daily Calories: <b>${calories.toFixed(0)} kcal</b><br>
             Protein: <b>${protein.toFixed(0)}g</b><br>
             Fat: <b>${fat.toFixed(0)}g</b><br>
             Carbs: <b>${carbs.toFixed(0)}g</b>`;
        resultDiv.classList.remove('hidden');

        fetch("/save-calories", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                calories: calories.toFixed(0)
            })
        });
    };

    const dietBlockMap = {
        Anything: [],
        Keto: ["Meat", "Seafood", "Carbs"],
        Vegetarian: ["Vegetables", "Dairy", "Nuts"]
    };

    function applyDietFilter(diet) {
        const blockedCategories = dietBlockMap[diet] || [];
        document.querySelectorAll('.food-checkbox').forEach(cb => {
            const category = cb.dataset.category;
            cb.checked = false;
            if (diet !== "Anything" && !blockedCategories.includes(category)) {
                cb.checked = true;
            }
        });
        saveFoodFilters();
    }

    const savedPreferredDiet = localStorage.getItem('preferred_diet');
    if (savedPreferredDiet) {
        document.getElementById('savedDiet').innerText = "Saved Diet: " + savedPreferredDiet;
        applyDietFilter(savedPreferredDiet);
    }

    loadMacroData();

    window.selectDiet = selectDiet;
});
</script>
</body>
</html>
