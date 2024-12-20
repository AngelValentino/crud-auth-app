<?php

function check_signup_errors() {
    // Check if there are errors in the session, and display them if they exist
    if (isset($_SESSION['errors'])) {
        // Get errors from the session
        $errors = $_SESSION['errors']; 
        // Clear the session errors after displaying them
        unset($_SESSION['errors']);

        return $errors;
    } 
    
    return [];
}

function get_form_data() {
    // Initialize an empty array for the form data
    $form_data = [];

    // Check if session data for form data exists
    if (isset($_SESSION['form_data'])) {
        // Get form data from session
        $form_data = $_SESSION['form_data'];
        // Clear the session data after it's retrieved
        unset($_SESSION['form_data']);

        return $form_data;
    }

    return [];
}


function render_login_form($errors, $form_data) {
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $username = isset($form_data['username']) ? htmlspecialchars($form_data['username']) : '';

    // Prepare error messages for each field
    $username_error = isset($errors['username']) ? "<div class='error'>{$errors['username']}</div>" : "";
    $password_error = isset($errors['password']) ? "<div class='error'>{$errors['password']}</div>" : "";

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="login-form" action="../controllers/login_controller.php" method="POST">
            <h2>Sign Up</h2>
            
            <label for="login-form__username-input">Username</label>
            $username_error
            <input id="login-form__username-input" type="text" name="username" value="$username">

            <label for="login-form__password-input">Password</label>
            $password_error
            <input id="login-form__password-input" type="password" name="password">

            <button class="login-btn" type="submit">Log in</button>
        </form>
    HTML;
}
