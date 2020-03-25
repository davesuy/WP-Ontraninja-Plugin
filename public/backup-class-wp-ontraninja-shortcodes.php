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
		        'label' => '',
		        'meta' => false
		    ), $atts, 'wp_ontraninja_cot_field' );


			$account_id = "";


		    //if(isset($_GET['id'])) {

				$account_id = 37;

				
				$requestParams = array(
				    "objectID" => $atts['object_id'], // Object type ID: 14
				    "id"       => $account_id
				);

				$response = $this->client->object()->retrieveSingle($requestParams);

				$response_decode = json_decode($response);

				//return '<pre>'.print_r($response, true).'</pre>';

			
				$label = "";
				
				if($atts['label']) {
					$label = '<strong>'.$atts['label'].'</strong>: ';
				}

				//return $label.$response_decode->data->{$atts['field']};

			//}

			$object_vars = get_object_vars($response_decode->data);

			$ak_object_vars  = array_keys($object_vars);

			$requestParamsMeta = array(
				   "objectID" => $atts['object_id'],
				    "format"   => "byId"
			);


			//if($atts['meta'] == true) {

				$response_meta = $this->client->object()->retrieveMeta($requestParamsMeta);

				$response_meta_decode = json_decode($response_meta);


				$meta_fields = $response_meta_decode->data->{$atts['object_id']};

				if(isset($meta_fields->fields)) {

					$get_meta_fields = $meta_fields->fields;

				}


				$gov_get_meta_fields = get_object_vars($get_meta_fields);

				$ak_gov_get_meta_fields = array_keys($gov_get_meta_fields);


				$i = 0;

				$data_value = "";
					

				foreach($ak_object_vars as $ak_object_var) {

					//echo '<pre>'.print_r($ak_object_var, true).'</pre>';



					
					if(isset($get_meta_fields->$ak_object_var)) {

						$gmf_abv = $get_meta_fields->$ak_object_var;


					}

	

					if(isset($gmf_abv->alias)) {

						$ga_alias = $gmf_abv->alias;

					}

					if(isset($ak_gov_get_meta_fields[$i])) {
				
						$count_ak_gov_get_meta_fields = $ak_gov_get_meta_fields[$i];

					}


					if(isset($get_meta_fields->$count_ak_gov_get_meta_fields->alias)) {

						$data_value .= '<p><strong>'.$get_meta_fields->$count_ak_gov_get_meta_fields->alias.' - <i>'.$count_ak_gov_get_meta_fields.'</i></strong></p>';

					}
					
							
					if(isset($object_vars[$count_ak_gov_get_meta_fields])) {

						$object_vars_value = $object_vars[$count_ak_gov_get_meta_fields];	
						
				
					

						if(isset($get_meta_fields->$count_ak_gov_get_meta_fields->options)) {

							//$data_value = $get_meta_fields->$count_ak_gov_get_meta_fields->options->$object_vars_value;

							$data_value .= '<p>'.$get_meta_fields->$count_ak_gov_get_meta_fields->options->$object_vars_value.'</p>';

								//echo '<p>'.$data_value.'</p>';

						} else {
								
							//$data_value = $object_vars_value;

								//echo '<p>'.$data_value.'</p>';
							$data_value .= '<p>'.$object_vars_value.'</p>';


						}

						

					}

					//return '<p>'.$info_name_code.'<p>
							//<p>'.$data_value.'</p>';
						//echo '<pre>'.print_r($data_value, true).'</pre>';

					$i++;

				}

				
				return $data_value;

			//}

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

			$label = "";
				
			if($atts['label']) {
				$label = '<strong>'.$atts['label'].'</strong>: ';
			}

			return $label.$response_contact_decode->data->$field_output;

	    } 

	}
		 
}

