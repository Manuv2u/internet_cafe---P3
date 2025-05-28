<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Computer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            min-height: 100vh;
            margin: 0;
            padding-top: 70px; /* Space for fixed navbar */
        }

        .form-container {
            background: #ffffff;
            padding: 40px 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: bold;
            letter-spacing: 1px;
        }

        form input[type="text"],
        form select {
            width: 100%;
            padding: 12px 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
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

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #333;
            font-weight: bold;
            text-decoration: none;
        }

        p {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-white fixed-top">
    <a class="navbar-brand font-weight-bold text-dark" href="dashboard.php">Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">

            <li class="nav-item dropdown font-weight-bold text-dark">
                <a class="nav-link dropdown-toggle" href="computer.php" id="computerDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Computer
                </a>
                <div class="dropdown-menu" aria-labelledby="computerDropdown">
                    <a class="dropdown-item" href="add_computer.php">Add Computer</a>
                    <a class="dropdown-item" href="manage_computers.php">Manage Computers</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    User
                </a>
                <div class="dropdown-menu" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="add_user.php">Add User</a>
                    <a class="dropdown-item" href="manage_user.php">Manage Users</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="booking.php">Bookings</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="search_user.php">Search</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="generate_report.php">Report</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Form -->
<div class="form-container">
    <h2>Edit Computer</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>

    <form method="POST" id="editForm">
        <label>Computer Name:</label>
        <input type="text" name="computer_name" value="<?= htmlspecialchars($computer['computer_name']) ?>" required>

        <label>IP Address:</label>
        <input type="text" name="ip_address" id="ip_address"
               value="<?= htmlspecialchars($computer['ip_address']) ?>"
               pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
               title="Enter a valid IP address like 192.168.0.1" required>

        <label>Status:</label>
        <select name="status">
            <option value="available" <?= $computer['status'] === 'available' ? 'selected' : '' ?>>Available</option>
            <option value="in use" <?= $computer['status'] === 'in use' ? 'selected' : '' ?>>In Use</option>
        </select>

        <button type="submit">Update</button>
        <a href="manage_computers.php">Cancel</a>
    </form>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Optional JS IP validation
    document.getElementById("editForm").addEventListener("submit", function (e) {
        const ip = document.getElementById("ip_address").value.trim();
        const regex = /^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        if (!regex.test(ip)) {
            alert("Please enter a valid IP address (e.g., 192.168.0.1)");
            e.preventDefault();
        }
    });
</script>

</body>
</html>

