<?php
session_start();
$file = 'online_users.txt';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if (!empty($username)) {
    $_SESSION['username'] = $username;
    $timestamp = time();
    $entry = "$username|$timestamp\n";
    file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    header("Location: chat.php");
    exit();
} else {
    echo "Please enter a valid username.";
}
?>