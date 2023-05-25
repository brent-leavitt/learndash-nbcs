<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Profile Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Profile_Widget{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		$user_info = wp_get_current_user(); 
	
		//Username 
		echo "<p>{$user_info->display_name}</p>
		<p>( {$user_info->user_email} )</p>";
		//Email

				
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>