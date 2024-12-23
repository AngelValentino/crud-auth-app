<?php

function check_form_errors() {
    // Check if there are errors in the session, and display them if they exist
    if (isset($_SESSION['errors'])) {
        // Get errors from the session
        $errors = $_SESSION['errors']; 
        // Clear the session errors after displaying them
        unset($_SESSION['errors']);

        return $errors;
    } 
    
    return [];
}

function get_form_data() {
    // Initialize an empty array for the form data
    $formData = [];

    // Check if session data for form data exists
    if (isset($_SESSION['formData'])) {
        // Get form data from session
        $formData = $_SESSION['formData'];
        // Clear the session data after it's retrieved
        unset($_SESSION['formData']);

        return $formData;
    }

    return [];
}


function render_add_task_form($errors, $formData) {
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $title = isset($formData['title']) ? htmlspecialchars($formData['title']) : '';
    $dueDate = isset($formData['dueDate']) ? htmlspecialchars($formData['dueDate']) : '';
    $description = isset($formData['description']) ? htmlspecialchars($formData['description']) : '';

    // Prepare error messages for each field
    $titleError = isset($errors['title']) ? "<div class='error'>{$errors['title']}</div>" : '';
    $dueDateError = isset($errors['dueDate']) ? "<div class='error'>{$errors['dueDate']}</div>" : '';
    $descriptionError = isset($errors['description']) ? "<div class='error'>{$errors['description']}</div>" : '';

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="manage-task-form" action="controllers/add_task_contr.php" method="POST">
            <label for="manage-task-form__title">Title</label>
            $titleError
            <input name="title" type="text" id="manage-task-form__title" value="$title">

            <label for="manage-task-form__due-date">Due date</label>
            $dueDateError
            <input name="dueDate" type="date" id="manage-task-form__due-date" value="$dueDate">

            <label for="manage-task-form__description">Description</label>
            $descriptionError
            <textarea name="description" id="manage-task-form__description" rows="10">$description</textarea>

            <button type="submit" name="addTaskBtn">Submit</button>
        </form>
    HTML;
}

function render_edit_task_form($taskData, $errors, $formData, $taskId) {
    // Assign values with htmlspecialchars to avoid XSS vulnerabilities
    $title = isset($formData['title']) ? htmlspecialchars($formData['title']) : $taskData['title'];
    $dueDate = isset($formData['dueDate']) ? htmlspecialchars($formData['dueDate']) : $taskData['due_date'];
    $description = isset($formData['description']) ? htmlspecialchars($formData['description']) : $taskData['description'];

    // Prepare error messages for each field
    $titleError = isset($errors['title']) ? "<div class='error'>{$errors['title']}</div>" : '';
    $dueDateError = isset($errors['dueDate']) ? "<div class='error'>{$errors['dueDate']}</div>" : '';
    $descriptionError = isset($errors['description']) ? "<div class='error'>{$errors['description']}</div>" : '';

    // Return the HTML form with error messages and values injected
    return <<<HTML
        <form class="manage-task-form" action="controllers/edit_task_contr.php" method="POST">
            <label for="manage-task-form__title">Title</label>
            $titleError
            <input name="title" type="text" id="manage-task-form__title" value="$title">

            <label for="manage-task-form__due-date">Due date</label>
            $dueDateError
            <input name="dueDate" type="date" id="manage-task-form__due-date" value="$dueDate">

            <label for="manage-task-form__description">Description</label>
            $descriptionError
            <textarea name="description" id="manage-task-form__description" rows="10">$description</textarea>

            <input type="hidden" value="$taskId" name="taskId">
            <button type="submit" name="editTaskBtn">Submit</button>
        </form>
    HTML;
}


function render_user_tasks(callable $get_user_tasks, callable $get_db_data, $user_id) {
    $tasks = $get_user_tasks($get_db_data, 'user_id', $user_id);

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
            
            $renderedTasks .= <<<HTML
                <li class="task">
                    <h2>$title</h2>
                    <h5>$dueDate</h5>
                    <p>$description</p>
                    <a class="task__delete-btn" href="controllers/delete_task_contr.php?action=delete&task-id=$taskId">Delete task</a>
                    <br>
                    <a class="task__edit-btn" href="edit.php?task-id=$taskId">Edit task</a>
                </li>
            HTML;
        }

        $renderedTasks .= '</ul>';

        return $renderedTasks;
    }
}