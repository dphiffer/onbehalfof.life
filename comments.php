<?php

if (! file_exists('config.php')) {
	die('Please set up config.php');
}

include('config.php');
$deliverator_url = $config['deliverator_url'];
$verify_ssl = $config['verify_ssl'];
$api_key = $config['api_key'];

$urls = array(
	'transparency' => 'https://www.regulations.gov/comment?D=EPA-HQ-OA-2018-0259-0001'
);

$dir = __DIR__;
$setup_db = false;
if (! file_exists($config['sqlite_path'])) {
	$setup_db = true;
}

$db = new PDO("sqlite://{$config['sqlite_path']}");

if ($setup_db) {
	$db->query("
		CREATE TABLE comment (
			id INTEGER PRIMARY KEY,
			campaign VARCHAR(255),
			species_id INTEGER,
			deliverator_id INTEGER,
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

	$deliverator_id = null;

	$campaign = $_POST['campaign'];
	if (! empty($urls[$campaign])) {

		$species_id = intval($_POST['species_id']);
		$species_path = __DIR__ . "/species/$species_id.json";
		$species_json = file_get_contents($species_path);
		$species = json_decode($species_json, 'as hash');
		$on_behalf_of = $species['common'] . ' (' . $species['latin'] . ')';

		$data = http_build_query(array(
			'url' => $urls[$campaign],
			'comment' => $_POST['comment'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],
			'on_behalf_of' => $on_behalf_of,
			'api_key' => $api_key
		));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $deliverator_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if (! $verify_ssl) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		$json = curl_exec($ch);
		curl_close($ch);

		$rsp = json_decode($json, 'as hash');
		$deliverator_id = $rsp['id'];

		$query = $db->prepare("
			UPDATE comment
			SET deliverator_id = ?
			WHERE id = ?
		");
		$query->execute(array($id, $deliverator_id));
	}

	header('Content-Type: application/json');
	echo json_encode(array(
		'ok' => 1,
		'id' => $id,
		'deliverator' => $rsp
	));
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	header('Content-Type: application/json');
	echo json_encode(array(
		'ok' => 0,
		'error' => 'Please include a non-empty comment.'
	));
}
