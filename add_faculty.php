<?php
session_start();
include('db_connect.php'); // Database connection

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $course_id = $_POST['course_id'];
    $dob = $_POST['dob'];
    $mobile_number = $_POST['mobile_number'];
    $qualification = $_POST['qualification'];

    // Insert query with updated VARCHAR ID
    $insert_query = "INSERT INTO faculty (id, name, email, password, department, course_id, dob, mobile_number, qualification) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssssssss", $id, $name, $email, $password, $department, $course_id, $dob, $mobile_number, $qualification);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: manage_faculty.php"); // Redirect after adding
        exit();
    } else {
        echo "Error adding faculty: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty</title>
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
        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            margin-top: 50px;
        }
        label, input {
            display: block;
            width: 100%;
            margin: 10px 0;
        }
        .btn {
            padding: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-add {
            background: green;
        }
        .btn-back {
            background: blue;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Add Faculty</h2>
    <div class="container">
        <form method="post">
            <label>ID:</label>
            <input type="text" name="id" required>
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="text" name="password" required>
            <label>Department:</label>
            <input type="text" name="department" required>
            <label>Course ID:</label>
            <input type="text" name="course_id" required>
            <label>Date of Birth:</label>
            <input type="date" name="dob">
            <label>Mobile Number:</label>
            <input type="text" name="mobile_number">
            <label>Qualification:</label>
            <input type="text" name="qualification">
            <button type="submit" class="btn btn-add">Add Faculty</button>
        </form>
        <a href="manage_faculty.php" class="btn btn-back">Back to Faculty Management</a>
    </div>
</body>
</html>
