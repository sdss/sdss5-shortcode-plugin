<?php


function show_publications($atts) {

    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    
    $sdss5_surveys = array('');
    $sdss4_surveys = array('SDSS-IV General', 'eBOSS', 'APOGEE-2', 'MaNGA', 'MaStar', 'SPIDERS', 'TDSS');
    $sdss3_surveys = array('BOSS', 'APOGEE', 'MARVELS');

    $wporg_atts = shortcode_atts(
		array(
            'phase' => 'sdss5',
			'type' => '',
            'by_survey' => true,
            'just_modified_time' => false,
            'show_toc' => false
		), $atts
	);

    foreach ($wporg_atts as $k => $v) {
        if (is_string($v)) {
            if (($v == 'true') || ($v == 'false')) {
                $wporg_atts[$k] = boolify($v);
            }
        }
    }


    if ($wporg_atts['phase'] == 'sdss5') {
        $publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
    }
    elseif (($wporg_atts['phase'] == 'sdss4') || ($wporg_atts['phase'] == 'sdss3')) {
        $publications_data_json = @file_get_contents(  PATH_JSON_SDSS4 . 'publications.json' );
    } else {
        $errorhtml = '<h2><font color="red">ERROR: Phase '.$wporg_atts['phase'].' not found!</font></h2>';
        return $errorhtml;
    }

    $publications_data = json_decode( $publications_data_json, true );

    // just print last modified time and gtfo
    if ($wporg_atts['just_modified_time']) {  // use this to print only the last modified time
        return "<p>Last modified: ".$publications_data['modified']."</p>";
    }



    $thehtml = "";


//    $thehtml .= set_up_author_javascript();

    if ($wporg_atts['phase'] == 'sdss5') {
        $these_publications = $publications_data['publications'];
    } elseif ($wporg_atts['phase'] == 'sdss4') {
        $these_publications = array_filter($publications_data['publications'], "is_sdss4");
    } elseif ($wporg_atts['phase'] == 'sdss3') {
        $these_publications = array_filter($publications_data['publications'], "is_sdss3");
    } else {
        $errorhtml = '<h2><font color="red">ERROR: No publications JSON link found for phase '.$wporg_atts['phase'].'</font></h2>';
        return $errorhtml;
    }

    if ($wporg_atts['type'] == 'technical') {
        $these_publications = array_filter($publications_data['publications'], "is_tech_paper");
    }
    if ($wporg_atts['by_survey']) {

        // look up which surveys to display (based on selected phase)
        if ($wporg_atts['phase'] == 'sdss5') {
            $these_surveys = $sdss5_surveys;
        } elseif ($wporg_atts['phase'] == 'sdss4') {
            $these_surveys = $sdss4_surveys;
        } elseif ($wporg_atts['phase'] == 'sdss3') {
            $these_surveys = $sdss3_surveys;
        } else {
            $errorhtml = '<h2><font color="red">ERROR: No surveys found for phase '.$wporg_atts['phase'].'</font></h2>';
            return $errorhtml;
        }


        // loop through each of this phase's surveys and display survey title, then publications from that survey
        foreach ($these_surveys as $this_survey) {
            
            if ($this_survey == '') {
                $these_survey_publications = $these_publications;
            } elseif ($this_survey == 'SDSS-IV General') {
                $these_survey_publications = array_filter($these_publications, "is_sdss4_general");
            } elseif ($this_survey == 'eBOSS') {
                $these_survey_publications = array_filter($these_publications, "is_eboss");
            } elseif ($this_survey == 'APOGEE-2') {
                $these_survey_publications = array_filter($these_publications, "is_apogee2");
            } elseif ($this_survey == 'MaNGA') {
                $these_survey_publications = array_filter($these_publications, "is_manga");
            } elseif ($this_survey == 'MaStar') {
                $these_survey_publications = array_filter($these_publications, "is_mastar");
            } elseif ($this_survey == 'SPIDERS') {
                $these_survey_publications = array_filter($these_publications, "is_spiders");
            } elseif ($this_survey == 'TDSS') {
                $these_survey_publications = array_filter($these_publications, "is_tdss");
            } elseif ($this_survey == 'BOSS') { 
                $these_survey_publications = array_filter($these_publications, "is_boss");
            } elseif ($this_survey == 'APOGEE') {
                $these_survey_publications = array_filter($these_publications, "is_apogee");
            } elseif ($this_survey == 'MARVELS') {
                $these_survey_publications = array_filter($these_publications, "is_marvels");
            } else {
                $these_survey_publications = array();
            }

            // print survey title
            if ($this_survey != '') {
                if ($this_survey == 'SDSS-IV General') {
                    $thehtml .= "<h3 id='sdss4-general'>General</h3>";
                } else {
                    $thehtml .= "<h3 id='".strtolower(str_replace("-","_",str_replace(" ", "_", $this_survey)))."'>".$this_survey."</h3>";
                }
            }

            // print publications from this survey
            $thehtml .= "<ul>"; //$thehtml .= "<ul class='fa-ul'>";  // when we get font awesome glyphs to work{
            foreach ($these_survey_publications as $this_pub) {
                $thehtml .= display_this_pub($this_pub);
            }
            $thehtml .= "</ul>";

            $thehtml .= do_shortcode('[to_top]');
        }
    } else {
        $thehtml .= "<ul>"; //$thehtml .= "<ul class='fa-ul'>";  // when we get font awesome glyphs to work{
        foreach ($these_publications as $this_pub) {
            $thehtml .= display_this_pub($this_pub);
        }
        $thehtml .= "</ul>";
    }

    return $thehtml;
}


