<?php 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete') {
    require_once '../models/db_model.php';
    require_once '../models/task_model.php';

    $isTaskDeleted = delete_task('delete_db_data', $_GET['task-id']);

    if ($isTaskDeleted) {
        header('Location: ../index.php');
        exit;
    }

    exit('Database error occurred while deleting a task.');
} 
else {
    header('Location: ../index.php');
    exit;
}