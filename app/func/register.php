<?php

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }

/* add_action( 'login_enqueue_scripts', function()
{	
	wp_enqueue_style( 'login-styles', $login_css );
}); */

if ( ! has_action( 'login_enqueue_scripts', 'wp_print_styles' ) )
    add_action( 'login_enqueue_scripts', 'wp_print_styles', 11 );

add_filter( 'login_headerurl', 'Doula_Course\App\Func\custom_login_header_url' );

function custom_login_header_url($url) {
	
	return 'https://www.trainingdoulas.com/';
}

function custom_styles() {
	$login_css = plugins_url( 'doula-course/templates/login-styles.css' );
	echo "<link href='//fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
	<link href='$login_css' rel='stylesheet' type='text/css'>";
}

add_action('login_head', 'Doula_Course\App\Func\custom_styles');


function no_login(){
	
	$request_uri = $_SERVER['REQUEST_URI'];
	
	//if request_uri ends in .js we are not going to force login. 
/* 	if( strcmp( substr( $request_uri, -3 ), '.js' ) == 0 )
		return true; */
	
	//strings from URLS that don't require logins to access. 
	$no_login = array(
		"cashier",
		"check-in",
		"moo",
		"register",
		"complete-registration",
		"new-student-coaching",
		"registration-completed",
		"payment-complete",
		"payment-completed",
		"wp-includes"
	);
	
	foreach( $no_login as $val ){
		if( strpos( $request_uri, $val ) )
			return true;
	}
	return false;
}

function nbdt_force_login(){

	if(!is_user_logged_in()){
		if( !no_login() ){
		
			$script_url = urlencode( $_SERVER['REQUEST_URI'] );
			$go_url = get_bloginfo('url').'/check-in?redirect_to='.$script_url;
			wp_redirect( $go_url ); exit;
		
		}
	}
	
	// Force User Login 
	
}

//add_action( 'init', 'Doula_Course\App\Func\nbdt_force_login' );


/*Restrict Content Pro - Registration Overrides
 *
 */
 
 function nb_add_detail_to_title_label( $title, $detail ){
	 	 
	 return ( !empty( $detail ) )?  $title .' <span>('. __( $detail, NBCS_TD ) . ')</span>' : $title ;
	 
 }
 
 
//MAIN REGISTRATION FORMS
//Username

function nb_reg_username_label( $title ){

	$detail = 'Subject to availablility, no spaces please. This cannot be changed after registration is completed. No one sees this except for you when you log in. ';
	return nb_add_detail_to_title_label( $title, $detail );  
}

add_filter( 'rcp_registration_username_label', 'Doula_Course\App\Func\nb_reg_username_label', 10 );

//Email

function nb_reg_email_label( $title ){

	$detail = 'Valid email address for course correspondence only; PLEASE double check your spelling!';
	return nb_add_detail_to_title_label( $title, $detail );  
}

add_filter( 'rcp_registration_email_label', 'Doula_Course\App\Func\nb_reg_email_label', 10 );


//First Name

function nb_reg_firstname_label( $title ){

	$detail = 'Your first given name that you are formally recognized as; this will be printed on your certificate if registering for certification.';
	return nb_add_detail_to_title_label( $title, $detail );  
}

add_filter( 'rcp_registration_firstname_label', 'Doula_Course\App\Func\nb_reg_firstname_label', 10 );

//Last Name
function nb_reg_lastname_label( $title ){

	$detail = 'Your current legal last name; this will also be printed on your certificate.';
	return nb_add_detail_to_title_label( $title, $detail );  
}

add_filter( 'rcp_registration_lastname_label', 'Doula_Course\App\Func\nb_reg_lastname_label', 10 );

/*
* EXTRA FIELDS
* Display Name
* Address 
* Address2
* City/Town
* State/Province/Region
* Postal Code/Zip Code
* Country
* Phone Number 
*/

function nb_reg_extra_user_fields() {
	
	$sets = [
		[
			'name' => 'nickname',
			'title' => 'Display Name',
			'detail' => 'Your preferred name for contact and for public and private communications; nicknames are ok here.'
		],
		[
			'name' => 'address',
			'title' => 'Address',
			'detail' => 'Current physical address; may be used for connecting to your local birth community, such as other doulas-in-training, alumni, and potential clients.'
		],
		[
			'name' => 'address2',
			'title' => 'Address, 2nd Line',
			'detail' => 'Apt#, etc.'
		],
		[
			'name' => 'city',
			'title' => 'City or Town',
			'detail' => ''
		],
		[
			'name' => 'state',
			'title' => 'State/Province/Region',
			'detail' => ''
		],
		[
			'name' => 'postalcode',
			'title' => 'Postal or Zip Code',
			'detail' => ''
		],
		[
			'name' => 'country',
			'title' => 'Country',
			'detail' => ''
		],
		[
			'name' => 'phone',
			'title' => 'Phone Number',
			'detail' => 'If we should need to contact you by phone, please provide a current, working number.'
		]
	];
	
	echo "<hr>"; 

	foreach( $sets as $args )
		nb_add_single_user_field( $args ); 
		
	echo "<hr>"; 

}


