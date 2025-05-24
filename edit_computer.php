<?php
require 'db_connect.php';

if (!isset($_GET['id'])) {
    die("No computer ID specified.");
}

$id = intval($_GET['id']);

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM computers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$computer = $result->fetch_assoc();

if (!$computer) {
    die("Computer not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['computer_name']);
    $ip = trim($_POST['ip_address']);
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE computers SET computer_name = ?, ip_address = ?, status = ? WHERE id = ?");
    $update->bind_param("sssi", $name, $ip, $status, $id);

    if ($update->execute()) {
        header("Location: manage_computers.php?updated=1");
        exit();
    } else {
        $error = "Error updating computer.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Computer</title>
    <style>
	 * {
            box-sizing: border-box;
        }
         body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
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
<div class="container">
 <div class="form-container">
    <h2>Edit Computer</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Computer Name:</label><br>
        <input type="text" name="computer_name" value="<?= htmlspecialchars($computer['computer_name']) ?>" required><br><br>

        <label>IP Address:</label><br>
        <input type="text" name="ip_address" value="<?= htmlspecialchars($computer['ip_address']) ?>"><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="available" <?= $computer['status'] === 'available' ? 'selected' : '' ?>>Available</option>
            <option value="in use" <?= $computer['status'] === 'in use' ? 'selected' : '' ?>>In Use</option>
        </select><br><br>

        <button type="submit">Update</button>
        <a href="manage_computers.php">Cancel</a>
    </form>
</body>
</html>