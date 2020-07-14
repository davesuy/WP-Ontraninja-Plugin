(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );


jQuery( document ).ready(function() { 






////// First Name

	if ( jQuery( "#won-shortcode-request" ).length ) {



		val_object = jQuery( "#objectID" ).val();
		val_accountID = jQuery( "#account_id" ).val();
		val_field = jQuery( "#fieldAttr" ).val();



		 jQuery.post(won_ajax_object.ajax_url, {"action" : "request_data", "objectID" : val_object, "account_id": val_accountID, "field_attr" :val_field}) // you will need to get the ajax url for you site
	     .done(function(data){
	     	
	     	//console.log(data);

	     	jQuery('.val_data_request').text(data);
	  
	   
	     }).success(function() {

	     	 //alert('Added successFully :');

	     	jQuery('.gif-loader-first').css('display', 'none');
	   
    	
 		 });
 

 

   	}




	jQuery("#won-shortcode-request-other .input-data-val").each(function() {

		var op_val = jQuery(this).val();


		var op_val_res = op_val.replace(/ /g, "_");

		var op_format = jQuery('.input-data-val-format').val();
		var op_label = jQuery('.input-data-val-label').val();
		var op_color = jQuery('.input-data-val-color').val();

		// var op_id = jQuery(this).attr('ajax_attr');


		// var op_id_res = op_id.replace(/ /g, "_");
	
		//console.log(op_id);
		if ( jQuery( "#won-shortcode-request-other" ).find(".won-shortcode-request-other_" + op_val_res) ) {

	   		
			//console.log(op_val_res);

	   	    val_object_other = jQuery( "#objectID_other" ).val();
			val_accountID_other = jQuery( "#account_id_other" ).val();
			val_field_other = jQuery( "#fieldAttr_other" ).val();

			//val_field_other_str = jQuery( "#field_attr_str" ).val();

			val_field_other_str = op_val;

			//val_field_attr_type = jQuery( "#field_attr_type" ).val();


			 jQuery.post(won_ajax_object.ajax_url, {

			 	"action" : "request_data_other", 
			 	"objectID" : val_object_other, 
			 	"account_id": val_accountID_other, 
			 	"format": op_format,
			 	//"label": op_label,
			 	"color": op_color,
			 	"field_attr_other": val_field_other_str

			}) 
			.done(function(data){
		     	
		     	//console.log(val_field_other_str_type);

		    	data_res = jQuery.trim(data);

		    	console.log(data);
		     		
		     	if (data.indexOf("bg") >= 0) {

		     		var str = data;
					var res = str.replace("bg_", "");

					jQuery('.ontraninja-con-bg').css("background", res);

		     	} else if(data.indexOf("cc") >= 0) {

		     		
		     		
		     		var strc = data;
					var resc = strc.replace("cc_", "");

					jQuery('.ontraninja-text').css("color", resc);

		     	} else {

		     		jQuery('.val_data_request_other_' + op_val_res).text(data_res);


		     	}


		     }).success(function(data) {

		     	 //alert('Added successFully :');
		     	// console.log(op_id_res + "-id-here");




		     		jQuery('.gif-loader-'+ op_val_res).css('display', 'none');

		     	
		   
	    	
	 		 });

		     	//alert(1);
	 	}


	});




	

		// if ( jQuery( "#won-shortcode-request-other" ).find(".input-data-valx") ) {

	   		
		// 	//console.log(op_val_res);

	 //   	    val_object_other = jQuery( "#objectID_other" ).val();
		// 	val_accountID_other = jQuery( "#account_id_other" ).val();
		// 	val_field_other = jQuery( "#fieldAttr_other" ).val();

		// 	//val_field_other_str = jQuery( "#field_attr_str" ).val();

		// 	//val_field_other_str = op_val;

		// 	//val_field_attr_type = jQuery( "#field_attr_type" ).val();


		// 	 jQuery.post(won_ajax_object.ajax_url, {

		// 	 	"action" : "request_data_other", 
		// 	 	"objectID" : val_object_other, 
		// 	 	"account_id": val_accountID_other, 
		// 	 	"format": "j M Y (+00:00)",
		// 	 	"field_attr_other": "Event Start Time"

		// 	}) 
		// 	.done(function(data){
		     	
		//      	//console.log(val_field_other_str_type);

		//     	data_res = jQuery.trim(data);
		     		
		//      	jQuery('.val_data_request_other_Event_Start_Time').text(data_res);


		//      }).success(function() {

		//      	 //alert('Added successFully :');

		//      	jQuery('.gif-loader-Event_Start_Time').css('display', 'none');
		   
	    	
	 // 		 });

		//      	//alert(1);
	 // 	}




// ///////////// Type

//    if ( jQuery( "#won-shortcode-request-other" ).find(".won-shortcode-request-other_Type") ) {

   	

//    	    val_object_other = jQuery( "#objectID_other" ).val();
// 		val_accountID_other = jQuery( "#account_id_other" ).val();
// 		val_field_other = jQuery( "#fieldAttr_other" ).val();

// 		//val_field_other_str = jQuery( "#field_attr_str" ).val();

// 		val_field_other_str_type = "Type";

// 		//val_field_attr_type = jQuery( "#field_attr_type" ).val();


// 		 jQuery.post(won_ajax_object.ajax_url, {

// 		 	"action" : "request_data_other", 
// 		 	"objectID" : val_object_other, 
// 		 	"account_id": val_accountID_other, 

// 		 	"field_attr_other": val_field_other_str_type

// 		}) 
// 		.done(function(data){
	     	
// 	     	//console.log(val_field_other_str_type);
	     	
// 	     	jQuery('.val_data_request_other_' + val_field_other_str_type).text(data);


// 	     }).success(function() {

// 	     	 //alert('Added successFully :');

// 	     	jQuery('.gif-loader-'+ val_field_other_str_type).css('display', 'none');
	   
    	
//  		 });

// 	     	//alert(1);
//  	}


// ///////////// Price

//    if ( jQuery( "#won-shortcode-request-other" ).find(".won-shortcode-request-other_Price") ) {

   	

//    	    val_object_other = jQuery( "#objectID_other" ).val();
// 		val_accountID_other = jQuery( "#account_id_other" ).val();
// 		val_field_other = jQuery( "#fieldAttr_other" ).val();

// 		//val_field_other_str = jQuery( "#field_attr_str" ).val();

// 		val_field_other_str_price = "Price";

// 		//val_field_attr_type = jQuery( "#field_attr_price" ).val();


// 		 jQuery.post(won_ajax_object.ajax_url, {

// 		 	"action" : "request_data_other", 
// 		 	"objectID" : val_object_other, 
// 		 	"account_id": val_accountID_other, 

// 		 	"field_attr_other": val_field_other_str_price

// 		}) 
// 		.done(function(data){
	     	
// 	     	//console.log(val_field_other_str_price);
	
// 	     	jQuery('.val_data_request_other_' + val_field_other_str_price).text(jQuery.trim(data));


// 	     }).success(function() {

// 	     	 //alert('Added successFully :');

// 	     	jQuery('.gif-loader-'+ val_field_other_str_price).css('display', 'none');
	   
    	
//  		 });

// 	     	//alert(1);
//  	}


// ///////////// Event

//    if ( jQuery( "#won-shortcode-request-other" ).find(".won-shortcode-request-other_Event-Selection") ) {

   	

//    	    val_object_other = jQuery( "#objectID_other" ).val();
// 		val_accountID_other = jQuery( "#account_id_other" ).val();
// 		val_field_other = jQuery( "#fieldAttr_other" ).val();

// 		//val_field_other_str = jQuery( "#field_attr_str" ).val();

// 		val_field_other_str_Event_Selection = "Event Selection";
// 		val_field_other_str_Event_Selection_ = "Event_Selection";

// 		//val_field_attr_type = jQuery( "#field_attr_price" ).val();


// 		 jQuery.post(won_ajax_object.ajax_url, {

// 		 	"action" : "request_data_other", 
// 		 	"objectID" : val_object_other, 
// 		 	"account_id": val_accountID_other, 

// 		 	"field_attr_other": val_field_other_str_Event_Selection

// 		}) 
// 		.done(function(data){
	     	
// 	     	//console.log(data + 'gana');
	   
	
// 	     	jQuery('.val_data_request_other_'+ val_field_other_str_Event_Selection_).text(data);


// 	     }).success(function() {

// 	     	 //alert('Added successFully :');

// 	     	jQuery('.gif-loader-' + val_field_other_str_Event_Selection_).css('display', 'none');
	   
    	
//  		 });

// 	     	//alert(1);
//  	}



	
});
