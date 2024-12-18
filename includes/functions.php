<?php

function get_action_location($isEditTask) {
    $baseUrl = htmlspecialchars($_SERVER['PHP_SELF']);

    if ($isEditTask && isset($_GET['id'])) {
        return $baseUrl . '?id=' . htmlspecialchars($_GET['id']);
    } 
    else {
        return $baseUrl;
    }
}

function validate_form($requestData, $fields) {
    // Initialize an empty array to store error messages
    $errors = [];
    // Initialize an empty array to store validated input values
    $validated = [];

    // Loop through each field configuration in the $fields array
    foreach ($fields as $field => $config) {
        $label = $config['label'];
        $regex = isset($config['regex']) ? $config['regex'] : null;

        // Check if the input for the field is empty
        if (empty($requestData[$field])) {
            $errors[$field] = "$label is required";
            $validated[$field] = $requestData[$field]; // Keep original value in case of error
        } 
        else {
            // If the field is not empty, trim the input to remove whitespace
            $value = trim($requestData[$field]);

            // If a regex is provided, validate the input against the regex pattern
            if ($regex && !preg_match($regex, $value)) {
                $errors[$field] = "$label has an invalid format";
                $validated[$field] = $requestData[$field]; // Keep original value in case of error
            } 
            else {
                $validated[$field] = $value; // Store the final validated value 
                $errors[$field] = ''; // Clean the errors array
            }
        }
    }

    return [
        'errors' => $errors,
        'validated' => $validated
    ];
}
