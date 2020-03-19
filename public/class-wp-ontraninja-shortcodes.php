<?php

use OntraportAPI\Ontraport;

class Wp_Ontraninja_Shortcodes {

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

	private $client;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $client) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->$app_id = $app_id;
		$this->$app_key = $app_key;

		$this->client = $client;

	}

	
	public function public_shortcodes() {

		add_shortcode( 'wp_ontraninja_cot_field', array($this,'wp_ontraninja_cot_field_func') );
		add_shortcode( 'wp_ontraninja_contact_field', array($this,'wp_ontraninja_contact_field_func') );

	}

	public function wp_ontraninja_cot_field_func($atts) {

			$atts = shortcode_atts( array(
		        'object_id' => '',
		        'field' => '',
		        'label' => ''
		    ), $atts, 'wp_ontraninja_cot_field' );


			$account_id = "";


		    if(isset($_GET['id'])) {

				$account_id = $_GET['id'];

				
				$requestParams = array(
				    "objectID" => $atts['object_id'], // Object type ID: 14
				    "id"       => $account_id
				);

				$response = $this->client->object()->retrieveSingle($requestParams);

				$response_decode = json_decode($response);

				//return '<pre>'.print_r($response, true).'</pre>';

				return '<p><strong>'.$atts['label'].':</strong> '.$response_decode->data->{$atts['field']}.'</p>';

			} else {

	    		return '<p>The Shortcode requires adding a get parameter on the URL like this "?id=x"</p>';

			}
	}

	public function wp_ontraninja_contact_field_func($atts) {

		$atts = shortcode_atts( array(
	        'object_id' => '',
	        'field' => '',
	        'label' => ''
	    ), $atts, 'wp_ontraninja_contact_field' );


		$account_id = "";


	    if(isset($_GET['id'])) {

    		$account_id = $_GET['id'];

			$requestParams = array(
			    "objectID" => $atts['object_id'], // Object type ID: 14
			    "id"       => $account_id
			);

			$response = $this->client->object()->retrieveSingle($requestParams);

			$response_decode = json_decode($response);


	    	$contact_id = $response_decode->data->{"f2915"};


			$requestParamsContact = array(
			    "id" => $contact_id
			);

			$response_contact = $this->client->contact()->retrieveSingle($requestParamsContact);


			$response_contact_decode = json_decode($response_contact);

			//return '<pre>'.print_r( $response_contact_decode->data->email, true).'</pre>';

			$field_output = $atts['field'];

			return '<p><strong>'.$atts['label'].':</strong> '.$response_contact_decode->data->$field_output.'</p>';

	    } else {

	    	return '<p>The Shortcode requires adding a get parameter on the URL like this "?id=x"</p>';

	    }

	}
		 
}

