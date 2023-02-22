<?php
//session_start();

include '_room.php';
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    // Set the HTTP response status code to 404 (Not Found)
    http_response_code(404);
    // Redirect to the error page
    header('Location: /404.php');
    exit();
}

