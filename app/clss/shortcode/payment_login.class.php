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

class Payment_Login{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $atts ){
		
		// Lifted from PIPPIN
		
			if(!is_user_logged_in()) {
	 
				//global $nn_load_css;
		 
				// set this to true so the CSS is loaded
				//$nn_load_css = true;
				
				$output = self::get_user_forms();
				
/* 				$output = '<p>Testing this field!</p>'; */
				
			} else {
			
			
				// could show some logged in user info here
				// $output = 'user info here';
				// Or do a redirect. 
				
				$output = '<p>You are already logged in. Move on!</p>';
				
			}
			return $output;
	}	
	
	public static function get_user_forms( $post = '', $atts = '' ){
		
		$uforms = new UserForms();
		
		//GET REGISTRATION FORM
		$output = $uforms->form( 'registerlite', $post );
		
		// GET LOGIN FORM
		$output .= $uforms->form( 'login', $post );
		
		//ALLOW FOR SKIP REGISTRATION OPTION, JUMP STRAIGHT TO CHECKOUT FOR GUEST REGISTRATION
		//if Skip Attr is set
		
		//The code below was giving this errror message: 
		//Notice: Uninitialized string offset: 0 in /home/87259.cloudwaysapps.com/kquamczdrq/public_html/wp-content/plugins/nn-network/nn_network/init/ShortCodes.class.php on line 330
		
		/* if( $atts[ 0 ] == 'skip' ){
			
			$output .= "<h2>Guest Registration?</h2> 
			<p>Are you registering on behalf of someone else? Not ready to make an account?</p> ";
			
			$output .= $uforms->skip( $post );
			
			$output .= "<p>All account details will be sent to the email used at time of payment.</p>";
		} */
		
		
		return $output;
	}
}
?>