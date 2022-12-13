<?php

function show_vacs( $thearguments ) {

	//global $debug;
	$debug = ( defined( 'WP_DEBUG' ) && ( WP_DEBUG ) );
	



	$vacs_data_json = @file_get_contents(  PATH_JSON_VACS . 'vacs.json' );
	$vacs_data = json_decode( $vacs_data_json, true );
	
	if ((strpos($_SERVER['REQUEST_URI'], 'vac_id')) > 0) {
		$single_id = get_single_id_from_url($_SERVER['REQUEST_URI']);
	} else {
		$single_id = -1;
	}

	$all_vacs = array();
	foreach ($vacs_data['vacs'] as $this_vac) {
		$all_vacs[intval($this_vac['id'])] = $this_vac;
	}
	krsort($all_vacs);

	
	$thehtml = "";

	if ( $single_id == -1 ) {
		$thehtml = show_all_vacs($all_vacs, $thehtml, $vacs_data['modified'], $debug);
	} else {
		$thehtml = show_single_vac($all_vacs[$single_id], $thehtml, $debug);
	}

	return $thehtml;
}

function show_all_vacs($all_vacs, $thehtml, $last_modified, $debug) {
	foreach ($all_vacs as $id => $thisvac) {
		
		$thehtml .= "<h2>";
		$thehtml .= "<a href='".$_SERVER['REQUEST_URI']."?vac_id=".$thisvac['id']."'>";

		if ($debug) {
			$thehtml .= $id.": ";
		}
		$thehtml .= $thisvac['title'];
		if ($debug) {
			$thehtml .= " (".$thisvac['identifier'].")";
		}
		$thehtml .= "</a></h2>";

		$thehtml .= "<p><strong>Description:</strong> ".$thisvac['description']."</p>";
		
		$thehtml .= "<p><strong>Authors:</strong> <em>".$thisvac['authors']."</em></p>";

		$thehtml .= "<p><b>WWW url:</b> ".$thisvac['www_url']."</p>";

		$thehtml .= "<p><strong>Survey:</strong> ".$thisvac['survey']."</p>";
		$thehtml .= "<p><strong>Category:</strong> ".$thisvac['category']."</p>";

		$thehtml .= "<p><strong>Data releases:</strong> ";
		foreach ($thisvac['data_releases'] as $thisdr) {
			$thehtml .= $thisdr.", ";
		}
		$thehtml .= "</p>";

		$thehtml .= "<p><strong>Object classes:</strong> ";
		foreach ($thisvac['object_classes'] as $thisdr) {
			$thehtml .= $thisdr.", ";
		}
		$thehtml .= "</p>";

		$thehtml .= "<p>Data model URL: ".$thisvac['datamodel_url']."</p>";
		$thehtml .= "<p>SAS folder: ".$thisvac['sas_folder']."</p>";

		if ($thisvac['includes_marvin']) {
			$thehtml .= "<p>Included in Marvin!</p>";
		}

		if ($thisvac['includes_cas']) {
			$thehtml .= "<p>Included in CAS:</p>";
			$thehtml .= "<ul>";
			$thehtml .= "<li>CAS table(s): ";
			foreach ($thisvac['cas_table'] as $this_cas_table) {
				$thehtml .= $this_cas_table.", ";
			}
			$thehtml .= "</li>";
			//if (count($thisvac['cas_join']) > 0) {
			if ($thisvac['cas_join'] != "") {
				$thehtml .= "<li>CAS join(s): ";
				foreach ($thisvac['cas_join'] as $this_cas_join) {
					$thehtml .= $this_cas_join.", ";
				}
				$thehtml .= "</li>";
			}
			$thehtml .= "</ul>";
		}

		$thehtml .= "<h3>Abstract</h3>";
		$thehtml .= "<p>".$thisvac['abstract']."</p>";

		if ($thisvac['publication_ids'] != "") {
			$thehtml .= "<p><strong>Publication IDs:</strong> ";
			foreach ($thisvac['publication_ids'] as $thispub) {
				$thehtml .= $thispub.", ";
			}
			$thehtml .= "</p>";
		}

		$thehtml .= "<p><strong>Catalog last modified:</strong> ".$thisvac['modified']."</p>";

		$thehtml .= "<hr />";
	}

	$thehtml .= "<p>Value-added catalog list last modified: ".$last_modified."</p>";

	return $thehtml;
}

