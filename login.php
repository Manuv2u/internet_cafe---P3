<?php
session_start();
require 'db_connect.php';

$error = ""; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Basic server-side validation
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } elseif (!preg_match("/^[A-Za-z]+$/", $username)) {
        $error = "Username must contain only alphabets (A–Z or a–z).";
    } else {
        // Check if user exists in the database
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful – start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // Record login time
            $login_time = date('Y-m-d H:i:s');
            $insert = $conn->prepare("INSERT INTO sessions (user_id, login_time) VALUES (?, ?)");
            $insert->bind_param("is", $user['id'], $login_time);
            $insert->execute();

            $_SESSION['session_id'] = $conn->insert_id;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
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
            background-clip: text;
           -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .input-group {
            position: relative;
            margin-bottom: 10px;
            height: 44px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
            font-size: 16px;
            pointer-events: none;
        }

        .input-group input {
            width: 100%;
            height : 100%;
            padding: 12px 12px 12px 40px;
            line-height :1.5;
            border-radius: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
            color: #000;
            font-size: 15px;
            transition: 0.3s ease;
            box-sizing : border-box;
        }

        .input-group input:focus {
            outline: none;
            border-color: #ff9a9e;
            background: #f9f9f9;
        }
        .error-message {
            color: red;
            font-size : 13px;
             margin :4px 0 12px 5px; 
            text-align :left;
            padding-left :10px;
            /* min-height :16px; */
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
            margin-top:10px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(to right, #ff6a00, #ee0979);
        }

        /* .error-message { 
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
        /* } */

        .input-group div {
            margin-top: 4px;
            padding-left :5px;
            font-size: 13px;
            color:red !important;
            text-align: left;
            position : relative;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($error)) echo "<div class='error-message'>" . htmlspecialchars($error) . "</div>"; ?>
        <h2>Login</h2>
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div id="usernameError" 
                class="error-message"></div>
            <div class="input-group">
                <i class="fa fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div id="passwordError" 
                class="error-message">
            </div>
            <button type="submit" id="loginBtn" disabled>Login</button>
        </form>
    </div>

   <script>
const usernameInput = document.getElementById("username");
const passwordInput = document.getElementById("password");
const loginBtn = document.getElementById("loginBtn");

const usernameError = document.getElementById("usernameError");
const passwordError = document.getElementById("passwordError");

function validateForm() {
    const username = usernameInput.value.trim();
    const password = passwordInput.value;
    let isValid = true;

    // Username validation
    if (username === "") {
        usernameError.textContent = "Username is required.";
        isValid = false;
    } else if (!/^[A-Za-z]+$/.test(username)) {
        usernameError.textContent = "Only alphabets are allowed in username.";
        isValid = false;
    } else {
        usernameError.textContent = "";
    }

    // Password validation
    if (password === "") {
        passwordError.textContent = "Password is required.";
        isValid = false;
    } else if (password.length < 6) {
        passwordError.textContent = "Password must be at least 6 characters.";
        isValid = false;
    } else {
        passwordError.textContent = "";
    }

    loginBtn.disabled = !isValid;
    return isValid;
}

usernameInput.addEventListener("input", validateForm);
passwordInput.addEventListener("input", validateForm);

loginBtn.disabled = true;
</script>

</body>
</html>
