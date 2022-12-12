<?php
/**
 * Plugin Name: SDSS-V Custom Shortcodes
 * Plugin URI: https://www.sdss5.org
 * Description: Parse and display JSON content
 * Version: 0.2
 * Text Domain: sdss5-custom-shortcodes
 * Author: Jordan Raddick
 * Author URI: https://www.jordanraddick.com
 */

require_once('affiliations.php');           // Show affiliations
require_once('coco.php');           // Show CoCo
require_once('publications.php');           // Show publications
require_once('architects.php');           // Show architects
require_once('roles.php');           // Show roles
require_once('vacs.php');          // Show VACs

add_shortcode( 'show_affiliations', 'show_affiliations');
add_shortcode( 'show_coco', 'show_coco');
add_shortcode( 'show_publications', 'show_publications');
add_shortcode( 'show_architects', 'show_architects');
add_shortcode( 'show_roles', 'show_roles');
add_shortcode( 'show_vacs', 'show_vacs');
?>