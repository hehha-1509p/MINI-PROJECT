<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Register Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <a href="home.html" class="back-home">← Back to Home</a>

    <div class="register-container">
        
        <p class="login-link">Already have an account? <a href="login.html">Log In</a></p>
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