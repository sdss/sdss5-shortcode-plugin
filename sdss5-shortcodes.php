<?php
/**
 * Plugin Name: SDSS-V Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Functions for sdss.org: parse and display JSON content, display VACs, create TOCs
 * Version: 1.4.2
 * Text Domain: sdss5-custom-shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 * Version updates:
 *** 1.1. Fixed special chars in TOC, added hierarchy of headers (h2, h3...) marking in TOC
 *** 1.2. TOC: Allow user to specify what side to display TOC on (default left), and what levels to include (default h2, h3, h4 specified by '234')
 *** 1.3. TOC accordionized! (as long as you have the accordion-blocks plugin installed)
 *** 1.4. VAC list page formatted
 *** 1.4.1. VACS: Fixed link to CAS tables for single VACs that have them
 *** 1.4.2. TOC: displays correctly with or without accordion
 *** 1.5. TOC: added to top button for use with TOC
 */

require_once('affiliations.php');           // Show affiliations
require_once('coco.php');           // Show CoCo
require_once('publications.php');           // Show publications
require_once('architects.php');           // Show architects
require_once('roles.php');           // Show roles
require_once('vacs.php');          // Show VACs
require_once('sdss_toc.php');          // Show within-page table of contents 
require_once('sdss_to_top.php');          // Button to go to top of page


add_shortcode( 'show_affiliations', 'show_affiliations');
add_shortcode( 'show_coco', 'show_coco');
add_shortcode( 'show_publications', 'show_publications');
add_shortcode( 'show_architects', 'show_architects');
add_shortcode( 'show_roles', 'show_roles');
add_shortcode( 'show_vacs', 'show_vacs');
add_shortcode( 'show_toc', 'show_toc' );
add_shortcode( 'to_top', 'sdss_to_top' );

?>