<?php

function add_task(callable $set_db_data, $title, $dueDate, $description, $userId) {
    return $set_db_data('tasks', [
        'user_id' => $userId,
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
    ]);
}

function get_user_tasks(callable $get_db_data, $conditions, $onlyOne = null) {
    return $get_db_data('tasks', $conditions, $onlyOne);
}

function delete_task(callable $delete_db_data, $conditions) {
    return $delete_db_data('tasks', $conditions);
}

function edit_task(callable $update_db_data, $title, $dueDate, $description, $taskId, $userId) {
    return $update_db_data('tasks', [
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
    ], [
        'id' => $taskId,
        'user_id' => $userId
    ]);
}