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

function get_user_tasks(callable $get_db_data, $column, $rowData, $onlyOne = null) {
    $userTasks = $get_db_data('tasks', $column, $rowData, $onlyOne);
    return $userTasks;
}

//TODO Add user id to the SQL query to avoid other users managing eachother tasks
function delete_task(callable $delete_db_data, $taskId) {
    $isTaskDeleted = $delete_db_data('tasks', $taskId);
    return $isTaskDeleted;
}

//TODO Add user id to the SQL query to avoid other users managing eachother tasks
function edit_task(callable $update_db_data, $title, $dueDate, $description, $taskId) {
    $isTaskUpdated = $update_db_data('tasks', [
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
    ], $taskId);
    return $isTaskUpdated;
}