<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.4goa.net/
 * @since             1.0.0
 * @package           Locate_Anything
 *
 * @wordpress-plugin
 * Plugin Name:       LocateAnything
 * Plugin URI:        http://www.4goa.net/locate-anything
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            4GOA
 * Author URI:        http://www.4goa.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       locate-anything
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-locate-anything-activator.php
 */
function activate_locate_anything() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locate-anything-activator.php';
	Locate_Anything_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-locate-anything-deactivator.php
 */
function deactivate_locate_anything() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locate-anything-deactivator.php';
	Locate_Anything_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_locate_anything' );
register_deactivation_hook( __FILE__, 'deactivate_locate_anything' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-locate-anything.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_locate_anything() {

	$plugin = new Locate_Anything();
	$plugin->run();

}
run_locate_anything();
