<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Page</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-container">

    <h2>Log In</h2>
    <p>Don't have an account? <a href="register.html">Register</a></p>
    <form id="loginForm">
        <label>Email or Username</label>
        <input type="text" id="username" required>

        <label>Password</label>
        <input type="password" id="password" required>

        <button type="submit">Log In</button>
    </form>

</div>

<script src="script.js"></script>

</body>
</html>