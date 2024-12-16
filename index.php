<?php
  require_once 'includes/functions.php';

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

    <br>

    <form class="signup-form" action="includes/signup.inc.php" method="POST">
      <h2>Sign Up</h2>

      <label for="signup-form__username-input">Username</label>
      <input id="signup-form__username-input" type="text" name="username">

      <label for="signup-form__email-input">Email</label>
      <input id="signup-form__email-input" type="text" name="email">

      <label for="signup-form__password-input">Password</label>
      <input id="signup-form__password-input" type="text" name="pwd">

      <button class="signup-form__signup-btn" type="submit">Sign Up</button>
    </form>

    <form class="login-form" action="includes/login.inc.php" method="POST">
      <h2>Log in</h2>

      <label for="login-form__username-input">Username</label>
      <input id="login-form__username-input" type="text" name="username">

      <label for="login-form__password-input">Password</label>
      <input id="login-form__password-input" type="text" name="pwd">

      <button class="login-form__signup-btn" type="submit">Log in</button>
    </form>
  </main>
</body>
</html>