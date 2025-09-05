<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Receipt Shortcodes Class
 *
 * 	
 *
 * 
 */

class Receipt{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $atts ){
		
		if(!is_user_logged_in()) {
	 
			$post = array(
				'tx_id' => $_REQUEST[ 'tx_id' ],
				'action' => 'receipt'
			);
			
			$uforms = new UserForms();
			
			$output = $uforms->form( 'login', $post );
			
/* 				$output = '<p>Testing this field!</p>'; */
			
		} else {
			
			$tx_id = $_REQUEST[ 'tx_id' ];
			
			$output = "<p>The transaction ID is: $tx_id</p>";
			
			
			// could show some logged in user info here
			// $output = 'user info here';
			// Or do a redirect. 
			
			$output .= '<p>This will programatically pull the receipt by the provided transaction ID! There some work to be done here, because all receipt should be stored elsewhere on the network. But loading a receipt for review should be a network-wide function. Perhaps a function called nn_get_receipt( $receipt_id ) could universally load a receipt by its ID? </p>';
			
		}
		return $output;
	}	
	

}
?>