<?php
session_start();

$response = array('is_logged_in' => false);

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['is_logged_in'] = true;
    $response['username'] = $_SESSION['username'];
    $response['userid'] = $_SESSION['userid'];
}

echo json_encode($response);
?>
