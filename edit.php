<?php
  require_once 'config/session_config.php';
  require_once 'models/db_model.php';
  require_once 'models/task_model.php';
  require_once 'views/task_view.php';

  if (
      !isset($_GET['task-id']) || 
      empty($_GET['task-id']) || 
      !filter_var($_GET['task-id'], FILTER_VALIDATE_INT) || 
      $_GET['task-id'] < 1 ||
      !isset($_SESSION['user_id']) ||
      $_SESSION['user_id'] < 1
    ) {
    header('Location: index.php');
    exit;
  } 
  else {
    $task = get_user_tasks('get_db_data', [
      'id' => $_GET['task-id'],
      'user_id' => $_SESSION['user_id']
    ], true);

    if (!$task) {
      header('Location: index.php');
      exit;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php include 'templates/header.php'; ?>
  <main>
    <?= render_edit_task_form($task, check_form_errors(), get_form_data(), $_GET['task-id']); ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>