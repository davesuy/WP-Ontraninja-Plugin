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

	public function won_start_session() {

		if(!session_id()) {

			session_start();
		
		}

		$account_id = "";

	    if(isset($_GET['id'])) {

			$account_id = $_GET['id'];
		}


		if (isset($_SESSION['dynamic_cot_'.$account_id])) {


			if(!isset($_SESSION['CREATED_DC_'.$account_id])) {

				 $_SESSION['CREATED_DC_'.$account_id] = time();

			} 
		} 


		if (isset($_SESSION['dynamic_cot_name_'.$account_id])) {

		    if(!isset($_SESSION['CREATED_DCN_'.$account_id])) {

				 $_SESSION['CREATED_DCN_'.$account_id] = time();

			} 

		} 


		$time = $_SERVER['REQUEST_TIME'];

		/**
		* for a 30 minute timeout, specified in seconds
		*/
		$timeout_duration = 21600; // 6 hours 1800 = 30 minutes

		/**
		* Here we look for the user's LAST_ACTIVITY timestamp. If
		* it's set and indicates our $timeout_duration has passed,
		* blow away any previous $_SESSION data and start a new one.
		*/
		if (isset($_SESSION['dynamic_cot_'.$account_id]) && 
		   ($time - $_SESSION['CREATED_DC_'.$account_id]) > $timeout_duration) {
		    
			unset($_SESSION["dynamic_cot_".$account_id]);
			unset($_SESSION["CREATED_DC_".$account_id]);

		}

		if (isset($_SESSION['dynamic_cot_name_'.$account_id]) && 
		   ($time - $_SESSION['CREATED_DCN_'.$account_id]) > $timeout_duration) {
		    
		
			unset($_SESSION["dynamic_cot_name_".$account_id]);
			unset($_SESSION["CREATED_DCN_".$account_id]);
			
		}


	
		
		//echo  '<pre>'.print_r($_SESSION, true).'</pre>';
		//echo  '<pre>'.print_r($time, true).'</pre>';
		
		//echo  '<pre>'.print_r($timeout_duration, true).'</pre>';

	}

	
	public function public_shortcodes() {

		add_shortcode( 'wp_ontraninja_cot_field', array($this,'wp_ontraninja_cot_field_func') );
		add_shortcode( 'wp_ontraninja_contact_field', array($this,'wp_ontraninja_contact_field_func') );

		add_shortcode( 'wp_ontraninja_fieldeditor', array($this,'wp_ontraninja_fieldeditor_func') );


		add_shortcode( 'wp_ontraninja_register_information', array($this,'wp_ontraninja_register_information_func') );

		// Shortcode Used

		add_shortcode( 'wp_ontraninja_dynamic_cot_field', array($this,'wp_ontraninja_dynamic_cot_field_func') );
	
	}


	public function won_ajax_request_data()  {

			$objectID = "";

			if($_POST['objectID']) {

				$objectID = $_POST['objectID'];

			}


			$account_id = "";

			if($_POST['account_id']) {

				$account_id = $_POST['account_id'];

			}


			$field_attr = "";

			if($_POST['field_attr']) {

				$field_attr = $_POST['field_attr'];

			}

			/** Connect API Regsistration **/

			$connect_api_instance = new Wp_Ontraninja_Connect_Ontraport;

				
			$connect_api_instance->connect_api_shortcode($objectID, $account_id);

			
			
			/** End Connect API Regsistration **/
				
			//echo '<pre>'.print_r($response_decode_array, true).'</pre>';
			

			if(is_array($connect_api_instance->ak_response_contact_decode_data)) {

				if(in_array($field_attr, $connect_api_instance->ak_response_contact_decode_data)) {


					//echo '"not set" ';

			
					$set_session_val = $connect_api_instance->response_contact_decode->data->$field_attr;

					$_SESSION['dynamic_cot_name_'.$account_id]['firstname'] = $set_session_val;

					$field_name = $_SESSION['dynamic_cot_name_'.$account_id]['firstname'];
						
						

				}

			}

			echo  $field_name;

		// echo $objectID;
		// echo $account_id;
		//echo $field_attr;
		die();

	}


	public function won_ajax_request_data_other()  {


		$objectID = "";

		if($_POST['objectID']) {

			$objectID = $_POST['objectID'];

		}


		$account_id = "";

		if($_POST['account_id']) {

			$account_id = $_POST['account_id'];

		}

		$field_attr_other = "";

		if($_POST['field_attr_other']) {

			$field_attr_other = $_POST['field_attr_other'];

		}


		$format = "";

		if($_POST['format']) {

			$format = $_POST['format'];

		}

		// $label = "";

		// if($_POST['label']) {

		// 	$label = $_POST['label'];

		// }

		$color = "";

		if($_POST['color']) {

			$color = $_POST['color'];

		}

		//echo $field_attr_other;



		$section = "Registration Information";
		//$color = "background";

		/** Connect API Regsistration **/

	 	$connect_api_instance = new Wp_Ontraninja_Connect_Ontraport;

			
	 	$connect_api_instance->connect_api_shortcode($objectID, $account_id);


	 	/** End Connect API Regsistration **/

	 	if(is_array($connect_api_instance->ak_response_contact_decode_data)) {
		
	 		if(!in_array($field_attr_other, $connect_api_instance->ak_response_contact_decode_data )) {

	
	 			// Register Information
		

	 			$object_vars = get_object_vars($connect_api_instance->response_decode->data);


	 			$requestParams_reg = array(
	 			    "objectID" => $objectID,
	 			    "section"       => $section
	 			);

	 			$response_field = $this->client->object()->retrieveFields($requestParams_reg);

	 			$response_field_decode = json_decode($response_field);


	 			$get_fieldeditor = array();

	 			if(isset($response_field_decode->data->fields)) {

	 				$get_fieldeditor = $response_field_decode->data->fields;
				
	 			}

	 			//echo '<pre>'.print_r( $response_field_decode, true).'</pre>';

	 			$reg_value = "";
	 			$gf_alias = "";
	 			$gf_field = "";
	 			$bgc = "";

	 			foreach($get_fieldeditor as $g_field => $pp) {


	 		        foreach($pp as $p) {

	 		        	//echo $p->alias.'xx<br/>';
			        

	 			        if($p->alias == $field_attr_other) {
				        		
	 		                //echo $p->alias; 
	 		                // echo $p->field; 

	 			        	$options_decode = json_decode($p->options);

	 			        	if(is_array($options_decode)) {


	 			        		if(!empty($options_decode)) {

	 			        			foreach($options_decode as $options_decod) {

	 				                   //echo '<pre>'.print_r( $options_decode, true).'</pre>';

	 				                    if(isset($options_decod->value)) {


	 				                    	if($options_decod->value == $object_vars[$p->field]) {

					                    				
	 				                    		if(!empty($color) && $field_attr_other != "Type" && $field_attr_other != "Event Selection") {

	 					                    		if($color == 'text') {

	 					                    			//echo $options_decod->color.'3';


	 					                    			// Info Color

	 						                    		$field_name_output_c = "";

	 													if(isset($_SESSION['dynamic_cot_'.$account_id][$field_attr_other])) {

	 														//echo 'info set - color ';

	 														$field_name_output_c = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

										                    echo $field_name_output_c;

	 					                    			} else {

	 					                    				//echo 'info not set - color ';

	 					                    				$set_session_val_output_c = $options_decod->color;

	 														$_SESSION['dynamic_cot_'.$account_id][$field_attr_other] = $set_session_val_output_c;

	 														$field_name_output_c = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

							                    			
	 														echo  'cc_'.$options_decod->color;

	 					                    			}

	 					                    			

	 					                    		} elseif($color == 'background') {

	 					                    			//echo $options_decod->backgroundColor.'2';



	 					                    			// Info background Colr

	 						                    		$field_name_output_bc = "";

	 													if(isset($_SESSION['dynamic_cot_'.$account_id][$field_attr_other])) {

	 														//echo 'info set - bc ';

	 														$field_name_output_bc = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

									                     	echo $field_name_output_bc;

	 					                    			} else {

	 					                    				//echo 'info not set - bc ';

	 					                    				$set_session_val_output_bc = $options_decod->backgroundColor;

	 														$_SESSION['dynamic_cot_'.$account_id][$field_attr_other] = $set_session_val_output_bc;

	 														$field_name_output_bc = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

							                    			
	 														echo 'bg_'.$options_decod->backgroundColor;

	 					                    			}

	 					                    			

	 					                    		}

	 					                    	} else {

	 					                    		// Info Label

	 					                    		$field_name_output_label = "";

	 												if(isset($_SESSION['dynamic_cot_'.$account_id][$field_attr_other])) {

	 													//echo 'info set - Label ';

	 													$field_name_output_label = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

								                     	echo $field_name_output_label;

	 				                    			} else {

	 				                    				//echo 'info not set - Label ';

	 				                    				$set_session_val_output_label = $options_decod->label;

	 													$_SESSION['dynamic_cot_'.$account_id][$field_attr_other] = $set_session_val_output_label;

	 													$field_name_output_label = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

	 					                    			echo $options_decod->label;


	 				                    			}

	 				                    			//echo '('.$param.'+paramRunX)';

	 				                    			//echo $options_decod->label;
	 				                    			//echo '/option-label';

	 				                    			//echo '<--('.$field_name_output_label.')--una->';

	 				                    		}


	 				                    	}

	 				                    }


	 					            }

	 			        		}

	 			       	 		//echo '-runx';
				        				

	 			        	} else {
	 			        		 //echo 'runy';
	      						
	      						// Ajax Date

	 			                $object_value =  $object_vars[$p->field]; 




		                        if ((string) (int) $object_value === $object_value && ($object_value <= PHP_INT_MAX)
									&& ($object_value >= ~PHP_INT_MAX) && strlen($object_value) >= 10) {


									$unix_timestamp = $object_value;


	 							 	//$output_object_value = $object_value;

									$datetime = new DateTime("@$unix_timestamp");

									$default_timezone = wp_timezone_string();

									$format_output = 'j M Y ('.$default_timezone.')';

									if($format != "") {

										$format_output = $format;

									}


									$format_explode = explode("(",$format_output);
									
									//echo '<pre>'.print_r($format, true).'</pre>';
								// $time_zone_to ='Australia/Sydney';
									// $format_time = 'j M Y';
		 								// $format_time_h = 'g:i A';


									$time_zone_to = $format_explode[1];
									$format_time = $format_explode[0];




	 								$datetime = new DateTime("@$unix_timestamp");

	 								$date_time_format = date_format($datetime, $format_time);
	 								//$date_time_format_h = date_format($datetime, $format_time_h);


	 								$time_zone_from = "UTC";


									try {

		 								$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));

											//$display_date_h = new DateTime($date_time_format_h, new DateTimeZone($time_zone_from));

		 								$display_date->setTimezone(new DateTimeZone($time_zone_to));

											//$display_date_h->setTimezone(new DateTimeZone($time_zone_to));
								//$output_object_value = $display_date->format($format_time).' at '.$display_date_h->format($format_time_h);

										$output_object_value = $display_date->format($format_time);

									 } catch (Exception $e) {

											$output_object_value = '<p><strong class="text-success">Date Data: </strong>Error Date and Timezone Format!</p>';

									}



		                        } else {

	 	                        	  $output_object_value = $object_vars[$p->field];
	 	                        }

	 	                       // echo 'info not set - ';

	 	                        //echo '('.$param.'+paramRunY)';

	 	                      	//echo $output_object_value;
		                    
	 	                      	$set_session_val_output = $output_object_value;

	 							$_SESSION['dynamic_cot_'.$account_id][$field_attr_other]  = $set_session_val_output;

	 							$field_name_output = $_SESSION['dynamic_cot_'.$account_id][$field_attr_other];

	 							echo $field_name_output;
	 							//echo '/last';

	 			        	}

	 			        }

	 		        }

	 		    }

							
	 		}

	 	}

	 	
		
	

	 	die();


	}



	public function wp_ontraninja_dynamic_cot_field_func($atts) {


		global $wp_session;


		$default_timezone = wp_timezone_string();

		$atts = shortcode_atts( array(
	        'object_id' => '',
	        'field' => '',
	        'label' => '',
	        'color' => '',
	        'format' => ''

	    ), $atts, 'wp_ontraninja_dynamic_cot_field' );

		$account_id = "";

	    if(isset($_GET['id'])) {

			$account_id = $_GET['id'];
		}

		if($account_id == "") {
			return;
		}

		if($atts['object_id'] == "") {
			return;
		}


	


		$field_attr = $atts['field'];
		$format = $atts['format'];
		$label = $atts['label'];
		$color = $atts['color'];


		$objectID = $atts['object_id'];
		$section = "Registration Information";



		ob_start();

	//echo '--'.$field_attr.'--</br>';

		$field_name = "";

		if($field_attr == 'firstname' || $field_attr == 'lastname') {

			if(isset($_SESSION['dynamic_cot_name_'.$account_id])) {

				//echo '"set" ';

				$field_name = $_SESSION['dynamic_cot_name_'.$account_id]['firstname'];


			} else { 


				

				echo '<span id="won-shortcode-request">';

					echo '<input type="hidden" id="account_id" name="account_id" value="'.$account_id.'">';
					echo '<input type="hidden" id="objectID" name="objectID" value="'.$objectID.'">';
					echo '<input type="hidden" id="fieldAttr" name="fieldAttr" value="'.$field_attr.'">';

					echo '<span class="gif-loader-first"></span><span class="val_data_request"></span>';

				echo '</span>';



			

			}

		
			echo  $field_name;

		} else {


			// Other Data

			$field_name_output = "";
			//echo '<pre>'.print_r($_SESSION,true).'</pre>';

			if(isset($_SESSION['dynamic_cot_'.$account_id][$field_attr])) {

				//echo 'info set - ';

				$field_name_output = $_SESSION['dynamic_cot_'.$account_id][$field_attr];

				if($color == "") {

					echo $field_name_output;

				} else {
					?>

					<style>
						.ontraninja-con-bg {
							background: <?php echo $field_name_output;; ?>;
						}

						.ontraninja-text-color {
							color: <?php echo $field_name_output;; ?>;
						}
					</style>
					<?php

				}

			} else { 


				//echo 'info NOT set - ';

				$field_attr_str = str_replace(" ","_", $field_attr);

				$field_attr_str_low = strtolower($field_attr_str);




				echo '<span id="won-shortcode-request-other">';
					echo '<span class="won-shortcode-request-other_'.$field_attr_str.'">';
						if($field_attr != "color") {
							
							// Append
							echo '<span class="val_data_request_other_'.$field_attr_str.'"></span>';
						
						}
					
						echo '<input type="hidden" id="account_id_other" name="account_id" value="'.$account_id.'">';
						echo '<input type="hidden" id="objectID_other" name="objectID" value="'.$objectID.'">';
						echo '<input type="hidden" id="field_attr_str" name="fieldAttrStr" value="'.$field_attr_str.'">';

						echo '<input type="hidden" class="input-data-val" id="fieldAttr_other" ajax_attr="'.$field_attr.'" name="fieldAttr" value="'.$field_attr.'">';

						//echo '<input type="hidden" id="field_attr_'.$field_attr_str_low.'" name="fieldAttr'.$field_attr_str.'" value="'.$field_attr_str.'">';

						echo '<input type="hidden" class="input-data-val input-data-val-format" id="format" name="format" value="'.$format.'">';
						echo '<input type="hidden" class="input-data-val input-data-val-label" id="label" name="label" value="'.$label.'">';
						echo '<input type="hidden" class="input-data-val input-data-val-color" id="color" name="color" value="'.$color.'">';

						echo '<span class="gif-loader-'.$field_attr_str.'"></span>';

					echo '</span>';
				echo '</span>';

			

			} // Ende Else Other


			 //echo $field_name_output;

		} // End else first name

		$output = ob_get_clean();
	
	    return $output;		


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

				//return '<pre>'.print_r($response_decode , true).'</pre>';

			
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

			//return '<pre>'.print_r( $response_decode, true).'</pre>';


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


	public function wp_ontraninja_fieldeditor_func($atts) {

		$atts = shortcode_atts( array(
	        'object_id' => '',
	        'field_alias' => '',
	        'section' => ''
	    ), $atts, 'wp_ontraninja_fieldeditor' );


	    // Retrieve Fields

    	$requestParamsFieldeditor = array(
		    "objectID" => $atts['object_id'],
		    "section" => $atts['section']
		 
		);

		$response_field = $this->client->object()->retrieveFields($requestParamsFieldeditor);

		$response_field_decode = json_decode($response_field);

	

		$fields = $response_field_decode->data->fields[0];

		

		foreach($fields as $field) {


			
			if($field->alias == $atts['field_alias']) {

				$field_output = $field->field;

			}


		}

		//return'<pre>'.print_r($field_output, true).'</pre>';


		$requestParamsFieldeditor2 = array(
		    "objectID" => $atts['object_id'],
		    "section" => $atts['section'],
		    "field" => $field_output
		 
		);

		$response_field2 = $this->client->object()->retrieveFields($requestParamsFieldeditor2);

		$response_field_decode2 = json_decode($response_field2);


		$response_field_decode2_options = json_decode($response_field_decode2->data->options);

		$val = "";

		foreach($response_field_decode2_options as $response_field_decode2_option) {

			$value = $response_field_decode2_option->value;
			$label = $response_field_decode2_option->label;
			$color = $response_field_decode2_option->color;
			$bg_color = $response_field_decode2_option->backgroundColor;

			$val .= "<h3><strong>Registration ".$atts['field_alias']."</strong></h3>";
			$val .= "<p><strong>Value:</strong> ".$value."</p>";
			$val .= "<p><strong>Label:</strong> ".$label."</p>";
			$val .= "<p style='color: ".$color."'><strong >Color:</strong> ".$color."</p>";
			$val .= "<p style='padding: 10px; background-color: ".$bg_color."'><strong>Background Color:</strong> ".$bg_color."</p>";
			$val .= "<br/>";
			//return'<pre>'.print_r($response_field_decode2_options , true).'</pre>';


		}
		
		return $val;


	}

	public function wp_ontraninja_register_information_func($atts) {

		$default_timezone = wp_timezone_string();

		$atts = shortcode_atts( array(
	        'register_id' => '',
	        'format' => 'j M Y ('.$default_timezone.')'
	       
	    ), $atts, 'wp_ontraninja_register_information' );

		/*** Get URL ***/

    	$account_id = "";

    	if(isset($_GET['id'])) {

    		$account_id = $_GET['id'];
    	}

    	if($account_id == "") {
			return;
		}

		/*** Connect API ***/
    	
    	$requestParams = array(
		    "objectID" => 10029, 
		    "id"       => $account_id
		    //"id"       => 37
		);


		$response = $this->client->object()->retrieveSingle($requestParams);

		$response_decode = json_decode($response);
		
		$object_vars = get_object_vars($response_decode->data);


		$response_field = $this->client->object()->retrieveFields(array(
		    "objectID" => 10029,
		    "section" => "Registration Information"
		));

		$response_field_decode = json_decode($response_field);

		$get_fieldeditor = array();

		if(isset($response_field_decode->data->fields)) {

			$get_fieldeditor = $response_field_decode->data->fields;
		
		}


    	$reg_value = "";
		$gf_alias = "";
		$gf_field = "";
		$bgc = "";

		ob_start();

		?>

		<!-- Register Information Tempate -->

	    <div class="pricingTableWrapper">

		    <div class="container">

		    	<h1 class="display-1 mb-4 text-center jumbotron">Register Information</h1>

		        <div class="row">

		        	<?php

		        	foreach($get_fieldeditor as $g_field => $pp) {

		        		foreach($pp as $p) {

				        	?>

				            <div class="col-md-4 col-sm-6 mb-4">

				                <div class="pricingTable <?php echo $p->field; ?>">
				                    <div class="pricingTable-header">
				                        <h3 class="title"><?php echo $p->alias; ?></h3>
				                        <span class="duration"><?php echo $p->field; ?></span>
				                    </div>

				                    <?php

				                    	$options_decode = json_decode($p->options);

				                    	if(is_array($options_decode)) {

					                    	if(!empty($options_decode)) {

					                    		foreach($options_decode as $options_decod) {
					                    			if(isset($options_decod->value)) {
					                    				if($options_decod->value == $object_vars[$p->field]) {

										                    ?>
											                    <div class="pricing-content">
											                        <div class="price-value <?php echo $p->field; ?>">
											                           
											                          <span class="amount" style="font-size:20px">Value - <?php echo $options_decod->value; ?></span>
											                        </div>
											                        <ul class="inner-content">
											                            <li>Label - <?php echo $options_decod->label; ?></li>
											                            <li>
											                            	
																		<?php

																			if(isset($options_decod->color)) {

																				?>
																				 	<style>
																				 		.pricingTable.<?php echo $p->field; ?> h3,
																				 		.pricingTable.<?php echo $p->field; ?> span,
																				 		.pricingTable.<?php echo $p->field; ?> p
																				 		{
																				 			color: <?php echo $options_decod->color; ?> !important;
																						 }

																						.pricingTable .price-value.<?php echo $p->field; ?> h3,
																						.pricingTable .price-value.<?php echo $p->field; ?> span,
																						.pricingTable .price-value.<?php echo $p->field; ?> p
																				 		{
																				 			color: <?php echo $options_decod->color; ?> !important;
																						 }
																				 	</style>
																				 <?php

																				echo '<pre>Color - '.print_r($options_decod->color, true).'</pre>';

																			}

																		?>

											                            </li>

											                            <li>

											                            <?php 

																			if(isset($options_decod->backgroundColor)) {

																				$bgc .= $options_decod->backgroundColor.',';

																				 ?>
																				 	<style>
																				 		.pricingTable.<?php echo $p->field; ?>
																				 		{
																				 			background: <?php echo $options_decod->backgroundColor; ?> !important;
																						 }

																						.pricingTable .price-value.<?php echo $p->field; ?>
																				 		{
																				 			background: <?php echo $options_decod->backgroundColor; ?> !important;
																						 }

																				 	</style>
																				 <?php
																				echo '<pre>BackgroundColor - '.print_r($options_decod->backgroundColor, true).'</pre>';
																			}

											                            ?>
											                            	
											                            </li>
											                       
											                        </ul>
											                    
											                    </div>

										                    <?php

								                		}
								                	}
							                	}
					                    	}

				                    	} else {
				                    ?>

				                    	<div class="pricing-content">
					                        <div class="price-value">
					                            <span class="amount" style="font-size:16px">Value - <?php 

					                $object_value =  $object_vars[$p->field]; 

			                        if ((string) (int) $object_value === $object_value && ($object_value <= PHP_INT_MAX)
        								&& ($object_value >= ~PHP_INT_MAX) && strlen($object_value) >= 10) {


										$unix_timestamp = $object_value;


									 	//$output_object_value = $object_value;

										$datetime = new DateTime("@$unix_timestamp");

										$format_explode = explode("(",$atts['format']);
									

										// $time_zone_to ='Australia/Sydney';
										// $format_time = 'j M Y';
										// $format_time_h = 'g:i A';


										$time_zone_to = $format_explode[1];
										$format_time = $format_explode[0];




										$datetime = new DateTime("@$unix_timestamp");

										$date_time_format = date_format($datetime, $format_time);
										//$date_time_format_h = date_format($datetime, $format_time_h);


										$time_zone_from = "UTC";

										try {

											$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));

											//$display_date_h = new DateTime($date_time_format_h, new DateTimeZone($time_zone_from));

											$display_date->setTimezone(new DateTimeZone($time_zone_to));

											//$display_date_h->setTimezone(new DateTimeZone($time_zone_to));

											//$output_object_value = $display_date->format($format_time).' at '.$display_date_h->format($format_time_h);

											$output_object_value = $display_date->format($format_time);

										 } catch (Exception $e) {

											$output_object_value = '<p><strong class="text-success">Date Data: </strong>Error Date and Timezone Format!</p>';

										}



			                        } else {

			                        	  $output_object_value = $object_vars[$p->field];
			                        }

			                      	echo $output_object_value;



					                            ?></span>
					                        </div>
					                      
					                    </div>


				                    <?php
				                    	}
				                    		//echo '<pre>BackgroundColor - '.print_r($options_decod->backgroundColor, true).'</pre>';
				                    ?>

				                </div>
				            </div>

				            <?php

		            	}

		       		 }

		            ?>


		        </div>
		    </div>

		</div>

		<!-- End Register Information Tempate -->

	    <?php

	    $output = ob_get_clean();

	    return $output;
	}

		 
}

