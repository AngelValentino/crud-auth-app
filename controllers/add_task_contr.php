<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php';
    require_once PROJECT_ROOT . '/utils/utils.php';
    require_once PROJECT_ROOT . '/models/db_model.php';
    require_once PROJECT_ROOT . '/models/task_model.php';

    $title = trim($_POST['title']);
    $dueDate = trim($_POST['dueDate']);
    $description = trim($_POST['description']);

    $errors = [
        'title' => validate_task_title($title),
        'dueDate' => validate_task_due_date($dueDate),
        'description' => validate_task_description($description)
    ];

    if (!array_filter($errors)) {
        $isTaskAdded = add_task('set_db_data', $title, $dueDate, $description, $_SESSION['userId']);

        if ($isTaskAdded === null) {
            $errors['db'] = 'Unable to add your task at the moment. Please try again later.';
            set_task_errors($_SESSION, $errors, $title, $dueDate, $description);
            header('Location: ' . BASE_URL . '/pages/add.php');
            exit;
        } 
        else {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    } 
    else {
        set_task_errors($_SESSION, $errors, $title, $dueDate, $description);
        header('Location: ' . BASE_URL . '/pages/add.php');
        exit;
    }
} 
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}