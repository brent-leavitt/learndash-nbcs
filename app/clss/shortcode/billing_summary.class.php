<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Billing Summary Shortcodes Class
 *
 * 	
 *
 * 
 */

class Billing_Summary{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		echo "<h2>Billing Summary</h2>";
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>