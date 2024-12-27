<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/crud-auth-app/config/constants_config.php';
  require_once PROJECT_ROOT . '/config/session_config.php';
  require_once PROJECT_ROOT . '/utils/utils.php';
  require_once PROJECT_ROOT . '/views/login_view.php';

  if (is_user_logged($_SESSION)) {
    header('Location: ' . BASE_URL . '/index.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php include PROJECT_ROOT . '/pages/templates/header.php'; ?>
  <main>
    <?= render_login_form(check_form_errors($_SESSION), get_form_data($_SESSION)) ?>
  </main>
  <?php include PROJECT_ROOT . '/pages/templates/footer.html'; ?>
</html>