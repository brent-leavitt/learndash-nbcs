<?php

namespace Doula_Course\App\Clss\Shortcode;

use Doula_Course\App\Clss\User_Page\User_Page_Director;
use Doula_Course\App\Clss\User_Page\User_Page_Builder;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Payment Shortcodes Class
 *
 * 	
 *
 * 
 */

class Register_Lite{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $a, $b, $c ){
		
		
		if(!is_user_logged_in()) {
 
			$director  = new User_Page_Director( 'register_lite' );
			$builder = new User_Page_Builder( 'Create Account' );
			$director->set_builder( $builder );

			$director->build();	
			$output = $builder->get_page()->render( false );
			
			
		}
		
		
		return $output;
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>