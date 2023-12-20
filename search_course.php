<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php?error_access_denied");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_criteria']) && isset($_GET['search_value'])) {
    $role = $_SESSION['user']['role'];

    $search_criteria = $_GET['search_criteria'];
    $search_value = trim($_GET['search_value']); 
    $courses = json_decode(file_get_contents('data/courses.json'), true);

    $search_results = [];

    foreach ($courses['courses'] as $course) {
        $match = false;

        if ($search_criteria === 'name') {
            $match = stripos($course['name'], $search_value) !== false;
        } elseif ($search_criteria === 'time') {
            $match = date('H:i', strtotime($course['startTime'])) === $search_value;
        }

        if ($match) {
            $search_results[] = $course;
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Course</title>
    </head>
    <body>
        <h2>Search Results</h2>

        <?php if (empty($search_results)): ?>
            <p>No results found.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($search_results as $result): ?>
                    <li>
                        <strong><?php echo $result['name']; ?></strong><br>
                        Instructor: <?php echo $result['instructor']; ?><br>
                        Description: <?php echo $result['description']; ?><br>
                        Start Time: <?php echo date('H:i', strtotime($result['startTime'])); ?><br>
                        End Time: <?php echo date('H:i', strtotime($result['endTime'])); ?><br>
                        Number of Students: <?php echo $result['numStudents']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <br>
        <a href="<?php echo "{$role}_dashboard.php"; ?>">Back to Dashboard</a>
    </body>
    </html>
    <?php
} else {
    header("Location: student_dashboard.php");
    exit();
}
?>
