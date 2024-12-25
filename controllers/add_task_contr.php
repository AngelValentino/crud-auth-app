<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/session_config.php';
    require_once '../utils/utils.php';
    require_once '../models/db_model.php';
    require_once '../models/task_model.php';

    $title = trim($_POST['title']);
    $dueDate = trim($_POST['dueDate']);
    $description = trim($_POST['description']);

    $errors = [
        'title' => validate_title($title),
        'dueDate' => validate_due_date($dueDate),
        'description' => validate_description($description)
    ];

    if (!array_filter($errors)) {
        $isTaskAdded = add_task('set_db_data', $title, $dueDate, $description, $_SESSION['userId']);

        if ($isTaskAdded) {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location: ../index.php');
            exit;
        }

        exit('Database error occurred while adding a task.');
    } 
    else {
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = [
            'title' => $title,
            'dueDate' => $dueDate,
            'description' => $description
        ];
        header('Location: ../add.php');
        exit;
    }
} 
else {
    header('Location: ../index.php');
    exit;
}