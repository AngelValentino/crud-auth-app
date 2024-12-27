<?php
  require_once __DIR__ . '/../config/constants_config.php';
  require_once PROJECT_ROOT . '/config/session_config.php';
  require_once PROJECT_ROOT . '/utils/utils.php';
  require_once PROJECT_ROOT . '/views/task_view.php';
?>

<!DOCTYPE html>
<html lang="en">
  <?php include PROJECT_ROOT . '/pages/templates/header.php'; ?>
  <main>
    <?php if (is_user_logged($_SESSION)): ?>
      <?= render_add_task_form(check_form_errors($_SESSION), get_form_data($_SESSION)); ?>
    <?php else: ?>
      <p>You must be logged in to add or manage a task.</p>
    <?php endif; ?>
  </main>
  <?php include PROJECT_ROOT . '/pages/templates/footer.html'; ?>
</html>