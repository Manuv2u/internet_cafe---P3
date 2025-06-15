<?php
require 'db_connect.php';
$sql="SELECT * FROM user ";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Old User</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
        body {
            font-family: 'Poppins', sans-serif;
            background:  linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            margin: 0;
            padding: 64px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
                /* Navbar */
          .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #ffffff;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .add-link {
            display: block;
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .add-link:hover {
            text-decoration: underline;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 16px 35px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e6f7ff;
        }

        a.action-link {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
        }

        a.action-link:hover {
            text-decoration: underline;
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


<h2>Archived User</h2>
<a href="manage_user.php">Back to Manage User</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>address</th><th>mobile_number</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
		<td><?= htmlspecialchars($row['address']) ?></td>
		  <td><?= htmlspecialchars($row['mobile_number']) ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>