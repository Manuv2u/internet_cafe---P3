<?php
include 'db_connect.php';

date_default_timezone_set("Asia/Kolkata");
$now = date('Y-m-d H:i:s');

// Auto-update computers whose session ended
$sql = "SELECT DISTINCT c.id 
        FROM computers c
        JOIN bookings b ON c.id = b.computer_name
        WHERE c.status = 'in_use' AND b.end_session <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $now);
$stmt->execute();
$resultCheck = $stmt->get_result();

while ($row = $resultCheck->fetch_assoc()) {
    $comp_id = $row['id'];
    $conn->query("UPDATE computers SET status = 'available' WHERE id = $comp_id");
}

// Delete handler
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query("DELETE FROM computers WHERE id = $id");
}

$result = $conn->query("SELECT * FROM computers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta charset="UTF-8">
    <title>Manage Computers</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee 100%);
            margin: 0;
            padding: 40px 0;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color:#8ec5fc  ;
        }

        .add-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .add-link:hover {
            text-decoration: underline;
        }

        .table-header,
        .table-row {
            display: flex;
            padding: 18px 24px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }

        .table-header {
            font-weight: bold;
            background-color: #e0c3fc  ;
        }

        .col {
            flex: 1;
            display: flex;
            align-items: center;
        }
          .col,
        .col.name {
            flex: 1;
        }

        .icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .text {
            font-size: 15px;
            color: #333;
        }

        .status.available {
            color: green;
            font-size: 20px;
        }

        .status.in-use {
            color: red;
            font-size: 20px;
        }
		.col.action{
			justify-content:flex-start;
			gap:12px;
		}

        .col.action a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
            font-weight: 500;
        }

        .col.action a:hover {
            text-decoration: underline;
        }
		.col.action i:hover {
    transform: scale(1.1);
    filter: brightness(1.2);
}
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Computers</h2>
   <!- <a class="add-link" href="add_computer.php">

    <div class="table-header">
        <div class="col name">Name</div>
        <div class="col">IP Address</div>
        <div class="col">Status</div>
        <div class="col action">Action</div>
    </div>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="table-row">
            <div class="col name">
                <div class="text"><?= htmlspecialchars($row['computer_name']) ?></div>
            </div>
            <div class="col"><?= htmlspecialchars($row['ip_address']) ?></div>
            <div class="col status <?= $row['status'] == 'in use' ? 'in-use' : 'available' ?>">
                <?= ucfirst($row['status']) ?>
            </div>
               <div class="col action">
    <a href="edit_computer.php?id=<?= $row['id'] ?>" title="Edit">
        <i class="fas fa-edit" style="color: #3498db; cursor: pointer;"></i>
    </a>
    <a href="#" class="delete-btn" data-id="<?= $row['id'] ?>" title="Delete">
        <i class="fas fa-trash-alt" style="color: #e74c3c; cursor: pointer;"></i>
    </a>
</div>
           
        </div>
    <?php } ?>
</div>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to delete this computer?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#a6c1ee',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =' ?delete=${id}';
            }
        });
    });
});
</script>


</body>
</html>