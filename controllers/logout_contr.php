<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'logout') {
    require_once __DIR__ . '/../config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php';
    
    // Destroy all session data
    session_unset();
    session_destroy();
        
    // Expire the session cookie
    setcookie(session_name(), '', time() - 3600, '/'); // Expire the cookie

    // Redirect to login page (or home page)
    header('Location: ' . BASE_URL . '/index.php');
    exit;
} 
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}