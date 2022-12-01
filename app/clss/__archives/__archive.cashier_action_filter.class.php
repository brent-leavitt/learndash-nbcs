<?php

namespace Doula_Course\App\Clss;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * 
 * 
 * 
 */

class Cashier_Action_Filter{
	
	 
	 /**
	 * enrollment
	 * 
	 * enrollment token
	 *
	 * @since 2.0
	 * @var string
	 */	
	private $enrollment = ''; 
	
	
	
	 /**
	 * service
	 * 
	 * Service to be registered for
	 *
	 * @since 2.0
	 * @var string
	 */
	private $service = '';

	
	
	 /**
	 * patron
	 * 
	 * The user object
	 *
	 * @since 2.0
	 * @var int
	 */
	private $patron = 0;
	 
	 	
	
	 /**
	 * skip
	 * 
	 * Does the user need to be logged in?
	 *
	 * @since 2.0
	 * @var bool
	 */
	private $skip = false;
	
	
	 /**
	 * action
	 * 
	 * Action to be taken 
	 *
	 * @since 2.0
	 * @var string
	 */
	private $action;
	 
	 
	
	/**
	 *  
	 *
	 *
	 */
	 
	public function __construct( ){
		
		//Set available variables for manipulation: enrollment, service, patron, skip
		
		
		if( isset( $_REQUEST[ 'enrollment' ] ) )
			$this->enrollment = esc_html( $_REQUEST[ 'enrollment' ] );
		
		if( isset( $_REQUEST[ 'service' ] ) )
			$this->service = esc_html( $_REQUEST[ 'service' ] );
		
		if( isset( $_REQUEST[ 'patron' ] ) )	
			$this->patron = intval( $_REQUEST[ 'patron' ] );
		
		if( isset( $_REQUEST[ 'skip' ] ) )
			$this->skip = boolval( $_REQUEST[ 'skip' ] );

	}
	
		
		
	/**
	 * Set Action
	 *  
	 * Performs Checks then Set Action
	 *
	 * @since 2.0
	 * @return bool
	 */
	 
	public function set_action( ):void
	{
		
		//if action is not already set, parse other request info to determin action.. 
		if( !empty( $_REQUEST[ 'action' ] ) )
				return;
			
		$_REQUEST[ 'action' ] =	$this->parse_request( );
	}
	
		
	/**
	 * Parse Request
	 *  
	 * Take given information to perform requested action. 
	 *
	 * @since 2.0
	 * @return bool
	 */
	 
	private function parse_request( ): string
	{
		//if enrollment or service is empty, you shouldn't be here: HARD STOP.  
		if( empty( $this->enrollment ) || empty( $this->service ) )
			return 'default';
			
			
		//if patron is set, and both enrollment/service are set: proceed to checkout.
		if( 
			!empty( $this->patron ) || 
			( empty( $this->patron ) && ( $this->skip == true ) ) 
		)
			return 'checkout';
			
		
		//if patron is not set, and skip is set: 
		if( empty( $this->patron ) && ( $this->skip == false ) )			
			return 'register';
			
	}
	
	
	
}
?>