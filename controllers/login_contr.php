<?php

function validate_username($username, $user_data) {
    return match (true) {
        $user_data === null => null, // Check if there's no database error
        empty($username) => 'Username is required.', // Check if username is empty
        !$user_data => 'No such user exists.', // Check if user doesn't exist
        default => null // No issues, return null
    };
}

function validate_password($password, $user_data) {
    return match (true) {
        $user_data === null => null, // Check if there's no database error
        empty($password) => 'Password is required.', // Check if password is empty
        $user_data && !password_verify($password, $user_data['pwd']) => 'Password is invalid.', // Check if password doesn't match
        !$user_data => 'Username is required.', // Check if user doesn't exist
        default => null // No issues, return null
    };
}

function validate_db_connection($data) {
    // if there are any query errors
    if ($data === null) {
        return 'There was an issue retrieving your login information. Please try again later.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php';
    require_once PROJECT_ROOT . '/models/db_model.php';
    require_once PROJECT_ROOT . '/models/auth_model.php';
    require_once PROJECT_ROOT . '/utils/utils.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_data = get_user('get_db_data', ['username' => $username]);

    // Validate inputs
    $errors = [
        'username' => validate_username($username, $user_data),
        'password' => validate_password($password, $user_data),
        'db' => validate_db_connection($user_data)
    ];

    if (!array_filter($errors)) {
        regenerate_session_id();
        $_SESSION['userId'] = $user_data['id'];
        $_SESSION['username'] = htmlspecialchars($user_data['username']);

        unset($_SESSION['errors']);
        unset($_SESSION['formData']);
       
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    } 
    else {
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = ['username' => $username];

        header('Location:' . BASE_URL . '/pages/login.php');
        exit;
    }
}
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}