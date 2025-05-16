<?php
session_start();
include 'db_connect.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // No hashing as per your requirement
    $mobile_number = $_POST['mobile_number'];
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $qualification = $_POST['qualification'];

    // Check if email already exists
    $check_query = "SELECT * FROM faculty WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='register_faculty.php';</script>";
    } else {
        // Insert faculty details into database
        $query = "INSERT INTO faculty (name, email, password, mobile_number, dob, department, qualification) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $name, $email, $password, $mobile_number, $dob, $department, $qualification);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Faculty Registered Successfully!'); window.location.href='faculty_login.php';</script>";
        } else {
            echo "<script>alert('Error Registering Faculty!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSL9KLrKNPS0aS50PNFKowzJM1kUvoMYT2K3CmMIrFbzjqdVd7e-D7KrLc&s') no-repeat center center fixed;
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

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            text-align: left;
        }

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

        button:hover {
            background: #0056b3;
        }

        .login-btn {
            display: block;
            margin-top: 15px;
            padding: 10px;
            background: #28a745;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .login-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Faculty Registration</h2>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" required>

            <label>Department:</label>
            <input type="text" name="department" required>

            <label>Qualification:</label>
            <input type="text" name="qualification" required>

            <button type="submit">Register</button>
        </form>

        <!-- Login Button -->
        <a href="faculty_login.php" class="login-btn">Back to Login</a>
    </div>
</body>
</html>