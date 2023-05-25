<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Account Widget for an Overview of All Certificates Earned Shortcodes Class
 *
 * 	
 *
 * 
 */

class Certificates_Overview{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL, $handler = NULL ){
		
		ob_start();
		
		//get available certificates for user. 

		echo "<p>An overview of the certificates available goes here.</p>"; 
		//If not avaialble, post a preview or promo code here. 
		print_pre( $attr, "Attributes of the Shortcode"  );
		print_pre( $content, "There is no CONTENT" );
		print_pre( $handler, "The name of the shortcode handler" );

		
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>