<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Logged In or Out Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Toggle_Sidebar{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		$logged_in = is_user_logged_in(); 
		
		if( !$logged_in )//Not Logged In
		{
			echo "<p>User is not logged in.</p>"; 
		}
		else //Is Logged In
		{
			echo Trainer_Assignments::load_callback( [] ); 
			echo Reader_Upgrade::load_callback( [] );  
			
			echo "<p>User is logged in!</p>";

		}
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>