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

function set_db_data($table, $data) {
    // Get the PDO connection
    $pdo = get_db_connection();
   
    // If there are no errors try to submit the data to the databse
    try {
        // Extract column names and values from the $data array
        $columns = array_keys($data);
        $values = array_values($data);
 
        // Dynamically construct the column names and placeholders
        $columns_list = implode(', ', $columns); // e.g., "title, due_date, `description`"
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

function update_db_data($table, $data, $id) {
    // Get the PDO connection
    $pdo = get_db_connection();

    // If there are no errors try to submit the data to the databse
    try {
        // Extract column names and values from the $data array
        $columns = array_keys($data);
        $values = array_values($data);


        // Dynamically construct the SET part of the query
        $setClause = '';
        foreach ($columns as $index => $column) {
            $setClause .= "$column = ?";

            // Add trailing comma and space if it is not the last element
            if ($index < count($data) - 1) {
                $setClause .= ', ';
            }
        }

        // SQL query with placeholders
        $query = "UPDATE $table SET $setClause WHERE id = ?";

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

function get_db_data($table) {
    // Get the PDO connection
    $pdo = get_db_connection();
    
    // Check if the connection is valid
    if (isset($pdo)) {
        // Fetch tasks
        try {
            // SQL query to fetch all records from the table
            $query = "SELECT * FROM $table";
    
            // Prepare and execute the SQL statement
            $stmt = $pdo->prepare($query);
            $stmt->execute();
    
            // Fetch all data as an associative array
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $tasks;
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
}

function delete_db_data($table, $id) {
    $pdo = get_db_connection();

    try {
        // SQL query with placeholders
        $query = "DELETE FROM $table WHERE id = ?";

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