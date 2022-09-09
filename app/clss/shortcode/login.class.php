<?php

namespace Doula_Course\App\Clss\Shortcode;

use Doula_Course\App\Clss\User_Page\User_Page_Director;
use Doula_Course\App\Clss\User_Page\User_Page_Builder;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Login Shortcodes Class
 *
 * 	
 *
 * 
 */

class Login{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $atts ){
		
		
			if(!is_user_logged_in()) {
	 
				$director  = new User_Page_Director( 'login' );
				$builder = new User_Page_Builder( 'Check In' );
				$director->set_builder( $builder );
				
				$director->build();	
				$output = $builder->get_page()->render( false );
				
				
			} else {
			
			
				// could show some logged in user info here
				// $output = 'user info here';
				// Or do a redirect. 
				
				$output = '<p>You are already logged in. Move on!</p>';
				
			}
			return $output;
	}	
	

}
?>