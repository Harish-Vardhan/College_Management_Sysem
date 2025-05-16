<?php
session_start();

// Ensure either admin or faculty is logged in
if (!isset($_SESSION['admin']) && !isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php"); // Redirect if no valid session
    exit();
}

include('db_connect.php'); // Database connection

// Fetch all students
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin & Faculty Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkzRwqZy4GZ7sc0POxW48f0AHjvCLlJ9lGJA&s');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
        }
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            margin: auto;
            margin-top: 50px;
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
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 2px;
        }
        .btn-edit {
            background: green;
        }
        .btn-delete {
            background: red;
        }
        .btn-add {
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h2>Manage Students</h2>
    
    <!-- Dynamic Dashboard Link -->
    <a href="<?php echo isset($_SESSION['admin']) ? 'admin_dashboard.php' : 'faculty_dashboard.php'; ?>">Back to Dashboard</a>

    <a href="<?php echo isset($_SESSION['admin']) ? 'admin_logout.php' : 'faculty_logout.php'; ?>">Logout</a>

    <!-- Admin and Faculty Can Add Students -->
    <a href="add_student.php" class="btn-add">Add New Student</a>

    <div class="container">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Date of Birth</th>
                <th>Academic Year</th>
                <th>Mobile Number</th>
                <th>Course</th>
                <th>Course ID</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['password'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['dob'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['academic_year'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['mobile_number'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['course'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($row['course_id'] ?? 'N/A'); ?></td>
                <td>
                    <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>

                    <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
