<?php

function add_task(callable $set_db_data, $title, $dueDate, $description, $user_id) {
    $isTaskAdded = $set_db_data('tasks', [
        'user_id' => $user_id,
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
    ]);

    return $isTaskAdded;
}

function get_user_tasks(callable $get_db_data, $user_id) {
    $userTasks = $get_db_data('tasks', 'user_id', $user_id);
    return $userTasks;
}

function delete_task(callable $delete_db_data, $taskId) {
    $isTaskDeleted = $delete_db_data('tasks', $taskId);
    return $isTaskDeleted;
}