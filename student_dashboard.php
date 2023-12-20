<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php?error=access_denied");
    exit();
}
$user = $_SESSION['user'];
$search_criteria = 'name';
if (isset($_GET['search_criteria'])) {
    $search_criteria = $_GET['search_criteria'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        function toggleSearchInput() {
            var searchCriteria = document.getElementById('search_criteria').value;
            var searchInput = document.getElementById('search_value');

            if (searchCriteria === 'time') {
                var select = document.createElement('select');
                select.setAttribute('id', 'search_value');
                select.setAttribute('name', 'search_value');
                select.setAttribute('required', 'required');

                <?php
                $courses = json_decode(file_get_contents('data/courses.json'), true);
                $availableTimes = array_map(function ($course) {
                    return date('H:i', strtotime($course['startTime']));
                }, $courses['courses']);
                $availableTimes = array_unique($availableTimes);
                sort($availableTimes);
                foreach ($availableTimes as $time) {
                    echo "var option = document.createElement('option');";
                    echo "option.value = '$time';";
                    echo "option.text = '$time';";
                    echo "select.appendChild(option);";
                }
                ?>

                searchInput.replaceWith(select);
            } else {
                var input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.setAttribute('id', 'search_value');
                input.setAttribute('name', 'search_value');
                input.setAttribute('required', 'required');

                searchInput.replaceWith(input);
            }
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2, h3 {
            color: #007bff;
        }

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .nav-bar {
            background-color: #007bff;
            padding: 20px;
            border-radius: 5px;
            width: 200px;
            position: fixed;
            height: 100%;
        }

        .nav-link {
            display: block;
            margin-bottom: 10px;
            color: white;
            text-decoration: none;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .content {
            margin-left: 220px; /* Adjust the margin to leave space for the navbar */
        }

        .create-course-link,
        .modify-information-link,
        .logout-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
        }

        .create-course-link:hover,
        .modify-information-link:hover,
        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
    </style>
</head>
<body>
<div class="nav-bar">
        <!-- Navigation Bar -->
        <a href="#" class="nav-link">Dashboard</a>
        <a href="modify_information.php" class="nav-link">Modify Information</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>
    <div class="content">
    <h2>Welcome, <?php echo $user['firstName']; ?>!</h2>

    <h3>Search for Courses</h3>
    <form action="search_course.php" method="get">
        <div class="form-group">
            <label for="search_criteria">Search by:</label>
            <select class="form-control" id="search_criteria" name="search_criteria" onchange="toggleSearchInput()">
                <option value="name" <?php echo ($search_criteria === 'name') ? 'selected' : ''; ?>>Course Name</option>
                <option value="time" <?php echo ($search_criteria === 'time') ? 'selected' : ''; ?>>Start Time</option>
            </select>
        </div>

        <div class="form-group">
            <label for="search_value">Search Value:</label>
            <?php if ($search_criteria === 'time'): ?>
                <input type="text" class="form-control" id="search_value" name="search_value" required>
                <small class="form-text text-muted">Time format: H:i (24-hour)</small>
            <?php else: ?>
                <input type="text" class="form-control" id="search_value" name="search_value" required>
            <?php endif; ?>
        </div>
        
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <br>
    <h3>Courses</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Instructor</th>
                <th>Description</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Number of Students</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $courses = json_decode(file_get_contents('data/courses.json'), true);

            foreach ($courses['courses'] as $course) {
                echo "<tr>";
                echo "<td>{$course['name']}</td>";
                echo "<td>{$course['instructor']}</td>";
                echo "<td>{$course['description']}</td>";
                echo "<td>{$course['category']}</td>";
                echo "<td>{$course['subject']}</td>";
                echo "<td>" . date('H:i', strtotime($course['startTime'])) . "</td>";
                echo "<td>" . date('H:i', strtotime($course['endTime'])) . "</td>";
                echo "<td>{$course['numStudents']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

        </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
