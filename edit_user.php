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



