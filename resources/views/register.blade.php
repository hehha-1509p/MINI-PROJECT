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

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-container {
            width: 420px;
            padding: 40px;
        }

        .login-link {
            margin-top: 5px;
            margin-bottom: 30px;
            font-size: 15px;
            color: #000000;
        }

        .login-link a,
        .checkbox-group a {
            color: #4da6ff;
            text-decoration: none;
        }

        .login-link a:hover,
        .checkbox-group a:hover {
            text-decoration: underline;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }

        label {
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #000000;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid #000000;
            background-color: #ffffff;
            color: #000000;
            font-size: 15px;
            width: 100%;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #888888;
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
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-top: 4px;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            accent-color: #ff6a3d;
            cursor: pointer;
        }

        .checkbox-group label {
            display: flex;
            flex-direction: column;
            margin: 0;
            line-height: 1.5;
            cursor: pointer;
            font-weight: normal;
            color: #000000;
        }

        .subtext {
            font-size: 13px;
            color: #888888;
            font-style: italic;
            margin-top: 4px;
        }

        button {
            padding: 14px;
            border: none;
            border-radius: 25px;
            background-color: #ff6a3d;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            transition: opacity 0.2s;
            margin-top: 0px;
        }

        button:hover { opacity: 0.85; }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #4da6ff;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-home:hover {
            color: #007bff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="/" class="back-home">← Back to Home</a>
    <div class="register-container">
        <p class="login-link">Already have an account? <a href="/login">Log In</a></p>
        <form>
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username">
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div class="input-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="regPassword">
                    <i class="fa-regular fa-eye" id="togglePassword"></i>
                </div>
            </div>

            <div class="checkbox-group" style="margin-top: 20px;">
                <input type="checkbox" id="tos">
                <label for="tos">
                    <span>I agree to the <a href="#">Terms of Service <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i></a></span>
                </label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="newsletter">
                <label for="newsletter">
                    Send me a once-a-week email with meal ideas
                    <span class="subtext">Optional! These can help maintain your meal planning mindset, and you can opt-out at anytime.</span>
                </label>
            </div>

            <button type="submit">
                <i class="fa-regular fa-user"></i> Create Account
            </button>
        </form>
    </div>
</body>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('regPassword');

    // 1. What happens when you HOLD the button down
    function showPassword() {
        passwordInput.setAttribute('type', 'text');
        togglePassword.classList.remove('fa-eye');
        togglePassword.classList.add('fa-eye-slash');
    }

    // 2. What happens when you RELEASE the button
    function hidePassword() {
        passwordInput.setAttribute('type', 'password');
        togglePassword.classList.remove('fa-eye-slash');
        togglePassword.classList.add('fa-eye');
    }

    // --- Desktop (Mouse) Controls ---
    togglePassword.addEventListener('mousedown', showPassword);
    togglePassword.addEventListener('mouseup', hidePassword);
    // This makes sure it hides if you hold it down and drag your mouse away from the icon!
    togglePassword.addEventListener('mouseleave', hidePassword);

    // --- Mobile (Touch) Controls ---
    togglePassword.addEventListener('touchstart', function(e) {
        e.preventDefault(); // This stops the screen from accidentally scrolling or double-tapping
        showPassword();
    });
    togglePassword.addEventListener('touchend', hidePassword);
    togglePassword.addEventListener('touchcancel', hidePassword);
</script>
</html>
