<?php
  require_once 'config/session_config.php';
  require_once 'utils/utils.php';
  require_once 'views/login_view.php';

  if (is_user_logged($_SESSION)) {
    header('Location: index.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD auth app</title>
  <link rel="stylesheet" href="styles/reset.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <?= render_login_form(check_form_errors($_SESSION), get_form_data($_SESSION)) ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>