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
                    <input type="password" name="password">
                    <i class="fa-regular fa-eye"></i>
                </div>
            </div>


            <div class="checkbox-group">
                <input type="checkbox" id="tos">
                <label for="tos">
                    <span>I agree to the <a href="#">Terms of Service <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i></a></span>
                </label>
            </div>

            <div class="checkbox-group mb-30">
                <input type="checkbox" id="newsletter">
                <label for="newsletter">
                    Send me a once-a-week email with meal ideas
                    <span class="subtext">Optional! These can help maintain your meal planning mindset, and you can opt-out at any time.</span>
                </label>
            </div>

            <button type="submit">
                <i class="fa-regular fa-user"></i> Create Account
            </button>
        </form>
    </div>

</body>
</html>
