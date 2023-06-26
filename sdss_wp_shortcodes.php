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


// Path to JSONs for publications etc.
define('PATH_JSON', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/organization/');
define('PATH_JSON_PUBS', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/publications/');
define('PATH_JSON_VACS', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss5/');
define('PATH_JSON_SDSS4', '/code/wp-content/plugins/sdss_wp_shortcodes/wiki/data/collaboration/vacs/vac-sdss4/');
?>
