<?php
  require_once 'includes/functions.php';
  require_once 'models/db_model.php';
  require_once 'config/session_config.php'; 

  // Handle delete requests
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete-task-btn'])) {
    $idToDelete = $_POST['id-to-delete'];
    delete_db_data('tasks', $idToDelete);
  }

  // Fetch tasks
  $tasks = get_db_data('tasks');
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
            <button type="submit" class="task__delete-btn" name="delete-task-btn">Delete task</button>
          </form>
          <a class="edit" href="add.php?id=<?= $task['id'] ?>">Edit task</a>
        </li>
      <?php endforeach; ?>
    </ul>

    <a class="add-task-btn" href="add.php">Add a task</a>

    <?php 
      if (isset($_SESSION['user_id'])) {
        echo "<p class=\"user-text\">Hello {$_SESSION['username']}.</p>";
        echo '<a class="logout-btn" href="controllers/logout_contr.php?action=logout">Logout</a>';
      } 
      else {
        echo '<p class="user-text">Anonymous user</p>';
        echo '<a class="signup-btn" href="pages/signup.php">Sign up</a>';
        echo '<a class="login-btn" href="pages/login.php">Log in</a>';
      }
    ?>
  </main>
</body>
</html>