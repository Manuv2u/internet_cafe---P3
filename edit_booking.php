<?php
require 'db_connect.php';

$user = null;
$message = "";
$selected_mode = "available";

// Load available computers
// Load available computers
$computers = $conn->query("SELECT computer_name, computer_name, id FROM computers WHERE status = 'available'");

if (isset($_GET['user'])) {
    $search = $_GET['user'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE name LIKE ? OR mobile_number LIKE ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if(isset($_GET['computer_id'])){
    $computer_id = $_GET['computer_id'];
    $stmt = $conn -> prepare("SELECT computer_name, computer_name, id FROM computers WHERE id = ? ");
    $stmt->bind_param('i',$computer_id);
    $stmt->execute();
    $computer = $stmt->get_result()->fetch_assoc();
}

if(isset($_GET['booking_id'])){
    $booking_id = $_GET['booking_id'];
    $stmt = $conn -> prepare("SELECT * FROM bookings WHERE id = ? ");
    $stmt->bind_param('i',$booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    $start_session = $booking['start_session'];
    $start_time = new DateTime($start_session);
    echo $start_time->format('Y-m-d\TH:i');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $booking_id = $_POST['booking_id'];
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

        // $stmt = $conn->prepare("INSERT INTO bookings (user_id, computer_name, start_session, end_session, duration, amount_billed) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt = $conn->prepare("UPDATE bookings SET end_session = ?, duration= ?,amount_billed = ? WHERE id = ?");

        $stmt = $conn->prepare("UPDATE bookings SET end_session = ?, duration= ?,amount_billed = ? WHERE id = ?");
        $stmt->bind_param("sidi", $end_session,$duration,$amount_billed,$booking_id );

        if ($stmt->execute()) {
            $conn->query("UPDATE computers SET status = 'available' WHERE id = $computer_id");
            $message = "Booking recorded. Session ended. Duration: {$duration} mins, Bill: ₹" . number_format($amount_billed, 2);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Session</title>
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
    <h2>End User Session</h2>

    <?php if ($message): ?>
        <div class="<?= strpos($message, 'Error') !== false ? 'info' : 'success' ?>"><?= $message ?></div>
    <?php endif; ?>

    <!-- <form method="GET">
        <input type="text" name="search" placeholder="Enter name or mobile number" required>
        <button type="submit">Search User</button>
    </form> -->

    <?php if ($user): ?>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($user['mobile_number']) ?></p>
        </div>

        <div id="add_session_form">
        <form method="POST">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

            <label>Computer Name:</label>
            <label name="computer_name"><?php echo $computer['computer_name'] ?> </label>

            <input type="hidden" name="computer_id" value="<?= $computer_id ?>" ></input>

            <input type="hidden" name="booking_id" value="<?= $booking_id ?>" ></input>

            <label>Start Session:</label>
            <input type="datetime-local" readonly="readonly" name="start_session" id="start_session" required onpaste="return false;" value="<?php echo $start_time->format('Y-m-d\TH:i'); ?>">

            <label>End Session:</label>
            <input type="datetime-local" name="end_session" id="end_session" required onpaste="return false;">

            <button type="submit">End Session</button>
        </form>
        </div>
    
    <?php elseif (isset($_GET['search'])): ?>
        <p class="info">No user found matching your search.</p>
    <?php endif; ?>

    <button id="back_button">Back</button>
</div>

<script>
window.onload = function () {

    const now = new Date();
    now.setSeconds(0, 0);

    const iso = now.toLocaleString('sv').replace(' ', 'T').slice(0, 16); 

    const startInput = document.getElementById('start_session');
    const endInput = document.getElementById('end_session');

    endInput.min = iso;
    endInput.value = iso;

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

    const back = document.getElementById('back_button')
    back.addEventListener('click', function(){
        var host =   "http://" + window.location.host;
        let url = new URL(host+"/p/Booking.php")
        window.location.href = url.toString()
    })



};
</script>

</body>
</html>