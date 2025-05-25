<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = $_POST['name'];
    $address       = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $email         = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO user (name, address, mobile_number, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $address, $mobile_number, $email);

    if ($stmt->execute()) {
        $message = "User added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color:  rgba(255, 255, 255, 0.85) ;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #007bff;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 170px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            z-index: 999;
            border-radius: 6px;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 14px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f0f0f0;
            color: #007bff;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 60px);
            padding: 30px;
        }

        .form-container {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: bold;
            letter-spacing: 1px;
        }

        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 12px 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s;
        }

        form input:focus,
        form textarea:focus {
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
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: linear-gradient(to right, #ACB6E5, #74ebd5);
        }

        a.manage-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            font-weight: bold;
            text-decoration: none;
        }

        a.manage-link:hover {
            color: #007bff;
        }

        p {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <div class="dropdown">
        <a href="#"><i class="fas fa-desktop"></i> Computer <i class="fas fa-caret-down"></i></a>
        <div class="dropdown-content">
            <a href="add_computer.php">Add Computer</a>
            <a href="manage_computers.php">Manage Computers</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#"><i class="fas fa-users"></i> User <i class="fas fa-caret-down"></i></a>
        <div class="dropdown-content">
            <a href="add_user.php">Add User</a>
            <a href="manage_user.php">Manage Users</a>
        </div>
    </div>
        <a href="booking.php"><i class="fa-solid fa-window-maximize"></i> Bookings</a>
    <a href="search_user.php"><i class="fas fa-search"></i> Search</a>
    <a href="generate_report.php"><i class="fas fa-chart-line"></i> Reports</a>
</div>

<!-- Main Form -->
<div class="container">
    <div class="form-container">
        <h2>Add New User</h2>

        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Address:</label>
            <textarea name="address" rows="3" required></textarea>

            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <button type="submit">Add User</button>
        </form>

       <!-- <a class="manage-link" href="manage_user.php">-->
    </div>
</div>

</body>
<script>
document.querySelector("form").addEventListener("submit", function (e) {
    const name = document.querySelector('input[name="name"]');
    const address = document.querySelector('textarea[name="address"]');
    const mobile = document.querySelector('input[name="mobile_number"]');
    const email = document.querySelector('input[name="email"]');

    const nameRegex = /^[A-Za-z ]{2,}$/;
    const mobileRegex = /^[6-9]\d{9}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let errorMsg = "";

    if (!name.value.trim() || !nameRegex.test(name.value.trim())) {
        errorMsg += "- Enter a valid name (at least 2 letters).\n";
    }

    if (!address.value.trim()) {
        errorMsg += "- Address cannot be empty.\n";
    }

    if (!mobileRegex.test(mobile.value.trim())) {
        errorMsg += "- Enter a valid 10-digit mobile number starting with 6-9.\n";
    }

    if (!emailRegex.test(email.value.trim())) {
        errorMsg += "- Enter a valid email address.\n";
    }

    if (errorMsg) {
        e.preventDefault();
        alert("Please correct the following:\n" + errorMsg);
    }
});
</script>
</html>