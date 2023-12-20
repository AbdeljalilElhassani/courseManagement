<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'instructor') {
    header("Location: index.php?error=access_denied");
    exit();
}

$instructor = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['name']) &&
    isset($_POST['description']) &&
    isset($_POST['category']) &&
    isset($_POST['subject']) &&
    isset($_POST['start_time']) &&
    isset($_POST['end_time']) &&
    isset($_POST['num_students'])) {

    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $category = htmlspecialchars(trim($_POST['category']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $start_time = htmlspecialchars(trim($_POST['start_time']));
    $end_time = htmlspecialchars(trim($_POST['end_time']));
    $num_students = (int)$_POST['num_students'];
    $new_course = array(
        'name' => $name,
        'instructor' => $instructor['username'],
        'description' => $description,
        'category' => $category,
        'subject' => $subject,
        'startTime' => $start_time,
        'endTime' => $end_time,
        'numStudents' => $num_students,
    );

    $courses = json_decode(file_get_contents('data/courses.json'), true);
    $courses['courses'][] = $new_course;
    file_put_contents('data/courses.json', json_encode($courses, JSON_PRETTY_PRINT));
    header("Location: instructor_dashboard.php");
    exit();
}
$categories = json_decode(file_get_contents('data/category.json'), true)['categories'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            color: #007bff;
        }

        form {
            margin-top: 20px;
        }

        label {
            margin-top: 10px;
        }

        button {
            margin-top: 20px;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create a New Course</h2>
        <form action="create_course.php" method="post">
            <div class="form-group">
                <label for="name">Course Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <?php
                    foreach ($categories as $category) {
                        echo "<option value=\"$category\">$category</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <div class="form-group">
                <label for="start_time">Start Time:</label>
                <select class="form-control" id="start_time" name="start_time" required>
                    <?php
                    for ($hour = 8; $hour <= 18; $hour++) {
                        $time = sprintf("%02d:00", $hour);
                        echo "<option value=\"$time\">$time</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="end_time">End Time:</label>
                <select class="form-control" id="end_time" name="end_time" required>
                    <?php
                    for ($hour = 9; $hour <= 19; $hour++) {
                        $time = sprintf("%02d:00", $hour);
                        echo "<option value=\"$time\">$time</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="num_students">Number of Students:</label>
                <input type="number" class="form-control" id="num_students" name="num_students" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Course</button>
        </form>

        <a href="instructor_dashboard.php">Back to Instructor Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
