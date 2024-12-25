<?php

function validate_username(callable $get_user, $username) {
    if (empty($username)) {
        return 'Username is required.';
    }
    else if ($get_user('get_db_data', ['username' => $username])) {
        return 'Username is already being used, try another one.';
    }
    else if (strlen($username) > 32) {
        return 'Username must be less than or equal 32 characters.';
    }
    return null;
}

function validate_email(callable $get_user, $email) {
    if (empty($email)) {
        return 'Email is required.';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Email is not valid.';
    }
    else if ($get_user('get_db_data', ['email' => $email])) {
        return 'Email is already being used, try another one.';
    }
    else if (strlen($email) > 100) {
        return 'Email must be less than or equal 100 characters.';
    }
    return null;
}

function validate_password($password) {
    if (empty($password)) {
        return 'Password is required.';
    }
    else if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }
    else if (strlen($password) > 255) {
        return 'Password must be less than or equal 255 characters.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/session_config.php'; 
    require_once '../models/db_model.php'; 
    require_once '../models/auth_model.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    $errors = [
        'username' => validate_username('get_user', $username),
        'email' => validate_email('get_user', $email),
        'password' => validate_password($password)
    ];

    // If there are no errors, proceed to save data and create a new user
    if (!array_filter($errors)) {
        $isDataSent = create_user('set_db_data', $username, $email, $password);

        if ($isDataSent) {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location: ../login.php');
            exit;
        }

        die('Database error occurred while registering user.');
    } 
    else {
        // Store errors and form data in session for persistence across redirects
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = [
            'username' => $username,
            'email' => $email
        ];

        header('Location: ../signup.php');
        die();
    }
} 
else {
    header('Location: ../index.php');
    die();
}