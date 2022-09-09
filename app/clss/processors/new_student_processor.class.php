<?php

namespace Doula_Course\App\Clss\Forms;

use Doula_Course\App\Clss\Interfaces\Processor;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This particular sub_process uses the wp_update_user function form WordPress.
 * Be aware that WordPress does a lot behind the scene when updating user info, 
 * like sending a generic WP email notice when an email address or password are udpated. 
 *
 * This updates the user info the database.
 */
 
class New_Student_Processor implements Processor
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
	private $post; 
		
		
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
	 * meta
	 * 
	 * The user meta data to be updated
	 *
	 * @since 2.0
	 * @var array
	 */
	private $meta = [
		'student_address' 			=> '', 
		'student_address2'			=> '', 
		'student_city' 				=> '', 
		'student_state' 			=> '', 
		'student_country' 			=> '', 
		'student_postalcode' 		=> '',  
		'student_phone' 			=> '',  
		
		'student_facebook' 			=> '', 
		'student_pay_service' 		=> '', 
		'student_pay_id' 			=> '', 
		'student_pay_email' 		=> '', 
		'last_payment_received'		=> '', 
		'billing_type' 				=> '', // still relevant?
		'certificate_id'			=> '',
		'certification_date'		=> '',
		'certificaiton_last_update' => '',
		'admin_notes' 				=> '', 
		 
	]; 
	
	
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
	* @return void
	*/
    public function __construct( int $uid ){
		
		$this->uid = $uid;
		$this->post = $_POST;
		//$this->user = get_userdata( $this->uid );
	}
	
	
	
	/**
	* set role
	*
	* Sets the user role based on the student_status post field  
	*
	* @return void 
	*/
	private function set_role(): void
	{
		
		$status = intval( $this->post[ 'student_status' ] ) ; //1 = current or 0 = inactive
		
		$action = [
			'student_inactive' , 
			'student_active' 
		];
		
		$this->post[ 'role' ] = $action[ $status ];
		
		unset( $this->post[ 'student_status' ] );
		
	}
	
	
	/**
	* set meta
	*
	* Sets the user meta
	*
	* @return void 
	*/
	private function set_meta(): void
	{
		
		foreach( $this->meta as $key => $val )
			$this->meta[ $key ] = $this->post[ $key ]  ?: '';
		
	}
			
		
	
	/**
	* update_user_meta
	*
	* updates_user_meta
	*
	* @return array
	*/
	private function update_user_meta( ): array
	{
		$results = [];
		
		foreach( $this->meta as $key => $val ){
			if( !empty( $val ) )
				$results[] = update_user_meta( $this->uid, $key, $val );	
		}
		
		return $results;
		
	}
		
	
	
	/**
	* Process
	*
	* @return array 
	*/
	public function process(): array
	{
		
		if( !empty( $this->uid ) )
			$this->post[ 'ID' ] = $this->uid;
		
		$this->set_role();
		$this->set_meta();
		
		//\print_pre( $this->post );
		//\print_pre( $this->meta );
		
		$user_data = wp_insert_user( $this->post );
		
		if ( is_wp_error( $user_data ) )
		{
			$this->notices[ 'error' ][] = $user_data->get_error_message();
		} 
		elseif( !empty( $user_data ) ){
			
			$this->uid = $user_data;
			$user_meta = $this->update_user_meta();
			
			
			$this->notices[ 'messages' ][] = "The user's data has been updated.";
		}
		
		//\print_pre( $user_data );
		//\print_pre( $user_meta );
		
		//if( $user_data == $this->uid )
		
		return $this->notices;
	}
	
	
	
	

}


?>
