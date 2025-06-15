 <?php
require 'db_connect.php';

$results = [];
$search_term = "";

if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    $stmt = $conn->prepare("
        SELECT 
            u.name, 
            u.mobile_number, 
            b.computer_name, 
            b.start_session, 
            b.end_session, 
            b.duration, 
            b.amount_billed
        FROM bookings b
        JOIN user u ON b.user_id = u.id
     /* JOIN computers c ON b.computer_name = c.id */
        WHERE u.name LIKE ? OR u.mobile_number LIKE ?
        ORDER BY b.start_session DESC
    ");
    $like = "%$search_term%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Booking Records</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #ffffffdd;
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
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
			color:#8ec5fc  ;
        }

        form input[type="text"] {
            width: 80%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        form button {
            padding: 12px 20px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 14px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .no-results {
            text-align: center;
            font-weight: bold;
            color: #333;
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

<div class="container">
    <h2>Search User Details</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter user name " value="<?= htmlspecialchars($search_term) ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if (isset($_GET['search'])): ?>
        <?php if ($results && $results->num_rows > 0): ?>
            <table>
                <tr>
                    <th>User Name</th>
                    <th>Mobile Number</th>
                    <th>Computer Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration (mins)</th>
                    <th>Amount Billed (₹)</th>
                </tr>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['mobile_number']) ?></td>
                        <td><?= htmlspecialchars($row['computer_name']) ?></td>
                        <td><?= date('d-M-Y H:i', strtotime($row['start_session'])) ?></td>
                        <td><?= date('d-M-Y H:i', strtotime($row['end_session'])) ?></td>
                        <td><?= htmlspecialchars($row['duration']) ?></td>
                        <td><?= htmlspecialchars(number_format($row['amount_billed'], 2)) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-results">No user records found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>