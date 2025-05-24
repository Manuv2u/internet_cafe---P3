<?php
require 'db_connect.php';

// Get optional filter (e.g., date)
$date_filter = $_GET['date'] ?? date('Y-m-d');

// Example: Get total number of sessions for a date
$stmt = $conn->prepare("SELECT COUNT(*) as total_sessions FROM sessions WHERE DATE(start_time) = ?");
$stmt->bind_param("s", $date_filter);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();

// Example: Get top used computers
$top_computers = $conn->query("
    SELECT computer_name, COUNT(*) as usage_count
    FROM sessions s
    JOIN computers c ON s.computer_id = c.id
    WHERE DATE(start_time) = '$date_filter'
    GROUP BY computer_id
    ORDER BY usage_count DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head><title>Daily Usage Report</title></head>
<body>
<h2>Generate Daily Report</h2>

<form method="GET">
    <label>Select Date:</label>
    <input type="date" name="date" value="<?= $date_filter ?>">
    <button type="submit">Generate</button>
</form>

<h3>Summary for <?= $date_filter ?></h3>
<p>Total Sessions: <?= $stats['total_sessions'] ?></p>

<h4>Top 5 Used Computers</h4>
<table border="1" cellpadding="6">
    <tr><th>Computer</th><th>Usage Count</th></tr>
    <?php while ($row = $top_computers->fetch_assoc()) { ?>
    <tr>
        <td><?= htmlspecialchars($row['computer_name']) ?></td>
        <td><?= $row['usage_count'] ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>