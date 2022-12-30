<?php
// publications array keys:
//   Array ( [0] => adsabs [1] => adsabs_url [2] => arxiv [3] => arxiv_url [4] => author_alphabetic [5] => authors [6] => blind_analysis [7] => category [8] => doi [9] => doi_url [10] => forward_url [11] => identifier [12] => journal [13] => journal_page [14] => journal_reference [15] => journal_volume [16] => journal_year [17] => modified [18] => paper_id [19] => paper_url [20] => publication_id [21] => reference [22] => status [23] => survey [24] => title )

// categories:
// Array ( [0] => Infrastructure Paper [1] => Meeting Abstract [2] => Paper in Conference Proceeding [3] => Scientific Paper in Journal [4] => Technical Paper in Journal )

function show_publications($atts) {

    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $wporg_atts = shortcode_atts(
		array(
			'type' => ''
		), $atts
	);

    $publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
    $publications_data = json_decode( $publications_data_json, true );

    $thehtml = "";
    $thehtml .= "<ul>"; //$thehtml .= "<ul class='fa-ul'>";  // when we get font awesome glyphs to work

    if ($wporg_atts['type'] == 'technical') {
        $these_publications = array_filter($publications_data['publications'], "is_tech_paper");
    } else {
        $these_publications = $publications_data['publications'];
    }
    
    foreach ($these_publications as $this_pub):
        $dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
                        (( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
    					(( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false )
                    );

        $thehtml .= "<li>"; // $thehtml .= "<li><i class='fa-li fa fa-book'></i>";   // when we get font awesome glyphs to work

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


?>