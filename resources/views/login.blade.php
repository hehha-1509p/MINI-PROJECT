<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <a href="/" class="back-home">← Back to Home</a>

<div class="login-container">
    <h2>Log In</h2>
    <p class="register">Don't have an account? <a href="/register">Register</a></p>
    <form id="loginForm">
        <label>Email or Username</label>
        <input type="text" id="username" required>

        <label>Password</label>
        <input type="password" id="password" required>

        <button type="submit">Log In</button>
    </form>

</div>

<script>
    document.getElementById("loginForm").addEventListener("submit", function(event){
        const password = document.getElementById("password").value;
        const errorMessage = document.getElementById("errorMessage");
        const passwordPattern = 
        /^(?=.*[0-9])(?=.*[!@#$%^&*][A-Za-z0-9!@#$%^&*]{8,}$/;
        if(!passwordPattern.test(password)) {
        event.preventDefault();
        errorMessage.innerText =
        "Password must be at least 8 characters and include a number + symbol.";
}
        });

</body>
</html>
