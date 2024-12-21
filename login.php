<?php
  require_once 'config/session_config.php';
  require_once 'views/login_view.php';

  // Set errors if there are any
  $errors = check_signup_errors();
  // Fetch form data from session
  $form_data = get_form_data();
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
    <?= render_login_form($errors, $form_data) ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>