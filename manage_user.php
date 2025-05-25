<?php
require 'db_connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if (is_numeric($id)) {
        try {
            // Attempt to delete the user
            $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            header("Location: manage_user.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            // Catch foreign key constraint failure
            echo "<script>
                alert('Cannot delete user: related records exist in other tables.');
                window.location.href = 'manage_user.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid ID');</script>";
    }
}

$sql = "SELECT * FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    * { box-sizing: border-box; }
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #e0c3fc, #8ec5fc 100%);
        margin: 0;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }
    .add-link, .view-old-link {
        display: inline-block;
        text-align: center;
        background: linear-gradient(to right, #43e97b, #38f9d7);
        color: white;
        padding: 10px 20px;
        margin: 10px auto 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: background 0.3s ease, transform 0.2s;
    }
    .add-link:hover, .view-old-link:hover {
        transform: scale(1.03);
        opacity: 0.9;
    }
    table {
        width: 95%;
        margin: auto;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 14px 16px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color:#8ec5fc ;
        color: white;
        font-weight: 600;
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
        font-weight: 600;
        margin: 0 6px;
    }
    a.action-link:hover {
        text-decoration: underline;
    }
    .delete-btn {
        color: red !important;
        cursor: pointer;
    }
</style>
</head>
<body>

<h2>Manage Users</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Address</th>
        <th>Mobile Number</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['address']) ?></td>
        <td><?= htmlspecialchars($row['mobile_number']) ?></td>
        <td>
            <a class="action-link" href="edit_user.php?id=<?= $row['id'] ?>">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a class="action-link delete-btn" data-id="<?= $row['id'] ?>">
                <i class="fas fa-trash-alt"></i> Delete
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to delete this user?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#2575fc',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'manage_user.php?delete=${id}';
            }
        });
    });
});
</script>

</body>
</html>