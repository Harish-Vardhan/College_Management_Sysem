<?php
session_start();
include('db_connect.php'); // Database connection

// Ensure admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if faculty ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_faculty.php");
    exit();
}

$id = $_GET['id'];

// Fetch faculty details
$query = "SELECT * FROM faculty WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "Faculty not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_id = $_POST['id'];
    $name = !empty($_POST['name']) ? $_POST['name'] : "N/A";
    $email = !empty($_POST['email']) ? $_POST['email'] : "N/A";
    $password = !empty($_POST['password']) ? $_POST['password'] : "N/A";
    $department = !empty($_POST['department']) ? $_POST['department'] : "N/A";
    $course_id = !empty($_POST['course_id']) ? $_POST['course_id'] : "N/A";
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : "N/A";
    $mobile_number = !empty($_POST['mobile_number']) ? $_POST['mobile_number'] : "N/A";
    $qualification = !empty($_POST['qualification']) ? $_POST['qualification'] : "N/A";

    // Check if the new ID already exists and is not the current one
    if ($new_id !== $id) {
        $check_query = "SELECT id FROM faculty WHERE id = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $new_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "<script>alert('Error: The new Faculty ID already exists. Please use a different ID.');</script>";
        } else {
            // Proceed with the update
            $update_query = "UPDATE faculty SET id = ?, name = ?, email = ?, password = ?, department = ?, course_id = ?, dob = ?, mobile_number = ?, qualification = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "ssssssssss", $new_id, $name, $email, $password, $department, $course_id, $dob, $mobile_number, $qualification, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                header("Location: manage_faculty.php");
                exit();
            } else {
                echo "Error updating faculty: " . mysqli_error($conn);
            }
        }
    } else {
        // Update without changing ID
        $update_query = "UPDATE faculty SET name = ?, email = ?, password = ?, department = ?, course_id = ?, dob = ?, mobile_number = ?, qualification = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssssssss", $name, $email, $password, $department, $course_id, $dob, $mobile_number, $qualification, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: manage_faculty.php");
            exit();
        } else {
            echo "Error updating faculty: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty</title>
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
        .btn-save {
            background: green;
        }
        .btn-back {
            background: blue;
            margin-left: 10px; /* Space between buttons */
        }
    </style>
</head>
<body>
    <h2>Edit Faculty</h2>
    <div class="container">
        <form method="post">
            <label>ID:</label>
            <input type="text" name="id" value="<?= htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Password:</label>
            <input type="text" name="password" value="<?= htmlspecialchars($row['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Department:</label>
            <input type="text" name="department" value="<?= htmlspecialchars($row['department'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Course ID:</label>
            <input type="text" name="course_id" value="<?= htmlspecialchars($row['course_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            <label>Date of Birth:</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($row['dob'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <label>Mobile Number:</label>
            <input type="text" name="mobile_number" value="<?= htmlspecialchars($row['mobile_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <label>Qualification:</label>
            <input type="text" name="qualification" value="<?= htmlspecialchars($row['qualification'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            
            <button type="submit" class="btn btn-save">Save Changes</button>
            <a href="manage_faculty.php" class="btn btn-back">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
