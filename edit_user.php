<?php
require 'db_connect.php';

if (!isset($_GET['id'])) {
    die("No user ID specified.");
}

$id = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) die("User not found.");

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = trim($_POST['name']);
    $address       = trim($_POST['address']);
    $mobile_number = trim($_POST['mobile_number']);
    $email         = trim($_POST['email']);

    // Server-side validation
    if (!preg_match("/^([A-Z][a-z]+)( [A-Z][a-z]+)*$/", $name)) {
        $message = "Each word in the name must start with a capital letter.";
    } elseif (strlen($address) < 10) {
        $message = "Address must be at least 10 characters long.";
    } elseif (!preg_match("/^[6789][0-9]{9}$/", $mobile_number)) {
        $message = "Mobile number must start with 6, 7, 8, or 9 and be 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
        $message = "Email must be a valid Gmail address.";
    } else {
        $check = $conn->prepare("SELECT id FROM user WHERE (mobile_number=? OR email=?) AND id != ?");
        $check->bind_param("ssi", $mobile_number, $email, $id);
        $check->execute();
        $check_result = $check->get_result();
        if ($check_result->num_rows > 0) {
            $message = "Mobile number or email already exists.";
        } else {
            $update = $conn->prepare("UPDATE user SET name=?, address=?, mobile_number=?, email=? WHERE id=?");
            $update->bind_param("ssssi", $name, $address, $mobile_number, $email, $id);
            if ($update->execute()) {
                $message = "✅ User updated successfully!";
                $user = ['name' => $name, 'address' => $address, 'mobile_number' => $mobile_number, 'email' => $email];
            } else {
                $message = "Update failed: " . $update->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
      * {
            box-sizing: border-box;
        }   
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            min-height: 100vh;
            margin: 0;
        }
     .navbar {
    background-color: rgba(255, 255, 255, 0.95);
    display: flex;
    align-items: center;         /* Vertically center items */
    justify-content: flex-start; /* Keep items left-aligned */
    gap: 24px;
    padding: 6px 16px;           /* Reduce top-bottom space */
    height: 50px;                /* Fix a compact height */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid #ccc;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar a {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
    transition: background-color 0.2s ease;
}

.navbar a:hover {
    background-color: #f2f2f2;
}

.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 6px;
    min-width: 140px;
    z-index: 999;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown-content a {
    display: block;
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
}

.dropdown-content a:hover {
    background-color: #f0f0f0;
}
        .form-container {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
        }
        h2 {
            text-align: center;
            color: #333;
            font-weight: bold;
        }
        form input, form textarea {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        form input:focus, form textarea:focus {
            border-color: #74ebd5;
            outline: none;
            box-shadow: 0 0 8px rgba(116, 235, 213, 0.5);
        }
        button {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            border: none;
            padding: 14px;
            border-radius: 8px;
            width: 100%;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: linear-gradient(to right, #ACB6E5, #74ebd5);
        }
        p {
            text-align: center;
            color: green;
            font-weight: bold;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #007bff;
        }
        .error-message {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<!-- Navbar -->

<div class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="https://cdn-icons-png.flaticon.com/512/888/888879.png" alt="Logo" style="width: 30px;">
        <strong style="color: #007bff; font-size: 18px;">Internet Cafe Shop</strong>
    </div>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <div class="dropdown">
        <a href="#"><i class="fas fa-desktop"></i> Computer</a>
        <div class="dropdown-content">
            <a href="add_computer.php">Add Computer</a>
            <a href="manage_computers.php">Manage Computers</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#"><i class="fas fa-users"></i> User</a>
        <div class="dropdown-content">
            <a href="add_user.php">Add User</a>
            <a href="manage_user.php">Manage Users</a>
        </div>
    </div>

    <a href="booking.php"><i class="fa-solid fa-window-maximize"></i> Bookings</a>
    <a href="search_user.php"><i class="fas fa-search"></i> Search</a>
    <a href="generate_report.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="logout.php" style="margin-left:auto;"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="form-container">
    <h2>Edit User</h2>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <div class="error-message" id="nameError"></div>

        <label>Address:</label>
        <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>
        <div class="error-message" id="addressError"></div>

        <label>Mobile Number:</label>
        <input type="text" name="mobile_number" value="<?= htmlspecialchars($user['mobile_number']) ?>" required>
        <div class="error-message" id="mobileError"></div>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <div class="error-message" id="emailError"></div>

        <button type="submit">Update</button>
    </form>
    <a href="manage_user.php">Back to User List</a>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const name = document.querySelector('input[name="name"]');
    const address = document.querySelector('textarea[name="address"]');
    const mobile = document.querySelector('input[name="mobile_number"]');
    const email = document.querySelector('input[name="email"]');

    const nameErr = document.getElementById("nameError");
    const addressErr = document.getElementById("addressError");
    const mobileErr = document.getElementById("mobileError");
    const emailErr = document.getElementById("emailError");

    function validateName() {
        const value = name.value.trim();
        const regex = /^([A-Z][a-z]+)( [A-Z][a-z]+)*$/;
        nameErr.textContent = regex.test(value) ? "" : "Each word must start with capital letter.";
    }

    function validateAddress() {
        addressErr.textContent = address.value.trim().length < 10 ? "Address must be at least 10 characters." : "";
    }

    function validateMobile() {
        const regex = /^[6789]\d{9}$/;
        mobileErr.textContent = regex.test(mobile.value.trim()) ? "" : "Must be 10 digits starting with 6-9.";
    }

    function validateEmail() {
        const value = email.value.trim();
        emailErr.textContent = (!value.endsWith("@gmail.com") || value.indexOf("@") <= 0) ? "Use a valid Gmail address." : "";
    }

    name.addEventListener("input", validateName);
    address.addEventListener("input", validateAddress);
    mobile.addEventListener("input", validateMobile);
    email.addEventListener("input", validateEmail);

    name.addEventListener("blur", () => {
        name.value = name.value
            .toLowerCase()
            .split(' ')
            .filter(word => word)
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
        validateName();
    });

    document.querySelector("form").addEventListener("submit", (e) => {
        validateName();
        validateAddress();
        validateMobile();
        validateEmail();
        if (nameErr.textContent || addressErr.textContent || mobileErr.textContent || emailErr.textContent) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
