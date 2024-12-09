<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	$title = trim($_POST['title']);
	$dueDate = trim($_POST['due-date']);
	$description = trim($_POST['description']);

	if (empty($title) || empty($dueDate) || empty($description)) {
		die("All fields are required.");
	}

	try {
		require_once 'dbh.inc.php';

		// SQL query with placeholders
		$query = 'INSERT INTO tasks (title, due_date, `description`) VALUES (?, ?, ?);';

		// Prepare the SQL statement
		$stmt = $pdo->prepare($query);

		// Bind values to the statement
		$stmt->execute([$title, $dueDate, $description]);

		$pdo = null;
		$stmt = null;

		header('Location: ../index.php');
		die();
	}
	catch (PDOException $e) {
		die('Query failed: ' . $e->getMessage());
	}
}
else {
	header('Location: ../index.php');
}