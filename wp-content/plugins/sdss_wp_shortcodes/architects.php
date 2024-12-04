<?php


function show_architects() {
    //echo "<pre>".PATH_JSON."architects.json</pre>";
    $thehtml = "";
    $architects_data_json = @file_get_contents(  PATH_JSON . 'architects.json' );
    //echo $architects_data_json;

    $architects_data = json_decode( $architects_data_json, true );
    foreach ($architects_data['architects'] as $this_architect_array) {
        $thehtml .= "<p><strong>" . $this_architect_array['name'] . "</strong> has been " . $this_architect_array['comment'] . "</p>";
        //echo "k = ". $k . "; v = ". $v . "\n";
    }
    $thehtml .= "<p>&nbsp;</p>";
    $thehtml .= "<p>Last modified: " . $architects_data['modified'] . "</p>";

    return $thehtml;

}

?>