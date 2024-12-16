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
        // If connection fails, display the error
        echo "Connection failed: " . $e->getMessage();
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
    if (isset($pdo)) {
        try {
            // Extract column names and values from the $data array
            $columns = array_keys($data);
            $values = array_values($data);
     
            // Dynamically construct the column names and placeholders
            $columns_list = implode(', ', array_map('format_column', $columns));
            $placeholders = implode(', ', array_fill(0, count($data), '?')); // e.g., "?, ?, ?"
    
            // SQL query with placeholders
            $query = "INSERT INTO $table ($columns_list) VALUES ($placeholders);";
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($query);
    
            // Bind values to the statement
            $stmt->execute($values);
            
            return true;
        }
        catch (PDOException $e) {
            // If connection fails, display the error
            echo 'Query failed: ' . $e->getMessage();
            return false;
        }
        finally {
            // Close the connection to the database
            $pdo = null;
            $stmt = null;
        }
    }
}

function update_db_data($table, $data, $id) {
    // Get the PDO connection
    $pdo = get_db_connection();

    // Check if the connection is valid
    if (isset($pdo)) {
        try {
            // Extract column names and values from the $data array
            $columns = array_keys($data);
            $values = array_values($data);

            // Dynamically construct the SET part of the query
            $setClause = '';
            foreach ($columns as $index => $column) {
                $setClause .= format_column($column) . ' = ?';

                // Add trailing comma and space if it is not the last element
                if ($index < count($data) - 1) {
                    $setClause .= ', ';
                }
            }

            // SQL query with placeholders
            $query = "UPDATE $table SET $setClause WHERE id = ?;";

            // Prepare the SQL statement
            $stmt = $pdo->prepare($query);

            // Add the ID to the values array for the WHERE clause
            $values[] = $id;

            // Bind values to the statement
            $stmt->execute($values);

            return true;
        }
        catch (PDOException $e) {
            // If connection fails, display the error
            echo 'Query failed: ' . $e->getMessage();
            return false;
        }
        finally {
            // Close the connection to the database
            $pdo = null;
            $stmt = null;
        }
    }
}

function get_db_data($table, $id = null) {
    // Get the PDO connection
    $pdo = get_db_connection();
    
    // Check if the connection is valid
    if (isset($pdo)) {
        // Fetch tasks
        try {
            // Initialize query variable
            $query = "SELECT * FROM $table";

            // If an id is provided, modify the query to fetch a single record
            if ($id) $query .= ' WHERE id = ?';

            // Prepare and execute the SQL statement
            $stmt = $pdo->prepare($query . ';');
            
            if ($id) {
                $stmt->execute([$id]);
                // Fetch the single data as an associative array
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } 
            else {
                $stmt->execute();
                // Fetch all data as an array of associative arrays(2D array)
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }  
    
            return $data;
        }
        catch (PDOException $e) {
            // If connection fails, display the error
            echo 'Query failed: ' . $e->getMessage();
            return [];
        }
        finally {
            // Close the connection to the database
            $stmt = null;
            $pdo = null;
        }
    }

    return [];
}

function delete_db_data($table, $id) {
    $pdo = get_db_connection();

    // Check if the connection is valid
    if (isset($pdo)) {
        try {
            // SQL query with placeholders
            $query = "DELETE FROM $table WHERE id = ?;";
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($query);
    
            // Bind values to the statement
            $stmt->execute([$id]);
    
            return true;
        }
        catch (PDOException $e) {
            // If connection fails, display the error
            echo 'Query failed: ' . $e->getMessage();
            // Return null if connection fails
            return false;
        }
        finally {
            // Close the connection to the database
            $stmt = null;
            $pdo = null;
        }
    }
}