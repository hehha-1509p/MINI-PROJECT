<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen">

<div id="homePage" class="h-full p-6">
  <h1 class="text-4xl font-bold mb-6">NomNomNom 🍽️</h1>

  <div class="flex justify-end items-center mb-6">
    <div class="absolute top-6 right-8 flex items-center space-x-4">
      <a href="/login" class="text-gray-600 hover:text-black font-medium">Log In</a>
      <a href="/register" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
    </div>
  </div>

  <h2 class="text-xl font-semibold mb-3 text-center">Preferred Diet</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <button onclick="selectDiet('Anything')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/anything.jpg.jpeg') }}" class="w-16 h-16 mb-2" alt="image">
      <span>Anything</span>
    </button>
    <button onclick="selectDiet('Keto')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/keto.jpg.jpeg') }}" class="w-16 h-16 mb-2" alt="image">
      <span>Keto</span>
    </button>
    <button onclick="selectDiet('Vegetarian')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
      <img src="{{ asset('images/vegetarian.jpg.jpeg') }}" class="w-16 h-16 mb-2" alt="image">
      <span>Vegetarian</span>
    </button>

  </div>

  <p id="savedDiet" class="text-green-600 mb-6 font-semibold"></p>

  <button onclick="openCalculator()" class="text-blue-600 underline bg-transparent border-none cursor-pointer text-lg">Calorie Calculator →</button>
</div>

<div id="foodFilterWidget" class="absolute bottom-6 right-6 bg-white p-4 rounded-2xl shadow-xl w-72 border border-gray-100 flex flex-col max-h-64 z-50">
  <h3 class="font-semibold mb-2">Food Filter 🚫</h3>
  <input id="foodInput" type="text" placeholder="Type food you don't eat..." class="w-full p-2 border rounded mb-2 outline-none focus:ring-2 focus:ring-red-300">
  <button onclick="addFood()" class="w-full bg-red-400 text-white py-2 rounded hover:bg-red-500 transition font-medium">Add</button>
  <ul id="foodList" class="mt-3 text-sm space-y-2 max-h-24 overflow-y-auto pr-1 custom-scrollbar"></ul>
</div>

<div id="calculatorPage" class="hidden py-12 flex justify-center">
  <div class="bg-white p-8 rounded-2xl shadow w-[400px]">
    <h2 class="text-2xl font-bold mb-4 text-center">Calorie Calculator</h2>

    <form id="calorieForm">
      <input id="height" type="number" placeholder="Height (cm)" class="w-full p-2 border rounded mb-2">
      <input id="weight" type="number" placeholder="Weight (kg)" class="w-full p-2 border rounded mb-2">

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
    // Resets all input fields to their defaults
    document.getElementById('calorieForm').reset();
    // Clears the results display
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = "";
    resultDiv.classList.add('hidden');
  }

  // --- Page Navigation ---
  function openCalculator() {
    resetCalculator(); // Added to refresh when entering
    document.getElementById('homePage').classList.add('hidden');
    document.getElementById('calculatorPage').classList.remove('hidden');
    document.getElementById('foodFilterWidget').classList.add('hidden');
  }

  function goHome() {
    resetCalculator(); // Added to refresh when leaving
    document.getElementById('calculatorPage').classList.add('hidden');
    document.getElementById('homePage').classList.remove('hidden');
    document.getElementById('foodFilterWidget').classList.remove('hidden');
  }

  // --- Food Filter Logic ---
  let bannedFoods = JSON.parse(localStorage.getItem('bannedFoods')) || [];

  function addFood() {
    const input = document.getElementById('foodInput');
    const food = input.value.trim();

    if (food !== "") {
      const isDuplicate = bannedFoods.some(item => item.toLowerCase() === food.toLowerCase());

      if (isDuplicate) {
        alert("You already added '" + food + "' to your filter!");
        input.value = "";
        return;
      }

      bannedFoods.push(food);
      localStorage.setItem('bannedFoods', JSON.stringify(bannedFoods));
      renderFoods();

      input.value = "";
      input.focus();
    }
  }

  function removeFood(index) {
    bannedFoods.splice(index, 1);
    localStorage.setItem('bannedFoods', JSON.stringify(bannedFoods));
    renderFoods();
  }

  function renderFoods() {
    const list = document.getElementById('foodList');
    list.innerHTML = "";

    bannedFoods.forEach((food, index) => {
      const li = document.createElement('li');
      li.className = "flex justify-between items-center bg-gray-100 p-2 rounded-lg border border-gray-200";
      li.innerHTML = `
        <span class="font-medium text-gray-700">🚫 ${food}</span>
        <button onclick="removeFood(${index})" class="text-red-500 hover:text-red-700 font-bold px-2 text-xl">&times;</button>
      `;
      list.appendChild(li);
    });
  }

  document.getElementById('foodInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      addFood();
    }
  });

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

  renderFoods();

  const savedDietMemory = localStorage.getItem('diet');
  if(savedDietMemory) {
      document.getElementById('savedDiet').innerText = "Saved Diet: " + savedDietMemory;
  }
</script>
</body>
</html>
