<?php

namespace Doula_Course\App\Clss\Registar;

use Doula_Course\App\Clss\Interfaces\Registar_Action;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * This is the Login Sub-Action class for the Registar class
 * 
 * 
 */

class Login implements Registar_Action {
	
	

	 /**
	 * registar
	 * 
	 * The registar Object that is the controller for this object. 
	 *
	 * @since 2.0
	 * @var object
	 */	
	private $registar; 
	 
	 
	
	/**
	 *  
	 *
	 *
	 */
	 
	public function __construct( ){
		
	
		
	}
	
		
		
	/**
	 * set_registar
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return void
	 */
	 
	public function set_registar( object $registar ): VOID
	{
		$this->registar = $registar; 
	
	}
	
		
	/**
	 * _
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return void
	 */
	 
	private function do_action( ): VOID
	{
		if( !is_user_logged_in() ){ 
		
			$content = __( 'You\'re not logged in, so let\'s fix that: <br>' ); 
			$content .= do_shortcode( '[nbcs_login]' );
			$this->registar->add_output( $content ); 
		
		}
	}
		
	

}
?>