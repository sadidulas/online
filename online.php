<?php
session_start();
$file = 'online_users.txt';
$timeout = 300;
$users = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($user, $ts) = explode('|', $line);
        if ((time() - (int)$ts) < $timeout) {
            $users[$user] = true;
        }
    }
}
echo "<ul>";
foreach (array_keys($users) as $user) {
    echo "<li>" . htmlspecialchars($user) . "</li>";
}
echo "</ul>";
?>