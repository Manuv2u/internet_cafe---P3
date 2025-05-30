<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;

        $login_time = date('Y-m-d H:i:s');
        $insert = $conn->prepare("INSERT INTO sessions (user_id, login_time) VALUES (?, ?)");
        $insert->bind_param("is", $user['id'], $login_time);
        $insert->execute();
        $_SESSION['session_id'] = $conn->insert_id;

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
        }

        .login-container {
            background: white;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: #fff;
            text-align: center;
        }

        .login-container h2 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 700;
            background:black;
           -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border-radius: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            color: #000;
            font-size: 15px;
            transition: 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #ff9a9e;
            background: rgba(255, 255, 255, 0.15);
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background: linear-gradient(to right, #ff6a00, #ee0979);
        }

        .error-message {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .input-group div {
            margin-top: 5px;
            font-size: 13px;
            color: #ff6666;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) echo "<div class='error-message'>" . htmlspecialchars($error) . "</div>"; ?>
        <h2>Login</h2>
        <form method="POST" action="">
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" disabled>Login</button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');
        const loginButton = document.querySelector('button[type="submit"]');

        const usernameError = document.createElement('div');
        const passwordError = document.createElement('div');

        usernameError.style.color = '#ff8080';
        usernameError.style.fontSize = '13px';
        usernameError.style.marginTop = '5px';

        passwordError.style.color = '#ff8080';
        passwordError.style.fontSize = '13px';
        passwordError.style.marginTop = '5px';

        usernameInput.parentElement.appendChild(usernameError);
        passwordInput.parentElement.appendChild(passwordError);

        function validateForm() {
            const username = usernameInput.value.trim();
            const password = passwordInput.value;
            let isValid = true;

            if (!/^[A-Za-z]+$/.test(username)) {
                usernameError.textContent = "Only alphabets are allowed in username.";
                isValid = false;
            } else {
                usernameError.textContent = "";
            }

            if (password.length < 6) {
                passwordError.textContent = "Password must be at least 6 characters.";
                isValid = false;
            } else {
                passwordError.textContent = "";
            }

            loginButton.disabled = !isValid;
        }

        usernameInput.addEventListener('input', validateForm);
        passwordInput.addEventListener('input', validateForm);

        loginButton.disabled = true;
    });
    </script>
</body>
</html>