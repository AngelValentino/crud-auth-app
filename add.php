<?php
  require_once 'config/session_config.php';
  require_once 'views/task_view.php';
?>

<!DOCTYPE html>
<html lang="en">
  <?php include 'templates/header.php'; ?>
  <main>
    <?php if (isset($_SESSION['user_id'])): ?>
      <?= render_add_task_form(check_form_errors(), get_form_data()); ?>
    <?php else: ?>
      <p>You must be logged in to add or manage a task.</p>
    <?php endif; ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>