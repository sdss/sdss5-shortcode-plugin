<?php
function show_publications() {
#    echo PATH_JSON;
    $publications_data_json = @file_get_contents(  PATH_JSON . 'publications.json' );
    $publications_data = json_decode( $publications_data_json, true );##

    $thehtml = "";
    $thehtml .= "<ul class='fa-ul'>";
    foreach ($publications_data['publications'] as $this_pub): #( $publications_data['publications'] as $this_pub ) :  
        $dflt_url = ( !empty( $this_pub[ 'adsabs_url' ] ) ) ? $this_pub[ 'adsabs_url' ] : 
                        (( !empty( $this_pub[ 'doi_url' ] ) ) ? $this_pub[ 'doi_url' ] : 
    					(( !empty( $this_pub[ 'arxiv_url' ] ) ) ? $this_pub[ 'arxiv_url' ] : false )
                    );
        #echo "<li>". $dflt_url."</li>";
        $thehtml .= "<li><i class='fa-li fa fa-book'></i>";
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

?>