add_action( 'rcp_after_password_registration_field', 'Doula_Course\App\Func\nb_reg_extra_user_fields' );



function nb_profile_extra_user_fields() {
	
	$sets = [
		[
			'name' => 'address',
			'title' => 'Address',
			'detail' => 'Current physical address; used for connecting with other students and potential clients  .'
		],
		[
			'name' => 'address2',
			'title' => 'Address, 2nd Line',
			'detail' => 'Apt#, etc.'
		],
		[
			'name' => 'city',
			'title' => 'City or Town',
			'detail' => ''
		],
		[
			'name' => 'state',
			'title' => 'State/Province/Region',
			'detail' => ''
		],
		[
			'name' => 'postalcode',
			'title' => 'Postal or Zip Code',
			'detail' => ''
		],
		[
			'name' => 'country',
			'title' => 'Country',
			'detail' => ''
		],
		[
			'name' => 'phone',
			'title' => 'Phone Number',
			'detail' => 'Should we need to contact you by phone, please provide a current, working number. Text, voice, or cell numbers are okay.'
		]
	];
	

	foreach( $sets as $args )
		nb_add_single_user_field( $args ); 
		
}

add_action( 'rcp_profile_editor_after', 'Doula_Course\App\Func\nb_profile_extra_user_fields' );



function nb_add_single_user_field( $args ){
	extract( $args ); 
	
	$name = nb_reg_get_meta_name( $name );
		
	$value = get_user_meta( get_current_user_id(), $name, true );
	
	echo "
	<p>
		<label for='{$name}'>". nb_add_detail_to_title_label( $title, $detail ) ."</label>
		<input name='{$name}' id='{$name}' type='text' value='". esc_attr( $value ) ."'/>
	</p>";
	
}


/**
 * Determines if there are problems with the registration data submitted
 *
 */

function nb_validate_user_fields_on_register( $posted ) {

	if ( is_user_logged_in() )
	   return;	
	
	$sets = [
		[
			'name' => 'nickname',
			'message' =>  'Please enter your desired display name.'
		],
		[
			'name' => 'address',
			'message' =>  'Please enter your primary street address.'
		],
		[
			'name' => 'city',
			'message' =>  'Please enter your city or town name.'
		],
		[
			'name' => 'state',
			'message' =>  'Please enter the name of the state, province, or region where you live.'
		],
		[
			'name' => 'postalcode',
			'message' =>  'Please enter your postal code or zip code.'
		],
		[
			'name' => 'country',
			'message' =>  'Please enter the name of the country were you live.'
		],
		[
			'name' => 'phone',
			'message' =>  'Please enter a working phone number. Text or voice numbers are okay.'
		]
	];
	
	foreach( $sets as $args )
		nb_add_reg_error( $posted, $args ); 

}

add_action( 'rcp_form_errors', 'Doula_Course\App\Func\nb_validate_user_fields_on_register', 10 );


function nb_add_reg_error( $posted, $args ){
	extract( $args ); 
	
	$name = nb_reg_get_meta_name( $name );
	
	if( empty( $posted[ $name ] ) )
		rcp_errors()->add( 'invalid_'.$name , __( $message, NBCS_TD ), 'register' );
	
}


/**
 * Stores the information submitted during registration
 *
 */
function nb_save_user_fields_on_register( $posted, $user_id ) {

$fields = [
			'nickname',
			'address',
			'address2',
			'city',
			'state',
			'postalcode',
			'country',
			'phone'
		];

	foreach( $fields as $name )
		nb_reg_update_user_meta( $name, $posted, $user_id ); 

}
add_action( 'rcp_form_processing', 'Doula_Course\App\Func\nb_save_user_fields_on_register', 10, 2 );

function nb_reg_update_user_meta( $name, $posted, $user_id ){
	
	$name = nb_reg_get_meta_name( $name );
	
	if( ! empty( $posted[ $name ] ) )
		update_user_meta( $user_id, $name, sanitize_text_field( $posted[ $name ] ) );
}


//Skip for meta_key 'nickname' only, assumes that all other meta is prefixed with 'student_'
function nb_reg_get_meta_name( $name ){
	
	return ( strcmp( 'nickname', $name ) !== 0) ?  'student_' . $name : $name  ;
	
}

//Allows for grandfathering of current registation levels. 
add_filter( 'rcp_can_renew_deactivated_membership_levels', '__return_true' );
?>