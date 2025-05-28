<?php
require 'db_connect.php';

if (!isset($_GET['id'])) {
    die("User ID is missing.");
}

$id = $_GET['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = $_POST['name'];
    $address       = $_POST['address'];
    $mobile_number = $_POST['mobile_number'];
    $email         = $_POST['email'];

    $update = $conn->prepare("UPDATE user SET name=?, address=?, mobile_number=?, email=? WHERE id=?");
    $update->bind_param("ssssi", $name, $address, $mobile_number, $email, $id);

    if ($update->execute()) {
        $message = "User updated successfully!";
        // Optional: Redirect
        // header("Location: manage_user.php");
    } else {
        $message = "Update failed: " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
	 * {
            box-sizing: border-box;
        }

         body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
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
        form select,
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

        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form select:focus,
        form textarea:focus {
            border-color: #74ebd5;
            outline: none;
            box-shadow: 0 0 8px rgba(116, 235, 213, 0.5);
        }

        form textarea {
            resize: vertical;
            min-height: 80px;
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

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        a:hover {
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

<div class="container">
 <div class="form-container">
       <h2>Edit User</h2>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <form method="POST">
        Name: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>
        Address: <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea><br><br>
        Mobile Number: <input type="text" name="mobile_number" value="<?= htmlspecialchars($user['mobile_number']) ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>
        <button type="submit">Update</button>
    </form>

    <br>
    <a href="manage_user.php">Back to User List</a>
</body>
</html>



