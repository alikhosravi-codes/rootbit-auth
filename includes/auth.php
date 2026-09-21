<?php

session_set_cookie_params([
    'httponly' => true,
    'secure' => false,
    'samesite' => 'Lax'
]);

session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: ../public/login.php');
    exit;
}