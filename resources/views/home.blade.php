<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-[200vh] relative">

<div id="homePage" class="h-full p-6">
  <h1 class="text-4xl font-bold mb-6">NomNomNom 🍽️</h1>
  
  <div class="flex justify-end items-center mb-6">
    <div class="absolute top-6 right-8 flex items-center space-x-4">
      <a href="login.html" class="text-gray-600 hover:text-black font-medium">Log In</a>
      <a href="register.html" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
    </div>
  </div>
  
  <h2 class="text-xl font-semibold mb-3 text-center">Preferred Diet</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <button onclick="selectDiet('Anything')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="anything.jpg" class="w-16 h-16 mb-2" alt="Anything">
      <span>Anything</span>
    </button>
    <button onclick="selectDiet('Keto')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="keto.jpg" class="w-12 h-12 mb-2" alt="Keto">
      <span>Keto</span>
    </button>
    <button onclick="selectDiet('Vegetarian')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="vegetarian.jpg" class="w-16 h-16 mb-2" alt="Vegetarian">
      <span>Vegetarian</span>
    </button>
  </div>

  <p id="savedDiet" class="text-green-600 mb-6 font-semibold"></p>

  <button onclick="openCalculator()" class="text-blue-600 underline bg-transparent border-none cursor-pointer text-lg">Calorie Calculator →</button>
  <br>
  <br>
  <a href="/diet_option" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Diet Option</a>
</div>

<div id="foodFilterWidget" class="absolute top-110 right-8 bg-white p-4 rounded-2xl shadow-xl w-96 z-50">
  <h3 class="font-semibold mb-3">Food Filter 🚫</h3>

  <div class="flex gap-2 mb-4">
    <button onclick="selectAllFoods()" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
      Select All
    </button>
    <button onclick="unselectAllFoods()" class="bg-gray-400 text-white px-3 py-1 rounded text-sm hover:bg-gray-500 transition">
      Unselect All
    </button>
  </div>

  <div class="grid grid-cols-2 gap-4">
      
      <div class="mb-2">
        <h4 class="font-bold">Meat 🍖</h4>
        <label><input type="checkbox" value="Chicken" class="food-checkbox"> Chicken</label><br>
        <label><input type="checkbox" value="Beef" class="food-checkbox"> Beef</label><br>
        <label><input type="checkbox" value="Lamb" class="food-checkbox"> Lamb</label><br>
        <label><input type="checkbox" value="Pork" class="food-checkbox"> Pork</label><br>
        <label><input type="checkbox" value="Turkey" class="food-checkbox"> Turkey</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Seafood 🦐</h4>
        <label><input type="checkbox" value="Fish" class="food-checkbox"> Fish</label><br>
        <label><input type="checkbox" value="Prawn" class="food-checkbox"> Prawn</label><br>
        <label><input type="checkbox" value="Crab" class="food-checkbox"> Crab</label><br>
        <label><input type="checkbox" value="Squid" class="food-checkbox"> Squid</label><br>
        <label><input type="checkbox" value="Shellfish" class="food-checkbox"> Shellfish</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Vegetables 🥦</h4>
        <label><input type="checkbox" value="Broccoli" class="food-checkbox"> Broccoli</label><br>
        <label><input type="checkbox" value="Carrot" class="food-checkbox"> Carrot</label><br>
        <label><input type="checkbox" value="Spinach" class="food-checkbox"> Spinach</label><br>
        <label><input type="checkbox" value="Mushroom" class="food-checkbox"> Mushroom</label><br>
        <label><input type="checkbox" value="Onion" class="food-checkbox"> Onion</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Carbs 🍞</h4>
        <label><input type="checkbox" value="Rice" class="food-checkbox"> Rice</label><br>
        <label><input type="checkbox" value="Bread" class="food-checkbox"> Bread</label><br>
        <label><input type="checkbox" value="Pasta" class="food-checkbox"> Pasta</label><br>
        <label><input type="checkbox" value="Potato" class="food-checkbox"> Potato</label><br>
        <label><input type="checkbox" value="Noodles" class="food-checkbox"> Noodles</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Dairy 🧀</h4>
        <label><input type="checkbox" value="Milk" class="food-checkbox"> Milk</label><br>
        <label><input type="checkbox" value="Cheese" class="food-checkbox"> Cheese</label><br>
        <label><input type="checkbox" value="Yogurt" class="food-checkbox"> Yogurt</label><br>
        <label><input type="checkbox" value="Butter" class="food-checkbox"> Butter</label>
      </div>

      <div class="mb-2">
        <h4 class="font-bold">Nuts 🥜</h4>
        <label><input type="checkbox" value="Peanuts" class="food-checkbox"> Peanuts</label><br>
        <label><input type="checkbox" value="Almonds" class="food-checkbox"> Almonds</label><br>
        <label><input type="checkbox" value="Walnuts" class="food-checkbox"> Walnuts</label>
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

