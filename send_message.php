<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit("Unauthorized");
}

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
if (empty($message)) exit();

$data = [
    'id' => time(),
    'user' => $_SESSION['username'],
    'message' => $message,
    'time' => date("H:i:s")
];

file_put_contents('messages.log', json_encode($data) . PHP_EOL, FILE_APPEND | LOCK_EX);
?>