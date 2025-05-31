<?php
require 'db_connect.php';

if (!isset($_GET['id'])) {
    die("User ID is missing.");
}

$id = $_GET['id'];

// Fetch current user
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) die("User not found.");

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name          = trim($_POST['name']);
    $address       = trim($_POST['address']);
    $mobile_number = trim($_POST['mobile_number']);
    $email         = trim($_POST['email']);

    // Server-side validation
    if (!preg_match("/^([A-Z][a-z]+)( [A-Z][a-z]+)*$/", $name)) {
        $message = "Each word in the name must start with a capital letter and contain no numbers.";
    } elseif (!preg_match("/^[6789][0-9]{9}$/", $mobile_number)) {
        $message = "Mobile number must start with 6, 7, 8, or 9 and be 10 digits long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Check for duplicate mobile/email (excluding current user)
        $check = $conn->prepare("SELECT id FROM user WHERE (mobile_number=? OR email=?) AND id != ?");
        $check->bind_param("ssi", $mobile_number, $email, $id);
        $check->execute();
        $check_result = $check->get_result();
        if ($check_result->num_rows > 0) {
            $message = "Mobile number or email already exists.";
        } else {
            $update = $conn->prepare("UPDATE user SET name=?, address=?, mobile_number=?, email=? WHERE id=?");
            $update->bind_param("ssssi", $name, $address, $mobile_number, $email, $id);
            if ($update->execute()) {
                $message = "User updated successfully!";
                // Optionally redirect
                // header("Location: manage_user.php");
            } else {
                $message = "Update failed: " . $update->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            const name = document.forms[0]["name"].value.trim();
            const mobile = document.forms[0]["mobile_number"].value.trim();
            const email = document.forms[0]["email"].value.trim();

            const nameRegex = /^([A-Z][a-z]+)( [A-Z][a-z]+)*$/;
            const mobileRegex = /^[6789][0-9]{9}$/;
            const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            let error = "";

            if (!nameRegex.test(name)) {
                error += "- Name must have each word start with a capital letter and contain no numbers.\n";
            }
            if (!mobileRegex.test(mobile)) {
                error += "- Mobile number must start with 6, 7, 8, or 9 and be 10 digits.\n";
            }
            if (!emailRegex.test(email)) {
                error += "- Email must be valid and contain letters, numbers, and special characters.\n";
            }

            if (error !== "") {
                alert("Please fix the following:\n" + error);
                return false;
            }
            return true;
        }

        // Auto-capitalize name input
        function capitalizeName(input) {
            input.value = input.value
                .toLowerCase()
                .split(' ')
                .filter(w => w.length > 0)
                .map(w => w.charAt(0).toUpperCase() + w.slice(1))
                .join(' ');
        }
    </script>
    <style>
        /* same styling as your original CSS — keep as-is */
        /* Include styling from your previous code here */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            min-height: 100vh;
            margin: 0;
        }

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
            margin: 50px auto;
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: bold;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
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
            cursor: pointer;
        }

        button:hover {
            background: linear-gradient(to right, #ACB6E5, #74ebd5);
        }

        p {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <div class="dropdown">
        <a href="#">Computer</a>
        <div class="dropdown-content">
            <a href="add_computer.php">Add Computer</a>
            <a href="manage_computers.php">Manage Computers</a>
        </div>
    </div>
    <div class="dropdown">
        <a href="#">User</a>
        <div class="dropdown-content">
            <a href="add_user.php">Add User</a>
            <a href="manage_user.php">Manage Users</a>
        </div>
    </div>
    <a href="booking.php">Bookings</a>
    <a href="search_user.php">Search</a>
    <a href="generate_report.php">Reports</a>
</div>

<div class="form-container">
    <h2>Edit User</h2>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <form method="POST" onsubmit="return validateForm();">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required onblur="capitalizeName(this)">

        <label>Address:</label>
        <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>

        <label>Mobile Number:</label>
        <input type="text" name="mobile_number" value="<?= htmlspecialchars($user['mobile_number']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <button type="submit">Update</button>
    </form>

    <a href="manage_user.php">Back to User List</a>
</div>

</body>
</html>
