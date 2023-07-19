<?php
function show_affiliations( $thearguments ) {
	$thehtml = "";

	if (WHICH_PHASE == 'sdss5') {
		if (empty($thearguments)) {
			$path = PATH_JSON;
		} elseif ($thearguments['path'] == '') {
			$path = PATH_JSON;
		} else {
			$path = $thearguments['path'];
		}
	//	$path = $thearguments['path'];
	
		// If the path supplied by the user doesn't come with a trailing slash, add the trailing slash

		if ( $path[-1] != '/') {
			$path = $path . '/';
		}
	
		$project_data_json = @file_get_contents(  $path . 'project.json' );
	
		$project_data = json_decode( $project_data_json, true );
	
		//$thehtml .= $project_data;
		//print_r($project_data);
	
		$thehtml .=  "<h3>Full Member Institutions</h3>";
		$thehtml .= "<ul>";
		foreach ($project_data['full_members'] as $x) {
			$thehtml .= "<li>" . $x['title'] . "</li>";
		}
		$thehtml .= "</ul>";

		$thehtml .=  "<h3>Associate Member Institutions</h3>";
		$thehtml .= "<ul>";
		foreach ($project_data['associate_members'] as $x) {
			$thehtml .= "<li>" . $x['title'] . "</li>";
		}
		$thehtml .= "</ul>";

		$thehtml .=  "<h3>Participation Groups</h3>";

		foreach ($project_data['participations'] as $x) {
			$thehtml .= "<h4>".$x['title']."</h4>";
			$thehtml .= "<ul>";
			foreach ($x['affiliations'] as $y) {
				$thehtml .= "<li>".$y['title']."</li>";
			}
			$thehtml .= "</ul>";
		}
		$thehtml .= "<p>Last modified: " . $project_data['modified'] . "</p>";
	} elseif (WHICH_PHASE == 'sdss4') {
		$project_data_json = @file_get_contents(  PATH_JSON . 'project.json' );
		$project_data = json_decode( $project_data_json, true );

		$thehtml .= "<div class='sdss-wrapper'>";
		$thehtml .= "<h2>Full Member Institutions</h2>";
		$thehtml .= "<ul>";
		foreach ($project_data['full_members'] as $thisrow) {
			//foreach ($this_project_data_container as $thisrow) {
			$thehtml .= ("<li>".$thisrow['title']."</li>");//$thehtml .= "<li>".$x['title']."</li>";
			}
		$thehtml .= "</ul>";

		$thehtml .= "<h2>Associate Member Institutions</h2>";
		$thehtml .= "<ul>";
		foreach ($project_data['associate_members'] as $thisrow) {
			//foreach ($this_project_data_container as $thisrow) {
			$thehtml .= ("<li>".$thisrow['title']."</li>");//$thehtml .= "<li>".$x['title']."</li>";
			}
		$thehtml .= "</ul>";

		$thehtml .= "<h2>Participation Groups</h2>";
		$thehtml .= "<ul>";

		foreach ($project_data['participations'] as $thisrow) {
			$thehtml .= "<li><strong>".$thisrow['title']."</strong><ul>";
			foreach ($thisrow['affiliations'] as $this_affiliation) {
				$thehtml .= "<li>".$this_affiliation['title']."</li>";
			}
			$thehtml .= "</ul></li>";
		}

		$thehtml .= "</ul>";

		$thehtml .= "<p>Last modified: ".$project_data['modified']."</p>";

		$thehtml .= "</div>";

	} else {
		$thehtml = 'Affiliations not found';
	}
	return $thehtml;
}
?>