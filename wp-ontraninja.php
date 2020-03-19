<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://itmooti.com/
 * @since             1.0.0
 * @package           Wp_Ontraninja
 *
 * @wordpress-plugin
 * Plugin Name:       WP Ontraninja
 * Plugin URI:        https://itmooti.com/
 * Description:       
 * Version:           1.0.0
 * Author:            ITMOOTI
 * Author URI:        https://itmooti.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-ontraninja
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
define( 'WP_ONTRANINJA_VERSION', '1.0.0' );
define( 'WP_ONTRANINJA_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'WP_ONTRANINJA_PLUGIN_PATH', plugin_dir_path(__FILE__) );

define( 'WP_ONTRANINJA_APP_ID', get_option('won_api_app_id'));
define( 'WP_ONTRANINJA_APP_KEY', get_option('won_api_app_key'));


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-ontraninja-activator.php
 */
function activate_wp_ontraninja() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-ontraninja-activator.php';
	Wp_Ontraninja_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-ontraninja-deactivator.php
 */
function deactivate_wp_ontraninja() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-ontraninja-deactivator.php';
	Wp_Ontraninja_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_ontraninja' );
register_deactivation_hook( __FILE__, 'deactivate_wp_ontraninja' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-ontraninja.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_ontraninja() {

	$plugin = new Wp_Ontraninja();
	$plugin->run();

}
run_wp_ontraninja();
