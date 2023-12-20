<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    include 'data/users.json';
    $users = $json_data['users'];

    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            $role = $user['role'];
            header("Location: {$role}_dashboard.php");
            exit();
        }
    }
    $_SESSION['error_message'] = "Invalid username or password";
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
