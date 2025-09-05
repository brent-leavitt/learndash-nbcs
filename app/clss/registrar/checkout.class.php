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

class Checkout implements Registar_Action {
	
	

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
		
		if( $this->require_login() )
			return;
		
		$content = __( 'Let\'s checkout!: <br>' ); 
		//Option for skipped registration.
		
		//Explaination of Transaction
		
		
		//Payment buttons. 	
		$content .= do_shortcode( '[nbcs_m service='. $this->registrar->get_arg( 'service' ) .' enrollment='. $this->registrar->get_arg(  'enrollment' ) .' patron='. $this->registrar->get_arg( 'patron' ) .' ]' );	
		
		$this->registrar->add_output = $content;
	
	}
	
	
	/**
	 * require_login
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return bool
	 */
	 
	private function require_login( ): bool
	{
		
		if( $this->registar->is_login_required() && empty( $this->registrar->get_patron() ) ){
			
			//not logged in, redirect to register page. 
			$this->registar->set_action = 'register';
			$this->register->go();
			return true;
		}
		
		return false;
	}

}
?>