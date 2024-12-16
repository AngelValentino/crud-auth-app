<?php

require_once '../models/db_model.php'; 
require_once '../config/session_config.php'; 

session_init();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate the data
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    // If there are no errors, proceed to save data
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $isDataSent = set_db_data('users', [
            'username' => $username,
            'email' => $email,
            'pwd' => $hashed_password,
        ]);

        if ($isDataSent) {
            $_SESSION['success_message'] = 'User registered successfully!';
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
            'email' => $email,
            'password' => $password,
        ];

        header('Location: ../index.php');
        die();
    }

} 
else {
    header('Location: ../index.php');
    die();
}
