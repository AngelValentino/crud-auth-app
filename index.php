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
  <?php include 'templates/header.php'; ?>
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
  </main>
  <?php include 'templates/footer.html'; ?>
</html>