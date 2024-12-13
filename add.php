<?php

require_once 'includes/functions.php';

$isEditTask = false;
$title = $dueDate = $description = '';
$errors = [
  'title'=> '',
  'dueDate'=> '',
  'description'=> '',
  'id'=> ''
];



//TODO Form validation needs to be refactored into reusable function

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
    // If there are no errors submit the data to the databse
    $isDataSet = set_db_data('tasks', [
      'title' => $title,
      'due_date' => $dueDate,
      'description' => $description
    ]);

    // Redirect the user to home if everything went alright
    if ($isDataSet) header('Location: index.php');
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
    $isDataUpdated = update_db_data('tasks', [
      'title' => $title,
      'due_date' => $dueDate,
      'description' => $description
    ], $idToEdit);

    // Redirect the user to home if everything went alright
    if ($isDataUpdated) header('Location: index.php');
  }
}

// Add task info for edit
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $isEditTask = true;
  $task = get_db_data('tasks', $_GET['id']);

  print_r($task);
  $title = $task['title'];
  $dueDate = $task['due_date'];
  $description = $task['description'];
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