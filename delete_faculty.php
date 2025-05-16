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

// Delete faculty from database
$query = "DELETE FROM faculty WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: manage_faculty.php?message=Faculty Deleted Successfully");
    exit();
} else {
    echo "Error deleting faculty: " . mysqli_error($conn);
}
?>
