<?php

function validate_title($title) {
    if (empty($title)) {
        return 'A title is required.';
    } 
    else if (strlen($title) > 150) {
        return 'Title must be less than or equal 150 characters.';
    }
}

function validate_due_date($dueDate) {
    if (empty($dueDate)) {
        return 'A due date is required.';
    }
}

function validate_description($description) {
    if (empty($description)) {
        return 'A description is required.';
    } 
    else if (strlen($description) > 1000) {
        return 'Description must be less than or equal 1000 characters.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/session_config.php';
    require_once '../models/db_model.php';
    require_once '../models/task_model.php';

    $title = trim($_POST['title']);
    $dueDate = trim($_POST['dueDate']);
    $description = trim($_POST['description']);
    $taskId = trim($_POST['taskId']);

    $errors = [
        'title' => validate_title($title),
        'dueDate' => validate_due_date($dueDate),
        'description' => validate_description($description)
    ];

    if (!array_filter($errors)) {
        $isTaskEdited = edit_task('update_db_data', $title, $dueDate, $description, $taskId, $_SESSION['user_id']);

        if ($isTaskEdited) {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location: ../index.php');
            exit;
        }

        exit('Database error occurred while editing a task.');
    } 
    else {
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = [
            'title' => $title,
            'dueDate' => $dueDate,
            'description' => $description
        ];
        header("Location: ../edit.php?task-id=". urlencode($taskId));
        exit;
    }
} 
else {
    header('Location: ../index.php');
    exit;
}