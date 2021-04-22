<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           calcVat
 *
 * @wordpress-plugin
 * Plugin Name:       Calc Vat
 * Description:       Add shortcode for calculating Vat. 
 * Version:           1.0.0
 * Author:            Edyta Jozdowska
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       calcVat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'calcVat_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-calcVat-activator.php
 */
function activate_calcVat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calcVat-activator.php';
	calcVat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-calcVat-deactivator.php
 */
function deactivate_calcVat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-calcVat-deactivator.php';
	calcVat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_calcVat' );
register_deactivation_hook( __FILE__, 'deactivate_calcVat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-calcVat.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_calcVat() {

	$plugin = new calcVat();
	$plugin->run();

}
run_calcVat();
