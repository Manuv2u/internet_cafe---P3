<?php
require 'db_connect.php';

$user = null;
$message = "";

// Load available computers
$computers = $conn->query("SELECT computer_name, computer_name FROM computers WHERE status = 'available'");

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE name LIKE ? OR mobile_number LIKE ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $computer_id = $_POST['computer_id'];
    $start_session = $_POST['start_session'];
    $end_session = $_POST['end_session'];

    $start = new DateTime($start_session);
    $end = new DateTime($end_session);
    $now = new DateTime();

    if ($end <= $start) {
        $message = "Error: End session must be after start session.";
    } else {
        $interval = $start->diff($end);
        $duration = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        $rate_per_minute = 1.50;
        $amount_billed = $duration * $rate_per_minute;

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, computer_name, start_session, end_session, duration, amount_billed) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissid", $user_id, $computer_id, $start_session, $end_session, $duration, $amount_billed);

        if ($stmt->execute()) {
            if ($end <= $now) {
                $conn->query("UPDATE computers SET status = 'available' WHERE id = $computer_id");
                $message = "Booking recorded. Session ended. Duration: {$duration} mins, Bill: ₹" . number_format($amount_billed, 2);
            } else {
                $conn->query("UPDATE computers SET status = 'in_use' WHERE id = $computer_id");
                $message = "Booking recorded. Duration: {$duration} mins, Bill: ₹" . number_format($amount_billed, 2);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Session</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: linear-gradient(to right, #ACB6E5, #74ebd5);
        }
        .info, .success {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
        .success {
            color: green;
        }
        .info {
            color: #555;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Book User Session</h2>

    <?php if ($message): ?>
        <div class="<?= strpos($message, 'Error') !== false ? 'info' : 'success' ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="GET">
        <input type="text" name="search" placeholder="Enter name or mobile number" required>
        <button type="submit">Search User</button>
    </form>

    <?php if ($user): ?>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($user['mobile_number']) ?></p>
        </div>

        <form method="POST">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

            <label>Computer Name:</label>
            <select name="computer_id" required>
                <option value="">Select Computer</option>
                <?php
                $computers = $conn->query("SELECT id, computer_name FROM computers WHERE status = 'available'");
                while ($row = $computers->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['computer_name']) . '</option>';
                }
                ?>
            </select>

            <label>Start Session:</label>
            <input type="datetime-local" name="start_session" id="start_session" required onpaste="return false;">

            <label>End Session:</label>
            <input type="datetime-local" name="end_session" id="end_session" required onpaste="return false;">

            <button type="submit">Generate Bill</button>
        </form>
    <?php elseif (isset($_GET['search'])): ?>
        <p class="info">No user found matching your search.</p>
    <?php endif; ?>
</div>

<script>
window.onload = function () {
    const now = new Date();
    now.setSeconds(0, 0);

    const iso = now.toISOString().slice(0, 16); // yyyy-MM-ddTHH:mm
    const startInput = document.getElementById('start_session');
    const endInput = document.getElementById('end_session');

    startInput.min = iso;
    endInput.min = iso;
    startInput.value = iso;

    // Adjust endInput min when start changes
    startInput.addEventListener('change', function () {
        endInput.min = this.value;
        if (endInput.value < this.value) {
            endInput.value = this.value;
        }
    });

    // Disable paste
    document.querySelectorAll('input[type="datetime-local"]').forEach(input => {
        input.addEventListener('paste', e => e.preventDefault());
    });
};
</script>

</body>
</html>