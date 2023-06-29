<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {    // check whether form has been submitted
  $name = $_POST['proof'];       
  if (!empty($name)) {
    $surveys = array("sdss4", "sdss5");
    $jsonfiles = array('affiliations', 'architects', 'coco', 'project', 'publications', 'roles', 'vacs');
    chdir('sdss_org_wp_data/');
    foreach ($surveys as $this_survey) {
        echo "<p>Getting json files for ".$this_survey."...</p>";
        chdir($this_survey);
        chdir('json/');
        foreach ($jsonfiles as $this_json_file) {
            $savefilename = $this_json_file.".json";
            $gitlink = 'https://raw.githubusercontent.com/sdss/sdss_org_wp_data/pantheon/'.$this_survey.'/json/'.$this_json_file.'.json';
            echo "&nbsp;&nbsp;&nbsp;Saving ".$savefilename."...<br />";
            file_put_contents($savefilename, file_get_contents($gitlink));
        }
        chdir('../../');
    }
    $dt = new DateTime("now", new DateTimeZone('America/New_York'));
    echo "<p>Done at ".$dt->format('m/d/Y, H:i:s')."</p>";
    echo "<h2><a href='/update-jsons/'>Return to the JSON Updates page</a></h2>";
  }
}

/*function execThenPrint($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}*/
?>

<?php
function show_json_updater() {
    $thehtml .= "<form action='/wp-content/plugins/sdss_wp_shortcodes/update_pull_json_manually.php' method='post'>";
    $thehtml .= "<input type='hidden' id='proof' name='proof' value=True>";
    $thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "<input type='submit' value='Update JSON files'>";
	$thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "</form>";
	return $thehtml;
}
?>