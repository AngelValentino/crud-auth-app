<?php

require_once 'includes/dbh.inc.php';

$isEditTask = false;
$title = $dueDate = $description = '';
$errors = [
  'title'=> '',
  'dueDate'=> '',
  'description'=> '',
  'id'=> ''
];

// Form validation and add task
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


// Form validation and edit task
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['edit_task_btn'])) {
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

  $idToEdit = trim($_POST['id-to-edit']);
  if (empty($idToEdit)) {
    $errors['id'] = 'Invalid ID';
  }

  // Check for errors
  if (!array_filter($errors)) {
    // If there are no errors try to submit the data to the databse
    try {
      // SQL query with placeholders
      $query = 'UPDATE tasks SET title = ?, due_date = ?, `description` = ? WHERE id = ?';

      // Prepare the SQL statement
      $stmt = $pdo->prepare($query);

      // Bind values to the statement
      $stmt->execute([$title, $dueDate, $description, $idToEdit]);

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

// Add task info for edit
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $isEditTask = true;
  // Fetch tasks
  try {
    // SQL query with placeholders
    $query = 'SELECT id, title, due_date, `description` FROM tasks WHERE id = ?';

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_GET['id']]);

    $tasks = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close the connection to the database
    $stmt = null;
    $pdo = null;
  }
  catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
  }

  print_r($tasks);
  $title = $tasks['title'];
  $dueDate = $tasks['due_date'];
  $description = $tasks['description'];
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
  <form class="submit-task-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <label for="title">Title</label>
    <div class="error"><?= $errors['title'] ?></div>
    <input name="title" type="text" id="title" value="<?= $title ?>">

    <label for="date">Due date</label>
    <div class="error"><?= $errors['dueDate'] ?></div>
    <input name="due-date" type="date" id="date" value="<?= $dueDate ?>">

    <label for="description">Description</label>
    <div class="error"><?= $errors['description'] ?></div>
    <textarea name="description" id="description" rows="10"><?= $description ?></textarea>

    <?php if($isEditTask): ?>
      <input type="hidden" name="id-to-edit" value="<?= htmlspecialchars($_GET['id']) ?>">
      <div class="error"><?= $errors['id'] ?></div>
      <button type="submit" name="edit_task_btn">Submit</button>
    <?php else: ?>
      <button type="submit" name="add_task_btn">Submit</button>
    <?php endif; ?> 
  </form>
</body>
</html>