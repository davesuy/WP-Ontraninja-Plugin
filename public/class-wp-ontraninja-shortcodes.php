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

		add_shortcode( 'wp_ontraninja_fieldeditor', array($this,'wp_ontraninja_fieldeditor_func') );


		add_shortcode( 'wp_ontraninja_register_information', array($this,'wp_ontraninja_register_information_func') );

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

		$atts = shortcode_atts( array(
	        'register_id' => ''
	       
	    ), $atts, 'wp_ontraninja_register_information' );


		ob_start();

	    ?>

	    <div class="demo">
		    <div class="container">
		        <div class="row">

		        	<?php

		        	$requestParams = array(
					    "objectID" => 10029, 
					    "id"       => $atts['register_id']
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
								

									$time_zone_to ='Australia/Sydney';
									$format_time = 'j M Y';




									$datetime = new DateTime("@$unix_timestamp");

									$date_time_format = date_format($datetime, $format_time);



									$time_zone_from = "UTC";


									try {

										$display_date = new DateTime($date_time_format, new DateTimeZone($time_zone_from));

										$display_date->setTimezone(new DateTimeZone($time_zone_to));

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

	    <?php

	    $output = ob_get_clean();

	    return $output;
	}

		 
}

