<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Progress Report Shortcodes Class
 *
 * 	
 *
 * 
 */

class Progress_Report{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $a, $b, $c ){
		
		ob_start();
		
		/* print_pre( $a );
		print_pre( $b );
		print_pre( $c ); */

		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
}
?>