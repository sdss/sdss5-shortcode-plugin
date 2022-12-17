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

    $my_param = 'my theme name';
    add_filter('the_content', function ($content) use ($my_param) {
/*        $shortcode_finder = '[show_toc]';
        $getter = get_the_content();
        
        $shortcode_point = find_shortcode_on_page($getter, $shortcode_finder);
        
        
        $thetoc = "";
        //$thetoc = '<h1 style="color:pink;">Getter: '.$getter.'</h1>';
        //R$thetoc .= '<h1 style="color:orange;">Content: '.$content.'</h1>';
        //$thetoc .= '<h1>Shortcode point: '.$shortcode_point.'</h1>';

        

        $thetoc .= '<h1 style="color:pink;">'.'Shortcode point: '.$shortcode_point.'</h1><hr />';
        

        $pretoc = "";//$pre_shortcode;
        $posttoc = ""; //$content;

        //$pregetternum = $shortcode_point;
        $getter_before_str = substr($getter, 0, $shortcode_point);
        $content_match_to_pos = strpos($content, $getter_before_str) + strlen($getter_before_str);

        $pregetter = substr($content, 0, $content_match_to_pos);

        //$thetoc .= '<h1 style="color:pink;">'.'$getter_before_str: '.$content_match_to_pos.'</h1><hr />';
        $thetoc .= '<h1 style="color:pink;">'.'$pregetter: '.$pregetter.'</h1><hr />';
        //$thetoc .= '<h1 style="color:pink;">'.'$content_match_to_pos: '.$content_match_to_pos.'</h1><hr />';


        $pretoc = ""; //$pregetter; //substr($content, 0, $shortcode_point);
        //$posttoc = substr($content, $shortcode_point-strlen($shortcode_finder)); // $content; //substr($content, $shortcode_point);
        */

        $regex = "/<h\d.*>.*\/h\d>/";
        $thetoc = '';

        if (preg_match_all($regex, $content, $matches)) {
            $thetoc .= "<div class='toc toc-left'>";
            $thetoc .= "<p>Table of Contents</p>";
            $thetoc .= "<ul>";
            //$thetoc .= "<p style='color:red;'>".htmlentities($regex)."</p>";
            foreach ($matches as $thismatch) {
                foreach ($thismatch as $thistag) {
                    $whatlevel = htmlspecialchars($thistag[2]);

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
            $thetoc .= '</ul>';
            $thetoc .= '</div>';
          
            $content =  $thetoc . $content;
        }
        return $content;
    }, 99, 1);

    return;
}
?>
