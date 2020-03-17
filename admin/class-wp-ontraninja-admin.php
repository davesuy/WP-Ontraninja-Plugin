<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://itmooti.com/
 * @since      1.0.0
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/admin
 * @author     ITMOOTI <dev@itmooti.com>
 */
class Wp_Ontraninja_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ontraninja_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ontraninja_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-ontraninja-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Ontraninja_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Ontraninja_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-ontraninja-admin.js', array( 'jquery' ), $this->version, false );

	}

	// Create menu method

	public function ontraninja_menu() {

		add_menu_page("Ontraport Management Tool", "Ontraport Management Tool", "manage_options", "ontraport-management-tool", array($this, "ontraport_management_dashboard"), "", 22);
	}

	// Menu callback function

	public function ontraport_management_dashboard() {
		echo "<h3>Welcome to Menu</h3>";
	}

}
