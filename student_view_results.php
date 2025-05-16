<?php
session_start();
include 'db_connect.php';

// Check if the student is logged in
if (!isset($_SESSION['id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['id'];

// Fetch student name
$student_query = "SELECT name FROM students WHERE id = '$student_id'";
$student_result = $conn->query($student_query);
$student = $student_result->fetch_assoc();
$student_name = $student['name'];

// Fetch student results
$query = "SELECT * FROM results WHERE student_id = '$student_id'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Exam Results</title>
    <style>
        body {
            background-image: url('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAJQA8QMBIgACEQEDEQH/xAAaAAEBAQEAAwAAAAAAAAAAAAAAAQIEAwUH/8QAGRABAQEBAQEAAAAAAAAAAAAAAAERQTGB/8QAGgEAAwEBAQEAAAAAAAAAAAAAAAECAwQGBf/EABkRAQEBAQEBAAAAAAAAAAAAAAABAhESE//aAAwDAQACEQMRAD8A+IarKuqVKiK0lCrGVaTRNCarbOialGWtdGdE0Mq2miEFTaBm1bWWOqYFRlaYWlRlqmCDK0wErO0CAzpgCQAJ4EFD8hVB0SEAL4BQVIQoNISrGVaZvA0JFb50SpTxLRqgQGNpiBWdoQBlTEVEU0ARQgoiw0ALgFwxVTITBVX4JBRfAigqQhcILkIBYuQACpALKkFTsDVZVDtIBEUwoVnQgIzpqgqTRFE8CKA4AA5kAq4uYLqCjTwSCKyhixFXAAdaEKIqQKiigQAAAAEUTQgqIoSimIsNAPhWABS8hlVIcyExcUXMkijUjXOAiNDTwXXiUHFIpYEGkIVFVAIqLgUAwAAABgATYAClwIKF5CCg8hFCDgMMUVMhFxZBpMF0kBptnAZGlX4T14CCx8uNABcJQFwIAYUJ4GAAAAAVAABQ+Ehi4uDyGVXFw/IZMaRUwCCmNJggxVaTBJii42zgdTFUa+CcyosfAjUAWS8ReIuAVAwoBgAAAAAAgsaZVpmkoitZkkFxcOZCGKrSYCYosjSYJFkXBrnJGKLI3zguoNDTwTj6qdV5eNhUFBUWC4EFQwAAAKAgoAAEBYiw80K0ysdeKmtARvJKQsWQazBEhirjWYJDFxWucEYDUjozgkwUafMdcPVRXjI3AFBRFOAAPoAB0AA6AQLoAC6BU1TzQ0rMV1YpNwSK6sVNaisxp1YSsVIroyBcIrozmJMWQWOjOYRg0jXzCeuig8DHUAKIAMAAAAAAAABBWVE0IsAZNY0DqwmrGgdeE1YoOrBLGgdOCVYDpylVgOnKVAah/9k='); /* Change to your background image */
            background-size: cover;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .results-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 50px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        button {
            padding: 10px 20px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-right: 10px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h2>Exam Results - Semester III</h2>
        <p><strong>Student ID:</strong> <?php echo $student_id; ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>

        <?php if ($result->num_rows > 0) { 
            $row = $result->fetch_assoc();
        ?>
            <table id="resultTable">
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
                <tr>
                    <td>Subject 1</td>
                    <td><?php echo htmlspecialchars($row['grade1']); ?></td>
                </tr>
                <tr>
                    <td>Subject 2</td>
                    <td><?php echo htmlspecialchars($row['grade2']); ?></td>
                </tr>
                <tr>
                    <td>Subject 3</td>
                    <td><?php echo htmlspecialchars($row['grade3']); ?></td>
                </tr>
                <tr>
                    <td>Subject 4</td>
                    <td><?php echo htmlspecialchars($row['grade4']); ?></td>
                </tr>
                <tr>
                    <td>Subject 5</td>
                    <td><?php echo htmlspecialchars($row['grade5']); ?></td>
                </tr>
                <tr>
                    <td>Subject 6</td>
                    <td><?php echo htmlspecialchars($row['grade6']); ?></td>
                </tr>
            </table>

            <!-- Buttons -->
            <button onclick="printResults()">Print Results</button>
            <button onclick="downloadResults()">Download Results</button>

            <!-- Scripts -->
            <script>
                function printResults() {
                    var printContents = document.getElementById("resultTable").outerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                }

                function downloadResults() {
                    const table = document.getElementById("resultTable");
                    let text = "Exam Results - Semester III\n";
                    text += "Student ID: <?php echo $student_id; ?>\n";
                    text += "Name: <?php echo htmlspecialchars($student_name); ?>\n\n";
                    text += "Subject\t\tGrade\n";

                    for (let i = 1; i < table.rows.length; i++) {
                        const subject = table.rows[i].cells[0].innerText;
                        const grade = table.rows[i].cells[1].innerText;
                        text += `${subject}\t\t${grade}\n`;
                    }

                    const blob = new Blob([text], { type: 'text/plain' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Exam_Result_Semester_III.txt';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            </script>

        <?php } else { ?>
            <p>No exam results found.</p>
        <?php } ?>
        
        <br><br>
        <a href="student_dashboard.php"><button>Back to Dashboard</button></a>
    </div>
</body>
</html>
