<?php
  require_once 'config/session_config.php';
  require_once 'utils/utils.php';
  require_once 'models/db_model.php';
  require_once 'models/task_model.php';
  require_once 'views/task_view.php';

  if (validate_user_task($_GET, $_SESSION)) {
    $task = get_user_tasks('get_db_data', [
      'id' => $_GET['task-id'],
      'user_id' => $_SESSION['userId']
    ], true);

    if (!$task) {
      header('Location: index.php');
      exit;
    }
  } 
  else {
    header('Location: index.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php include 'templates/header.php'; ?>
  <main>
    <?= render_edit_task_form(check_form_errors($_SESSION), get_form_data($_SESSION), $task, $_GET['task-id']); ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>