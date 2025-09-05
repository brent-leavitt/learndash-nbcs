<?php

namespace Doula_Course\App\Clss\Processors;

use Doula_Course\App\Clss\Interfaces\Processor;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 *
 *
 * This updates the user info the database.
 */
 
class Email_Student_Processor implements Processor
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
	private $message = [
		'email_subject' 			=> '', 
		'email_body'			    => '', 
		'to_student_email' 			=> '', 
		'cc_student_email' 			=> '', 
		'message_admin_notes' 		=> '',  
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
        if( $this->empty_email() )
            return; 
		
        $this->uid = $uid;
		$this->message = $_POST;
		//$this->messsage = build( $this->post );
	}
	

		
	
	
	
	/**
	* Checks to see if enough info has been received to send an email. 
	*
	* @return boolean
	*/
	private function empty_email()
	{
		$must_haves = [
            'to_student_email',
            'email_subject',
            'email_body',
            'message_admin_notes'

        ];

        foreach( $must_haves as $field ){
            if( empty( $_POST[ $field ] ) )
                return true;
        }
        
        return false; 
	}
	
	/**
	* Process
	*
	* @return array || void 
	*/
	public function process(): array
	{
        if( $this->empty_email() )
            return []; 

        print_pre( $_POST, "The Post object" ); 
        print_pre( $this, "The Email Student Processor object" ); 

		/* $this->post[ 'ID' ] = $this->uid;
		
		//$this->set_role(); //Not relevant after LMS/Content Restriction updates. 
		$this->set_meta();
		
		$user_data = wp_update_user( $this->post );
		$user_meta = $this->update_user_meta();
		
		if( $user_data == $this->uid )
			$this->notices[ 'messages' ][] = "The user's data has been updated.";
		*/
        $this->notices[ 'messages' ][] = "Holding Space.";
		return $this->notices; 
	}
	
	
	
	

}


?>
