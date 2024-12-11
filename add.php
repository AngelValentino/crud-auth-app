<?php

$title = $dueDate = $description = '';
$errors = [
  'title'=> '',
  'dueDate'=> '',
  'description'=> ''
];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_task_btn'])) {
  // Form validation
  if (empty($_POST['title'])) {
    $errors['title'] = 'title is required';
  } 
  else {
    $title = trim($_POST['title']);
  }

  if (empty($_POST['due-date'])) {
    $errors['dueDate'] = 'due date is required';
  } 
  else {
    $dueDate = trim($_POST['due-date']);
  }

  if (empty($_POST['description'])) {
    $errors['description'] = 'description is required';
  } 
  else {
    $description = trim($_POST['description']);
  }

  // Check for errors
  if (!array_filter($errors)) {
    // If there are no errors try to submit the data to the databse
    try {
      require_once 'includes/dbh.inc.php';

      // SQL query with placeholders
      $query = 'INSERT INTO tasks (title, due_date, `description`) VALUES (?, ?, ?);';

      // Prepare the SQL statement
      $stmt = $pdo->prepare($query);

      // Bind values to the statement
      $stmt->execute([$title, $dueDate, $description]);

      // Close the connection to the database
      $pdo = null;
      $stmt = null;

      header('Location: index.php');
      die();
    }
    catch (PDOException $e) {
      die('Query failed: ' . $e->getMessage());
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="styles/reset.css">
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <label for="title">Title</label>
    <div class="error"><?= $errors['title'] ?></div>
    <input name="title" type="text" id="title" value="<?= $title ?>">

    <label for="date">Due date</label>
    <div class="error"><?= $errors['dueDate'] ?></div>
    <input name="due-date" type="date" id="date" value="<?= $dueDate ?>">

    <label for="description">Description</label>
    <div class="error"><?= $errors['description'] ?></div>
    <textarea name="description" id="description" rows="10"><?= $description ?></textarea>
  
    <button type="submit" name="add_task_btn">Submit</button>
  </form>
</body>
</html>