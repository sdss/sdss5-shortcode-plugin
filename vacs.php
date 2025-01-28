<?php

function show_vacs( $thearguments ) {

	$sdss_debug = WP_DEBUG;
	//$sdss_debug = false;
	$current_dr_number = CURRENT_DR;
	$current_dr = "DR".strval($current_dr_number);

	if (WP_DEBUG == 1) {
		$vacs_data_json = @file_get_contents(  PATH_JSON_VACS . 'vacs-testng.json' );
	} else {
		$vacs_data_json = @file_get_contents(  PATH_JSON_VACS . 'vacs.json' );
	}
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

	$nvacs = count($all_vacs);
	
	
	$thehtml = "";

	if ( $single_id == -1 ) {
		$thehtml = show_all_vacs($all_vacs, $thehtml, $vacs_data['modified'], $current_dr, $sdss_debug);
	} else {
		$thehtml = show_single_vac($all_vacs[$single_id], $thehtml, $current_dr, $sdss_debug);
	}

	return $thehtml;
}

function show_all_vacs($all_vacs, $thehtml, $last_modified, $current_dr, $sdss_debug) {

	$thethml = "";
	$thehtml .= "<div class='vaclist'>";

	foreach ($all_vacs as $id => $thisvac) {
		
		$thisvactags = get_vac_tags($thisvac);

		$thehtml .= "<div class='vac ";
		foreach ($thisvactags as $tagi) {
			$thehtml .= $tagi ." ";
		}
		$thehtml .= "'>";
		
		$thehtml .= "<h2>";
		$thehtml .= "<a href='".$_SERVER['REQUEST_URI']."?vac_id=".$thisvac['id']."'>";

		if ($sdss_debug) {
			if (in_array($current_dr, $thisvac['data_releases'])) {
				$thehtml .= $thisvac['identifier'].": ";	
			} else {
				$thehtml .= $id.": ";
			}
		}
		$thehtml .= $thisvac['title'];
		$thehtml .= "</a></h2>";


		// TAGS
		$thehtml .= "<div class='vac-tags'>";

		// category
		$thehtml .= "<div class='vac-tag vac-category'>".$thisvac['category']."</div>";

		
		// surveys
		$thehtml .= "<div class='vac-tag vac-survey'>".$thisvac['survey']."</div>";

		// object type
		foreach ($thisvac['object_classes'] as $this_obj_type) {
			$thehtml .= "<div class='vac-tag vac-object'>".$this_obj_type."</div>";
		}

		// has cas?
		if ($thisvac['includes_cas']) {
			$thehtml .= "<div class='vac-tag vac-cas-marvin'>CAS</div>";
		}

		// has marvin?
		if ($thisvac['includes_marvin']) {
			$thehtml .= "<div class='vac-tag vac-cas-marvin'>Marvin</div>";
		}

		// data releases
		$dr_tags_display = '';
		foreach (array_reverse($thisvac['data_releases']) as $this_dr) {
			$dr_tags_display .= ($this_dr == $current_dr) ? "<span class='vac-tag vac-dr vac-dr-latest'>".$this_dr."</span>" : "<span class='vac-tag vac-dr'>".$this_dr."</span>";
		}
		$thehtml .= $dr_tags_display;

		$thehtml .= "</div>";  // closing class vac-tags

		// AUTHORS
		$these_authors = explode( ",", $thisvac['authors']);
		switch ( count($these_authors) ) {
			case 0:
				$author_display_str = "";
				break;
			case 1:
				$author_display_str = $these_authors[0];
				break;
			case 2: 
				if (preg_match('/\s(and)|&\s/', $these_authors[1])) {
					$author_display_str =  $these_authors[0] . $these_authors[1];
				} else {
					$author_display_str =  str_replace(",", "", $these_authors[0]) . " and " . $these_authors[1];
				}
				break;
			default:
				$author_display_str = $these_authors[0] . " et&nbsp;al.";
		}
		// special case fix for catalog 90 (HI-MaNGA DR3)
		if ($id == 90) {
			$author_display_str = "David Stark, Karen Masters, and the rest of the HI-MaNGA team";
		}
		$thehtml .= "<div class='vac-authors'>" . $author_display_str."</div>";
	
		// DESCRIPTION
		$thehtml .= "<div class='vac-description'>".$thisvac['description']."</div>";

		$thehtml .= "</div>"; // close vac class

		/*
		if ($cnt % 2 == 0) {
			$thehtml .= "<div class='clearfix'></div>";
		}
		$cnt = $cnt + 1; */
	}

	$thehtml .= "</div>";  // close vaclist class

	$thehtml .= "<div class='clearfix'></div><p>Value-added catalog list last modified: ".$last_modified."</p>";

	return $thehtml;
}

