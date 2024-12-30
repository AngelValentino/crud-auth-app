<?php
require_once __DIR__ . '/../config/constants_config.php';

function render_login_form($errors, $formData) {
    $baseUrl = BASE_URL;
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $username = isset($formData['username']) ? htmlspecialchars($formData['username']) : '';

    // Prepare error messages for each field
    $usernameError = isset($errors['username']) ? "<div class='error'>{$errors['username']}</div>" : '';
    $passwordError = isset($errors['password']) ? "<div class='error'>{$errors['password']}</div>" : '';
    $dbError = isset($errors['db']) ? "<div class='error'>{$errors['db']}</div>" : '';

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="login-form" action="{$baseUrl}/controllers/login_contr.php" method="POST">
            <h2>Log In</h2>
            
            <label for="login-form__username-input">Username</label>
            $usernameError
            <input id="login-form__username-input" type="text" name="username" value="$username">

            <label for="login-form__password-input">Password</label>
            $passwordError
            <input id="login-form__password-input" type="password" name="password">

            $dbError
            <button class="login-btn" type="submit">Log in</button>
        </form>
    HTML;
}
