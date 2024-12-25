<?php
  require_once 'config/session_config.php';
  require_once 'utils/utils.php';
  require_once 'views/task_view.php';
?>

<!DOCTYPE html>
<html lang="en">
  <?php include 'templates/header.php'; ?>
  <main>
    <?php if (is_user_logged($_SESSION)): ?>
      <?= render_add_task_form(check_form_errors($_SESSION), get_form_data($_SESSION)); ?>
    <?php else: ?>
      <p>You must be logged in to add or manage a task.</p>
    <?php endif; ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>