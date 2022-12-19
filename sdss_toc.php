<?php

//ini_set('memory_limit', '1024M'); // or you could use 1G

/*
function find_shortcode_on_page($unexpanded_content, $shortcode_finder) {
    
    if (strpos($unexpanded_content, $shortcode_finder)) {
        return strpos($unexpanded_content, $shortcode_finder); }
    else {
        return 0;
    }
}*/

function show_toc($atts) {

    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $wporg_atts = shortcode_atts(
		array(
			'side' => 'left',
            'headers' => '234'
		), $atts
	);

    // Display which headers in TOC? As specified by user (default h2, h3, h4)
    $headervals = array();
    for($i = 0; $i < strlen($wporg_atts['headers']); ++$i) {// < and not <=, cause the index starts at 0!
        if ((intval($wporg_atts['headers'][$i]) >= 2) && (intval($wporg_atts['headers'][$i]) <= 6)) {
            array_push($headervals, intval($wporg_atts['headers'][$i]));
        }
    }
    $wporg_atts['headers_to_display'] = $headervals;
    
    add_filter('the_content', function ($content) use ($wporg_atts) {

        $regex = "/<h\d.*>.*\/h\d>/";
        $thetoc = '';

        if (preg_match_all($regex, $content, $matches)) {
            $thetoc .= "<div class='toc toc-".$wporg_atts['side']."'>";
            $thetoc .= "<p>Table of Contents</p>";
            $thetoc .= "<ul>";
            //$thetoc .= "<p style='color:red;'>".htmlentities($regex)."</p>";
            foreach ($matches as $thismatch) {
                foreach ($thismatch as $thistag) {
                    $whatlevel = htmlspecialchars($thistag[2]);

                    if (in_array($whatlevel, $wporg_atts['headers_to_display'])) {  // only show TOC items for headers specified in headers_to_display list
                        $thetoc .= "<li class='forh".$whatlevel."' >";
                    
                        $target_regex = "/id\s*=\s*(\'|\").*(\'|\")/";
                        if (preg_match($target_regex, $thistag, $idmatch)) {
                            $thetoc .= "<a href='#".substr(trim($idmatch[0]), 4, -1)."'>";
                        }
                        $label_regex = "/>.*<\/h\d>/";
                        if (preg_match($label_regex, $thistag, $labelmatch)) {
                            $thetoc .= substr($labelmatch[0], 1, -5);
                            //$thetoc .= substr($labelmatch[0], 1, -2);
                        }
                        if (preg_match($target_regex, $thistag, $idmatch)) {
                            $thetoc .= "</a>";
                        }
                        $thetoc .= "</li>";
                    }
                }
            }
            $thetoc .= '</ul>';
            $thetoc .= '</div>';
          
            $content =  $thetoc . $content;
        }
        return $content;
    }, 99, 1);

    return;
}
?>
