<?php
function show_affiliations( $thearguments ) {
	$thehtml = "";

	/*foreach($thearguments as $k => $v) {
		echo $k.$v;
	}*/
	;
	//$thehtml .= "<pre>". in_array('path', array_values(array_keys($thearguments)))) . "</pre>";
	if (empty($thearguments)) {
		$path = PATH_JSON;
	} /*elseif (in_array("path", array_keys($thearguments)) {
		$path = PATH_JSON;
	} */elseif ($thearguments['path'] == '') {
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
	
	//echo $project_data;
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

	return $thehtml;


	/*echo "<div class='elementor-element elementor-widget elementor-widget-text-editor'>";
		echo "<div class='elementor-text-editor elementor-clearfix'>";
			echo "<div class='elementor-text-editor elementor-clearfix'>";
				echo "<h2>Full Member Institutions</h2>";
				echo "<ul>";
					foreach ($project_data['full_members'] as $x) {
							echo "<li>" . $x['title'] . "</li>";
						}
				echo "</ul>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	
	echo "<div class='elementor-element elementor-widget elementor-widget-text-editor'>";
		echo "<div class='elementor-text-editor elementor-clearfix'>";
			echo "<div class='elementor-text-editor elementor-clearfix'>";
				echo "<h2>Associate Member Institutions</h2>";
				echo "<ul>";
					foreach ($project_data['associate_members'] as $x) {
						echo "<li>".$x['title']."</li>";
					}
				echo "</ul>";
			echo "</div>";
		echo "</div>";
	echo "</div>";


	echo "<div class='elementor-element elementor-widget elementor-widget-text-editor'>";
		echo "<div class='elementor-text-editor elementor-clearfix'>";
			echo "<div class='elementor-text-editor elementor-clearfix'>";
				echo "<h2>Participation Groups</h2>";
					foreach ($project_data['participations'] as $x) {
						echo "<h3>".$x['title']."</h3>";
						echo "<ul>";
						foreach ($x['affiliations'] as $y) {
							echo "<li>".$y['title']."</li>";
						}
						echo "</ul>";
					}
			echo "</div>";
		echo "</div>";
	echo "</div>";

	echo "<div class='elementor-element elementor-widget elementor-widget-text-editor'>";
		echo "<div class='elementor-text-editor elementor-clearfix'>";
			echo "<div class='elementor-text-editor elementor-clearfix'>";
				echo "<p>Last modified: " . $project_data['modified'] . "</p>";
			echo "</div>";
		echo "</div>";
	echo "</div>"; */

	//return $thehtml;
}
?>