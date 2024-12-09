<?php

// Database connection settings
$host = 'localhost';
$dbname = 'crud_auth_app';
$username = 'root';
$password = '';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Create PDO instance
try {
    // Establish connection
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully to the database!";
} 
catch (PDOException $e) {
    // If connection fails, display the error
    echo "Connection failed: " . $e->getMessage();
}