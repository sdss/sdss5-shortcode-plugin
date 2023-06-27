
<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $name = $_POST['proofbox'];
  if (!empty($name)) {
    echo "<h1>Done!</h1>";
    // Print the exec output inside of a pre element
    #execThenPrint("git pull https://user:password@bitbucket.org/user/repo.git master");
    #execThenPrint("git status");
    $thisdir = getcwd();
    $topdir = $_SERVER['DOCUMENT_ROOT'];
    //execThenPrint("cd ..");
    chdir($topdir);
    #execThenPrint("pwd");
    execThenPrint("git subtree pull --prefix wp-content/sdss_wp_shortcodes/wiki/ https://github.com/sdss/wiki pantheon --squash");
    execThenPrint("git status");
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