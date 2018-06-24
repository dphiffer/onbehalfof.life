<?php

$dir = __DIR__;
$setup_db = false;
if (! file_exists("$dir/comments.db")) {
	$setup_db = true;
}

$db = new PDO("sqlite://$dir/comments.db");

if ($setup_db) {
	$db->query("
		CREATE TABLE comment (
			id INTEGER PRIMARY KEY,
			campaign VARCHAR(255),
			species_id INTEGER,
			comment TEXT,
			name VARCHAR(255),
			email VARCHAR(255),
			created DATETIME,
			moderated DATETIME,
			submitted DATETIME
		);
	");
}

if (! empty($_POST['comment']) &&
    ! empty($_POST['campaign']) &&
    ! empty($_POST['species_id'])) {

	$columns = array(
		'comment',
		'campaign',
		'species_id',
		'created'
	);

	$values = array(
		$_POST['comment'],
		$_POST['campaign'],
		$_POST['species_id'],
		date('Y-m-d H:i:s')
	);

	if (! empty($_POST['name'])) {
		$columns[] = 'name';
		$values[] = $_POST['name'];
	}

	if (! empty($_POST['email'])) {
		$columns[] = 'email';
		$values[] = $_POST['email'];
	}

	$column_list = implode(', ', $columns);
	$placeholders = implode(', ', array_fill(0, count($values), '?'));

	$query = $db->prepare("
		INSERT INTO comment
		($column_list)
		VALUES ($placeholders)
	");
	$query->execute($values);
	$id = $db->lastInsertId();

	header('Content-Type: application/json');
	echo json_encode(array(
		'ok' => 1,
		'id' => $id
	));
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	header('Content-Type: application/json');
	echo json_encode(array(
		'ok' => 0,
		'error' => 'Please include a non-empty comment.'
	));
}
