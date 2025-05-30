<?php
require 'db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('❌ Invalid computer ID.');
}

$id = intval($_GET['id']);
$message = "";

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM computers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$computer = $result->fetch_assoc();

if (!$computer) {
    die('❌ Computer not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['computer_name'];
    $ip = $_POST['ip_address'];
    $status = $_POST['status'];

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $message = "❌ Invalid IP address format.";
    } else {
        $update = $conn->prepare("UPDATE computers SET computer_name = ?, ip_address = ?, status = ? WHERE id = ?");
        $update->bind_param("sssi", $name, $ip, $status, $id);
        $message = $update->execute() ? "✅ Computer updated successfully!" : "❌ Error updating computer.";
        // Refresh data after update
        $stmt = $conn->prepare("SELECT * FROM computers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $computer = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Computer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reuse the styles from add_computer.php */
        /* ... (same styles as in your existing add_computer.php) ... */
        /* Paste all CSS from your existing file here */
    </style>
</head>
<body>

<!-- Navbar (same as add_computer.php) -->
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
    <h2>Edit Computer</h2>
    <?php
        if (!empty($message)) {
            echo "<div class='message " . (str_starts_with($message, '✅') ? '' : 'error') . "'>" . htmlspecialchars($message) . "</div>";
        }
    ?>
    <form method="POST">
        <label for="computer_name">Computer Name:</label>
        <input type="text" id="computer_name" name="computer_name" value="<?= htmlspecialchars($computer['computer_name']) ?>" required>

        <label for="ip_address">IP Address:</label>
        <input type="text" id="ip_address" name="ip_address" value="<?= htmlspecialchars($computer['ip_address']) ?>" required pattern="^(\d{1,3}\.){3}\d{1,3}$" title="Enter a valid IP address (e.g. 192.168.1.1)">

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="available" <?= $computer['status'] == 'available' ? 'selected' : '' ?>>Available</option>
            <option value="in use" <?= $computer['status'] == 'in use' ? 'selected' : '' ?>>In Use</option>
        </select>

        <button type="submit">Update Computer</button>
    </form>
    <a class="back-link" href="manage_computers.php">← Back to Manage Computers</a>
</div>

</body>
</html>
