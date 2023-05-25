<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Alumnus Certificates Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Alumnus_Certificates{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		if( nb_role_is( 'alumnus' ) )
		{
			?>

			<h3>Alumnus Certificates</h3>
			<p>List of Certificates Found Here:</p>
			<ul>
				<li>Birth Doula (Expires 12/23/2024) </li>
				<li>Postpartum Doula (Expired 11/05/2019, renew &raquo;)</li>
				<li>Fertility Doula (Expires 01/04/2025)</li>	
			</ul>

			<p><a href="/renewal/">Manage Certificates</a></p> 
			<?php
		}
		
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>