function show_single_vac($thisvac, $thehtml, $current_dr, $sdss_debug) {

	
	$sas_base = "https://data.sdss.org";
	//$sas_base = "https://data.sdss.org/sas/".strtolower($current_dr);

	$skysever_base = "http://skyserver.sdss.org/";

	$thehtml .= "<div class='single-vac'>";
	
	$thehtml .= "<h2>";
	//$thehtml .= "<a href='".$_SERVER['REQUEST_URI']."?vac_id=".$thisvac['id']."'>";

	if ($sdss_debug) {
		if (in_array($current_dr, $thisvac['data_releases'])) {//(!strpos($thisvac['identifier'], 'V VAC')) {
			$thehtml .= $thisvac['identifier'].": ";	
		} else {
			$thehtml .= $thisvac['id'].": ";
		}
	}
	$thehtml .= $thisvac['title'];
	//$thehtml .= "</a></h2>";
	$thehtml .= "</h2>";


	// TAGS
	$thehtml .= "<div class='vac-tags'>";

	// category
	$thehtml .= "<div class='vac-tag vac-category'>".$thisvac['category']."</div>";

	/// surveys
	$thehtml .= "<div class='vac-tag vac-survey'>".$thisvac['survey']."</div>";

	// object type
	foreach ($thisvac['object_classes'] as $this_obj_type) {
		$thehtml .= "<div class='vac-tag vac-object'>".$this_obj_type."</div>";
	}

	// has cas?
	if ($thisvac['includes_cas']) {
		$thehtml .= "<div class='vac-tag vac-cas-marvin vac-table'>CAS</div>";
	}

	// has marvin?
	if ($thisvac['includes_marvin']) {
		$thehtml .= "<div class='vac-tag vac-cas-marvin'>Marvin</div>";
	}
	
	/// data releases
	$dr_tags_display = '';
	foreach (array_reverse($thisvac['data_releases']) as $this_dr) {
		$dr_tags_display .= ($this_dr == $current_dr) ? "<span class='vac-tag vac-dr vac-dr-latest'>".$this_dr."</span>" : "<span class='vac-tag vac-dr'>".$this_dr."</span>";
	}
	$thehtml .= $dr_tags_display;

	$thehtml .= "</div>";  // closing class vac-tags


	// DESCRIPTION
	$thehtml .= "<div class='single-vac-description'><p>".$thisvac['description']."</p></div>";


	// AUTHORS (list them all)
	$thehtml .= "<div class='vac-authors-full'>".$thisvac['authors']."</div>";
	
	// SAS link
	$thehtml .= "<div class='vac-box vac-sas'>Location on SAS: ";
	$thehtml .= "<a href='".$sas_base.$thisvac['sas_folder']."' target='_blank'>";
	$thehtml .= $sas_base.$thisvac['sas_folder'];
	$thehtml .= "</a>";
	$thehtml .= "</div>";

	// Data model link
	$thehtml .= "<div class='vac-box vac-datamodel'>Data model: ";
	$thehtml .= "<a href='".$thisvac['datamodel_url']."' target='_blank'>";
	$thehtml .= $thisvac['datamodel_url'];
	$thehtml .= "</a>";
	$thehtml .= "</div>";

	// www URL
	if ($thisvac['www_url'] != '') {
		$thehtml .= "<div class='vac-www'>This VAC is described in full at <br /><a href='".$thisvac['www_url']."'>";
		$thehtml .= str_replace('/', '/<wbr>', str_replace('-', '&#8209;', $thisvac['www_url']));  // replace dash with non-breaking dash and slash with breaking slash
		//$thehtml .= str_replace('-', '&#8209;', $thisvac['www_url']);  // replace dash with non-breaking dash
		$thehtml .= "</a></div>";
	}


	/// CAS information (if any)
	// has cas?
	if ($thisvac['includes_cas']) {
		$thehtml .= "<div class='vac-cas-info'>";

		$thehtml .= "<div class='vac-cas-info-title'>";   // contains both "Catalog Data" title and list of data releases
		$thehtml .= "<h3>Catalog Data</h3>";

		$thehtml .= "<span class='vac-tags-cas-info'>";
		/// data releases
		$dr_tags_display = '';
		foreach (array_reverse($thisvac['data_releases']) as $this_dr) {
			$dr_tags_display .= ($this_dr == $current_dr) ? "<span class='vac-tag vac-dr vac-dr-latest'>".$this_dr."</span>" : "<span class='vac-tag vac-dr'>".$this_dr."</span>";
		}
		$thehtml .= $dr_tags_display;
		$thehtml .= "</span>";  // close vac-tags-cas-info class
		
		$thehtml .= "</div>";  // close vac-cas-info-title class


		$most_recent_dr = array_reverse($thisvac['data_releases'])[0];  // most recent data release in which this data appears
		
		// Links to CAS tables
		if ($thisvac['cas_table'] != '') {
			$thehtml .= "This dataset appears in ".$most_recent_dr." SkyServer and CasJobs in the following tables:";
			$thehtml .= "<ul>";
			foreach ($thisvac['cas_table'] as $this_cas_table) {
				if (intval(substr($most_recent_dr, 2)) >= 17) {
					$schema_link = $skysever_base.strtolower($most_recent_dr)."/MoreTools/browser?table=".$this_cas_table;
				} else if ((intval(substr($most_recent_dr, 2)) >= 9) && (intval(substr($most_recent_dr, 2)) <= 16)) {
					$schema_link = $skysever_base.strtolower($most_recent_dr)."/en/help/browser/browser.aspx#&&history=description+".$this_cas_table."+U";
				} else {
					$schema_link = $skysever_base.strtolower(substr($most_recent_dr, 2));
				}
				$thehtml .= "<li><a href='".$schema_link."' target='_blank'>".$this_cas_table."</a></li>";
			}
			$thehtml .= "</ul>";
		}

		/* Links to tables that are joinable */
		if ($thisvac['cas_join'] != '') {
			$thehtml .= "This dataset's catalog data can be joined to the following tables:";
			$thehtml .= "<ul>";
			foreach ($thisvac['cas_join'] as $this_cas_join) {
				if (intval(substr($most_recent_dr, 2)) >= 17) {
					$schema_link = $skysever_base.strtolower($most_recent_dr)."/MoreTools/browser?table=".str_replace('-','_',$this_cas_join);
				} else if ((intval(substr($most_recent_dr, 2)) >= 9) && (intval(substr($most_recent_dr, 2)) <= 16)) {
					$schema_link = $skysever_base.strtolower($most_recent_dr)."/en/help/browser/browser.aspx##history=description+".str_replace('-','_',$this_cas_join)."+U";
				}	else {
					$schema_link = $skysever_base.strtolower(substr($most_recent_dr, 2));
				}
				$thehtml .= "<li><a href='".$schema_link."' target='_blank'>".str_replace('-','_',$this_cas_join)."</a></li>";
			}
			$thehtml .= "</ul>";
		}
		$thehtml .= "</div>"; // end of vac-cas-info class
	}


	/// ABSTRACT
	$thehtml .= "<h3>Abstract</h3>";
	$thehtml .= "<p class='single-vac-abstract'>".$thisvac['abstract']."</p>";

	// PUBLICATIONS (if any)
	/*
	if ($thisvac['publication_ids'] != "") {
		$thehtml .= "<div class='vac-pubs'>";
		$thehtml .= "<h3>Publications</h3>";
		$thehtml .= get_publications_text($thisvac['publication_ids'], $sdss_debug);
		$thehtml .= "</div>";
	} */

		$thehtml .= "<p><strong>Catalog last modified:</strong> ".$thisvac['modified']."</p>";

		$thehtml .= "<button class='vac-back-to-list'><a href='".explode('?',$_SERVER['REQUEST_URI'])[0]."'>"."Back to list of value-added catalogs"."</a></button>";

	$thehtml .= "</div>"; // end class single-vac
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

function get_publications_text($pub_ids_array, $sdss_debug) {

	$publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
    $publications_data = json_decode( $publications_data_json, true );


	//echo PATH_JSON_SDSS4;


	$sdss4_publications_data_json = @file_get_contents(  PATH_JSON_SDSS4 . 'publications.json' );
    $sdss4_publications_data = json_decode( $sdss4_publications_data_json, true );

	$pubhtml = "<ul class='fa-ul'>";

	foreach ($pub_ids_array as $thispubid) {
		$found_in_sdss5 = false;

		foreach ($publications_data['publications'] as $thispub) {
			if (intval($thispub['publication_id']) == $thispubid) {
				$pubdata = $thispub;
				$found_in_sdss5 = true;
				break;
			}  
		}

		$found_in_sdss4 = false;
		if (!$found_in_sdss5) {
			foreach ($sdss4_publications_data['publications'] as $this_sdss4_pub) {
				if (intval($this_sdss4_pub['publication_id']) == $thispubid) {
				$found_in_sdss4 = true;	
				$pubdata = $this_sdss4_pub;
					break;
				}
			}
		}

		if ($found_in_sdss5 | $found_in_sdss4) {
			$dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
							(( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
    						(( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false )
						);

			$pubhtml .= "<li><i class='fa-li fa fa-book'></i>";

			if ( $dflt_url ) $pubhtml .= "<a target='_blank' href='$dflt_url' >";
			$pubhtml .= "<strong>" . $pubdata[ 'title' ] . "</strong>";
			if ( $dflt_url ) $pubhtml .= "</a>";
			$pubhtml .= '<br />' . $pubdata[ 'authors' ] .  '. ' ;
			$pubhtml .= "</li>";
			if ( $pubdata[ 'journal_reference' ]) {
						$pubhtml .= $pubdata[ 'journal_reference' ];
					} else {
						$pubhtml .= '<em>' . $pubdata[ 'status' ] . '</em>';
					}
			if ( !empty($pubdata[ 'adsabs' ] ) ) $pubhtml .= "; <a href='" . $pubdata[ 'adsabs_url' ] . "' target='_blank'>adsabs:" . $pubdata[ 'adsabs' ] . "</a>";
			if ( !empty($pubdata[ 'doi' ] ))  $pubhtml .= "; <a href='" . $pubdata[ 'doi_url' ] . "' target='_blank'>doi:" . $pubdata[ 'doi' ] . "</a>";
			if ( !empty($pubdata[ 'arxiv_url' ] ) ) $pubhtml .= "; <a href='" . $pubdata[ 'arxiv_url' ] . "' target='_blank'>arXiv:" . $pubdata[ 'arxiv' ] . "</a>";
			$pubhtml .= '</li>';


			$pubhtml .= "</ul>";  // end of list of VAC publications
		} else {
			if ($sdss_debug) {
				$pubhtml .= "DEBUG MODE: PUBLICATION ID ".$thispubid." NOT FOUND!";
			} 
		}
	} 	

	return $pubhtml;
}

function get_vac_tags($thisvac) {

	$thetags = array();
	if ($thisvac['includes_cas']) {
		array_push($thetags, 'vac-tag-cas-yes');
	} else {
		array_push($thetags, 'vac-tag-cas-no');
	}
	if ($thisvac['includes_marvin']) {
		array_push($thetags, 'vac-tag-marvin-yes');
	} else {
		array_push($thetags, 'vac-tag-marvin-no');
	}	foreach ($thisvac['data_releases'] as $this_vac_dr_i) {
		array_push($thetags, 'vac-tag-'.strtolower($this_vac_dr_i));
	}
	//foreach ($thisvac['survey'] as $this_vac_survey_i) {
	array_push($thetags, 'vac-tag-'.strtolower($thisvac['survey']));
	//}
	foreach ($thisvac['object_classes'] as $this_vac_obj_i) {
		array_push($thetags, 'vac-tag-'.strtolower($this_vac_obj_i));
	}
	//print_r($thetags);
	return $thetags;
}