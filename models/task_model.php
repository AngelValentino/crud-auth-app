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

function get_user_tasks(callable $get_db_data, $conditions, $onlyOne = null) {
    $userTasks = $get_db_data('tasks', $conditions, $onlyOne);
    return $userTasks;
}

function delete_task(callable $delete_db_data, $conditions) {
    $isTaskDeleted = $delete_db_data('tasks', $conditions);
    return $isTaskDeleted;
}

function edit_task(callable $update_db_data, $title, $dueDate, $description, $taskId, $user_id) {
    $isTaskUpdated = $update_db_data('tasks', [
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
    ], [
        'id' => $taskId,
        'user_id' => $user_id
    ]);
    return $isTaskUpdated;
}