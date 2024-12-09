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
  <form action="../includes/formhandler.inc.php" method="post">
    <label for="title">Title</label>
    <input name="title" type="text" id="title">

    <label for="date">Due date</label>
    <input name="date" type="date" id="date">

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="10"></textarea>
  
    <button type="submit">Submit</button>
  </form>
</body>
</html>