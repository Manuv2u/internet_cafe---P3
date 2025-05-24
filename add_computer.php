
<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['computer_name'];
    $ip = $_POST['ip_address'];
    $status = $_POST['status'];

    // Server-side IP address validation
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $message = "❌ Invalid IP address format.";
    } else {
        $stmt = $conn->prepare("INSERT INTO computers (computer_name, ip_address, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $ip, $status);
        $message = $stmt->execute() ? "✅ Computer added successfully!" : "❌ Error adding computer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Computer</title>
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
            min-height: 100vh;
        }

        /* Navbar */
          .navbar {
    background-color: rgba(255, 255, 255, 0.85);
    padding: 14px 30px;
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(8px);
    border-bottom: 1px solid #ddd;
}

.navbar a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    padding: 10px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.navbar a:hover {
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

/* Dropdown styles */
.dropdown {
    position: relative;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    min-width: 180px;
    overflow: hidden;
    z-index: 999;
}

.dropdown-content a {
    padding: 12px 16px;
    color: #333;
    display: block;
    transition: background 0.3s;
}

.dropdown-content a:hover {
    background-color: #f0f0f0;
    color: #007bff;
}

.dropdown:hover .dropdown-content {
    display: block;
}

        /* Form Container */
       .form-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    padding: 35px 40px;
    border-radius: 20px;
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
    width: 100%;
    max-width: 500px;
    margin: 60px auto;
    border: 1px solid rgba(200, 200, 200, 0.3);
}


        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #222;
            font-weight: 600;
            font-size: 26px;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            color: #444;
            display: block;
            margin-bottom: 5px;
        }

         input[type="text"],
select {
	width:100%;
	margin-bottom:25px;
    background: #fdfdfd;
    border: 1px solid #ccc;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

input:focus,
select:focus {
    border-color: #74ebd5;
    box-shadow: 0 0 10px rgba(116, 235, 213, 0.6);
    background: #fff;
}

button {
    background: linear-gradient(to right, #74ebd5, #ACB6E5);
    border: none;
    padding: 14px;
    border-radius: 10px;
    width: 100%;
    color: white;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(116, 235, 213, 0.4);
}

button:hover {
    background: linear-gradient(to right, #ACB6E5, #74ebd5);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(116, 235, 213, 0.5);
}


        .message {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            color: green;
        }

        .error {
            color: red;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #555;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a.back-link:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
<?php include 'navbar.php';?>

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

<!-- Form -->
<div class="form-container">
    <h2>Add New Computer</h2>
    <?php
        if (isset($message)) {
            echo "<div class='message " . (str_starts_with($message, '✅') ? '' : 'error') . "'>" . htmlspecialchars($message) . "</div>";
        }
    ?>
    <form method="POST">
        <label for="computer_name">Computer Name:</label>
        <input type="text" id="computer_name" name="computer_name" required>

        <label for="ip_address">IP Address:</label>
        <input type="text" id="ip_address" name="ip_address" required pattern="^(\d{1,3}\.){3}\d{1,3}$" title="Enter a valid IP address (e.g. 192.168.1.1)">

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="available">Available</option>
            <option value="in use">In Use</option>
        </select>

        <button type="submit">Add Computer</button>
    </form>
   <!-- <a class="back-link" href="manage_computers.php">-->
</div>

</body>
</html>