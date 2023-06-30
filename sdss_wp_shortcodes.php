<?php
/**
 * Plugin Name: SDSS Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Functions for sdss.org: parse/display/update JSON content, display VACs, create TOCs
 * Version: 3.1.4.6
 * Text Domain: sdss_wp_shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 * Version updates:
 *** 3.1.4. Manual update of JSON files works
 *** 3.1.4.1. Debugging file permission problem
 *** 3.1.4.2. Changing file permissions back and forth
 *** 3.1.4.3. Typo: chnod -> chmod
 *** 3.1.4.4. Gitignoring json files
 *** 3.1.4.5. Forgot semicolon fml
 *** 3.1.4.6. Better test of file permissions on json directories
 */

require_once('affiliations.php');           // Show affiliations
require_once('coco.php');           // Show CoCo
require_once('publications.php');           // Show publications
require_once('architects.php');           // Show architects
require_once('roles.php');           // Show roles
require_once('vacs.php');          // Show VACs
require_once('vac-search.php');          // Search and filter controls for VACs
require_once('sdss_toc.php');          // Show within-page table of contents 
require_once('sdss_to_top.php');          // Button to go to top of page
/*require_once('sdss_readmore.php');          // Read more link for manual excerpts in news and blog posts */
require_once('update_pull_json.php');                   // Make sure JSONs are up to date
require_once('update_pull_json_manually.php');          // Make sure JSONs are up to date, manual option


add_shortcode( 'show_affiliations', 'show_affiliations');
add_shortcode( 'show_coco', 'show_coco');
add_shortcode( 'show_publications', 'show_publications');
add_shortcode( 'show_architects', 'show_architects');
add_shortcode( 'show_roles', 'show_roles');
add_shortcode( 'show_vacs', 'show_vacs');
add_shortcode( 'show_toc', 'show_toc' );
add_shortcode( 'to_top', 'sdss_to_top' );
add_shortcode( 'vac_search', 'vac_search');
//add_shortcode( 'readmore', 'read_more_in_excerpt');
add_shortcode( 'update_pull_json', 'pull_json' );
add_shortcode( 'update_pull_json_manually', 'show_json_updater' );


// Path to JSONs for publications etc.

define('PATH_JSON', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss5/json/');
define('PATH_JSON_VACS', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss5/json/');
define('PATH_JSON_SDSS4', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss4/json/');


#define('PATH_JSON', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/organization/');
#define('PATH_JSON_VACS', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss5/');
#define('PATH_JSON_SDSS4', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss4/');
?>
