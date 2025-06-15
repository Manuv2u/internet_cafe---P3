<?php
require 'db_connect.php';

$total_billed = 0;
$message = '';
$errors = [];

if (isset($_GET['refresh'])) {
    $start_date = null;
    $end_date = null;
} else {
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
}

if ($start_date && $end_date) {
    if (strtotime($start_date) > strtotime($end_date)) {
        $errors[] = "From date cannot be after To date.";
    } else {
        $sql = "SELECT 
                    u.name AS user_name,
                    u.mobile_number,
                    u.email,
                    c.computer_name,
                    b.start_session,
                    b.end_session,
                    b.duration,
                    b.amount_billed
                FROM bookings b
                JOIN user u ON b.user_id = u.id
                JOIN computers c ON b.computer_name = c.id
                WHERE DATE(b.start_session) BETWEEN ? AND ?
                ORDER BY b.start_session DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $message = "No users were booked during the selected period.";
        }
    }
}

if (!isset($result)) {
    $sql = "SELECT 
                u.name AS user_name,
                u.mobile_number,
                u.email,
                c.computer_name,
                b.start_session,
                b.end_session,
                b.duration,
                b.amount_billed
            FROM bookings b
            JOIN user u ON b.user_id = u.id
            JOIN computers c ON b.computer_name = c.id
            ORDER BY b.start_session DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
	 * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
        }
        main{
            padding: 10px 20px;
            margin-top : 60px;
        }
		.navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: #ffffff;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            gap: 30px;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
          color:   #333;
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


        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        input[type="date"], button {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            transition: 0.3s ease;
            cursor: pointer;
        }

        button.refresh-btn {
            background-color: #28a745;
        }

        button:hover {
            opacity: 0.9;
        }

        table {
            width: 90%;
            
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #8ec5fc;
            background-image:  #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #eef6ff;
        }

        .total {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }

        .pdf-button {
            text-align: center;
            margin-top: 25px;
        }

        .pdf-button button {
            background-color: #ff5722;
            font-weight: bold;
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
<main>
<h2> Report</h2>

<form method="GET" action="">
    <label>From:</label>
    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date ?? '') ?>">
    <label>To:</label>
    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date ?? '') ?>">
    <button type="submit">Search</button>
    <button type="submit" name="refresh" class="refresh-btn">Refresh</button>
</form>
<?php if (!empty($errors)) : ?>
    <div style="color: red; text-align:center; margin-bottom:15px;">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php elseif (!empty($message)) : ?>
    <div style="color: darkorange; text-align:center; margin-bottom:15px;">
        <p><?= htmlspecialchars($message) ?></p>
    </div>
<?php endif; ?>


<table>
    <thead>
        <tr>
            <th>User Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Computer</th>
            <th>Start Session</th>
            <th>End Session</th>
            <th>Duration (min)</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { 
            $total_billed += $row['amount_billed'];
        ?>
        <tr>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['mobile_number']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['computer_name']) ?></td>
            <td><?= htmlspecialchars($row['start_session']) ?></td>
            <td><?= htmlspecialchars($row['end_session']) ?></td>
            <td><?= htmlspecialchars($row['duration']) ?></td>
            <td><?= number_format($row['amount_billed'], 2) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="total">
    Total Amount Billed: <?= number_format($total_billed, 2) ?>
</div>

<div class="pdf-button">
    <button onclick="generatePDF()">Download PDF</button>
</div>

<script>
    async function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');

        doc.setFontSize(18);
        doc.text("Booking Report", 40, 40);

        const headers = [["User Name", "Mobile", "Email", "Computer", "Start", "End", "Duration", "Amount"]];
        const rows = [];

        document.querySelectorAll("table tbody tr").forEach(row => {
            const rowData = [];
            row.querySelectorAll("td").forEach(cell => {
                rowData.push(cell.textContent.trim());
            });
            rows.push(rowData);
        });

        doc.autoTable({
            head: headers,
            body: rows,
            startY: 60,
            theme: 'grid',
            headStyles: {
                fillColor: [255, 255, 255],
                textColor: 0,
                lineColor: [0, 0, 0],
                lineWidth: 0.1,
                fontStyle: 'bold',
            },
            styles: {
                fontSize: 10,
                cellPadding: 5,
                lineColor: [0, 0, 0],
                lineWidth: 0.1,
            },
        });

        doc.save("booking_report.pdf");
    }
</script>
</main>
</body>
</html>