<?php

function show_vacs( $thearguments ) {

	//global $debug;
	$debug = ( defined( 'WP_DEBUG' ) && ( WP_DEBUG ) );
	$current_dr_number = 18;
	$current_dr = "DR".strval($current_dr_number);


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
		$thehtml = show_all_vacs($all_vacs, $thehtml, $vacs_data['modified'], $current_dr, $debug);
	} else {
		$thehtml = show_single_vac($all_vacs[$single_id], $thehtml, $current_dr, $debug);
	}

	return $thehtml;
}

function show_all_vacs($all_vacs, $thehtml, $last_modified, $current_dr, $debug) {

	$cnt = 1;
	$thethml = "";
	$thehtml .= "<div class='vaclist'>";

	foreach ($all_vacs as $id => $thisvac) {
		
		if ($cnt % 2 == 1) {
			$side = 'left';
		} else {
			$side = 'right';
		}

		$thehtml .= "<div class='vac vac-".$side."'>";
		$thehtml .= "<h2>";
		$thehtml .= "<a href='".$_SERVER['REQUEST_URI']."?vac_id=".$thisvac['id']."'>";

		if ($debug) {
			if (in_array($current_dr, $thisvac['data_releases'])) {//(!strpos($thisvac['identifier'], 'V VAC')) {
				$thehtml .= $thisvac['identifier'].": ";	
			} else {
				$thehtml .= $id.": ";
			}
		}
		$thehtml .= $thisvac['title'];
		$thehtml .= "</a></h2>";


		// TAGS
		$thehtml .= "<div class='vac-tags'>";

		$thehtml .= "<div class='vac-tags-row'>";

		// category
		$thehtml .= "<div class='vac-tag vac-category'>".$thisvac['category']."</div>";

		/// surveys
		$thehtml .= "<div class='vac-tag vac-survey'>".$thisvac['survey']."</div>";

		$thehtml .= "</div>"; // close this row of vac-tags


		$thehtml .= "<div class='vac-tags-row'>";
		


		

		// object type
		foreach ($thisvac['object_classes'] as $this_obj_type) {
			$thehtml .= "<div class='vac-tag vac-object'>".$this_obj_type."</div>";
		}

/// has cas?
		if ($thisvac['includes_cas']) {
			$thehtml .= "<div class='vac-tag vac-cas-marvin'>CAS</div>";
/*			foreach ($vac['cas_table'] as $this_cas_table) {
				$thehtml .= "<a href='http://skyserver.sdss.org/dr17/MoreTools/browser' target='_blank' class='vac-tag vac-table'>";
				*/
		}
		//echo $vac_params_displayable['object_type_display'];
		/*
	if ($vac['includes_cas']) {
	//if ($vac['cas_table'] <> "") {
		foreach ($vac['cas_table'] as $this_cas_table) {
//			$vac_parsed['cas_link_text'] .= "<a href='".$skyserver_root."en/help/browser/browser.aspx#&&history=description+".$this_cas_table."+U' target='_blank'>";
//				$vac_parsed['cas_link_text'] .= "<div class='vac-tag vac-table'>";
//					$vac_parsed['cas_link_text'] .= "CAS: ";
//					$vac_parsed['cas_link_text'] .= $this_cas_table;
//				$vac_parsed['cas_link_text'] .= "</div>";
//			$vac_parsed['cas_link_text'] .= "</a>";
			$vac_parsed['cas_link_text'] .= "<a href='http://skyserver.sdss.org/dr17/MoreTools/browser' target='_blank' class='vac-tag vac-table'>";
			$vac_parsed['cas_link_text'] .= "<div>";
			$vac_parsed['cas_link_text'] .= "<span style='font-weight:normal;color:#ffffff;'>";
			$vac_parsed['cas_link_text'] .= "CAS (click, then search in Schema Browser)";
			$vac_parsed['cas_link_text'] .= "</span>";
			$vac_parsed['cas_link_text'] .= "<br />";
			$vac_parsed['cas_link_text'] .= $this_cas_table;
			$vac_parsed['cas_link_text'] .= "</div>";
			$vac_parsed['cas_link_text'] .= "</a>";
		}
	}
	*/

		// has marvin?
		if ($thisvac['includes_marvin']) {
			$thehtml .= "<div class='vac-tag vac-cas-marvin'>Marvin</div>";
		}

		$thehtml .= "</div>"; // close this row of vac-tags

		$thehtml .= "<div class='vac-tags-row'>";
		/// data releases
		$dr_tags_display = '';
		foreach (array_reverse($thisvac['data_releases']) as $this_dr) {
			$dr_tags_display .= ($this_dr == $current_dr) ? "<span class='vac-tag vac-dr vac-dr-latest'>".$this_dr."</span>" : "<span class='vac-tag vac-dr'>".$this_dr."</span>";
		}
		$thehtml .= $dr_tags_display;
		$thehtml .= "</div>"; // close this row of vac-tags

		$thehtml .= "</div>";  // closing class vac-tags

		// AUTHORS
		$the_authors = explode( ",", $thisvac['authors']);
		switch ( count($the_authors) ) {
			case 0:
				$author_display_str = "";
				break;
			case 1:
				$author_display_str = $the_authors[0];
				break;
			case 2: 
				if (preg_match('/\s(and)|&\s/', $the_authors[1])) {
					$author_display_str =  $the_authors[0] . $the_authors[1];
				} else {
					$author_display_str =  str_replace(",", "", $the_authors[0]) . " and " . $the_authors[1];
				}
				break;
			default:
				$author_display_str = $the_authors[0] . " et&nbsp;al.";
		}
		// special case fix for catalog 90 (HI-MaNGA DR3)
		if ($id == 90) {
			$author_display_str = "David Stark, Karen Masters, and the rest of the HI-MaNGA team";
		}
		$thehtml .= "<div class='vac-authors'>" . $author_display_str."</div>";
	


		// DESCRIPTION
		$thehtml .= "<div class='vac-description'>".$thisvac['description']."</div>";


		//$thehtml .= "<p><b>WWW url:</b> ".$thisvac['www_url']."</p>";

/*		$thehtml .= "<p><strong>Survey:</strong> ".$thisvac['survey']."</p>";
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
		*/
		/*
		$thehtml .= "<h3>Abstract</h3>";
		$thehtml .= "<p>".$thisvac['abstract']."</p>";

		if ($thisvac['publication_ids'] != "") {
			$thehtml .= "<p><strong>Publication IDs:</strong> ";
			foreach ($thisvac['publication_ids'] as $thispub) {
				$thehtml .= $thispub.", ";
			}
			$thehtml .= "</p>";
		}
		*/
		
		//$thehtml .= "<p><strong>Catalog last modified:</strong> ".$thisvac['modified']."</p>";

		$thehtml .= "</div>"; // close vac class

		//$thehtml .= "<hr />";


		if ($cnt % 2 == 0) {
			$thehtml .= "<div class='clearfix'></div>";
		}
		$cnt = $cnt + 1;
	}

	$thehtml .= "</div>";  // close vaclist class

	$thehtml .= "<div class='clearfix'></div><p>Value-added catalog list last modified: ".$last_modified."</p>";

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