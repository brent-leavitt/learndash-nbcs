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
 
class Register_Lite_Processor implements Processor
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
		'user_email' => '',
		'password' => '',
		'password_2' => '',
	
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
		if( empty( $this->post[ 'user_name' ] ) ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =  __( 'Please enter a username.' );
		
		//Username already exists		
		elseif( username_exists( $this->post[ 'user_name' ]  )  ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =   __( 'Username is already taken.' );
			
		//No Email Address Submitted		
		elseif( empty( $this->post[ 'user_email' ] ) ) 				
			$this->notices[ 'errors' ][ 'user_email' ] =   __( 'Please enter a valid email.' );
			
		//Email Address is Invalid		
		elseif( !is_email(  $this->post[ 'user_email' ] ) ) 				
			$this->notices[ 'errors' ][ 'user_email' ] =   __( 'Email Address is not formatted correctly.' );
		
		//Email address is already exists		
		elseif(  email_exists( $this->post[ 'user_email' ] ) !== false  ) 				
			$this->notices[ 'errors' ][ 'user_name' ] =   __( 'Email is already registered. Try logging in instead.' );
			
		//Empty Password
		elseif( empty( $this->post[ 'password'] ) )
			$this->notices[ 'errors' ][ 'password' ] =  __( 'Please enter a password.' );
		
		//Passwords do not match
		elseif( $this->post[ 'password'] !== $this->post[ 'password_2'] )
			$this->notices[ 'errors' ][ 'password_2' ] = __( 'Passwords do not match.' );	
			
	}
	
		
					
	
	/**
	*  Do register_lite functionality
	* 
	* return: void
	*/	
		
	private function do_register_lite(): void
	{
	
		// only log the user in if there are no errors
		if( empty( $this->notices[ 'errors' ] ) ) {
			
			
			$sid = wp_insert_user( array(
							'user_login'		=> $this->post[ 'user_name' ],
							'user_pass'	 		=> $this->post[ 'password' ],
							'user_email'		=> $this->post[ 'user_email' ],
							'user_registered'	=> date( 'Y-m-d H:i:s' ),
							'role'				=> 'subscriber'	
						)
					);
					
			if( $sid  ) {
						
				//Add user to masteruser list in base site (if using multisite)
				//if( is_multisite() ) add_user_to_blog( NN_BASESITE, $sid, 'reader' );
				
				//Set User Object after registered. 
				$this->user = get_user_by( 'ID', $sid );
				
				// send an email to the admin alerting them of the registration
				wp_new_user_notification( $sid  );
			}
			
		} 		
	}		
					
	
	/**
	*  Do final login functionality
	* 
	* return: void
	*/	
		
	private function do_login(): void
	{
	
		// only log the user in if there are no errors
		if( empty( $this->notices[ 'errors' ] ) ) {
			
			//print_pre( $_POST ); 
			wp_set_current_user( $this->user->ID, $this->user->user_login );	
			
			wp_set_auth_cookie( $this->user->ID ); //optional params: $remember, $secure
			//Replaced by above. wp_setcookie($_POST['nn_patron_login'], $_POST['nn_password'], true);
			
			do_action('wp_login', $this->user->user_login);
			
			$action = '/cashier/?';
			
			$action_str = 'action='. $_REQUEST[ 'action' ];
			$action_str .= '&service='. $_REQUEST[ 'service' ];
			$action_str .= '&enrollment='. $_REQUEST[ 'enrollment' ];
			
			$action .= $action_str;
			
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
		
		$this->check_for_errors();
		
		$this->do_register_lite();
		
		$this->unset_props( $this->post );
		
		$this->do_login();
		
		return $this->notices;
	}
	
	
	
	

}


?>
