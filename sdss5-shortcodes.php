<?php
/**
 * Plugin Name: SDSS-V Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Functions for sdss.org: parse and display JSON content, display VACs, create TOCs
 * Version: 2.1
 * Text Domain: sdss5-custom-shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 * Version updates:
 *** 2.0. VACs: filtering code added
 *** 2.0.0.1. Commented out data release checkboxes on VAC filtering
 *** 2.1. Registered new sidebars for news and blog pages
 *** 2.1.0.1. Commented out call to readmore (for now)
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
?>