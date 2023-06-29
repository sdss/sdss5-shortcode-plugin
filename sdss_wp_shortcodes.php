<?php
/**
 * Plugin Name: SDSS-V Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Functions for sdss.org: parse and display JSON content, display VACs, create TOCs
 * Version: 3.0
 * Text Domain: sdss5-custom-shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 * Version updates:
 *** 3.0. Renamed and moved JSON locations here (out of wp-config)
 *** 3.0.1. Fixed link to JSONs in pantheon branch
 *** 3.0.1.1. Actually Fixed link to JSONs in pantheon branch
 *** 3.1.1. Updated JSON path to be Joel's new sdss_org_wp_data repository
 *** 3.1.2. Pulling from github raw content, pantheon branch, testing on sdss5 architects
 *** 3.1.2.1. fml forgot a semicolon
 *** 3.1.2.2. Adding files for sdss_org_wp_data (but not git dirs), will delete .json files and get them from github.com instead
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
//require_once('update_pull_json.php');          // Make sure JSONs are up to date
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
add_shortcode( 'readmore', 'read_more_in_excerpt');
add_shortcode( 'update_pull_json_manually', 'show_json_updater' );


// Path to JSONs for publications etc.

define('PATH_JSON', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss5/json/');
define('PATH_JSON_VACS', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss5/json/');
define('PATH_JSON_SDSS4', '/code/wp-content/plugins/sdss_wp_shortcodes/sdss_org_wp_data/sdss4/json/');


#define('PATH_JSON', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/organization/');
#define('PATH_JSON_VACS', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss5/');
#define('PATH_JSON_SDSS4', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss4/');
?>