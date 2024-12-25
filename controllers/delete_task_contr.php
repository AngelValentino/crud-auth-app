<?php 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete') {
    require_once '../config/session_config.php';
    require_once '../models/db_model.php';
    require_once '../models/task_model.php';

    if (
            !isset($_GET['task-id']) || 
            empty($_GET['task-id']) || 
            !filter_var($_GET['task-id'], FILTER_VALIDATE_INT) || 
            $_GET['task-id'] < 1 ||
            !isset($_SESSION['user_id']) ||
            $_SESSION['user_id'] < 1
        ) {
        header('Location: ../index.php');
        exit;
    } 
    else {
        $isTaskDeleted = delete_task('delete_db_data', [
            'id' => $_GET['task-id'],
            'user_id' => $_SESSION['user_id']
        ]);
        
        if ($isTaskDeleted) {
            header('Location: ../index.php');
            exit;
        } 

        exit('Database error occurred while deleting a task.');
    }
} 
else {
    header('Location: ../index.php');
    exit;
}