<?php
include('db_connect.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // VARCHAR ID
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // No hashing
    $dob = $_POST['dob'];
    $academic_year = $_POST['academic_year'];
    $mobile_number = $_POST['mobile_number'];
    $course = $_POST['course'];
    $course_id = $_POST['course_id'];

    // Check if ID already exists
    $check_query = "SELECT * FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Student ID already exists! Choose a different ID.'); window.location.href='add_student.php';</script>";
        exit();
    }

    // Insert the new student if ID is unique
    $insert_query = "INSERT INTO students (id, name, email, password, dob, academic_year, mobile_number, course, course_id) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssssssss", $id, $name, $email, $password, $dob, $academic_year, $mobile_number, $course, $course_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Student added successfully!'); window.location.href='manage_students.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
    <h2>Add Student</h2>
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
            <label>Date of Birth:</label>
            <input type="date" name="dob">
            <label>Academic Year:</label>
            <input type="text" name="academic_year">
            <label>Mobile Number:</label>
            <input type="text" name="mobile_number">
            <label>Course:</label>
            <input type="text" name="course">
            <label>Course ID:</label>
            <input type="text" name="course_id">
            <button type="submit" class="btn btn-add">Add Student</button>
        </form>
        <a href="manage_students.php" class="btn btn-back">Back to Student Management</a>
    </div>
</body>
</html>
