<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Reader Upgrade Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Reader_Upgrade{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL, $handler ){
		
		ob_start();
		
		if( nb_role_is( 'reader' ) )
		{
			?>

			<h3>Ready to Certify?</h3>
			<p>Upgrade your subscription to "Student" and start submitting assignments today. 
				Get access to a personal trainer and community of doula students! </p>

			<p><a href="/register/">Upgrade!</a></p> 
			<?php
		}
	
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>