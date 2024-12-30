<?php

function validate_username(callable $get_user, $username) {
    // Fetch user data once
    $user_data = $get_user('get_db_data', ['username' => $username]);

    // Use a match expression for the validation steps
    return match (true) {
        empty($username) => 'Username is required.', // Check if username is empty
        strlen($username) > 32 => 'Username must be less than or equal 32 characters.', // Check length of username
        $user_data === null => 'Unable to verify your username at the moment. Please try again later.', // DB query error
        $user_data ? true : false => 'Username is already being used, try another one.', // Check if username is already in use
        default => null, // No issues, return null
    };
}

function validate_email(callable $get_user, $email) {
    // Fetch user data once
    $user_data = $get_user('get_db_data', ['email' => $email]);

    // Use a match expression for the validation steps
    return match (true) {
        empty($email) => 'Email is required.', // Check if email is empty
        !filter_var($email, FILTER_VALIDATE_EMAIL) => 'Email is not valid.', // Check if email is valid
        strlen($email) > 100 => 'Email must be less than or equal to 100 characters.', // Check length of email
        $user_data === null => 'Unable to verify your email at the moment. Please try again later.', // DB query error
        $user_data ? true : false => 'Email is already being used, try another one.', // Check if email is already in use
        default => null // No issues, return null
    };
}

function validate_password($password) {
    return match (true) {
        empty($password) => 'Password is required.',
        strlen($password) < 8 => 'Password must be at least 8 characters.',
        strlen($password) > 255 => 'Password must be less than or equal 255 characters.',
        default => null
    };
}

function set_signup_errors($errors, $username, $email) {
    $_SESSION['errors'] = $errors;
    $_SESSION['formData'] = [
        'username' => $username,
        'email' => $email
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php'; 
    require_once PROJECT_ROOT . '/models/db_model.php'; 
    require_once PROJECT_ROOT . '/models/auth_model.php';

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

        if ($isDataSent === null) {
            $errors['db'] = 'There was an issue creating your account. Please try again later.';
            // Store errors and form data in session for persistence across redirects
            set_signup_errors($errors, $username, $email);
            header('Location:' . BASE_URL . '/pages/signup.php');
            exit;
        } 
        else {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location:' . BASE_URL . '/pages/login.php');
            exit;
        }
    } 
    else {
        // Store errors and form data in session for persistence across redirects
        set_signup_errors($errors, $username, $email);
        header('Location:' . BASE_URL . '/pages/signup.php');
        exit;
    }
} 
else {
    header('Location:' . BASE_URL . '/index.php');
    exit;
}