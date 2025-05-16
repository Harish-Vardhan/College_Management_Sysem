<?php
session_start();
include 'db_connect.php'; // Ensure database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faculty_id = $_POST['faculty_id'];
    $password = $_POST['password'];

    // Check credentials
    $query = "SELECT * FROM faculty WHERE id = '$faculty_id' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn)); // Debugging line
    }

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['faculty'] = $faculty_id; // Set session
        header("Location: faculty_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid ID or Password!'); window.location.href='faculty_login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQ7JfVUL2g1G0B1ywtk-CIFuzJhprf0PdtTg&s') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* College Header */
        .college-header {
            width: 100%;
            background: black;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 50px;
            font-weight: bold;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
        }

        .college-header img {
            height: 100px;
            margin-right: 10px;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
            margin-top: 50px; /* Space for header */
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
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

        .btn:hover {
            background: #0056b3;
        }

        .register-btn {
            display: block;
            margin-top: 15px;
            padding: 10px;
            background: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .register-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <!-- College Header (Same as Admin Page) -->
    <div class="college-header">
        <img src="data:image/jpeg;base64,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" alt="College Logo">
        Nanjiah Lingammal Polytechnic College - Mettupalayam
    </div>

    <div class="container">
        <h2>Faculty Login</h2>
        <form action="faculty_login.php" method="POST">
            <label for="faculty_id">Faculty ID:</label>
            <input type="text" name="faculty_id" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Login</button>
        </form>

        <!-- Register Button -->
        <a href="register_faculty.php" class="register-btn">Register</a>
    </div>

</body>
</html>
