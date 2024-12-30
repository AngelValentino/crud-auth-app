<?php
require_once __DIR__ . '/../config/constants_config.php';

function render_signup_form($errors, $formData) {
    $baseUrl = BASE_URL;
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $username = isset($formData['username']) ? htmlspecialchars($formData['username']) : '';
    $email = isset($formData['email']) ? htmlspecialchars($formData['email']) : '';

    // Prepare error messages for each field
    $usernameError = isset($errors['username']) ? "<div class='error'>{$errors['username']}</div>" : '';
    $emailError = isset($errors['email']) ? "<div class='error'>{$errors['email']}</div>" : '';
    $passwordError = isset($errors['password']) ? "<div class='error'>{$errors['password']}</div>" : '';
    $dbError = isset($errors['db']) ? "<div class='error'>{$errors['db']}</div>" : '';

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="signup-form" action="{$baseUrl}/controllers/signup_contr.php" method="POST">
            <h2>Sign Up</h2>
            
            <label for="signup-form__username-input">Username</label>
            $usernameError
            <input id="signup-form__username-input" type="text" name="username" value="$username">

            <label for="signup-form__email-input">Email</label>
            $emailError
            <input id="signup-form__email-input" type="text" name="email" value="$email">

            <label for="signup-form__password-input">Password</label>
            $passwordError
            <input id="signup-form__password-input" type="password" name="password">

            $dbError
            <button class="signup-btn" type="submit">Sign Up</button>
        </form>
    HTML;
}
