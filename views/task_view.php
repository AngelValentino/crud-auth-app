<?php
require_once __DIR__ . '/../config/constants_config.php';

function format_task_entry($data, $key) {
    return $data ? $data[$key] : '';
}

function render_manage_task_form($errors, $formData, $taskData = null, $taskId = null) {
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $title = isset($formData['title']) ? htmlspecialchars($formData['title']) : format_task_entry($taskData, 'title');
    $dueDate = isset($formData['dueDate']) ? htmlspecialchars($formData['dueDate']) : format_task_entry($taskData, 'due_date');
    $description = isset($formData['description']) ? htmlspecialchars($formData['description']) : format_task_entry($taskData, 'description');
    $renderHiddenInput = $taskId ? "<input type='hidden' value='$taskId' name='taskId'>" : '';

    // Prepare error messages for each field
    $titleError = isset($errors['title']) ? "<p class='error'>{$errors['title']}</p>" : '';
    $dueDateError = isset($errors['dueDate']) ? "<p class='error'>{$errors['dueDate']}</p>" : '';
    $descriptionError = isset($errors['description']) ? "<p class='error'>{$errors['description']}</p>" : '';
    $dbError = isset($errors['db']) ? "<p class='error'>{$errors['db']}</p>" : '';
    $action = $taskData ? 'edit' : 'add';
    $formTitle = $taskData ? '<h2 class="manage-task-form__header-title">Edit task</h2>' : '<h2 class="manage-task-form__header-title">Add a task</h2>';
    $baseUrl = BASE_URL;

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="manage-task-form" action="{$baseUrl}/controllers/{$action}_task_contr.php" method="POST">
            $formTitle
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
            $dbError
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

function render_task_errors($errors) {
    if (!$errors) return ''; 
    $editTaskError = isset($errors['editTask']) ? "<p class='error'>{$errors['editTask']}</p>" : '';
    $deleteTaskError = isset($errors['deleteTask']) ? "<p class='error'>{$errors['deleteTask']}</p>" : '';

    return <<<HTML
        $editTaskError
        $deleteTaskError
    HTML;
}

function render_user_tasks($tasks) {
    if ($tasks === null) {
        return '<p class="error">An error occurred while retrieving your tasks. Please try again later.</p>';
    }

    // If no tasks are found
    if (empty($tasks)) {
        return '<p class="empty-tasks-message">No tasks to complete.</p>';
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
                    <h3 class="task__title">$title</h3>
                    <small class="task__due-date">$dueDate</small>
                    <p class="task__description">$description</p>
                    <div class="task__manage-btns-container">
                        <a class="task__edit-btn" href="{$baseUrl}/pages/edit.php?task-id=$taskId">Edit task</a>
                        <a class="task__delete-btn" href="{$baseUrl}/controllers/delete_task_contr.php?action=delete&task-id=$taskId">Delete task</a>
                    </div>
                   
                </li>
            HTML;
        }

        $renderedTasks .= '</ul>';

        return $renderedTasks;
    }
}