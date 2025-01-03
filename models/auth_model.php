<?php

function get_user(callable $get_db_data, $conditions) {
    return $get_db_data('users', $conditions, true);
}

function create_user(callable $set_db_data, $username, $email, $password) {
    $options = ['cost' => 12];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
    
    return $set_db_data('users', [
        'username' => $username,
        'email' => $email,
        'pwd' => $hashed_password
    ]);
}