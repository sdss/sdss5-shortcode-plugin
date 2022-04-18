<?php
function show_coco( $thearguments ) {

	$thehtml = "";

	if (empty($thearguments) | $thearguments == '') {
		$path = PATH_JSON;
	} /*elseif (in_array("path", array_keys($thearguments)) {
		$path = PATH_JSON;
	} */elseif ($thearguments['path'] == '') {
		$path = PATH_JSON;
	} else {
		$path = $thearguments['path'];
	}
	
    // If the path supplied by the user doesn't come with a trailing slash, add the trailing slash
	if ( $path[-1] != '/') {
		$path = $path . '/';
	}

	$coco_data_json = @file_get_contents( $path . 'coco.json' );
	$coco_data = json_decode( $coco_data_json, true );


	$affiliations_data_json = @file_get_contents( $path . 'affiliations.json' );
	$affiliations_data = json_decode( $affiliations_data_json, true );
	$institutions = $affiliations_data['affiliations'];
	$institution_ids = array_column($institutions, 'affiliation_id');

	//print_r($affiliations_data);

	$thehtml .= "Chair and SDSS-V Spokesperson: <strong>" . $coco_data['spokesperson']['fullname'] . "</strong> (". $institutions[array_search($coco_data['spokesperson']['affiliation_id'], $institution_ids)]['title'] . ")</p>";
	
	//print_r($coco_data['coco']);

	$thehtml .= "<h3>Current members</h3>";
	$thehtml .= "<dl>";

	foreach ($coco_data['coco'] as $x) {
/*		if ($x['affiliation_id'] != '') {
			$institution_displayer = array_search($x['affiliation_id'], $institution_ids)['title'];
		}  elseif ($x['participation_id'] != '') { 
			#print_r($x);
			$institution_displayer = '';#array_search($x['participation_id'], $institution_ids)['title'];
		} else {
			#print_r($x);
			$institution_displayer = 'not found';
		}
		echo $institution_displayer;
		#print_r($x['participation_id']);
		#echo "<br /><br />";
		*/
		$thehtml .= "<dt><strong>" . $x['fullname'] . "</strong></dt><dd>" . $x['institute'] . "</dd>";
		#$thehtml .= "<dt><strong>" . $x['fullname'] . "</strong></dt><dd>" . $x['institute'] . " (" . $institution_displayer . ")</dd>";
	}
	$thehtml .= "</dl>";

	foreach ($coco_data['lessthan3'] as $x) {
		$thehtml .= "<dt><strong>" . $x['fullname'] . "</strong></dt><dd>Associate Member Institutes with &lt;3 slots</dd>";
	}
	return $thehtml;

}

?>