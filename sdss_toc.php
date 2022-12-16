<?php

//ini_set('memory_limit', '1024M'); // or you could use 1G

/*function insert_toc($content) {
    // Maybe modify $example in some way.
    $regex = '/BHM/';
    $html_segment = '';
    if (preg_match_all($regex, $content, $matches)) {
        foreach ($matches as $thismatch) {
            foreach ($thismatch as $thismatchtext) {
                $html_segment .= "<p>".$thismatchtext."</p>";
            }
        }
    }

    $content = $html_segment . $content;
    return $content;
}*/

function show_toc($atts) {

    $my_param = 'my theme name';
    add_filter('the_content', function ($content) use ($my_param) {
        $regex = "/<h\d.*>.*\/h\d>/";
        //$regex = "/<footer/";
        
        //$html_segment = "<div class='toc toc-left'>";//'<p>Hello world</p>';
        if (preg_match_all($regex, $content, $matches)) {
            $thetoc = "<div class='toc toc-left'>";
            $thetoc .= "<ul>";
            //$thetoc .= "<p style='color:red;'>".htmlentities($regex)."</p>";
            foreach ($matches as $thismatch) {
                foreach ($thismatch as $thistag) {
                    $thetoc .= "<li>";
                    $target_regex = "/id\s*=\s*(\'|\").*(\'|\")/";
                    if (preg_match($target_regex, $thistag, $idmatch)) {
                        $thetoc .= "<a href='#".substr(trim($idmatch[0]), 4, -1)."'>";
                    }
                    $label_regex = "/>.*<\/h\d>/";
                    if (preg_match($label_regex, $thistag, $labelmatch)) {
                        $thetoc .= htmlentities(substr($labelmatch[0], 1, -5));
                        //$thetoc .= substr($labelmatch[0], 1, -2);
                    }
                    if (preg_match($target_regex, $thistag, $idmatch)) {
                        $thetoc .= "</a>";
                    }
                    $thetoc .= "</li>";
                }
            }
            $thetoc .= '</ul>';
            $thetoc .= '</div>';
            $content = $thetoc . $content;
        }
        return $content;
    }, 99, 1);

    return;

/*    $regex = '/the/';

    //preg_match_all($regex, $content, $matches);

    $thetoc = "<div class='toc toc-left'>";
    //print_r($matches);
    //foreach ($matches as $thismatch) {
      //  foreach ($thismatch as $thismatchtext) {
        //    $thetoc .= "<p>".$thismatch."</p>";
        //}
    //}
    $thetoc .= $content;
    $thetoc .= "</div>";

    $content = $thetoc . $content;*/

    return $content;

    //return apply_filters( 'the_content', 'insert_toc' , 99);
//    return apply_filter('the_content', 'insert_toc', 99);

 }


?>
