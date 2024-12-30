<?php

function get_db_connection() {
    // Database connection settings
    $host = 'localhost';
    $dbname = 'crud_auth_app';
    $dbUsername = 'root';
    $dbPassword = '';

    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    // Create PDO instance
    try {
        // Establish connection
        $pdo = new PDO($dsn, $dbUsername, $dbPassword);
        // Set error mode to exception for better error handling
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Return the PDO object if connection is successful
        return $pdo; 
    } 
    catch (PDOException $e) {
        // Log the detailed database connection error to the error log file
        error_log("[" . date('Y-m-d H:i:s') . "] Database connection error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
        // Return null if connection fails
        return null;
    }
}

// Wrap every column in backticks
function format_column($column) {
    return "`$column`";
}

function set_db_data($table, $data) {
    // Get the PDO connection
    $pdo = get_db_connection();

    // Check if the connection is valid
    if ($pdo) {
        try {
            // Extract column names and values from the $data array
            $columns = array_keys($data);
            $values = array_values($data);
     
            // Dynamically construct the column names and placeholders
            $columns_list = implode(', ', array_map('format_column', $columns));
            $placeholders = implode(', ', array_fill(0, count($data), '?')); // e.g., "?, ?, ?"
    
            // SQL query with placeholders
            $query = "INSERT INTO `$table` ($columns_list) VALUES ($placeholders);";
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($query);
    
            // Bind values to the statement
            $stmt->execute($values);
            
            return true;
        }
        catch (PDOException $e) {
            // Log the error message when the insert data query fails
            error_log("[" . date('Y-m-d H:i:s') . "] Insert data query connection error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
            return null;
        }
        finally {
            // Close the connection to the database
            $pdo = null;
            $stmt = null;
        }
    } 

    return null;
}

function update_db_data($table, $dataToUpdate, $conditions) {
    // Get the PDO connection
    $pdo = get_db_connection();

    // Check if the connection is valid
    if ($pdo) {
        try {
            // Extract column names and values from the $data array
            $columns = array_keys($dataToUpdate);
            $values = array_values($dataToUpdate);

            // Dynamically construct the SET part of the query
            $setClause = '';
            foreach ($columns as $index => $column) {
                $setClause .= "`$column` = ?";

                // Add trailing comma and space if it is not the last element
                if ($index < count($dataToUpdate) - 1) {
                    $setClause .= ', ';
                }
            }

            // SQL query with placeholders
            $query = "UPDATE `$table` SET $setClause";

            // Build the WHERE clause dynamically if conditions are provided
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $column => $rowData) {
                    $whereClauses[] = "`$column` = ?";
                    $values[] = $rowData;
                }
                $query .= " WHERE " . implode(" AND ", $whereClauses);
            }

            echo $query;
            // Prepare the SQL statement
            $stmt = $pdo->prepare($query . ';');

            // Bind values to the statement
            $stmt->execute($values);

            return true;
        }
        catch (PDOException $e) {
            // Log the error message when the update data query fails
            error_log("[" . date('Y-m-d H:i:s') . "] Update data query connection error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
            return null;
        }
        finally {
            // Close the connection to the database
            $pdo = null;
            $stmt = null;
        }
    }

    return null;
}

function get_db_data($table, $conditions = [], $onlyOne = null) {
    // Get the PDO connection
    $pdo = get_db_connection();
    
    // Check if the connection is valid
    if ($pdo) {
        // Fetch tasks
        try {
            // Initialize query variable
            $query = "SELECT * FROM `$table`";
            $values = [];

            // Build the WHERE clause dynamically if conditions are provided
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $column => $rowData) {
                    $whereClauses[] = "`$column` = ?";
                    $values[] = $rowData;
                }
                $query .= " WHERE " . implode(" AND ", $whereClauses);
            }

            // Prepare and execute the SQL statement
            if ($onlyOne) $query .= " LIMIT 1";  
            $stmt = $pdo->prepare($query . ';'); 
            // Execute the statement with parameters
            $stmt->execute($values);

            if ($onlyOne) {
                // Fetch a single record
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } 
            else {
                // Fetch all records
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } 
    
            return $data;
        }
        catch (PDOException $e) {
            // Log the error message when the select data query fails
            error_log("[" . date('Y-m-d H:i:s') . "] Select data query connection error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
            return null;
        }
        finally {
            // Close the connection to the database
            $stmt = null;
            $pdo = null;
        }
    }

    return null;
}

function delete_db_data($table, $conditions) {
    $pdo = get_db_connection();

    // Check if the connection is valid
    if ($pdo) {
        try {
            // SQL query with placeholders
            $query = "DELETE FROM `$table`";
            $values = [];

            // Build the WHERE clause dynamically if conditions are provided
            if (!empty($conditions)) {
                $whereClauses = [];
                foreach ($conditions as $column => $rowData) {
                    $whereClauses[] = "`$column` = ?";
                    $values[] = $rowData;
                }
                $query .= " WHERE " . implode(" AND ", $whereClauses);
            }
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($query . ';');

            echo $query . ';';
            print_r($values);
            // Execute the statement with parameters
            $stmt->execute($values);
    
            return true;
        }
        catch (PDOException $e) {
            // Log the error message when the delete data query fails
            error_log("[" . date('Y-m-d H:i:s') . "] Delete data query connection error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../error_log.txt');
            // Return null if connection fails
            return null;
        }
        finally {
            // Close the connection to the database
            $stmt = null;
            $pdo = null;
        }
    }
    
    return null;
}