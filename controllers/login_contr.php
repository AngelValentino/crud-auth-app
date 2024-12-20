<?php

function validate_username($username, $user_data) {
    if (empty($username)) {
        return 'Username is required.';
    }
    else if (!$user_data) {
        return 'No such user exists.';
    }
    return null;
}

function validate_password($password, $user_data) {
    if (empty($password)) {
        return 'Password is required.';
    } 
    else if ($user_data && !password_verify($password, $user_data['pwd'])) {
        return 'Password is invalid.';
    } 
    else if (!$user_data) {
        return 'Username is required.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/session_config.php';
    require_once '../models/db_model.php';
    require_once '../models/login_model.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_data = get_user('get_db_data', 'username', $username);

    // Validate inputs
    $errors = [
        'username' => validate_username($username, $user_data),
        'password' => validate_password($password, $user_data)
    ];

    if (!array_filter($errors)) {
        regenerate_session_id();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = htmlspecialchars($user_data['username']);

        unset($_SESSION['errors']);
        unset($_SESSION['form_data']);
       
        header('Location: ../index.php');
        exit;
    } 
    else {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'username' => $username
        ];

        header('Location: ../login.php');
        die();
    }
}
else {
    header('Location: ../index.php');
    die();
}