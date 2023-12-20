<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$user = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['birthday']) &&
    isset($_POST['gender'])) {
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $birthday = htmlspecialchars(trim($_POST['birthday']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $_SESSION['user']['firstName'] = $first_name;
    $_SESSION['user']['lastName'] = $last_name;
    $_SESSION['user']['birthday'] = $birthday;
    $_SESSION['user']['gender'] = $gender;
    $all_users = json_decode(file_get_contents('data/users.json'), true);
    foreach ($all_users['users'] as &$json_user) {
        if ($json_user['username'] === $user['username']) {
            $json_user['firstName'] = $first_name;
            $json_user['lastName'] = $last_name;
            $json_user['birthday'] = $birthday;
            $json_user['gender'] = $gender;
            break;
        }
    }
    file_put_contents('data/users.json', json_encode($all_users, JSON_PRETTY_PRINT));
    header("Location: {$user['role']}_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h2 {
            color: #007bff;
        }

        .user-details {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .logout-link {
            display: block;
            margin-top: 20px;
            color: #007bff;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
        .content {
            margin-left: 220px; /* Adjust the margin to leave space for the navbar */
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
    </style>
</head>
<body>
<div class="nav-bar">
        <!-- Navigation Bar -->
        <a href="<?php echo "{$user['role']}_dashboard.php"; ?>" class="nav-link">Back to Dashboard</a>
        <a href="logout.php" class="nav-link">Logout</a>
    </div>
    <div class="content">
    <h2>Modify Information</h2>
    <form method="post" action="modify_information.php">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['firstName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['lastName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="birthday">Birthday:</label>
            <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $user['birthday']; ?>" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male" <?php echo ($user['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Information</button>
    </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
