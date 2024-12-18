<?php

function check_user_exists(callable $get_db_data, $column, $value) {
    return $get_db_data('users', $column, $value);
}

function create_user(callable $set_db_data, $username, $email, $password) {
    $options = ['cost' => 12];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
    
    $isDataSent = $set_db_data('users', [
        'username' => $username,
        'email' => $email,
        'pwd' => $hashed_password
    ]);

    return $isDataSent;
}