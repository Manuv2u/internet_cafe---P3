<?php
require 'db_connect.php';

$message = "";

// Server-side validation and registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $message = "<p style='color:red;'>All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p style='color:red;'>Invalid email format.</p>";
    } elseif (strlen($password) < 6) {
        $message = "<p style='color:red;'>Password must be at least 6 characters.</p>";
    } elseif (preg_match('/\d/', $username)) {
        $message = "<p style='color:red;'>Username must not contain numbers.</p>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "<p style='color:red;'>Email already registered.</p>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                $message = "<p style='color:green;'>Registration successful!</p>";
            } else {
                $message = "<p style='color:red;'>Registration failed. Try again.</p>";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  <style>
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

         .glass-form {
  background: white;
  border-radius: 20px;
  padding: 40px 30px;
  width: 100%;
  max-width: 400px;
  color: black;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
  border: 1px solid #ddd;
}


    .glass-form h2 {
      text-align: center;
      margin-bottom: 30px; 
      font-weight: 800;
      font-size: 30px;
      color: black;
    }

    .input-group {
      position: relative;
      margin-bottom: 20px;
    }

    .input-group i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: #ccc;
    }

    .input-group input {
      width: 100%;
      padding: 12px 12px 12px 42px;
      border-radius: 10px;
      border: 1px solid black;
      background: rgba(255, 255, 255, 0.1);
      color: black;
      transition: 0.3s ease;
    }

    .input-group input::placeholder {
      color: black;
    }

    .input-group input:focus {
      outline: none;
      border-color: #4eaaff;
      background: rgba(255, 255, 255, 0.2);
    }

    .glass-form input[type="submit"] {
      width: 100%;
      padding: 12px;
      border: none;
      background-color: #2575fc;
      color: white;
      font-size: 16px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .glass-form input[type="submit"]:hover {
      background-color: #1a5ed7;
    }

    i {
      color: white;
    }
	
/* Enabled button (already exists) */
.glass-form input[type="submit"] {
  width: 100%;
  padding: 12px;
  border: none;
  background-color: #2575fc;
  color: white;
  font-size: 16px;
  border-radius: 10px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

/* Hover style for enabled button */
.glass-form input[type="submit"]:hover:enabled {
  background-color: #1a5ed7;
}

/* Disabled button style */
.glass-form input[type="submit"]:disabled {
  background-color: #cccccc;     /* Light gray */
  color: #666666;                /* Dimmed text */
  cursor: not-allowed;
  opacity: 0.6;
  box-shadow: none;
}
  </style>
</head>
<body>

<div style="position: absolute; top: 20px; right: 30px;">
  <a href="login.php" class="btn btn-outline-light">Login</a>
</div>

<?php if (!empty($message)) echo "<div style='position:absolute; top:100px; text-align:center; width:100%;'>" . $message . "</div>"; ?>

<form class="glass-form" method="POST" action="" onsubmit="return validateForm();">
  <h2>Register</h2>

  <div class="input-group">
    <i class="fa fa-user"></i>
    <input type="text" name="username" id="username" placeholder="Username" oninput="validateUsername()" required>
  </div>
  <div id="usernameError" style="color: red; font-size: 14px; margin-top: -10px; margin-bottom: 15px;"></div>

<div class="input-group">
  <i class="fa fa-envelope"></i>
  <input type="email" name="email" id="email" placeholder="Email" oninput="validateEmail()" required>
</div>
<div id="emailError" style="color: red; font-size: 14px; margin-top: -10px; margin-bottom: 15px;"></div>


  <div class="input-group">
    <i class="fa fa-lock"></i>
    <input type="password" name="password" id="password" placeholder="Password" oninput="validatePassword()" required>
  </div>
  <div id="passwordError" style="color: black; font-size: 14px; margin-top: -10px; margin-bottom: 15px;"></div>

  <input type="submit" value="Register" id="registerBtn" disabled>


</form>

<script>
document.getElementById("username").addEventListener("input", validateForm);
document.getElementById("email").addEventListener("input", validateForm);
document.getElementById("password").addEventListener("input", validateForm);

// Master validation function
function validateForm() {
  const usernameValid = validateUsername();
  const emailValid = validateEmail();
  const passwordValid = validatePassword();

  const allValid = usernameValid && emailValid && passwordValid;
  document.getElementById("registerBtn").disabled = !allValid;
  return allValid;
}

// Username: only alphabets
function validateUsername() {
  const username = document.getElementById("username").value.trim();
  const errorDiv = document.getElementById("usernameError");
  const isValid = /^[A-Za-z]+$/.test(username);

  if (!isValid) {
    errorDiv.textContent = "Username must contain only alphabets (A–Z or a–z).";
    document.getElementById("username").style.borderColor = "red";
  } else {
    errorDiv.textContent = "";
    document.getElementById("username").style.borderColor = "black";
  }

  return isValid;
}

// Email: must end with @gmail.com
function validateEmail() {
  const emailInput = document.getElementById("email");
  const email = emailInput.value.trim();
  const errorDiv = document.getElementById("emailError");
  const isValid = /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email);

  if (!isValid) {
    errorDiv.textContent = "Email must end with @gmail.com";
    emailInput.style.borderColor = "red";
  } else {
    errorDiv.textContent = "";
    emailInput.style.borderColor = "black";
  }

  return isValid;
}

// Password: validate length and required characters
function validatePassword() {
  const password = document.getElementById("password").value;
  const errorDiv = document.getElementById("passwordError");

  const errors = [];

  if (password.length < 8 || password.length > 10)
    errors.push("8–10 characters");
  if (!/[A-Z]/.test(password))
    errors.push("One uppercase letter");
  if (!/[a-z]/.test(password))
    errors.push("One lowercase letter");
  if (!/[0-9]/.test(password))
    errors.push("One digit");
  if (!/[!@#$%^&*(),.?\":{}|<>]/.test(password))
    errors.push("At least one special character");

  if (errors.length > 0) {
    errorDiv.innerHTML = "Password must contain:<br>• " + errors.join("<br>• ");
    document.getElementById("password").style.borderColor = "red";
    return false;
  } else {
    errorDiv.innerHTML = "";
    document.getElementById("password").style.borderColor = "black";
    return true;
  }
}
</script>


</body>
</html>