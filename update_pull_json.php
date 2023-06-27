<h1>Done!</h1>
<?php 
function execThenPrint($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}
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
?>

<h1><a href='/update-jsons/'>Return to update page</a></h1>