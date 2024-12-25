<?php

function check_form_errors(&$session) {
    // Check if there are errors in the session, and display them if they exist
    if (isset($session['errors'])) {
        // Get errors from the session
        $errors = $session['errors']; 
        // Clear the session errors after displaying them
        unset($session['errors']);

        return $errors;
    } 
    
    return [];
}

function get_form_data(&$session) {
    // Initialize an empty array for the form data
    $formData = [];

    // Check if session data for form data exists
    if (isset($session['formData'])) {
        // Get form data from session
        $formData = $session['formData'];
        // Clear the session data after it's retrieved
        unset($session['formData']);

        return $formData;
    }

    return [];
}

function is_user_logged($session) {
    return isset($session['userId']);
}

function validate_user_task($get, $session) {
    $taskId = $get['task-id'];

    if  (
        !isset($taskId) || 
        empty($taskId) || 
        !filter_var($taskId, FILTER_VALIDATE_INT) || 
        $taskId < 1 ||
        !is_user_logged($session) ||
        $session['userId'] < 1
    ) {
        return false;
    } 
    else {
        return true;
    }
}

function validate_title($title) {
    if (empty($title)) {
        return 'A title is required.';
    } 
    else if (strlen($title) > 150) {
        return 'Title must be less than or equal 150 characters.';
    }
}

function validate_due_date($dueDate) {
    if (empty($dueDate)) {
        return 'A due date is required.';
    }
}

function validate_description($description) {
    if (empty($description)) {
        return 'A description is required.';
    } 
    else if (strlen($description) > 1000) {
        return 'Description must be less than or equal 1000 characters.';
    }
}