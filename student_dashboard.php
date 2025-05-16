<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body {
            background-image: url('data:image/jpeg;base64,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'); /* Change to your background image */
            background-size: cover;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 50px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }
        .welcome {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        button {
            padding: 12px 20px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            width: 250px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            background-color: red;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <p class="welcome">Welcome, Student ID: <?php echo htmlspecialchars($student_id); ?></p>
        
        <a href="student_view_results.php"><button>View Exam Results</button></a>
        <br><br>
        <a href="student_logout.php"><button class="logout-btn">Logout</button></a>
    </div>
</body>
</html>
