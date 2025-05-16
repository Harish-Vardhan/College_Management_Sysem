<?php
include('db_connect.php'); // Include database connection

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the student ID from the URL

    // Delete query
    $delete_query = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "s", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Student deleted successfully!'); window.location.href='manage_students.php';</script>";
    } else {
        echo "<script>alert('Error deleting student!'); window.location.href='manage_students.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='manage_students.php';</script>";
}
?>
