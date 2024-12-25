<?php
  require_once 'config/session_config.php'; 
  require_once 'utils/utils.php';
  require_once 'models/db_model.php';
  require_once 'models/task_model.php';
  require_once 'views/task_view.php';
?>

<!DOCTYPE html>
<html lang="en">
  <?php include 'templates/header.php'; ?>
  <main>
    <h1 class="user-tasks-title">Tasks</h1>

    <?php if (is_user_logged($_SESSION)): ?>
      <?= render_user_tasks(get_user_tasks('get_db_data', ['user_id' => $_SESSION['userId']])) ?>
    <?php else: ?>
      <p>You must be logged in to see your current tasks.</p>
    <?php endif; ?>

    <a class="add-task-btn" href="add.php">Add a task</a>
  </main>
  <?php include 'templates/footer.html'; ?>
</html>