<?php
  require_once 'includes/functions.php';
  require_once 'config/session_config.php'; 

  configure_session();
  session_init();

  // Handle delete requests
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete-task-btn'])) {
    $idToDelete = $_POST['id-to-delete'];
    delete_db_data('tasks', $idToDelete);
  }
  
  // Fetch tasks
  $tasks = get_db_data('tasks');


  // Display success message if it exists
  if (isset($_SESSION['success_message'])) {
    echo '<p>' . $_SESSION['success_message'] . '</p>';
    
    // Clear success message after displaying
    unset($_SESSION['success_message']);
  }

  // Retrieve form data from session, if available (in case of errors)
  $username_value = isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : '';
  $email_value = isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : '';
  $password_value = isset($_SESSION['form_data']['password']) ? $_SESSION['form_data']['password'] : '';
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

    <form class="signup-form" action="controllers/signup_controller.php" method="POST">
      <h2>Sign Up</h2>

      <label for="signup-form__username-input">Username</label>
      <?php if (isset($_SESSION['errors']['username'])): ?>
          <div class="error"><?= $_SESSION['errors']['username'] ?></div>
      <?php endif; ?>
      <input id="signup-form__username-input" type="text" name="username" value="<?= htmlspecialchars($username_value) ?>">

      <label for="signup-form__email-input">Email</label>
      <?php if (isset($_SESSION['errors']['email'])): ?>
          <div class="error"><?= $_SESSION['errors']['email'] ?></div>
      <?php endif; ?>
      <input id="signup-form__email-input" type="text" name="email" value="<?= htmlspecialchars($email_value) ?>">

      <label for="signup-form__password-input">Password</label>
      <?php if (isset($_SESSION['errors']['password'])): ?>
          <div class="error"><?= $_SESSION['errors']['password'] ?></div>
      <?php endif; ?>
      <input id="signup-form__password-input" type="password" name="password" value="<?= htmlspecialchars($password_value) ?>">

      <button class="signup-form__signup-btn" type="submit">Sign Up</button>
    </form>


    <form class="login-form" action="controllers/login_controller.php" method="POST">
      <h2>Log in</h2>

      <label for="login-form__username-input">Username</label>
      <input id="login-form__username-input" type="text" name="username">

      <label for="login-form__password-input">Password</label>
      <input id="login-form__password-input" type="password" name="password">

      <button class="login-form__signup-btn" type="submit">Log in</button>
    </form>
  </main>
</body>
</html>