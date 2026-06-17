<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #000000;
        }

        .password-wrapper {
            position: relative;
            display: flex;
        }

        input:focus {
            outline: none;
            border-color: #888888;
        }

        button:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body class="font-sans min-h-screen relative bg-fixed bg-cover bg-center bg-no-repeat"style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20231028/pngtree-fresh-and-calming-watercolor-texture-background-in-light-mint-pastel-green-image_13758848.png');">

<a href="/" class="fixed top-4 left-4 sm:top-6 sm:left-6 text-blue-500 hover:text-blue-700 transition flex items-center gap-1 z-50 bg-white/80 backdrop-blur-sm px-3 py-2 rounded-lg shadow-sm">
    <i class="fa-solid fa-arrow-left"></i>
    <span class="text-sm font-medium">Back to Home</span>
</a>

<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 md:p-10">

        <h2 class="text-center text-3xl font-bold mb-2 text-gray-900">Log In</h2>

        <div class="text-center mb-6">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="/register" class="text-orange-500 hover:text-orange-600 font-semibold transition">Register</a>
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-600 text-sm text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 text-red-500 text-sm text-center font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login" id="loginForm">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2 text-sm">Email or Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition"
                    placeholder="Enter your email or username">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2 text-sm">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="loginPassword" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition pr-12"
                        placeholder="Enter your password">
                    <i class="fa-regular fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition duration-300 flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-right-to-bracket"></i> Log In
            </button>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('loginPassword');

    function showPassword() {
        passwordInput.setAttribute('type', 'text');
        togglePassword.classList.remove('fa-eye');
        togglePassword.classList.add('fa-eye-slash');
    }

    function hidePassword() {
        passwordInput.setAttribute('type', 'password');
        togglePassword.classList.remove('fa-eye-slash');
        togglePassword.classList.add('fa-eye');
    }

    togglePassword.addEventListener('mousedown', showPassword);
    togglePassword.addEventListener('mouseup', hidePassword);
    togglePassword.addEventListener('mouseleave', hidePassword);

    togglePassword.addEventListener('touchstart', function(e) {
        e.preventDefault();
        showPassword();
    });

    togglePassword.addEventListener('touchend', hidePassword);
    togglePassword.addEventListener('touchcancel', hidePassword);
</script>

</body>
</html>
