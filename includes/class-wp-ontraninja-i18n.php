<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://itmooti.com/
 * @since      1.0.0
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/includes
 * @author     ITMOOTI <dev@itmooti.com>
 */
class Wp_Ontraninja_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-ontraninja',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
