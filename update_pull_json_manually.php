
<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $name = $_POST['proofbox'];
  if (!empty($name)) {
    echo "<h1>Done!</h1>";
    chdir('sdss_org_wp_data/sdss5/json/');
#    execThenPrint("git fetch");
    $thisjson = file_get_contents('https://raw.githubusercontent.com/sdss/sdss_org_wp_data/main/sdss5/json/architects.json');
    file_put_contents('architects.json', $thisjson);
    echo "<h1><a href='/update-jsons/'>Return to update page</a></h1>";
  }
}

function execThenPrint($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}

?>



<?php
function show_json_updater() {

	$thehtml = "<h3>Hello world</h3>";
	$thehtml .= "<form action='/wp-content/plugins/sdss_wp_shortcodes/update_pull_json_manually.php' method='post'>";
    $thehtml .= "<input type='textbox' name='proofbox' />Please enter something here";
    $thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "<input type='submit'>";
	$thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "</form>";
	return $thehtml;
}

?>