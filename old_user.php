<?php
require 'db_connect.php';
$sql="SELECT * FROM user ";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Old User</title>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            background:  linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .add-link {
            display: block;
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .add-link:hover {
            text-decoration: underline;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px 20px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            letter-spacing: 1px;
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
            margin: 0 5px;
            font-weight: bold;
        }

        a.action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h2>Archived User</h2>
<a href="manage_user.php">Back to Manage User</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>address</th><th>mobile_number</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
		<td><?= htmlspecialchars($row['address']) ?></td>
		  <td><?= htmlspecialchars($row['mobile_number']) ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>