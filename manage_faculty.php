<?php
session_start();
include('db_connect.php'); // Database connection

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all faculty members
$query = "SELECT * FROM faculty";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculty</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url('data:image/jpeg;base64,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');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
        }
        .top-nav {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            background: black;
        }
        .top-nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .btn-dashboard {
            background: blue;
        }
        .btn-logout {
            background: red;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        th {
            background: gray;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        .btn-edit {
            background: blue;
        }
        .btn-delete {
            background: red;
        }
        .btn-add {
            background: green;
            padding: 10px 15px;
            margin-bottom: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <a href="admin_dashboard.php" class="btn-dashboard">Back to Dashboard</a>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <h2>Manage Faculty</h2>
    <div class="container">
        <a href="add_faculty.php" class="btn btn-add">Add Faculty</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Department</th>
                <th>Course ID</th>
                <th>Date of Birth</th>
                <th>Mobile Number</th>
                <th>Qualification</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id'] ?? 'N/A') ?></td>
                    <td><?= !empty($row['name']) ? htmlspecialchars($row['name']) : 'N/A' ?></td>
                    <td><?= !empty($row['email']) ? htmlspecialchars($row['email']) : 'N/A' ?></td>
                    <td><?= !empty($row['password']) ? htmlspecialchars($row['password']) : 'N/A' ?></td>
                    <td><?= !empty($row['department']) ? htmlspecialchars($row['department']) : 'N/A' ?></td>
                    <td><?= !empty($row['course_id']) ? htmlspecialchars($row['course_id']) : 'N/A' ?></td>
                    <td><?= !empty($row['dob']) ? htmlspecialchars($row['dob']) : 'N/A' ?></td>
                    <td><?= !empty($row['mobile_number']) ? htmlspecialchars($row['mobile_number']) : 'N/A' ?></td>
                    <td><?= !empty($row['qualification']) ? htmlspecialchars($row['qualification']) : 'N/A' ?></td>
                    <td>
                        <a href="edit_faculty.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_faculty.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
