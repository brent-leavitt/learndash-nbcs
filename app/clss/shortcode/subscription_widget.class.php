<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Subscription Overview Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Subscription_Widget{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		if( !nb_role_is( 'trainer' ) && !nb_role_is( 'administrator' ) )
		{
			
			echo "<h3>Subscription</h3>";

			echo "<p>Details of your active or inactive subscription goes here.</p>";
		}
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>