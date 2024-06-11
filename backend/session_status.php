<?php
session_start();
header('Content-Type: application/json');

$response = array(
    "is_logged_in" => isset($_SESSION['username']),
    "username" => isset($_SESSION['username']) ? $_SESSION['username'] : null
);

echo json_encode($response);
?>
