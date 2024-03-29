<?php

$dir = __DIR__;
$setup_db = false;
if (! file_exists("$dir/twilio/twilio.db")) {
	$setup_db = true;
}

$db = new PDO("sqlite://$dir/twilio/twilio.db");

if ($setup_db) {
	$db->query("
		CREATE TABLE comment (
			id INTEGER PRIMARY KEY,
			twilio_id VARCHAR(255),
			type VARCHAR(255),
			phone VARCHAR(255),
			message TEXT,
			audio VARCHAR(255),
			duration INTEGER,
			visible INTEGER DEFAULT 1,
			featured INTEGER DEFAULT 0,
			created DATETIME,
			updated DATETIME
		);
	");

	$db->query("
		CREATE TABLE twilio (
			id INTEGER PRIMARY KEY,
			type VARCHAR(255) DEFAULT 'rx',
			message TEXT,
			created DATETIME
		);
	");
}

if (! empty($_POST['From'])) {
	$type = twilio_log();
	$comment = array(
		'type' => $type,
		'phone' => $_POST['From']
	);
	if ($type == 'sms') {
		$comment['twilio_id'] = $_POST['SmsSid'];
		$comment['message'] = $_POST['Body'];
		$comment = twilio_add_media($comment);
		twilio_sms_response('Thank you for your Public Comment! Your comment will appear on our website, onbehalfof.life, and will be delivered to the US EPA headquarters on June 15.');
		twilio_create_comment($comment);
	} else if ($type == 'ring') {
		twilio_ring_response('Please leave your public comment for the US EPA on behalf of all life. Press pound when you are done.');
	} else if ($type == 'recording') {
		$audio = date('Ymd-His-') . substr($_POST['From'], -4, 4) . '.mp3';
		$filename = __DIR__ . "/twilio/$audio";
		$comment['audio'] = $audio;
		$comment['duration'] = intval($_POST['RecordingDuration']);
		$comment['twilio_id'] = $_POST['CallSid'];
		twilio_save_recording($filename, 'Thank you for your Public Comment. Your comment will appear on our website, on behalf of dot life and will be delivered to the US EPA headquarters on June 15. Goodbye.');
		twilio_create_comment($comment);
	}
	exit;
}

function twilio_add_media($comment) {
	$num = 0;
	$dir = __DIR__;
	$msg = $comment['message'];
	while (! empty($_POST["MediaUrl$num"])) {

		$type = $_POST["MediaContentType$num"];
		if ($type == 'image/jpeg') {
			$ext = 'jpg';
		} else {
			continue;
		}

		curl_setopt($ch, CURLOPT_URL, $_POST["MediaUrl$num"]);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$media = curl_exec($ch);
		curl_close($ch);

		$when = date('Ymd-His');
		$who = substr($_POST['From'], -4, 4);
		$filename = "$when-$who-$num.$ext";
		if ($ext == 'jpg') {
			$msg .= "\n<img src=\"/twilio/$filename\">";
		}
		$num++;
	}
	$comment['message'] = $msg;
	return $comment;
}

function twilio_sms_response($response) {
	header('Content-Type: application/xml');
	echo <<<END
<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Message>$response</Message>
</Response>
END;
}

function twilio_ring_response($response) {
	//$url = $_SERVER['REQUEST_URI'];
	//  recordingStatusCallback="$url"
	header('Content-Type: application/xml');
	echo <<<END
<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Say voice="alice">$response</Say>
	<Record finishOnKey="#"></Record>
</Response>
END;
}

function twilio_save_recording($filename, $response) {
	$url = $_POST['RecordingUrl'] . '.mp3';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$mp3 = curl_exec($ch);
	curl_close($ch);
	file_put_contents($filename, $mp3);

	header('Content-Type: application/xml');
	echo <<<END
<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Say voice="alice">$response</Say>
</Response>
END;
}

function twilio_log() {
	global $db;
	$message = json_encode($_POST, JSON_PRETTY_PRINT);
	if (! empty($_POST['SmsSid'])) {
		$type = 'sms';
	} else if (! empty($_POST['CallStatus']) &&
	           $_POST['CallStatus'] == 'ringing') {
		$type = 'ring';
	} else if (! empty($_POST['RecordingUrl'])) {
		$type = 'recording';
	}
	$now = date('Y-m-d H:i:s');
	$query = $db->prepare("
		INSERT INTO twilio
		(type, message, created)
		VALUES (?, ?, ?)
	");
	$query->execute(array($type, $message, $now));
	return $type;
}

function twilio_create_comment($values) {
	global $db;

	$now = date('Y-m-d H:i:s');
	$values['created'] = $now;
	$values['updated'] = $now;

	$cols = array_keys($values);
	$cols = implode(', ', $cols);

	$placeholders = array();
	for ($i = 0; $i < count($values); $i++) {
		$placeholders[] = '?';
	}
	$placeholders = implode(', ', $placeholders);

	$values = array_values($values);

	$query = $db->prepare("
		INSERT INTO comment ($cols)
		VALUES ($placeholders)
	");
	return $query->execute($values);
}

function twilio_get_comments() {
	global $db;
	$query = $db->query("
		SELECT *
		FROM comment
		WHERE visible = 1
		ORDER BY featured DESC, created DESC
	");
	$comments = array();
	if (! $query) {
		return $comments;
	}
	while ($row = $query->fetchObject()) {
		$comments[] = $row;
	}
	return $comments;
}
