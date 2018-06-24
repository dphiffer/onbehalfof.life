<?php

ini_set('display_errors', 0);
$photo = null;
$species = null;
species();

function species() {
	global $photo, $species;

	$species_list = array(
		'transparency' => array(
			48599,
			48178,
			167829,
			55757,
			58961,
			55727,
			47911,
			59029,
			53059,
			52856,
			58127,
			52927,
			119792
		)
	);

	$species_arg = null;
	$list = null;

	if (! empty($_GET['species_list'])) {
		$species_arg = $_GET['species_list'];
	}

	if (! empty($species_arg) &&
		! empty($species_list[$species_arg])) {
		$list = array();
		foreach ($species_list[$species_arg] as $species_id) {
			$json = file_get_contents("species/$species_id.json");
			$list[] = json_decode($json);
		}
	}

	if (empty($_GET['id'])) {
		if (! empty($species_arg) &&
		    ! empty($species_list[$species_arg])) {
			$ids = $species_list[$species_arg];
			$index = rand(0, count($ids) - 1);
			$id = $ids[$index];
		} else if (! empty($_GET['reload'])) {
			$json = file_get_contents('species.json');
			$species = json_decode($json, 'as hash');
			$max = count($species['ids']);
			$index = rand(0, $max - 1);
			$id = $species['ids'][$index];
		} else {
			$files = glob('species/*.json');
			$ids = array();
			foreach ($files as $file) {
				if (preg_match('/(\d+)\.json/', $file, $matches)) {
					$ids[] = $matches[1];
				}
			}
			$index = rand(0, count($ids) - 1);
			$id = $ids[$index];
		}
	} else {
		$id = intval($_GET['id']);
	}

	if (file_exists("species/$id.jpg") &&
	    file_exists("species/$id.json")) {
		$photo = "species/$id.jpg";
		$species_json = file_get_contents("species/$id.json");
		$species = json_decode($species_json);
		list($width, $height, $type, $attr) = getimagesize("species/$id.jpg");
		if ($width < 500) {
			unset($_GET['id']);
			return species();
		}
	} else {
		$json = file_get_contents("https://api.inaturalist.org/v1/taxa/$id");
		$rsp = json_decode($json, 'as hash');
		if ($rsp && $rsp['results'] && count($rsp['results']) > 0) {
			if (! empty($rsp['results'][0]['default_photo'])) {
			    $url = $rsp['results'][0]['default_photo']['medium_url'];
				$photo_attr = $rsp['results'][0]['default_photo']['attribution'];
				$photo_url = $rsp['results'][0]['default_photo']['url'];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
				file_put_contents("species/$id.jpg", $data);
				list($width, $height, $type, $attr) = getimagesize("species/$id.jpg");
				if ($width < 500) {
					unset($_GET['id']);
					return species();
				}
				$photo = "species/$id.jpg";
			} else {
				return species();
			}
			$species = array(
				'id' => $id,
				'latin' => $rsp['results'][0]['name'],
				'common' => $rsp['results'][0]['preferred_common_name']
			);
			if (! empty($photo_attr)) {
				$species['photo_attr'] = $photo_attr;
			}
			if (! empty($photo_url)) {
				$species['photo_url'] = $photo_url;
			}
			$json = json_encode($species, JSON_PRETTY_PRINT);
			file_put_contents("species/$id.json", $json);
		}
	}

	$rsp = array(
		'species' => $species,
		'photo' => $photo
	);

	if (! empty($list)) {
		$rsp['list'] = $list;
	}

	header('Content-Type: application/json');
	echo json_encode($rsp);
}
