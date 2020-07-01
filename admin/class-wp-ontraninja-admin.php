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

		$this->admin_load_classes();

	}

	public function admin_load_classes() {

	
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$valid_pages = array("ontraport-management-tool", "ontraport-create-record");

		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";

		if(in_array($page, $valid_pages)) {

			wp_enqueue_style( "won-bootstrap", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( "won-bootstrap-theme", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap-theme.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( "won-bootstrap-utilities", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/bootstrap-4-utilities.min.css', array(), $this->version, 'all' );
		
			wp_enqueue_style( "won-datatable", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/jquery.dataTables.min.css', array(), $this->version, 'all' );

			wp_enqueue_style( "won-sweetalert", WP_ONTRANINJA_PLUGIN_URL . 'assets/css/sweetalert.css', array(), $this->version, 'all' );


			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-ontraninja-admin.css', array(), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$valid_pages = array("ontraport-management-tool", "ontraport-create-record");

		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : "";

		if(in_array($page, $valid_pages)) {

			wp_enqueue_script('jquery');

			wp_enqueue_script( 'won-bootstrap-js', WP_ONTRANINJA_PLUGIN_URL. 'assets/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'won-datatable-js', WP_ONTRANINJA_PLUGIN_URL. 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'won-validate-js', WP_ONTRANINJA_PLUGIN_URL. 'assets/js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( 'won-sweetalert-js', WP_ONTRANINJA_PLUGIN_URL. 'assets/js/sweetalert.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-ontraninja-admin.js', array( 'jquery' ), $this->version, false );

			wp_localize_script($this->plugin_name, 'won_data', array(
				"name" => "Wp Ontraninja",
				"author" => "ITMOOTI",
				"ajax" => admin_url("admin-ajax.php")
			));

		}

	}

	// Create menu method

	public function ontraninja_menu() {

		add_menu_page("Ontraport Management Tool", "Ontraport Management Tool", "manage_options", "ontraport-management-tool", array($this, "ontraport_management_settings"), "", 22);

		// Create submenus

		add_submenu_page("ontraport-management-tool", "General", "General ", "manage_options", "ontraport-management-tool", array($this, "ontraport_management_settings"));

		add_submenu_page("ontraport-management-tool", "Create Record", "Create Record", "manage_options", "ontraport-create-record", array($this, "ontraport_management_create"));


		// Activate custom settings
		add_action( 'admin_init', array($this,'ontraninja_custom_settings') );

	}

	// Menu and Submenu callback function

	public function ontraport_management_settings() {

		ob_start();

		include_once(WP_ONTRANINJA_PLUGIN_PATH."admin/partials/tmpl-settings.php");

		$template = ob_get_contents();

		ob_end_clean();

		echo $template;

	}

	public function ontraport_management_create() {
		echo "<h3>Create Record</h3>";
	}


	// Settings Options

	public function ontraninja_custom_settings() { 

		register_setting('ontraninja-settings-group', 'won_api_app_id');

		register_setting('ontraninja-settings-group', 'won_api_app_key');

		add_settings_section('ontraninja-api-options', "API Settings", array($this,'ontraninja_settings_options'), 'ontraport-management-tool');  

		add_settings_field('won-app-id', "API App ID", array($this,"ontraninja_settings_id"), "ontraport-management-tool",'ontraninja-api-options');

		add_settings_field('won-app-key', "API App key", array($this,"ontraninja_settings_key"), "ontraport-management-tool",'ontraninja-api-options');

	}

	public function ontraninja_settings_options() {

		echo "Enter your Ontraport Credentials";
	
	}

	public function ontraninja_settings_id() {

		$app_id = esc_attr(get_option('won_api_app_id'));

		$app_id_output = "";

		if (strlen($app_id) > 6) {

			$app_id_output = substr($app_id, 0, 5) . '******';

		}
		
		echo '<input type="text" name="won_api_app_id" class="regular-text" placeholder="'.$app_id_output.'"  />';
	
		
	}

	public function ontraninja_settings_key() {

		$app_key = esc_attr(get_option('won_api_app_key'));

		$app_key_output = "";

		if (strlen($app_key) > 6) {

			$app_key_output = substr($app_key, 0, 5) . '******';

		}
		echo '<input type="text" name="won_api_app_key" class="regular-text" placeholder="'.$app_key_output.'" />';
		

	}

	public function won_plugin_preloader_css() {

		//global $post;

		//if(has_shortcode( $post->post_content, 'wp_ontraninja_dynamic_cot_field')) {

			?>

			<!-- <div id="won-preloader"></div> -->

			<style type="text/css">

				#won-preloader{

					position: fixed;
					top: 0;
				 	left: 0;
				 	right: 0;
				 	bottom: 0;
					background:url(<?php echo WP_ONTRANINJA_PLUGIN_URL; ?>assets/images/preloader.GIF) no-repeat #FFFFFF 50%;
					-moz-background-size:64px 64px;
					-o-background-size:64px 64px;
					-webkit-background-size:64px 64px;
					background-size:64px 64px;
					z-index: 99998;
					width:100%;
					height:100%;

				}

			</style>

			<noscript>
	    		<style type="text/css">
	        		#won-preloader {
	        			display:none !important;
	        		}
	    		</style>
			</noscript>

			<?php

		//}
	}

}
