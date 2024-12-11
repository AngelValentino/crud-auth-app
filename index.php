<?php
   try {
    require_once 'includes/dbh.inc.php';

    // SQL query with placeholders
    $query = 'SELECT id, title, due_date, `description` FROM tasks';

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the connection to the database
    $stmt = null;
    $pdo = null;
  }
  catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD auth app</title>
  <link rel="stylesheet" href="styles/reset.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <main>
    <h1>Tasks</h1>
    <ul class="tasks_list">
      <?php foreach ($tasks as $task): ?>
        <li class="task">
          <h2><?= $task['title'] ?></h2>
          <h5><?= $task['due_date'] ?></h2> 
          <p><?= $task['description'] ?></p>
          <button class="task__delete-btn">
            delete task with an id of <?= $task['id'] ?>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>

    <a href="add.php">Add a task</a>
  </main>
</body>
</html>