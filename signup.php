<?php
  require_once 'config/session_config.php'; 
  require_once 'views/signup_view.php';

  if (isset($_SESSION['user_id'])) {
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
    <?= render_signup_form(check_signup_errors(), get_form_data()) ?>
  </main>
  <?php include 'templates/footer.html'; ?>
</body>
</html>