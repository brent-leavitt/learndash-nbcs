<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Progress Sidebar Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Progress_Widget{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		echo "<h3>Program Progress</h3>";

		echo "<p>Contents of progress will be displayed here depending upon whether any course progress has been made yet.</p> <p>Only display programs where progress has been made if any.</p>";
		
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>