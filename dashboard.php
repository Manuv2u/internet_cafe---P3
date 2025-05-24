<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100%;
      background: linear-gradient(135deg, #dfe9f3, #ffffff);
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .sidebar {
      height: 100%;
      width: 250px;
      background: #2c3e50;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 30px;
      color: white;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.3);
      z-index: 999;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      padding: 15px 20px;
    }

    .sidebar ul li a {
      text-decoration: none;
      color: white;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .sidebar ul li a:hover {
      background-color: #34495e;
      border-radius: 5px;
      padding-left: 30px;
    }

    .sidebar ul li a i {
      margin-right: 15px;
      font-size: 1.3rem;
    }

    .sidebar ul li a:hover i {
      color: #f39c12;
    }

    .content {
      margin-left: 260px;
      padding: 30px;
    }

    .header {
  background: linear-gradient(to right, #4e54c8, #8f94fb);
  padding: 25px 20px;
  color: #ffffff;
  text-align: center;
  font-size: 2.2rem;
  font-weight: 700;
  border-radius: 12px;
  margin-bottom: 40px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
  letter-spacing: 1px;
}


    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      padding: 10px;
    }

    .grid-item {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: #fff;
      border-radius: 12px;
      cursor: pointer;
      height: 160px;
      background: linear-gradient(135deg, #8e44ad, #9b59b6);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: all 0.4s ease;
      animation: fadeIn 0.8s ease-in-out;
    }

    .grid-item:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }
	.dashboard-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  justify-content: center;
  padding: 20px;
}

.card-widget {
  flex: 1 1 300px;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  color: white;
  border-radius: 15px;
  padding: 30px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.2);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.card-widget:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.3);
}

.card-icon {
  font-size: 3rem;
  background: rgba(255, 255, 255, 0.2);
  padding: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-content h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 600;
}

.card-content p {
  margin: 5px 0 0;
  font-size: 2rem;
  font-weight: bold;
}

/* Custom colors for different cards */
.card-widget.users {
  background: linear-gradient(135deg, #1d976c, #93f9b9);
}

.card-widget.computers {
  background: linear-gradient(135deg, #ff416c, #ff4b2b);
}





.sidebar {
  height: 100%;
  width: 250px;
  background: linear-gradient(to bottom, #4e54c8, #8f94fb);
  position: fixed;
  top: 0;
  left: 0;
  padding-top: 30px;
  color: white;
  box-shadow: 2px 0 12px rgba(0, 0, 0, 0.2);
  z-index: 999;
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
  overflow-y: auto;
}


.sidebar ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar ul li {
  margin: 10px 0;
}

.sidebar ul li a {
  text-decoration: none;
  color: white;
  font-size: 1rem;
  display: flex;
  align-items: center;
  padding: 12px 20px;
  transition: all 0.3s ease;
  border-radius: 8px;
}

.sidebar ul li a:hover {
  background: rgba(255, 255, 255, 0.1);
  padding-left: 25px;
}

.sidebar ul li a i {
  margin-right: 15px;
  font-size: 1.2rem;
  color: #f1c40f;
}

.sidebar ul li a:hover i {
  color: #ffffff;
}



.dropdown-menu-dark {
  background-color: rgba(0, 0, 0, 0.15);
  border: none;
  margin-left: 20px;
  padding: 0;
  border-radius: 8px;
  backdrop-filter: blur(10px);
  overflow: hidden;
}

.dropdown-item {
  color: #ffffff;
  font-size: 0.95rem;
  padding: 10px 20px;
  transition: background 0.3s ease;
}

.dropdown-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
}



    @media (max-width: 768px) {
      .content {
        margin-left: 0;
      }

      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }

      .sidebar ul li {
        text-align: center;
      }
    }

    .dropdown-menu-dark {
      background-color: #1a252f;
    }

    .dropdown-item:hover {
      background-color: #34495e;
    }
	
.top-bar {
    width: 100%;
    background-color: #f8f9fa;
    padding: 10px 20px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.logout-btn a {
    text-decoration: none;
    color: white;
    background-color: #dc3545;
    padding: 8px 16px;
    border-radius: 5px;
    font-weight: bold;
}

.logout-btn a:hover {
    background-color: #c82333;
}

  </style>
</head>
<body>
<div class="top-bar">
    <div class="logout-btn">
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="sidebar">
  <ul>
    <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-laptop"></i> Computer</a>
      <ul class="dropdown-menu dropdown-menu-dark">
        <li><a class="dropdown-item" href="add_computer.php">Add Computer</a></li>
        <li><a class="dropdown-item" href="manage_computers.php">Manage Computers</a></li>
      </ul>
    </li>
    <li><a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-users"></i> User</a>
      <ul class="dropdown-menu dropdown-menu-dark">
        <li><a class="dropdown-item" href="add_user.php">Add User</a></li>
        <li><a class="dropdown-item" href="manage_user.php">Manage User</a></li>
        <li><a class="dropdown-item" href="old_user.php">Old User</a></li>
      </ul>
    </li>
    <li><a href="Booking.php"><i class="fa-solid fa-window-maximize"></i> Booking</a></li>
    <li><a href="search_user.php"><i class="fas fa-search"></i> Search</a></li>
    <li><a href="generate_report.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
	
  </ul>
</div>

<div class="content">
  <div class="header">
    Welcome to the Dashboard
  </div>

 <div class="dashboard-cards">
  <div class="card-widget users" onclick="window.location.href='manage_user.php'">
    <div class="card-icon"><i class="fas fa-users"></i></div>
    <div class="card-content">
      <h3>Total Users</h3>
    </div>
  </div>

  <div class="card-widget computers" onclick="window.location.href='manage_computers.php'">
    <div class="card-icon"><i class="fas fa-desktop"></i></div>
    <div class="card-content">
      <h3>Total Computers</h3>
    </div>
  </div>
</div>

</div>

</body>
</html>