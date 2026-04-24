<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NomNomNom Meal Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div id="homePage" class="min-h-screen p-6">
  <h1 class="text-3xl font-bold mb-6">NomNomNom 🍽️</h1>
<div class="flex justify-end items-center mb-6">
    <div class="space-x-4">
        <a href="/login" class="text-gray-600 hover:text-black font-medium">Log In</a>
        <a href="register.html" class="bg-red-400 text-white px-4 py-2 rounded-xl shadow hover:bg-red-500 transition">Sign Up</a>
    </div>
</div>
  <h2 class="text-xl font-semibold mb-3">Preferred Diet</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <button onclick="selectDiet('Anything')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
                    {{-- <img src="anything.jpg" class="w-12 h-12 mb-2" alt="Anything"> --}}
                    <img src="{{ asset('images/anything.jpg.jpeg') }}"class="w-16 h-16 mb-1" alt="image">
                    <span>Anything</span>
                </button>
<button onclick="selectDiet('Keto')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
                    <img src="keto.jpg" class="w-12 h-12 mb-2" alt="Keto">
                    <span>Keto</span>
                </button>
<button onclick="selectDiet('Vegetarian')" class="dietBtn bg-white p-6 rounded-2xl shadow flex flex-col items-center hover:border-orange-500 border-2 border-transparent">
                    <img src="vegetarian.jpg" class="w-12 h-12 mb-2" alt="Vegetarian">
                    <span>Vegetarian</span>
                </button>
  </div>

  <p id="savedDiet" class="text-green-600 mb-6"></p>

  <a href="#" onclick="openCalculator()" class="text-blue-600 underline">Go to Calorie Calculator →</a>
</div>

<div class="fixed bottom-6 right-6 bg-white p-4 rounded-2xl shadow-xl w-72">
  <h3 class="font-semibold mb-2">Food Filter 🚫</h3>
  <input id="foodInput" type="text" placeholder="Type food you don't eat..." class="w-full p-2 border rounded mb-2">
  <button onclick="addFood()" class="w-full bg-red-400 text-white py-1 rounded">Add</button>
  <ul id="foodList" class="mt-2 text-sm"></ul>
</div>

<div id="calculatorPage" class="hidden min-h-screen p-6">
  <h2 class="text-2xl font-bold mb-4">Calorie Calculator</h2>

  <div class="bg-white p-6 rounded-2xl shadow max-w-md">
    <input id="height" type="number" placeholder="Height (cm)" class="w-full p-2 border rounded mb-2">
    <input id="weight" type="number" placeholder="Weight (kg)" class="w-full p-2 border rounded mb-2">

    <select id="sex" class="w-full p-2 border rounded mb-2">
      <option>Male</option>
      <option>Female</option>
    </select>

    <select id="age" class="w-full p-2 border rounded mb-2">
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
    </select>

    <select id="activity" class="w-full p-2 border rounded mb-2">
      <option value="1.2">No Exercise</option>
      <option value="1.375">Light Exercise</option>
      <option value="1.725">Heavy Exercise</option>
    </select>

    <select id="goal" class="w-full p-2 border rounded mb-3">
      <option value="lose">Lose Fat</option>
      <option value="maintain">Maintain Weight</option>
      <option value="gain">Build Muscle</option>
    </select>

    <button onclick="calculateCalories()" class="w-full bg-green-500 text-white py-2 rounded">Submit</button>

    <div id="result" class="mt-4 text-sm"></div>
  </div>

  <button onclick="goHome()" class="mt-4 text-blue-600 underline">← Back</button>
</div>

<script>
  let bannedFoods = JSON.parse(localStorage.getItem('bannedFoods')) || [];

  function selectDiet(diet) {
    localStorage.setItem('diet', diet);
    document.getElementById('savedDiet').innerText = "Saved: " + diet;
  }

  function addFood() {
    const input = document.getElementById('foodInput');
    const food = input.value.trim();
    if (food) {
      bannedFoods.push(food);
      localStorage.setItem('bannedFoods', JSON.stringify(bannedFoods));
      renderFoods();
      input.value = "";
    }
  }

  function renderFoods() {
    const list = document.getElementById('foodList');
    list.innerHTML = "";
    bannedFoods.forEach(f => {
      const li = document.createElement('li');
      li.innerText = "🚫 " + f;
      list.appendChild(li);
    });
  }

  function openCalculator() {
    document.getElementById('homePage').classList.add('hidden');
    document.getElementById('calculatorPage').classList.remove('hidden');
  }

  function goHome() {
    document.getElementById('calculatorPage').classList.add('hidden');
    document.getElementById('homePage').classList.remove('hidden');
  }

  function calculateCalories() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const age = parseFloat(document.getElementById('age').value);
    const activity = parseFloat(document.getElementById('activity').value);
    const sex = document.getElementById('sex').value;
    const goal = document.getElementById('goal').value;

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

    document.getElementById('result').innerHTML = `
      Daily Calories: <b>${calories.toFixed(0)}</b><br>
      Protein: ${protein.toFixed(0)}g<br>
      Fat: ${fat.toFixed(0)}g<br>
      Carbs: ${carbs.toFixed(0)}g
    `;
  }

  renderFoods();
</script>

</body>
</html>
