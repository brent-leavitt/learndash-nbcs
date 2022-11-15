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

function build_shortcodes( array $shortcodes ){
	
	foreach( $shortcodes as $sc ){
		$class_name = "Doula_Course\App\Clss\Shortcode\\" . ucfirst( $sc );
		$short_code = NBCS_PREFIX . $sc;
		add_shortcode( $short_code , array( $class_name, 'load_callback' ) ); 
	}	
	
}


//	REMINDER: Actual shortcode names will be prepended with the NBCS_PREFIX: 'nbcs_'. 
//	ex: payment = nbcs_payment

build_shortcodes( [ 
/* 	'payment',  		//
	'progress_report',	//
	'register', 		//
	'register_lite', 	//
	'login', 			//	
	'account', 			// */
	'message', 			//
/* 	'cashier', 			//
	'payment_login', 	//
	'receipt' 			// */
] );

?>