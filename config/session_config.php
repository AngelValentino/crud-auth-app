<?php

// Session configuration function
function configure_session() {
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    session_set_cookie_params([
        'lifetime' => 1800, // 30 minutes
        'domain' => 'localhost',
        'path' => '/',
        'secure' => true,   // Ensure cookies are sent over HTTPS
        'httponly' => true  // Prevent access to cookies via JavaScript
    ]);
}

// Session regeneration function
function regenerate_session_id() {
    session_regenerate_id();
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