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

?>