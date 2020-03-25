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

			$default_timezone = wp_timezone_string();

			$atts = shortcode_atts( array(
		        'object_id' => '',
		        'field' => '',
		        'label' => '',
		        'meta' => false,
		        'format' => 'j M Y ('.$default_timezone.')'

		    ), $atts, 'wp_ontraninja_cot_field' );


			$account_id = "";
			$data_value = "";

		    if(isset($_GET['id'])) {

				$account_id = $_GET['id'];

				
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

				if($atts['field']) {

					$data_value .= $label.$response_decode->data->{$atts['field']};

				}

			

				/** Meta Object **/

				$object_vars = get_object_vars($response_decode->data);

				$ak_object_vars  = array_keys($object_vars);

				$requestParamsMeta = array(
					   "objectID" => $atts['object_id'],
					    "format"   => "byId"
				);


				if($atts['meta'] == true) {

					$response_meta = $this->client->object()->retrieveMeta($requestParamsMeta);

					$response_meta_decode = json_decode($response_meta);


					$meta_fields = $response_meta_decode->data->{$atts['object_id']};

					if(isset($meta_fields->fields)) {

						$get_meta_fields = $meta_fields->fields;

					}


					$gov_get_meta_fields = get_object_vars($get_meta_fields);

					$ak_gov_get_meta_fields = array_keys($gov_get_meta_fields);

					$result = array_intersect($ak_object_vars,$ak_gov_get_meta_fields);

				

					foreach($result as $res) {
								
						$data_value .= '<h4><strong>'.$gov_get_meta_fields[$res]->alias.'</strong> - <i>'.$res.'</i></h4>';
						

						$object_value = $object_vars[$res];

						if(isset($gov_get_meta_fields[$res]->options)) {

							$data_value .= '<p><strong class="text-danger">Option Data:</strong> <i>'.$gov_get_meta_fields[$res]->options->$object_value.'</i></p>';

						} else {

							


							if ((string) (int) $object_value === $object_value && ($object_value <= PHP_INT_MAX)
&& ($object_value >= ~PHP_INT_MAX) && strlen($object_value) >= 10) {

		
								$unix_timestamp = $object_value;



								$datetime = new DateTime("@$unix_timestamp");
							
								$format_explode = explode("(",$atts['format']);

								$time_zone_to = $format_explode[1];
								$format_time = $format_explode[0];


								$datetime = new DateTime("@$unix_timestamp");

								$date_time_format = date_format($datetime, $format_time);

					
								$time_zone_from = "UTC";

								try {

									$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));

									$display_date->setTimezone(new DateTimeZone($time_zone_to));

									$data_value .= '<p><strong class="text-success">Date Data:</strong> '.$display_date->format($format_time).'</p>';

								 } catch (Exception $e) {

									$data_value .= '<p><strong class="text-success">Date Data: </strong>Error Date and Timezone Format!</p>';

								}

									
							
								
							} else {
								
								$data_value .= '<p><strong class="text-info">Data:</strong> <i>'.$object_value.'</i></p>';
							}



						}

					}
				
				}

				return $data_value;

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

			$label = "";
				
			if($atts['label']) {
				$label = '<strong>'.$atts['label'].'</strong>: ';
			}

			return $label.$response_contact_decode->data->$field_output;

	    } 

	}
		 
}

