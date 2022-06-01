<?php


function show_roles() {
    //echo "<pre>".PATH_JSON."architects.json</pre>";
    $thehtml = "";
    $roles_data_json = @file_get_contents(  PATH_JSON . 'roles.json' );
    $roles_data = json_decode( $roles_data_json, true );
    

    foreach ($roles_data['divisions'] as $division_number => $division) {
        $thehtml .= "<h2>" . $division['title'] . "</h2>";
        $thehtml .= "<ul>";
        foreach ($division['roles'] as $role_in_division_number => $this_role) {
            $thehtml .= "<li>".$this_role['role'].$this_role['mc'].": <strong>".$this_role['leaders']."</strong></li>";
        }
        $thehtml .= "</ul>";
    }

//    $architects_data = json_decode( $architects_data_json, true );
//    foreach ($architects_data['architects'] as $this_architect_array) {
//        $thehtml .= "<p><strong>" . $this_architect_array['name'] . "</strong> has been " . $this_architect_array['comment'] . "</p>";
        //echo "k = ". $k . "; v = ". $v . "\n";
//    }
//    $thehtml .= "<p>&nbsp;</p>";
    $thehtml .= "<p>Last modified: " . $roles_data['modified'] . "</p>";

    return $thehtml;

}

?>