<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Register Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #000000;
            overflow: hidden;
        }

        .password-wrapper {
            position: relative;
            display: flex;
        }

        .password-wrapper i {
            position: absolute;
            right: 15px;
            top: 55%;
            transform: translateY(-50%);
            color: #a0a0a0;
            cursor: pointer;
            z-index: 10;
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

<body class="font-sans min-h-screen flex items-center justify-center px-4 relative bg-fixed bg-cover bg-center bg-no-repeat"style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20231028/pngtree-fresh-and-calming-watercolor-texture-background-in-light-mint-pastel-green-image_13758848.png');">
{{-- Back to Home Button --}}
<a href="/" class="fixed top-4 left-4 sm:top-6 sm:left-6 text-blue-500 hover:text-blue-700 transition flex items-center gap-1 z-50 bg-white/80 backdrop-blur-sm px-3 py-2 rounded-lg shadow-sm">
    <i class="fa-solid fa-arrow-left"></i>
    <span class="text-sm font-medium">Back to Home</span>
</a>

<div class="w-full max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-5 sm:p-6 md:p-8">

        {{-- Login Link --}}
        <div class="text-center mb-4">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="/login" class="text-orange-500 hover:text-orange-600 font-semibold transition">Log In</a>
            </p>
        </div>

        <form method="POST" action="/register">
            @csrf

            {{-- Username --}}
            <div class="mb-3">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Username</label>
                <input type="text" name="username"
                    value="{{ old('username') }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition"
                    placeholder="Enter your username">

                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Email</label>
                <input type="email" name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition"
                    placeholder="Enter your email">

                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="block text-gray-700 font-medium mb-1 text-sm">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="regPassword"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition pr-12"
                        placeholder="Create a password">

                    <i class="fa-regular fa-eye absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition" id="togglePassword"></i>
                </div>

                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Terms of Service --}}
            <div class="flex items-start gap-3 mb-3">
                <div class="flex items-center h-5">
                    <input type="checkbox" id="tos" required
                        class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer">
                </div>
                <label for="tos" class="text-sm text-gray-700 cursor-pointer leading-5">
                    I agree to the
                    <a href="#" class="text-orange-500 hover:text-orange-600 transition font-medium">
                        Terms of Service <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                </label>
            </div>

            {{-- Newsletter --}}
            <div class="flex items-start gap-3 mb-5">
                <div class="flex items-center h-5">
                    <input type="checkbox" id="newsletter"
                        class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400 cursor-pointer">
                </div>
                <label for="newsletter" class="text-sm text-gray-700 cursor-pointer leading-5">
                    Send me a once-a-week email with meal ideas
                    <span class="block text-xs text-gray-400 italic mt-0.5">
                        Optional! You can opt-out at anytime.
                    </span>
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 transition duration-300 flex items-center justify-center gap-2 shadow-md">
                <i class="fa-regular fa-user"></i> Create Account
            </button>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('regPassword');

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

    // Desktop controls
    togglePassword.addEventListener('mousedown', showPassword);
    togglePassword.addEventListener('mouseup', hidePassword);
    togglePassword.addEventListener('mouseleave', hidePassword);

    // Mobile controls
    togglePassword.addEventListener('touchstart', function(e) {
        e.preventDefault();
        showPassword();
    });

    togglePassword.addEventListener('touchend', hidePassword);
    togglePassword.addEventListener('touchcancel', hidePassword);
</script>

</body>
</html>
