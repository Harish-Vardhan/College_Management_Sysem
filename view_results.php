<?php
include 'db_connect.php';
session_start();

// Check if admin or faculty is logged in
if (!isset($_SESSION['admin']) && !isset($_SESSION['faculty'])) {
    header("Location: admin_login.php");
    exit();
}

// Set dashboard redirect based on role
$dashboard_page = isset($_SESSION['admin']) ? "admin_dashboard.php" : "faculty_dashboard.php";

// Fetch student details with marks
$query = "SELECT s.id, s.name, s.course, s.academic_year, 
          r.subject1, r.grade1, r.subject2, r.grade2, 
          r.subject3, r.grade3, r.subject4, r.grade4, 
          r.subject5, r.grade5, r.subject6, r.grade6 
          FROM students s 
          LEFT JOIN results r ON s.id = r.student_id";
$result = mysqli_query($conn, $query) or die("Query Failed: " . mysqli_error($conn));

function safeValue($value) {
    return ($value === null || $value === '') ? "N/A" : $value;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results</title>
    <style>
    body {
        font-family: times new roman;
        background-color: #f8f9fa;
        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHuEhS3dx5_sqUZ43Gx06mfDemH422gL7pzQ&s'); 
        background-size: cover;
        background-position: center center;
        margin: 20px;
    }

    .container { width: 90%; margin: auto; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .header h2 { margin: 0; }
    .header a { text-decoration: none; padding: 10px 15px; color: white; border-radius: 5px; }
    .dashboard-btn { background: #007bff; }
    .dashboard-btn:hover { background: #0056b3; }
    .logout-btn { background: red; }
    .logout-btn:hover { background: darkred; }
    .view-reports-btn { background: green; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
    .view-reports-btn:hover { background: darkgreen; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background-color: #007bff; color: white; }
    .edit-btn { background: orange; color: white; padding: 5px 10px; border: none; cursor: pointer; }
    .edit-btn:hover { background: darkorange; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Student Results</h2>
        <div>
            <a href="<?= $dashboard_page; ?>" class="dashboard-btn">Back to Dashboard</a>
            <a href="pass_fail_report.php" class="view-reports-btn">View Reports</a>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Course</th><th>Year</th>
            <th>Subject 1</th><th>Grade</th>
            <th>Subject 2</th><th>Grade</th>
            <th>Subject 3</th><th>Grade</th>
            <th>Subject 4</th><th>Grade</th>
            <th>Subject 5</th><th>Grade</th>
            <th>Subject 6</th><th>Grade</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['course']; ?></td>
            <td><?= $row['academic_year']; ?></td>
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <td><?= safeValue($row["subject$i"]); ?></td>
                <td><?= safeValue($row["grade$i"]); ?></td>
            <?php endfor; ?>
            <td>
                <a href="edit_results.php?student_id=<?= $row['id']; ?>">
                    <button class="edit-btn">Edit</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
