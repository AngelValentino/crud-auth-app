<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/crud-auth-app/config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php';
    require_once PROJECT_ROOT . '/utils/utils.php';
    require_once PROJECT_ROOT . '/models/db_model.php';
    require_once PROJECT_ROOT . '/models/task_model.php';

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
            header('Location: ' . BASE_URL . '/index.php');
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
        header('Location: ' . BASE_URL . '/pages/add.php');
        exit;
    }
} 
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}