<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Page</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: #f3f4f6;
        color: #000000;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        overflow: hidden;
    }

    .login-container {
        background: #f3f4f6;
        padding: 40px;
        border-radius: 10px;
        width: 350px;
        text-align: center;
    }

   h2 {
        margin-bottom: 10px;
        font-size: 28px;
        font-weight: 600;
    }

    .register {
        font-size: 14px;
        margin-bottom: 20px;
        color: #000000;
    }

    .register a {
        color: #4da6ff;
        text-decoration: none;
    }

    .register a:hover { text-decoration: underline; }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        text-align: left;
        margin-top: 10px;
        font-size: 14px;
        font-weight: 500;
    }

   input {
        padding: 12px 15px;
        margin-top: 5px;
        border-radius: 6px;
        border: 1px solid #000000;
        background-color: #ffffff;
        color: #000000;
        font-size: 15px;
        box-sizing: border-box;
        width: 100%;
    }

    button {
        margin-top: 25px;
        padding: 12px;
        border: none;
        border-radius: 25px;
        background: #ff6a3d;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    button:hover { opacity: 0.9; }

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

<script src="script.js"></script>

</body>
</html>
