<?php
/**
 * Plugin Name: SDSS-V Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Parse and display JSON content
 * Version: 1.1
 * Text Domain: sdss5-custom-shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 * Version updates:
 *** 1.1. Fixed special chars in TOC, added hierarchy of headers (h2, h3...) marking in TOC
 *** 1.2. TOC: Allow user to specify what side to display TOC on (default left), and what levels to include (default h2, h3, h4 specified by '234')
 *** 1.3. TOC accordionized! (as long as you have the accordion-blocks plugin installed)
 */

require_once('affiliations.php');           // Show affiliations
require_once('coco.php');           // Show CoCo
require_once('publications.php');           // Show publications
require_once('architects.php');           // Show architects
require_once('roles.php');           // Show roles
require_once('vacs.php');          // Show VACs
require_once('sdss_toc.php');          // Show within-page table of contents 

add_shortcode( 'show_affiliations', 'show_affiliations');
add_shortcode( 'show_coco', 'show_coco');
add_shortcode( 'show_publications', 'show_publications');
add_shortcode( 'show_architects', 'show_architects');
add_shortcode( 'show_roles', 'show_roles');
add_shortcode( 'show_vacs', 'show_vacs');
add_shortcode( 'show_toc', 'show_toc' );

?>