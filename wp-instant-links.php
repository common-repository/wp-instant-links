<?php
/*
 * Plugin Name:	WP Instant Links
 * Description: Preload the next page the user is most likely to click to, resulting in super fast UX, and ultimately increasing your conversions.
 * Version:		1.1.0
 * Author:	  	Kevin Batdorf
 * License:	 	MIT
 *
 * Text Domain: wp-instant-links
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) die('No direct access.');

if (!class_exists('WpInstantLinks')) {
	require_once plugin_dir_path(__FILE__) . 'class-wp-instant-links.php';
	require_once plugin_dir_path(__FILE__) . 'class-admin-page.php';
	add_action('after_setup_theme', array(WpInstantLinks::getInstance(), 'setup'), 10);
}
