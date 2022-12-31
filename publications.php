<?php


function show_publications($atts) {

    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $wporg_atts = shortcode_atts(
		array(
            'survey' => 'sdss5',
			'type' => ''
		), $atts
	);

    if ($wporg_atts['survey'] == 'sdss5') {
        $publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
    }
    elseif (($wporg_atts['survey'] == 'sdss4') || ($wporg_atts['survey'] == 'sdss3')) {
        $publications_data_json = @file_get_contents(  PATH_JSON_SDSS4 . 'publications.json' );
    } else {
        $thehtml = '<h2><font color="red">ERROR: Survey '.$wporg_atts['survey'].' not found!</font></h2>';
        return $thehtml;
    }

    $publications_data = json_decode( $publications_data_json, true );

    $thehtml = "";
    $thehtml .= "<ul>"; //$thehtml .= "<ul class='fa-ul'>";  // when we get font awesome glyphs to work

    if ($wporg_atts['type'] == 'technical') {
        $these_publications = array_filter($publications_data['publications'], "is_tech_paper");
    } else {
        $these_publications = $publications_data['publications'];
    }

    if ($wporg_atts['survey'] == 'sdss4') {
        $these_publications = array_filter($publications_data['publications'], "is_sdss4");
    } elseif ($wporg_atts['survey'] == 'sdss3') {
        $these_publications = array_filter($publications_data['publications'], "is_sdss3");
    } else {
        $these_publications = $publications_data['publications'];
    }
    
    foreach ($these_publications as $this_pub):
        $dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
                        (( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
    					(( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false )
                    );

        $thehtml .= "<li>".$this_pub['survey'].": "; // $thehtml .= "<li><i class='fa-li fa fa-book'></i>";   // when we get font awesome glyphs to work

        if ( $dflt_url ) $thehtml .= "<a target='_blank' href='$dflt_url' >";
        $thehtml .= "<strong>" . $this_pub[ 'title' ] . "</strong>";
        if ( $dflt_url ) $thehtml .= "</a>";
        $thehtml .= '<br />' . $this_pub[ 'authors' ] .  '. ' ;
        $thehtml .= "</li>";
        if ( $this_pub[ 'journal_reference' ]) {
			        $thehtml .= $this_pub[ 'journal_reference' ];
		        } else {
			        $thehtml .= '<em>' . $this_pub[ 'status' ] . '</em>';
		        }
        if ( !empty($this_pub[ 'adsabs' ] ) ) $thehtml .= "; <a href='" . $this_pub[ 'adsabs_url' ] . "' target='_blank'>adsabs:" . $this_pub[ 'adsabs' ] . "</a>";
        if ( !empty($this_pub[ 'doi' ] ))  $thehtml .= "; <a href='" . $this_pub[ 'doi_url' ] . "' target='_blank'>doi:" . $this_pub[ 'doi' ] . "</a>";
        if ( !empty($this_pub[ 'arxiv_url' ] ) ) $thehtml .= "; <a href='" . $this_pub[ 'arxiv_url' ] . "' target='_blank'>arXiv:" . $this_pub[ 'arxiv' ] . "</a>";
        $thehtml .= '</li>';
    endforeach;
    $thehtml .= "</ul>";
    $thehtml .= "<p>Last modified: ".$publications_data['modified']."</p>";

    return $thehtml;
}

function is_tech_paper($var) {
    if ((strtolower($var['category']) == 'technical paper in journal') || (strtolower($var['category']) == 'infrastructure paper')) {
        return true;
    } else {
        return false;
    }
}

function is_sdss4($var) {
    $sdss4_surveys = array('APOGEE-2', 'MaNGA', 'MaStar', 'SDSS-IV General', 'SPIDERS', 'TDSS', 'eBOSS');
    if (in_array($var['survey'], $sdss4_surveys)) {
        return true;
    } else {
        return false;
    }
}

function is_sdss3($var) {
    $sdss3_surveys = array('APOGEE', 'BOSS', 'MARVELS');
    if (in_array($var['survey'], $sdss3_surveys)) {
        return true;
    } else {
        return false;
    }
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