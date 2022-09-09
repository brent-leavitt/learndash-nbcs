<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Payment Shortcodes Class
 *
 * 	
 *
 * 
 */

class Account{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $atts ){
		
	
		$atts_arr = shortcode_atts( array(
				'show' => '',	
			), $atts );

		//print_pre( $atts );
		
		return 'We are calling the ACCOUNT CB function!!';
				 
		
	
	}	
	
	
	
	/* public static function load_callback( $a, $b, $c ){
		
		ob_start();
		
		print_pre( $a );
		print_pre( $b );
		print_pre( $c );

		
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	} */	
	

}
?>