<?php
  require_once __DIR__ . '/config/constants_config.php';
  require_once PROJECT_ROOT . '/config/session_config.php'; 
  require_once PROJECT_ROOT . '/utils/utils.php';
  require_once PROJECT_ROOT . '/models/db_model.php';
  require_once PROJECT_ROOT . '/models/task_model.php';
  require_once PROJECT_ROOT . '/views/task_view.php';
?>

<!DOCTYPE html>
<html lang="en">
  <?php include PROJECT_ROOT . '/pages/templates/header.php'; ?>
  <main>
    <h2 class="user-tasks-title">Tasks</h2>

    <?php if (is_user_logged($_SESSION)): ?>
      <?= render_task_errors(get_session_errors($_SESSION)); ?>
      <?= render_user_tasks(get_user_tasks('get_db_data', ['user_id' => $_SESSION['userId']])); ?>
    <?php else: ?>
      <p class="guest-message">You must be logged in to see your current tasks.</p>
    <?php endif; ?>

    <a class="add-task-btn" href="<?= BASE_URL . '/pages/add.php'; ?>">Add a task</a>
  </main>
  <?php include PROJECT_ROOT . '/pages/templates/footer.html'; ?>
</html>