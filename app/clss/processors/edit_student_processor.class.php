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
 
class Edit_Student_Processor implements Processor
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
		'student_trainer' 			=> 0, 
		'admin_notes' 				=> []
		 
	]; 
	
	
	/**
	 * notices	 * 
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
		$this->user = get_userdata( $this->uid );
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
		foreach( $this->meta as $key => $val ){
			if( isset( $this->post[ $key ] ) ){
				$this->meta[ $key ] = $this->post[ $key ]  ?: '';
				unset( $this->post[ $key ] ); 
			}
		}
		
		//Turns the incoming string into an array. This is the hidden field which holds admin notes info. If empty, set empty array.
		$this->meta[ 'admin_notes' ]  =  ( !empty( $this->meta[ 'admin_notes' ] ) )? 
				maybe_json_decode( stripslashes( $this->meta[ 'admin_notes' ] ) ):
				[];			

		//Append the new row to the admin_notes meta array. 
		if( !empty( $admin_note = $this->post[ 'admin_notes_row' ] ) ){
			$this->add_admin_notes_row(  $admin_note  ); 
			unset( $this->post[ 'admin_notes_row' ] ); 
		}
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

		//This is a bit of hack. Holding space for trainer reassignments. 
		$old_trainer = 0; 
		$new_trainer = 0;
		
		foreach( $this->meta as $key => $val )
		{
			//setting old value for nb_trainer_reassignment action hook
			if( $key == 'student_trainer' ) 
				$old_trainer = get_user_meta( $this->uid, $key, true ); 

			if( !empty( $val ) )
				$results[] = update_user_meta( $this->uid, $key, $val );	
			else
				$results[] = delete_user_meta( $this->uid, $key );
			
			//Need to set an action hook for trainers to be notified of changes. 
			//action hook params: user_id, old_trainer, new_trainer, userdata
			if( $key == 'student_trainer' ){
				if( ( strcmp( $old_trainer, $val  ) !== 0 ) && !empty( $val ) )
					$new_trainer = $val;
				
			}	
		}

		//In no trainer is set fire the new student email trigger. 
		if( empty( $old_trainer ) && !empty( $new_trainer ) )
			do_action( 'nb_trainer_new_student', $this->uid,  $new_trainer );

		//If a trainer is being switched, firt the switch trigger. 
		if( !empty( $old_trainer ) &&  !empty( $new_trainer )  )
			do_action( 'nb_trainer_reassignment', $this->uid, $old_trainer, $new_trainer, $this->user );
		
		return $results;
		
	}
		
	
	
	/**
	* Process
	*
	* @return array 
	*/
	public function process(): array
	{
		
		$this->post[ 'ID' ] = $this->uid;
		
		$this->set_meta();
		
		$user_data = wp_update_user( $this->post );
		$user_meta = $this->update_user_meta();
		
		if( $user_data == $this->uid )
			$this->notices[ 'messages' ][] = "The user's data has been updated.";
		
		return $this->notices;
	}
		
	
	/**
	* Append the new admin note to the admin_notes meta data. 
	*
	* @return NULL 
	*/
	public function add_admin_notes_row( $note )
	{
		array_push( $this->meta[ 'admin_notes' ], [
			'uid' 	=> wp_get_current_user()->ID,
			'date'	=> current_time( 'mysql' ),
			'note'	=> $note
		] );
		
	}
	
	
	
	

}


?>
