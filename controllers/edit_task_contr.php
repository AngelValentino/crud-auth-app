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
    $taskId = trim($_POST['taskId']);

    $errors = [
        'title' => validate_title($title),
        'dueDate' => validate_due_date($dueDate),
        'description' => validate_description($description)
    ];

    if (!array_filter($errors)) {
        $isTaskEdited = edit_task('update_db_data', $title, $dueDate, $description, $taskId, $_SESSION['userId']);

        if ($isTaskEdited) {
            unset($_SESSION['errors']);
            unset($_SESSION['formData']);
            header('Location: ' . BASE_URL . '/index.php');
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
        header('Location: ' . BASE_URL . '/pages/edit.php?task-id='. urlencode($taskId));
        exit;
    }
} 
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}