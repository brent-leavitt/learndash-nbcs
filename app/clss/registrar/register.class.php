<?php

namespace Doula_Course\App\Clss\Registar;

use Doula_Course\App\Clss\Interfaces\Registar_Action;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * This is the _ Sub-Action class for the Registar class
 * 
 * 
 */

class Register implements Registar_Action {
	
	

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
	 * @return NULL
	 */
	 
	public function set_registar( object $registar ): NULL
	{
		$this->registar = $registar; 
	
	}
	
		
	/**
	 * do_action
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return void
	 */
	 
	private function do_action( ): void
	{
		if( !is_user_logged_in() ){ 
		
			$content = __( 'If you don\'t have an account, let\'s make one now!: <br>' ); 
			$content .= do_shortcode( '[nbcs_register_lite]' );	
			
			$content .= '<hr>';
			
			$redirect = urlencode( '/cashier/?enrollment='.$this->registrar->args[ 'enrollment' ].'&service='. $this->registrar->args[ 'service' ] );
			
			$content .= __( 'Already have an account? Go ahead and <a href="/check-in?redirect_to='. $redirect .'">login now</a>. <br>' ); 
			
			
			$this->output .= $content;
			
			return;
		
		}
		
		$this->registrar->action = 'checkout';
		$this->registrar->go();
		return;
	
	}
		
	

}
?>