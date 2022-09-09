<?php

namespace Doula_Course\App\Clss\Processors;

use Doula_Course\App\Clss\Interfaces\Processor;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** 
 *
 *
 *
 *
 *
 *
 */
 
class Cashier_Processor implements Processor
{
	
	
	
	/**
	 * uid
	 * 
	 * User ID to be processed
	 *
	 * @since 2.0
	 * @var int
	 */
	private $uid = 0; 
	
	
	/**
	 * post
	 * 
	 * The post data to be manipulated
	 *
	 * @since 2.0
	 * @var array
	 */
	private $post = [
		'' => '',
	
	]; 
		
		
	/**
	 * user
	 * 
	 * The user data as it is currently set in the database
	 *
	 * @since 2.0
	 * @var array
	 */
	private $user; 	
	
	
	/**
	 * action
	 * 
	 * The action to be taken by the login. 
	 *
	 * @since 2.0
	 * @var string
	 */
	private $action = '/'; 
	
	
	/**
	 * notices
	 * 
	 * notices returned on update.
	 *
	 * @since 2.0
	 * @var array
	 */
	private $notices = []; 
	
	
	
	
		/**
	* Constuctor
	*
	* INT is sent as a paramter just to be compliant with sub_processor interface. 
	*
	*
	* @return void
	*/
    public function __construct( int $uid = 0 ){
					
		foreach( $this->post as $key => $val)
			$this->post[ $key ] = $_POST[ $key ];	
		
		//$this->action = ( $_POST[ 'action' ] ) ?: '/';
	}
	
	
	/**
	* Set user from wordpress. 
	* 
	* return: void 
	*/	
		
	private function user_exists(): void
	{
		
		$this->user = get_user_by( 'login', $this->post[ 'user_name' ] );
		
	}
			
	
	/**
	* Remove properties from POST global.  
	* 
	* return: void 
	*/	
		
	private function unset_props( $props ): void
	{
		
		foreach( $props as $prop )
			unset( $_POST[ $prop ] );				
				
	}	

	
					
						
	
	/**
	*  Checks to see if username and password fields have errors. 
	* 
	* return: void 
	*/	
		
	private function check_for_errors(  ): void
	{
		
		//No Username Submitted
		/* if( empty( $this->post[ 'user_name' ] ) ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =  __( 'Please enter a username.' ); */
		
		//Username already exists		
		/* elseif( username_exists( $this->post[ 'user_name' ]  )  ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =   __( 'Username is already taken.' );
			 */
		
			
	}
		
					
	
	/**
	*  Do  functionality
	* 
	* return: void
	*/	
		
	private function _(): void
	{
	
		// only log the user in if there are no errors
		if( empty( $this->notices[ 'errors' ] ) ) {
		
			
		} 		
	}
		
		

		
	
	
	/**
	* Process
	*
	* @return array 
	*/
	public function process(): array
	{
		
		
		return $this->notices;
	}
	
	
	
	

}


?>
