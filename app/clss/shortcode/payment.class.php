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

class Payment{
	
	/**
	 * Payment Shortcode Class 
	 * 
	 * [nbcs_payment service_id="BDC" enrollment="certification_monthly"]
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $atts ){
		
		$atts_arr = shortcode_atts( array(
				'service_id' => '',	//Three Uppercase letter code that represents a company service (ie. BDC = 'birth doula certification')`
				'enrollment' => '', //see enrollment token types for full list of available types
			), $atts );

		return self::get_payment_form( $atts_arr );	
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	
	
	
	public static function get_payment_form( $atts ){
		
		$enrollment = $atts[ 'enrollment' ];
		$service = $atts[ 'service_id' ];
		$action = 'nbcs_payment_'.$enrollment.'_'.$service;
				
		$nonce = wp_nonce_field( $action, $action.'_nonce', false, false );
		
		$patron = wp_get_current_user();
		$patron_id = ( !empty( $patron ) )? $patron->ID : 0 ; 
		
		//$url_ref = $_SERVER['REQUEST_URI']; may be irrelevant
		
		$btn = "
		<form method='post' action='/cashier/'>
			<input type='hidden' name='action' value='checkout' />	
			<input type='hidden' name='enrollment' value='$enrollment' />	
			<input type='hidden' name='patron' value='$patron_id' />	
			<input type='hidden' name='service' value='$service' />	
			$nonce		
			<input type='submit' value='Start Now' />
		</form>";
		
		return $btn;
		
	}
	

}
?>