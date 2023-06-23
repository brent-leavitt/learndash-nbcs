<?php

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Shortcode\Shortcodes;

if ( !defined( 'ABSPATH' ) ) { exit; }


/**
 *  build_shortcodes
 *	
 *	(Description)
 *	
 * 	
 *	Returns
 */	

function build_shortcodes( ){
	
	//	REMINDER: Actual shortcode names will be prepended with the NBCS_PREFIX: 'nbcs_'. 
	//	ex: payment = nbcs_payment

	$shortcodes = [ 
		'alumnus_certificates', 	// For Alumnus, Certificates earned for the Sidebar Widget
		'billing_summary', 			// Billing Summary on the Accounts Overview Page
		'message', 					//
		'page_menu', 				// Adds an in page menu for jumping to various parts of the page. 
		'profile_widget', 			// Profile Summary for the Sidebar Widget
		'profile_overview', 		// User Profile Overview on the Accounts Overview Page
		'progress_widget', 			// For Students, Progress Report Summary for the Sidebar Widget
		'reader_upgrade', 			// For Readers, an upgrade to Student notice for the Sidebar Widget
		'students_only', 			// Adds Students-only features such as New Student Orientation, Support, and coaching links. 
		'subscription_summary', 	// Subscription Summary on the Accounts Overview Page
		'subscription_widget', 		// Subscription Summary for the Sidebar Widget
	/* 	'payment',  		//
		'progress_report',	//
		'register', 		//
		'register_lite', 	//
		'login', 			//	
		'account', 			// 
		'cashier', 			//
		'payment_login', 	//
		'receipt' 			// */
	];


	//print_pre( $shortcodes, "Short codes array loaded on line ".__LINE__." from file ".__FILE__ ); 
	foreach( $shortcodes as $sc ){


		$class_name =str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $sc ) ) );
		$short_code = NBCS_PREFIX . $sc;
		//print_pre( $short_code, "Shortcode var loaded on line ".__LINE__." from file ".__FILE__ ); 
		//print_pre( $class_name, "classname loaded on line ".__LINE__." from file ".__FILE__ ); 
		add_shortcode( $short_code , array( 'Doula_Course\App\Clss\Shortcode\\'.$class_name, 'load_callback' ) ); 
		
	}	
}

add_action( 'init', 'Doula_Course\App\Func\build_shortcodes' );

?>