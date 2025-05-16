<?php
include('db_connect.php');

// Fetch student count per course
$student_query = "SELECT course_id, COUNT(*) AS student_count FROM students GROUP BY course_id";
$student_result = mysqli_query($conn, $student_query);
$students_data = [];
while ($row = mysqli_fetch_assoc($student_result)) {
    $students_data[$row['course_id']] = $row['student_count'];
}

// Fetch faculty count per course
$faculty_query = "SELECT course_id, COUNT(*) AS faculty_count FROM faculty GROUP BY course_id";
$faculty_result = mysqli_query($conn, $faculty_query);
$faculty_data = [];
while ($row = mysqli_fetch_assoc($faculty_result)) {
    $faculty_data[$row['course_id']] = $row['faculty_count'];
}

$all_courses = array_unique(array_merge(array_keys($students_data), array_keys($faculty_data)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Course Count</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <style>
        body {
            background: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQuRdBnczQjVK94rXvjXz06Yir1utM1toX-nA&s') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            text-align: center;
            margin-top: 50px;
        }

        .course-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.3);
            text-align: center;
            color: white;
            width: 300px;
            margin: 15px;
            transition: transform 0.4s ease-in-out, box-shadow 0.4s ease-in-out;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 0px 25px rgba(255, 255, 255, 0.5);
        }

        .icon {
            font-size: 50px;
            margin-bottom: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .course-card:hover .icon {
            transform: rotateY(360deg);
        }

        .student-count {
            color: #00f2ff;
            font-size: 20px;
            font-weight: bold;
        }

        .faculty-count {
            color: #ffcb00;
            font-size: 20px;
            font-weight: bold;
        }

        h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .chart-container {
            display: none;
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.3);
        }

        .toggle-btn {
            background: #ff007b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .toggle-btn:hover {
            background: #cc0066;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>📊 Course-wise Student & Faculty Count</h2>
        
        <button class="toggle-btn" onclick="toggleChart()">📈 View Chart</button>

        <div class="row justify-content-center">
            <?php foreach ($all_courses as $course_id): 
                $student_count = isset($students_data[$course_id]) ? $students_data[$course_id] : 0;
                $faculty_count = isset($faculty_data[$course_id]) ? $faculty_data[$course_id] : 0;
            ?>
            <div class="col-md-4" data-aos="fade-up">
                <div class="course-card">
                    <h3>📚 <?= htmlspecialchars($course_id) ?></h3>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <p class="student-count">Students: <?= $student_count ?></p>
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <p class="faculty-count">Faculty: <?= $faculty_count ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="chart-container">
            <canvas id="courseChart"></canvas>
        </div>

    </div>

    <script>
        function toggleChart() {
            var chartContainer = document.querySelector('.chart-container');
            chartContainer.style.display = (chartContainer.style.display === 'none' || chartContainer.style.display === '') ? 'block' : 'none';
        }

        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById("courseChart").getContext("2d");

            var courseLabels = <?php echo json_encode(array_values($all_courses)); ?>;
            var studentCounts = <?php echo json_encode(array_values($students_data)); ?>;
            var facultyCounts = <?php echo json_encode(array_values($faculty_data)); ?>;

            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: courseLabels,
                    datasets: [
                        {
                            label: "Students",
                            data: studentCounts,
                            backgroundColor: "#00f2ff",
                            borderColor: "#00c2cc",
                            borderWidth: 1
                        },
                        {
                            label: "Faculty",
                            data: facultyCounts,
                            backgroundColor: "#ffcb00",
                            borderColor: "#d1a800",
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000
        });
    </script>

</body>
</html>
