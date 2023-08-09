<?php
function show_ac( $thearguments ) {

	$searchval = "Xi'aowei Liu"; // remove this name from display

	if (WHICH_PHASE == 'sdss5') {
		$path = PATH_JSON;
	} elseif (WHICH_PHASE == 'sdss4') {
		return "No science advisory council for SDSS4";
	} else {
		$path = PATH_JSON;
	}

	$ac_data_json = @file_get_contents(  $path . 'sdss5-ac.json' );
	$ac_data = json_decode( $ac_data_json, true );

	// Extract member names from JSON
	$thenames = array();
	foreach ($ac_data as $thisrow) {
		array_push($thenames, $thisrow['name']);
	}

	$thehtml = "";
	$thehtml .= "<h2>Members</h2>";
	$thehtml .= "<ul>";
	foreach ($thenames as $x) {
		$thehtml .= "<li>".$x."</li>";
	}
	$thehtml .= "</ul>";
	
	return $thehtml;
}
?>