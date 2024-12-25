<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'logout') {
    require_once '../config/session_config.php';
    
    // Destroy all session data
    session_unset();
    session_destroy();
        
    // Expire the session cookie
    setcookie(session_name(), '', time() - 3600, '/'); // Expire the cookie

    // Redirect to login page (or home page)
    header('Location: ../index.php');
    exit;
} 
else {
    header('Location: ../index.php');
    exit;
}