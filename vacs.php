<?php

function show_vacs( $thearguments ) {

	//global $debug;
	$debug = ( defined( 'WP_DEBUG' ) && ( WP_DEBUG ) );
	$single_id = get_query_var( 'vac_id' , '' );

	$vacs_data_json = @file_get_contents(  PATH_JSON_VACS . 'vacs.json' );
	$vacs_data = json_decode( $vacs_data_json, true );

	$all_vacs = array();
	foreach ($vacs_data['vacs'] as $this_vac) {
		$all_vacs[intval($this_vac['id'])] = $this_vac;
	}
	krsort($all_vacs);

	$thehtml = "";


	if ( empty( $single_id ) ) {
		$thehtml = show_all_vacs($all_vacs, $thehtml, $debug);
	} else {
		show_single_vac($all_vacs);
	}

	$thehtml .= "<p>Last modified: ".$vacs_data['modified']."</p>";	
	return $thehtml;
}

function show_all_vacs($all_vacs, $thehtml, $debug) {
	foreach ($all_vacs as $id => $thisvac) {
		$thehtml .= "<h2>";
		if ($debug) {
			$thehtml .= $id.": ";
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

		$thehtml .= "<hr />";
	}

	return $thehtml;
}


?>