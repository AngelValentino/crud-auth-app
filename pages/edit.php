<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/crud-auth-app/config/constants_config.php';
  require_once PROJECT_ROOT . '/config/session_config.php';
  require_once PROJECT_ROOT . '/utils/utils.php';
  require_once PROJECT_ROOT . '/models/db_model.php';
  require_once PROJECT_ROOT . '/models/task_model.php';
  require_once PROJECT_ROOT . '/views/task_view.php';

  if (validate_user_task($_GET, $_SESSION)) {
    $task = get_user_tasks('get_db_data', [
      'id' => $_GET['task-id'],
      'user_id' => $_SESSION['userId']
    ], true);

    if (!$task) {
      header('Location: ' . BASE_URL . '/index.php');
      exit;
    }
  } 
  else {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php include PROJECT_ROOT . '/pages/templates/header.php'; ?>
  <main>
    <?= render_edit_task_form(check_form_errors($_SESSION), get_form_data($_SESSION), $task, $_GET['task-id']); ?>
  </main>
  <?php include PROJECT_ROOT . '/pages/templates/footer.html'; ?>
</html>