function display_this_pub($this_pub) {
    
/*    foreach ($this_pub as $k => $v) {
        echo $k." = ".$v."<br />";
    }*/
    //echo $this_pub['publication_id']."<br />";
    $thishtml = '';

    $dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
            (( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
    	    (( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false )
        );

    //$thishtml .= "<li id='pub-".$this_pub['publication_id']."'>"; // $thehtml .= "<li><i class='fa-li fa fa-book'></i>";   // when we get font awesome glyphs to work
    $thishtml .= "<li>"; // $thehtml .= "<li><i class='fa-li fa fa-book'></i>";   // when we get font awesome glyphs to work

    if ( $dflt_url ) $thishtml .= "<a target='_blank' href='$dflt_url' >";
    $thishtml .= "<strong>" . $this_pub[ 'title' ] . "</strong>";
    if ( $dflt_url ) $thishtml .= "</a>";

    //$thishtml .= '<br />';
    //$thishtml .= parse_authors()

    $thishtml .= '<br />';
//    $thishtml .= '<span class="authors-short" id="authors-short-'.$this_pub['publication_id'].'">';
//    $thishtml .= parse_authors($this_pub['authors'], $this_pub['publication_id']);
//    $thishtml .= '</span>';
    $thishtml .= $this_pub['authors'];
    $thishtml .= ". ";

    if ( $this_pub[ 'journal_reference' ]) {
			    $thishtml .= $this_pub[ 'journal_reference' ];
		    } else {
			    $thishtml .= '<em>' . $this_pub[ 'status' ] . '</em>';
		    }
    if ( !empty($this_pub[ 'adsabs' ] ) ) $thishtml .= "; <a href='" . $this_pub[ 'adsabs_url' ] . "' target='_blank'>adsabs:" . $this_pub[ 'adsabs' ] . "</a>";
    if ( !empty($this_pub[ 'doi' ] ))  $thishtml .= "; <a href='" . $this_pub[ 'doi_url' ] . "' target='_blank'>doi:" . $this_pub[ 'doi' ] . "</a>";
    if ( !empty($this_pub[ 'arxiv_url' ] ) ) $thishtml .= "; <a href='" . $this_pub[ 'arxiv_url' ] . "' target='_blank'>arXiv:" . $this_pub[ 'arxiv' ] . "</a>";
    $thishtml .= '</li>';
    
    //$thishtml .= $author_display_str."<br />";//." (".count($these_authors)." total)<br />";

    return $thishtml;
}


function parse_authors($authorlist, $pid) {
    $these_authors = explode( ",", $authorlist);
    $nAuthors = count($these_authors);

   	switch ( $nAuthors ) {
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
            $nMores = $nAuthors - 1;
			//$author_display_str = $these_authors[0] ." <a href='#pub-".$pid."' onclick='javascript:hello();'>et&nbsp;al. (".$nMores." more)</a>";
            $author_display_str = $these_authors[0]." <a href='#pub-".$pid."' onclick='javascript:hello(".$pid.");event.preventDefault();'>et&nbsp;al. (".$nMores." more)</a>";
	}
    return $author_display_str;
}


function is_tech_paper($var) {
    if ((strtolower($var['category']) == 'technical paper in journal') || (strtolower($var['category']) == 'infrastructure paper')) {
        return true;
    } else {
        return false;
    }
}

function is_sdss4($var) {
    $sdss4_surveys = array('SDSS-IV General', 'eBOSS', 'APOGEE-2', 'MaNGA', 'MaStar', 'SPIDERS', 'TDSS');
    if (in_array($var['survey'], $sdss4_surveys)) {
        return true;
    } else {
        return false;
    }
}

function is_sdss3($var) {
    $sdss3_surveys = array('BOSS', 'APOGEE', 'MARVELS');
    if (in_array($var['survey'], $sdss3_surveys)) {
        return true;
    } else {
        return false;
    }
}

function is_sdss4_general($var) { if ($var['survey'] == 'SDSS-IV General') { return true; } else { return false; } }
function is_eboss($var) { if ($var['survey'] == 'eBOSS') { return true; } else { return false; } }
function is_apogee2($var) { if ($var['survey'] == 'APOGEE-2') { return true; } else { return false; } }
function is_manga($var) { if ($var['survey'] == 'MaNGA') { return true; } else { return false; } }
function is_mastar($var) { if ($var['survey'] == 'MaStar') { return true; } else { return false; } }
function is_spiders($var) { if ($var['survey'] == 'SPIDERS') { return true; } else { return false; } }
function is_tdss($var) { if ($var['survey'] == 'TDSS') { return true; } else { return false; } }
function is_boss($var) { if ($var['survey'] == 'BOSS') { return true; } else { return false; } }
function is_apogee($var) { if ($var['survey'] == 'APOGEE') { return true; } else { return false; } }
function is_marvels($var) { if ($var['survey'] == 'MARVELS') { return true; } else { return false; } }


function boolify($var) {if (strtolower($var) == 'true') {return true;} else {return false;}}



function set_up_author_javascript() {
    $jsifier = "<script type='text/javascript'>";
    $jsifier .= "function hello(pid) {";
    $jsifier .= "alert('Hello world pid');";
    $jsifier .= "}";
    $jsifier .= "</script>";
    return $jsifier;
}



// sdss5 publications array keys:
//   Array ( [0] => adsabs [1] => adsabs_url [2] => arxiv [3] => arxiv_url [4] => author_alphabetic [5] => authors [6] => blind_analysis [7] => category [8] => doi [9] => doi_url [10] => forward_url [11] => identifier [12] => journal [13] => journal_page [14] => journal_reference [15] => journal_volume [16] => journal_year [17] => modified [18] => paper_id [19] => paper_url [20] => publication_id [21] => reference [22] => status [23] => survey [24] => title )

// sdss4 publications array keys:
//Array ( [0] => adsabs [1] => adsabs_url [2] => arxiv [3] => arxiv_url [4] => author_alphabetic [5] => authors [6] => blind_analysis [7] => category [8] => doi [9] => doi_url [10] => first_author [11] => identifier [12] => journal [13] => journal_page [14] => journal_reference [15] => journal_volume [16] => journal_year [17] => member_ids [18] => modified [19] => publication_id [20] => status [21] => survey [22] => title )


// sdss5 category values:
// Array ( [0] => Infrastructure Paper [1] => Meeting Abstract [2] => Paper in Conference Proceeding [3] => Scientific Paper in Journal [4] => Technical Paper in Journal )

// sdss4 category values:
// Array ( [0] => Conference Proceeding based on Public SDSS Data [1] => Data Release Paper [2] => Meeting Abstract [3] => Paper in Conference Proceeding [4] => Scientific Paper in Journal [5] => Scientific Paper in Journal based on Public SDSS Data [6] => Technical Paper in Journal )

// sdss4 survey valeus:
// Array ( [0] => APOGEE [1] => APOGEE-2 [2] => BOSS [3] => MARVELS [4] => MaNGA [5] => MaStar [6] => SDSS-IV General [7] => SPIDERS [8] => TDSS [9] => eBOSS )


/*  TO FIND KEYS:
    $keylist = array();
    foreach ($publications_data['publications'] as $x) {
        foreach ($x as $k => $v) {
            if (!in_array($k, $keylist)) {
                array_push($keylist, $k);
            }
        }
    }
    sort($keylist);
    print_r($keylist);

    */

/*  TO FIND CATEGORIES:
    $catlist = array();
    foreach ($publications_data['publications'] as $x) {
        if (!in_array($x['category'], $catlist)) {
            array_push($catlist, $x['category']);
        }
    }
    sort($catlist);
    print_r($catlist);
*/

/*  TO FIND SURVEYS:
    $surveylist = array();
    foreach ($publications_data['publications'] as $x) {
        if (!in_array($x['survey'], $surveylist)) {
            array_push($surveylist, $x['survey']);
        }
    }
    sort($surveylist);
    print_r($surveylist);
*/

?>

