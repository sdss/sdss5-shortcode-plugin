<?php
function show_ac( $thearguments ) {
	if (WHICH_PHASE == 'sdss5') {
		$path = PATH_JSON;
	elseif (WHICH_PHASE == 'sdss4') {
		return "No science advisory council for SDSS4";
	} else {
		$path = PATH_JSON;
	}

	$ac_data_json = @file_get_contents(  $path . 'sdss5-ac.json' );
	
	$ac_data = json_decode( $ac_data_json, true );

	$thehtml .= "<h2>Members</h2>";
	$thehtml .= "<ul>";
	foreach ($ac_data as $x) {
		$thehtml .= "<li>".$x['name']."</li>";
	}
	$thehtml .= "</ul>";
	
	return $thehtml;
}
?>