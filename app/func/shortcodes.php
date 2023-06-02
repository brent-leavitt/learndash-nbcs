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
		'profile_overview', 		// User Profile Overview on the Accounts Overview Page
		'certificates_overview', 	// Certificates Overview on the Accounts Overview Page
		'subscription_summary', 	// Subscription Summary on the Accounts Overview Page
		'billing_summary', 			// Billing Summary on the Accounts Overview Page
		'trainer_assignments', 		// For Trainers, an overview of assignments for the Sidebar Widget
		'alumnus_certificates', 	// For Alumnus, Certificates earned for the Sidebar Widget
		'progress_widget', 			// For Students, Progress Report Summary for the Sidebar Widget
		'reader_upgrade', 			// For Readers, an upgrade to Student notice for the Sidebar Widget
		'subscription_widget', 		// Subscription Summary for the Sidebar Widget
		'profile_widget', 			// Profile Summary for the Sidebar Widget
		'toggle_sidebar', 			// A master shortcode that allows for us to programmatically switch what gets shown based on logged in status.
		'page_menu', 				// Adds an in page menu for jumping to various parts of the page. 
		'students_only', 			// Adds Students-only features such as New Student Orientation, Support, and coaching links. 
		'message', 			//
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