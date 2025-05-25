<?php
require 'db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if (is_numeric($id)) {
        try {
            $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            header("Location: manage_user.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            echo "<script>
                alert('Cannot delete user: related records exist in other tables.');
                window.location.href = 'manage_user.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid ID');</script>";
    }
}

$sql = "SELECT * FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0c3fc, #8ec5fc 100%);
            margin: 0;
            padding: 20px;
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

        h2 {
            text-align: center;
            color: #333;
            margin: 30px 0 20px;
        }

        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #8ec5fc;
            color: white;
            font-weight: 600;
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
            font-weight: 600;
            margin: 0 6px;
        }

        a.action-link:hover {
            text-decoration: underline;
        }

        .delete-btn {
            color: red !important;
            cursor: pointer;
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

<h2>Manage Users</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Mobile Number</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><?= htmlspecialchars($row['mobile_number']) ?></td>
        <td>
            <a class="action-link" href="edit_user.php?id=<?= $row['id'] ?>">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a class="action-link delete-btn" data-id="<?= $row['id'] ?>">
                <i class="fas fa-trash-alt"></i> Delete
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#2575fc',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `manage_user.php?delete=${id}`;
            }
        });
    });
});
</script>

</body>
</html>