function show_single_vac($thisvac, $thehtml, $debug) {
		
		$thehtml .= "<h2>";
		if ($debug) {
			$thehtml .= $thisvac['id'].": ";
		}
		$thehtml .= $thisvac['title'];
		if ($debug) {
			$thehtml .= " (".$thisvac['identifier'].")";
		}
		$thehtml .= "</h2>";

		$thehtml .= "<p><strong>Description:</strong> ".$thisvac['description']."</p>";
		
		$thehtml .= "<p><strong>Authors:</strong> <em>".$thisvac['authors']."</em></p>";

		$thehtml .= "<p><b>WWW url:</b> ".$thisvac['www_url']."</p>";

		$thehtml .= "<p><strong>Survey:</strong> ".$thisvac['survey']."</p>";
		$thehtml .= "<p><strong>Category:</strong> ".$thisvac['category']."</p>";

		$thehtml .= "<p><strong>Data releases:</strong> ";
		foreach ($thisvac['data_releases'] as $thisdr) {
			$thehtml .= $thisdr.", ";
		}
		$thehtml .= "</p>";

		$thehtml .= "<p><strong>Object classes:</strong> ";
		foreach ($thisvac['object_classes'] as $thisdr) {
			$thehtml .= $thisdr.", ";
		}
		$thehtml .= "</p>";

		$thehtml .= "<p>Data model URL: ".$thisvac['datamodel_url']."</p>";
		$thehtml .= "<p>SAS folder: ".$thisvac['sas_folder']."</p>";

		if ($thisvac['includes_marvin']) {
			$thehtml .= "<p>Included in Marvin!</p>";
		}

		if ($thisvac['includes_cas']) {
			$thehtml .= "<p>Included in CAS:</p>";
			$thehtml .= "<ul>";
			$thehtml .= "<li>CAS table(s): ";
			foreach ($thisvac['cas_table'] as $this_cas_table) {
				$thehtml .= $this_cas_table.", ";
			}
			$thehtml .= "</li>";
			//if (count($thisvac['cas_join']) > 0) {
			if ($thisvac['cas_join'] != "") {
				$thehtml .= "<li>CAS join(s): ";
				foreach ($thisvac['cas_join'] as $this_cas_join) {
					$thehtml .= $this_cas_join.", ";
				}
				$thehtml .= "</li>";
			}
			$thehtml .= "</ul>";
		}

		$thehtml .= "<h3>Abstract</h3>";
		$thehtml .= "<p>".$thisvac['abstract']."</p>";

		if ($thisvac['publication_ids'] != "") {
			$thehtml .= "<p><strong>Publication IDs:</strong> ";
			foreach ($thisvac['publication_ids'] as $thispub) {
				$thehtml .= $thispub.", ";
			}
			$thehtml .= "</p>";
		}
		
		$thehtml .= "<p><strong>Catalog last modified:</strong> ".$thisvac['modified']."</p>";

		$thehtml .= "<p><a href='".$_SERVER['REQUEST_URI']."'>"."Back to list of value-added catalogs"."</a></p>";

	return $thehtml;
}

function get_single_id_from_url($theuri) {
	$theuri = $_SERVER['REQUEST_URI'];
	$exploded_uri_array = explode('/', $theuri);
	$vac_id_as_url = end($exploded_uri_array);
	$exploded_vac_id_url = explode('=', $vac_id_as_url);
	
	$single_id = intval(end($exploded_vac_id_url));

	return $single_id;
}