<?php
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;
$messages = [];

if (file_exists('messages.log')) {
    $lines = file('messages.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $msg = json_decode($line, true);
        if ($msg && $msg['id'] > $lastId) {
            $messages[] = $msg;
        }
    }
}

echo json_encode($messages);
?>