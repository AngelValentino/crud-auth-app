<?php

require_once '../config/session_config.php'; 
require_once '../models/db_model.php'; 
require_once '../models/signup_model.php';

$errors = [];

function validate_username($username) {
    if (empty($username)) {
        return 'Username is required.';
    }
    if (check_user_exists('get_db_data', 'username', $username)) {
        return 'Username is already being used, try another one.';
    }
    return null;
}

function validate_email($email) {
    if (empty($email)) {
        return 'Email is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Email is not valid.';
    }
    if (check_user_exists('get_db_data', 'email', $email)) {
        return 'Email is already being used, try another one.';
    }
    return null;
}

function validate_password($password) {
    if (empty($password)) {
        return 'Password is required.';
    }
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    $errors = [
        'username' => validate_username($username, $db_get),
        'email' => validate_email($email, $db_get),
        'password' => validate_password($password)
    ];

    // If there are no errors, proceed to save data and create a new user
    if (!array_filter($errors)) {
        $isDataSent = create_user('set_db_data', $username, $email, $password);

        if ($isDataSent) {
            unset($_SESSION['errors']);
            unset($_SESSION['form_data']);
            header('Location: ../index.php');
            exit;
        }

        die('Database error occurred while registering user.');
    } 
    else {
        // Store errors and form data in session for persistence across redirects
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'username' => $username,
            'email' => $email
        ];

        header('Location: ../pages/signup.php');
        die();
    }
} 
else {
    header('Location: ../index.php');
    die();
}