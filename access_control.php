<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php?error=access_denied");
    exit();
}

$allowed_roles = array('instructor', 'student');  
if (!in_array($_SESSION['user']['role'], $allowed_roles)) {
    header("Location: index.php?error=access_denied");
    exit();
}
$user = $_SESSION['user'];
?>
