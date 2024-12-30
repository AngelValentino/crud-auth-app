<?php 

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete') {
    require_once __DIR__ . '/../config/constants_config.php';
    require_once PROJECT_ROOT . '/config/session_config.php';
    require_once PROJECT_ROOT . '/utils/utils.php';
    require_once PROJECT_ROOT . '/models/db_model.php';
    require_once PROJECT_ROOT . '/models/task_model.php';

    if (validate_user_task_id($_GET, $_SESSION)) {
        $isTaskDeleted = delete_task('delete_db_data', [
            'id' => $_GET['task-id'],
            'user_id' => $_SESSION['userId']
        ]);
        
        if ($isTaskDeleted === null) {
            $_SESSION['errors'] = ['deleteTask' => 'Unable to delete your task at the moment. Please try again later.'];
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        } 
        else {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    } 
    else {
        header('Location: '  . BASE_URL . '/index.php');
        exit;
    }
} 
else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}