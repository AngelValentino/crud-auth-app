<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/crud-auth-app/config/constants_config.php';

function format_task_entry($data, $key) {
    return $data ? $data[$key] : '';
}

function get_action($taskData) {
    return $taskData ? 'edit' : 'add';
}

function render_manage_task_form($errors, $formData, $taskData = null, $taskId = null) {
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $title = isset($formData['title']) ? htmlspecialchars($formData['title']) : format_task_entry($taskData, 'title');
    $dueDate = isset($formData['dueDate']) ? htmlspecialchars($formData['dueDate']) : format_task_entry($taskData, 'due_date');
    $description = isset($formData['description']) ? htmlspecialchars($formData['description']) : format_task_entry($taskData, 'description');
    $renderHiddenInput = $taskId ? "<input type='hidden' value='$taskId' name='taskId'>" : '';

    // Prepare error messages for each field
    $titleError = isset($errors['title']) ? "<div class='error'>{$errors['title']}</div>" : '';
    $dueDateError = isset($errors['dueDate']) ? "<div class='error'>{$errors['dueDate']}</div>" : '';
    $descriptionError = isset($errors['description']) ? "<div class='error'>{$errors['description']}</div>" : '';
    $action = get_action($taskData);
    $baseUrl = BASE_URL;

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="manage-task-form" action="{$baseUrl}/controllers/{$action}_task_contr.php" method="POST">
            <label for="manage-task-form__title">Title</label>
            $titleError
            <input name="title" type="text" id="manage-task-form__title" value="$title">

            <label for="manage-task-form__due-date">Due date</label>
            $dueDateError
            <input name="dueDate" type="date" id="manage-task-form__due-date" value="$dueDate">

            <label for="manage-task-form__description">Description</label>
            $descriptionError
            <textarea name="description" id="manage-task-form__description" rows="10">$description</textarea>

            $renderHiddenInput
            <button type="submit">Submit</button>
        </form>
    HTML;
}

function render_add_task_form($errors, $formData) {
    return render_manage_task_form($errors, $formData);
}

function render_edit_task_form($errors, $formData, $taskData, $taskId) {
    return render_manage_task_form($errors, $formData, $taskData, $taskId);
}

function render_user_tasks($tasks) {
    if ($tasks === null) {
        return '<p>An error occurred while retrieving your tasks. Please try again later.</p>';
    }

    // If no tasks are found
    if (empty($tasks)) {
        return '<p>No tasks to complete.</p>';
    }
    else {
        $renderedTasks = '<ul class="tasks_list">';

        foreach ($tasks as $task) {
            $title = htmlspecialchars($task['title']);
            $dueDate = htmlspecialchars($task['due_date']);
            $description = htmlspecialchars($task['description']);
            $taskId = htmlspecialchars($task['id']);
            $baseUrl = BASE_URL;
            
            $renderedTasks .= <<<HTML
                <li class="task">
                    <h2>$title</h2>
                    <h5>$dueDate</h5>
                    <p>$description</p>
                    <a class="task__delete-btn" href="{$baseUrl}/controllers/delete_task_contr.php?action=delete&task-id=$taskId">Delete task</a>
                    <br>
                    <a class="task__edit-btn" href="{$baseUrl}/pages/edit.php?task-id=$taskId">Edit task</a>
                </li>
            HTML;
        }

        $renderedTasks .= '</ul>';

        return $renderedTasks;
    }
}