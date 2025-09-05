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

class _ implements Registar_Action {
	
	

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
		
	
	}
		
	

}
?>