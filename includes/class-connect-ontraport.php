<?php

use OntraportAPI\Ontraport;

class Wp_Ontraninja_Connect_Ontraport {

	public $ak_response_contact_decode_data;
	public $response_contact_decode;
	public $response_decode;




	public function connect_api_shortcode( $objectID,  $account_id) {
		

		/** Connect API Regsistration **/

		$requestParams = array(
		    "objectID" => $objectID, // Object type ID: 14
		    "id"       => $account_id
		);

		$instance_get_client = new Wp_Ontraninja;


		$response = $instance_get_client->get_client()->object()->retrieveSingle($requestParams);



		$response_decode = json_decode($response);

		if($response_decode == "") {
			return;
		}


		$this->response_decode = $response_decode;


    	$contact_id = $response_decode->data->{"f2915"};

    	

		$requestParamsContact = array(
		    "id" => $contact_id
		);

		$response_contact = $instance_get_client->get_client()->contact()->retrieveSingle($requestParamsContact);

		if($response_contact == "") {
			return;
		}

		$response_contact_decode = json_decode($response_contact);

		$this->response_contact_decode = $response_contact_decode;

		//echo '<pre>'.print_r($response_contact_decode, true).'</pre>';



		$ob_response_contact_decode = get_object_vars($response_contact_decode->data);

		$ak_response_contact_decode_data = array_keys($ob_response_contact_decode);


		$this->ak_response_contact_decode_data = $ak_response_contact_decode_data;


		/** End Connect API Regsistration **/

	}

}


