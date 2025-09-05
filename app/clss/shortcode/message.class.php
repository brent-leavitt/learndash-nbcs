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

class Message{
	
	
	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attributes, $content = NULL, $handler ){
		
		if( !isset( $_GET[ 'student_id' ] ) || strcmp( NBCS_PREFIX.'message', $handler ) !== 0 )
			return; 
		
		if( ( $student_id = intval( $_GET[ 'student_id' ] ) ) === 0 )
			return; 
		
		$default_attr = [
			'first_name',
			'last_name',
			'student_address', 
			'student_address2', 
			'student_city', 
			'student_state', 
			'student_country', 
			'student_postalcode',  
			'student_phone',  
		];
		
		$student = get_user_by( 'id', $student_id ); 
		$output = []; 
		
		foreach( $attributes as $attr ){
			if( in_array( $attr, $default_attr ) )
				$output[] = $student->$attr;	
		}
				
		return implode( ' ', $output );
		
	}	
	

}
?>