<div id="calculatorPage" class="hidden py-12 flex justify-center">
  <div class="bg-white p-8 rounded-2xl shadow w-[400px]">
    <h2 class="text-2xl font-bold mb-4 text-center">Calorie Calculator</h2>
    
    <form id="calorieForm">
      <input id="height" type="number" placeholder="Height (cm)" max="250" class="w-full p-2 border rounded mb-2">
      <input id="weight" type="number" placeholder="Weight (kg)" max="250" class="w-full p-2 border rounded mb-2">

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

      <button type="button" onclick="calculateCalories()" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition font-bold">Calculate</button>
    </form>

    <div id="result" class="mt-4 p-4 bg-gray-50 rounded text-gray-800 text-lg empty:hidden"></div>

    <button onclick="goHome()" class="mt-4 text-blue-600 underline bg-transparent border-none cursor-pointer block">← Back to Home Page</button>
  </div>
</div>

<script>
  // --- Diet Selection ---
  function selectDiet(diet) {
    localStorage.setItem('diet', diet);
    document.getElementById('savedDiet').innerText = "Saved Diet: " + diet;
  }

  // --- REFRESH LOGIC ---
  function resetCalculator() {
    document.getElementById('calorieForm').reset();
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = "";
    resultDiv.classList.add('hidden');
  }

  // --- Page Navigation ---
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
    window.scrollTo(0, 0);
  }

  // --- NEW Food Filter Logic (Checkboxes) ---
  function selectAllFoods() {
    const checkboxes = document.querySelectorAll('.food-checkbox');
    checkboxes.forEach(cb => cb.checked = true);
  }

  function unselectAllFoods() {
    const checkboxes = document.querySelectorAll('.food-checkbox');
    checkboxes.forEach(cb => cb.checked = false);
  }

  // --- Calorie Calculator Logic ---
  function calculateCalories() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const age = parseFloat(document.getElementById('age').value);
    const activity = parseFloat(document.getElementById('activity').value);
    const sex = document.getElementById('sex').value;
    const goal = document.getElementById('goal').value;

    if (!weight || !height) {
      alert("Please enter both height and weight!");
      return;
    }

    // --- NEW VALIDATION: Check if height or weight exceeds 250 ---
    if (height > 250) {
      alert("Please enter a height of 250 cm or less.");
      return;
    }

    if (weight > 250) {
      alert("Please enter a weight of 250 kg or less.");
      return;
    }

    let bmr;
    if (sex === 'Male') {
      bmr = 10 * weight + 6.25 * height - 5 * age + 5;
    } else {
      bmr = 10 * weight + 6.25 * height - 5 * age - 161;
    }

    let calories = bmr * activity;

    if (goal === 'lose') calories -= 300;
    if (goal === 'gain') calories += 300;

    const protein = (calories * 0.3) / 4;
    const fat = (calories * 0.25) / 9;
    const carbs = (calories * 0.45) / 4;

    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `
      Daily Calories: <b class="text-blue-600">${calories.toFixed(0)} kcal</b><br>
      Protein: <b>${protein.toFixed(0)}g</b><br>
      Fat: <b>${fat.toFixed(0)}g</b><br>
      Carbs: <b>${carbs.toFixed(0)}g</b>
    `;
    resultDiv.classList.remove('hidden');
  }
  
  const savedDietMemory = localStorage.getItem('diet');
  if(savedDietMemory) {
      document.getElementById('savedDiet').innerText = "Saved Diet: " + savedDietMemory;
  }
</script>
</body>
</html>