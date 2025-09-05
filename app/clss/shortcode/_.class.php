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

class _{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL, $handler ){
		
		ob_start();
		
		print_pre( $attr, "Attributes of the Shortcode"  );
		print_pre( $content, "There is no CONTENT" );
		print_pre( $handler, "The name of the shortcode handler" );

		
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>