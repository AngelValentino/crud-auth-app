<?php
  require_once 'includes/functions.php';

  $isEditTask = false;
  $title = $dueDate = $description = '';
  $errors = [
    'title'=> '',
    'due-date'=> '',
    'description'=> '',
    'id'=> ''
  ];

  // Handles adding a task
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_task_btn'])) {
    $submitTaskValidation = validate_form($_POST, [
      'title' => [
        'label' => 'Title'
      ],
      'due-date' => [
        'label' => 'Due date'
      ],
      'description' => [
        'label' => 'Description'
      ]
    ]);

    $title = $submitTaskValidation['validated']['title'];
    $dueDate = $submitTaskValidation['validated']['due-date'];
    $description = $submitTaskValidation['validated']['description'];

    // Check for errors
    if (!array_filter($submitTaskValidation['errors'])) {
      // If there are no errors submit the data to the databse
      $isDataSet = set_db_data('tasks', [
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
      ]);

      // Redirect the user to home if everything went alright
      if ($isDataSet) header('Location: index.php');
      exit();
    } 
    else {
      $errors = $submitTaskValidation['errors'];
    }
  }

  // Handles editing a task
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['edit_task_btn'])) {
    $isEditTask = true;
    $editTaskValidation = validate_form($_POST, [
      'title' => [
        'label' => 'Title'
      ],
      'due-date' => [
        'label' => 'Due date'
      ],
      'description' => [
        'label' => 'Description'
      ]
    ]);

  /*   print_r($editTaskValidation); */
    $title = $editTaskValidation['validated']['title'];
    $dueDate = $editTaskValidation['validated']['due-date'];
    $description = $editTaskValidation['validated']['description'];
    $idToEdit = trim($_POST['id-to-edit']);

    // Check for errors
    if (!array_filter($editTaskValidation['errors'])) {
      // If there are no errors submit the edited data to the databse
      $isDataUpdated = update_db_data('tasks', [
        'title' => $title,
        'due_date' => $dueDate,
        'description' => $description
      ], $idToEdit);

      // Redirect the user to home if everything went alright
      if ($isDataUpdated) header('Location: index.php');
      exit();
    } 
    else {
      $errors = $editTaskValidation['errors'];
      $errors['id'] = '';
    }
  }

  // If we have a get request with an id fetch the task data to populate the form inputs
  if (isset($_GET['id']) && !empty($_GET['id']) && !isset($_POST['edit_task_btn'])) {
    $isEditTask = true;
    $task = get_db_data('tasks', $_GET['id']);

    if ($task) {
      $title = $task['title'];
      $dueDate = $task['due_date'];
      $description = $task['description'];
    } 
    else {
      header('Location: index.php');
      exit();
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
  <form class="submit-task-form" action="<?php get_action_location($isEditTask) ?>" method="POST">
    <label for="title">Title</label>
    <div class="error"><?= $errors['title'] ?></div>
    <input name="title" type="text" id="title" value="<?= htmlspecialchars($title) ?>">

    <label for="date">Due date</label>
    <div class="error"><?= $errors['due-date'] ?></div>
    <input name="due-date" type="date" id="date" value="<?= htmlspecialchars($dueDate) ?>">

    <label for="description">Description</label>
    <div class="error"><?= $errors['description'] ?></div>
    <textarea name="description" id="description" rows="10"><?= htmlspecialchars($description) ?></textarea>

    <?php if($isEditTask): ?>
      <input type="hidden" name="id-to-edit" value="<?= htmlspecialchars($_GET['id']) ?>">
      <button type="submit" name="edit_task_btn">Submit</button>
    <?php else: ?>
      <button type="submit" name="add_task_btn">Submit</button>
    <?php endif; ?> 
  </form>
</body>
</html>