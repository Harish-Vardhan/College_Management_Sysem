<?php
session_start();
include('db_connect.php'); // Include database connection

// Ensure either admin or faculty is logged in
if (!isset($_SESSION['admin']) && !isset($_SESSION['faculty'])) {
    header("Location: faculty_login.php"); // Redirect if no valid session
    exit();
}

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if student ID is provided
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$id = $_GET['id']; // Get student ID from URL

// Fetch student details
$query = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $id); // 's' since id is VARCHAR
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "Student not found!";
    exit();
}

$student = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $academic_year = $_POST['academic_year'];
    $mobile_number = $_POST['mobile_number'];
    $course = $_POST['course'];
    $course_id = $_POST['course_id'];

    // Check if new ID already exists
    if ($new_id !== $id) {
        $check_query = "SELECT id FROM students WHERE id = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $new_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            echo "<script>alert('Error: New Student ID already exists!');</script>";
        } else {
            $update_query = "UPDATE students SET id = ?, name = ?, email = ?, password = ?, dob = ?, academic_year = ?, mobile_number = ?, course = ?, course_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $new_id, $name, $email, $password, $dob, $academic_year, $mobile_number, $course, $course_id, $id);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Student updated successfully!'); window.location.href='manage_students.php';</script>";
            } else {
                echo "<script>alert('Error updating student!');</script>";
            }
        }
    } else {
        $update_query = "UPDATE students SET name = ?, email = ?, password = ?, dob = ?, academic_year = ?, mobile_number = ?, course = ?, course_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssssssss", $name, $email, $password, $dob, $academic_year, $mobile_number, $course, $course_id, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Student updated successfully!'); window.location.href='manage_students.php';</script>";
        } else {
            echo "<script>alert('Error updating student!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            background: url('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAJQA+AMBIgACEQEDEQH/xAAaAAEBAQEBAQEAAAAAAAAAAAAAAQMEAgUH/8QAKBABAQABBAEEAQMFAAAAAAAAAAECAxEhMUESUWFxIgRSkTJCocHh/8QAGAEBAQEBAQAAAAAAAAAAAAAAAAIDAQT/xAAbEQEBAQEBAAMAAAAAAAAAAAAAAQIRMSFBUf/aAAwDAQACEQMRAD8A/WNh4w1MM+rz7eXt6nhAAAAAAAAAAAAAAAAAAAAAAWdubVm2d9vDoedTGZY7VUrmp2OZFssqNGCy7cmXfH2i3x9AgAMfV78/6dOhr3eY5bem9VyPWN55LOktj6faPOjlc8PVe+q91i3l6gA6AAAAAAAAAAAAAAAAAAAAzzw/ZxWF4u1/F1/SWTKbZTdU1xGs9cu2y3ttdGen8eL4Y543G8xU11FzY8i/SKS5xUt2Ul2/pbxlPqui9OHQ1PTlZJ4jomtxfVP4Y6z9tsbnGomOWOU4yVxoAOAAAAAAAAAAAAAAAAAAAqQBfF+U8bdqgOfVxmOXHQ9a/gazxjr1wpJvlJ8rUx6yWyrXR/K5X5bY9ZfTPSm2E+WmPWX05VxN7xs209Xf8cuGPiHwmzrubx1jLRz34rWM62l6AOOgAAAAAAAAAAAAAALAEgsBKDzqZenC13haw1ct878Dz7VGkYOW8vUnqknvd3mN9PHbb6/6tnHucSR6nWX08vU6y+kqiIqBVl2svs6sbvjK5G+hd8bL4Tppi/TUBDWADgAAAAAAAAAAAAL4Rf7QRUWgjHXvTZj+o7x+lZ9TvxiA0YuXt12bcezkncdeXd+3anKPWPbysuziyotm1sQcG+h/Tfti6NKbYc+U6Xj17AZtQAAAAAAAAAAAAABfCKCLUW+QRj+on9LZ41Md8L7uz1zXjmAasHK1mp6cst+Zuyer+U+YuxEbY5zLp64cr1jncfO89nOO9dV55eTTzmfXHwqVGM9WUny62Ohjzcq2Z6vy1xPgASsAAAAAAAAAAAAAAXwiwEOwAF3AYaunz6p/A3FTVRcPkE4Bu8y8Xvv4Np+7/CALN8bv5dOOXrny5sbJdr1Wmj+OpZXLFSu/CbYSPRLvJ9DB6oAOAAAAAAAAAAAAAAAACoRaCAAAA+SA9LxgAD1btlL7SPPmLe8fqDsfQ0tTHaS9tvDinUaYalxu3cY3LfO/10CY5S9VUr6ADoA4AAAAAAAAAAAAAAKgu4IKA+QA9LxgAC3+36UB0TqLO4CKt63sytjoxu83oI00woCWsAHAAAAAAAAAAAAAAAAAAB//2Q==') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
        }
        h2 { margin-bottom: 20px; color: #333; }
        label { display: block; margin-top: 10px; font-weight: bold; text-align: left; }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>
        <form method="POST">
            <label>Student ID:</label>
            <input type="text" name="id" value="<?= htmlspecialchars($student['id']) ?>" required>

            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>

            <label>Password:</label>
            <input type="text" name="password" value="<?= htmlspecialchars($student['password']) ?>" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($student['dob']) ?>" required>

            <label>Academic Year:</label>
            <input type="text" name="academic_year" value="<?= htmlspecialchars($student['academic_year']) ?>" required>

            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" value="<?= htmlspecialchars($student['mobile_number']) ?>" required>

            <label>Course:</label>
            <input type="text" name="course" value="<?= htmlspecialchars($student['course']) ?>" required>

            <label>Course ID:</label>
            <input type="text" name="course_id" value="<?= htmlspecialchars($student['course_id']) ?>" required>

            <button type="submit">Update Student</button>
        </form>
    </div>
</body>
</html>
