<?php 
echo "<hr />";
if ($_SERVER["REQUEST_METHOD"] == "POST") {    // check whether form has been submitted
  $name = $_POST['proof'];       
  if (!empty($name)) {
    $surveys = array("sdss4", "sdss5");
    $jsonfiles = array('affiliations', 'architects', 'coco', 'project', 'publications', 'roles', 'vacs');
    chdir('sdss_org_wp_data/');
    foreach ($surveys as $this_survey) {
        echo "<p>Getting json files for ".$this_survey."...</p>";
        chdir($this_survey);
        echo "<p>File permissions on json directory:<br />";
        echo fileperms('json/');
        echo "<br />";
        echo parse_file_permissions(fileperms('json/'));
        echo "</p>";
//        echo "<p>Changing directory permissions of json dir...</p>";
//        chmod('json/', 0777);
        chdir('json/');
        #execThenPrint('ls -sal');
        foreach ($jsonfiles as $this_json_file) {
            $savefilename = $this_json_file.".json";
            $gitlink = 'https://raw.githubusercontent.com/sdss/sdss_org_wp_data/pantheon/'.$this_survey.'/json/'.$this_json_file.'.json';
            echo "&nbsp;&nbsp;&nbsp;Saving ".$savefilename."...<br />";
            file_put_contents($savefilename, file_get_contents($gitlink));
//            break;
        }
//        echo "<p>Changing directory permissions of json dir back to safe values...</p>";
//        chdir('../')
//        chmod('json/', 0644);
        execThenPrint('ls -sal');
        chdir('../../');
        echo "<hr />";
//        break;
    }

/*    echo "<dl>";
    foreach ($_ENV as $k => $v) {
        echo "<dt>".$k."</dt>";
        echo "<dd>".$v."</dd>";
    }
    echo "</dl>";
    */

    //echo "<h2 style='color:red;'>DISALLOW_FILE_MODS = ".get_option(DISALLOW_FILE_MODS)."</h2>";
    echo "<p>Idk maybe trying with git pull...</p>";
    chdir('/code/');
    execThenPrint('pwd');
    execThenPrint('git pull');


    echo "<p>Idk maybe trying with git subtree pull...</p>";
    execThenPrint('pwd');
    echo "trying to execThenPrint('git subtree pull --prefix wp_content/plugins/sdss_wp_shortcodes/ https://github.com/sdss/sdss_wp_shortcodes pantheon --squash'); and it will probably fail....";
    execThenPrint('git subtree pull --prefix wp_content/plugins/sdss_wp_shortcodes/ https://github.com/sdss/sdss_wp_shortcodes pantheon --squash');


    //echo "<p>Idk maybe trying with git subtree pull...</p>";
    
#    execThenPrint('git pull');
    $dt = new DateTime("now", new DateTimeZone('America/New_York'));
    echo "<p>Done at ".$dt->format('m/d/Y, H:i:s')."</p>";
    echo "<h2><a href='/update-jsons/'>Return to the JSON Updates page</a></h2>";
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
    $thehtml .= "<form action='/wp-content/plugins/sdss_wp_shortcodes/update_pull_json_manually.php' method='post'>";
    $thehtml .= "<input type='hidden' id='proof' name='proof' value=True>";
    $thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "<input type='submit' value='Update JSON files'>";
	$thehtml .= '<div class="clearfix"></div>';
	$thehtml .= "</form>";
	return $thehtml;
}



function parse_file_permissions($perms) {
    switch ($perms & 0xF000) {
        case 0xC000: // socket
            $info = 's';
            break;
        case 0xA000: // symbolic link
            $info = 'l';
            break;
        case 0x8000: // regular
            $info = 'r';
            break;
        case 0x6000: // block special
            $info = 'b';
            break;
        case 0x4000: // directory
            $info = 'd';
            break;
        case 0x2000: // character special
            $info = 'c';
            break;
        case 0x1000: // FIFO pipe
            $info = 'p';
            break;
        default: // unknown
            $info = 'u';
    }

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
                (($perms & 0x0800) ? 's' : 'x' ) :
                (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
                (($perms & 0x0400) ? 's' : 'x' ) :
                (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
                (($perms & 0x0200) ? 't' : 'x' ) :
                (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}
?>