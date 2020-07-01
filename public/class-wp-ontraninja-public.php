<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://itmooti.com/
 * @since      1.0.0
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/public
 * @author     ITMOOTI <dev@itmooti.com>
 */
class Wp_Ontraninja_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->public_load_classes();

	}


	public function public_load_classes() {

		// Shortcodes

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-ontraninja-shortcodes.php';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-ontraninja-public.css', array(), $this->version, 'all' );

		wp_enqueue_style( "won-bootstrap", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( "won-bootstrap-theme", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap-theme.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( "won-bootstrap-utilities", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap-4-utilities.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-ontraninja-public.js', array('jquery'), null, false);

	}


}
