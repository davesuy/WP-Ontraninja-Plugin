<?php

use OntraportAPI\Ontraport;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://itmooti.com/
 * @since      1.0.0
 *
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Ontraninja
 * @subpackage Wp_Ontraninja/includes
 * @author     ITMOOTI <dev@itmooti.com>
 */
class Wp_Ontraninja {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Ontraninja_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $client;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'WP_ONTRANINJA_VERSION' ) ) {
			$this->version = WP_ONTRANINJA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-ontraninja';

		$this->load_dependencies();

		$this->client = new Ontraport(WP_ONTRANINJA_APP_ID, WP_ONTRANINJA_APP_KEY);

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Ontraninja_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Ontraninja_i18n. Defines internationalization functionality.
	 * - Wp_Ontraninja_Admin. Defines all hooks for the admin area.
	 * - Wp_Ontraninja_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		// Ontraport PHP Library

		require_once plugin_dir_path( dirname( __FILE__ ) ) .'vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-ontraninja-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-ontraninja-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-ontraninja-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-ontraninja-public.php';



		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-ontraport.php';

	

		$this->loader = new Wp_Ontraninja_Loader();

	
	} 

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Ontraninja_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Ontraninja_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Ontraninja_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Action hook for admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ontraninja_menu' );

		// Preloader GIF
		//$this->loader->add_action('wp_head',  $plugin_admin, 'won_plugin_preloader_css');
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Ontraninja_Public( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 20 );

		// Shortcode Init
		
		$plugin_shortcode =  new Wp_Ontraninja_Shortcodes( $this->get_plugin_name(), $this->get_version(), $this->get_client());

		$this->loader->add_action( 'init', $plugin_shortcode, 'public_shortcodes', 10 );


		$this->loader->add_action( 'init', $plugin_shortcode, 'won_start_session', 20 );


		// Ajax Request Shortcode

		$this->loader->add_action( 'wp_ajax_request_data' , $plugin_shortcode, 'won_ajax_request_data' );
		$this->loader->add_action( 'wp_ajax_nopriv_request_data' , $plugin_shortcode, 'won_ajax_request_data' );


		$this->loader->add_action( 'wp_ajax_request_data_other' , $plugin_shortcode, 'won_ajax_request_data_other' );
		$this->loader->add_action( 'wp_ajax_nopriv_request_data_other' , $plugin_shortcode, 'won_ajax_request_data_other' );




	}



	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Ontraninja_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	public function get_client() {
		return $this->client;
	}



}
