<?php

// Session configuration function
function configure_session() {
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    // Set session cookie parameters dynamically
    $cookieParams = [
        'lifetime' => 1800, // 30 minutes
        'path' => '/',
        'secure' => true,
        'httponly' => true  // Prevent access to cookies via JavaScript
    ];

    // Check if we are in a production environment
    if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
        // Local development
        $cookieParams['domain'] = 'localhost';
    } 
    else {
        // Production environment
        $cookieParams['domain'] = '.myapp.com';  // or your actual domain
    }

    session_set_cookie_params($cookieParams);
}

// Session regeneration function
function regenerate_session_id() {
    session_regenerate_id(true);
    $_SESSION['last_regenerated_at'] = time();
}

function session_init() {
    // Check if session is not started yet
    if (session_status() == PHP_SESSION_NONE) {
        session_start();  // Start the session if it's not already started
    }

    // Check if the session needs regeneration
    if (!isset($_SESSION['last_regenerated_at'])) {
        regenerate_session_id();
    } 
    else {
        $resetSessionInterval = 60 * 30; // 30 minutes
        if (time() - $_SESSION['last_regenerated_at'] >= $resetSessionInterval) {
            regenerate_session_id();
        }
    }
}

configure_session();
session_init();