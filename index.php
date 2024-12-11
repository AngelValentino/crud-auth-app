<?php
  require_once 'includes/dbh.inc.php';

  // Handle delete requests
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete-task-btn'])) {
    try {
      $idToDelete = $_POST['id-to-delete'];

      if (empty($idToDelete) || !is_numeric($idToDelete)) {
        die('Invalid ID');
      }

      // SQL query with placeholders
      $query = 'DELETE FROM tasks WHERE id = ?';

      // Prepare the SQL statement
      $stmt = $pdo->prepare($query);

      // Bind values to the statement
      $stmt->execute([$idToDelete]);
    }
    catch (PDOException $e) {
      die('Query failed: ' . $e->getMessage());
    }
  }
  
  // Fetch tasks
  try {
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

    <?php if(empty($tasks)): ?>
      <p>No tasks to complete.</p>
    <?php endif; ?>

    <ul class="tasks_list">
      <?php foreach ($tasks as $task): ?>
        <li class="task">
          <h2><?= htmlspecialchars($task['title']) ?></h2>
          <h5><?= htmlspecialchars($task['due_date']) ?></h5> 
          <p><?= htmlspecialchars($task['description']) ?></p>
          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="hidden" name="id-to-delete" value="<?= htmlspecialchars($task['id']) ?>">
            <button type="submit" class="task__delete-btn" name="delete-task-btn">delete task</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>

    <a href="add.php">Add a task</a>
  </main>
</body>
</html>