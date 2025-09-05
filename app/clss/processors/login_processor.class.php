<?php

namespace Doula_Course\App\Clss\Processors;

use Doula_Course\App\Clss\Interfaces\Processor;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This particular sub_process uses the wp_update_user function form WordPress.
 * Be aware that WordPress does a lot behind the scene when updating user info, 
 * like sending a generic WP email notice when an email address or password are udpated. 
 *
 * This updates the user info the database.
 */
 
class Login_Processor implements Processor
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
		'user_name' => '',
		'password' => '',
	
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
		
	private function set_user(){
		
		$this->user = get_user_by( 'login', $this->post[ 'user_name' ] );
		
	}
			
	
	/**
	* Remove properties from POST global.  
	* 
	* return: void 
	*/	
		
	private function unset_props( $props ){
		
		foreach( $props as $prop )
			unset( $_POST[ $prop ] );				
				
	}	

	
					
						
	
	/**
	*  Checks to see if username and password fields have errors. 
	* 
	* return: void 
	*/	
		
	private function check_for_errors(  ){
		
		//No Username Submitted
		if( empty( $this->post[ 'user_name' ] ) ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =  __( 'Please enter a username.' );
		
		//User Not Found		
		elseif( empty( $this->user )  ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =   __( 'Username not found.' );
			
		//Empty Password
		elseif( empty( $this->post[ 'password'] ) )
			$this->notices[ 'errors' ][ 'password' ] =  __( 'Please enter a password.' );
		
		//password mismatch
		elseif( !wp_check_password( $this->post[ 'password'], $this->user->user_pass, $user->ID ) )
			$this->notices[ 'errors' ][ 'password' ] = __( 'Password is incorrect.' );	
			
	}
	
		
					
	
	/**
	*  Do final login functionality
	* 
	* return: void
	*/	
		
	private function do_login(){
	
		// only log the user in if there are no errors
		if( empty( $this->notices[ 'errors' ] ) ) {
			
			//print_pre( $_POST ); 
			wp_set_current_user( $this->user->ID, $this->user->user_login );	
			
			wp_set_auth_cookie( $this->user->ID ); //optional params: $remember, $secure
			//Replaced by above. wp_setcookie($_POST['nn_patron_login'], $_POST['nn_password'], true);
			
			do_action('wp_login', $this->user->user_login);
			
			$action = urldecode( $_GET[ 'redirect_to' ] );
			
			nocache_headers();
			
			wp_safe_redirect( $action ); exit;
			
		} 		
	}
		
		

		
	
	
	/**
	* Process
	*
	* @return array 
	*/
	public function process(): array
	{
		
		$this->set_user();
			
		$this->unset_props( $this->post );
		
		$this->check_for_errors();
		
		$this->do_login();
		
		return $this->notices;
	}
	
	
	
	

}


?>
