<?php

namespace Doula_Course\App\Clss\Forms;

use Doula_Course\App\Clss\Student;

//use student ?

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The interface for preparing form values 
 * This sets up the values to be passed into forms.
 * 
 * By default, this is propogated in the constructor. 
 */
 
class Admin_Emailer_Values implements Form_Values_Interface
{

   
	private $values = [
		//Any needed form values will appear here. 
		'email_template' => 0,
		'first_name' => '',
		'last_name' => '',
		'user_email' => '',
		'student_pay_email' => '',
		'to_student_email' => '',
		'cc_student_email' => '',
		'email_subject' => '',
		'email_body' => '',
		'message_admin_notes' => '',
		
		
	];
	
	private $student;
	
	
	
	
	/**
	* Constuctor
	*
	* @return void
	*/
	public function __construct(  ){

		
	}

	
	/**
	* Build the new student page
	*
	* @return void
	*/	
	public function set_student( int $sid ){

		$this->student = new \WP_User( $sid );
		//$this->build_values();
		
	}
		
	
	
	
	
	/**
	* Build the page's values
	*
	* @return void
	*/	
	public function build_values(  ){
		
	
		$this->build_student();
		$this->build_message();
		
	}		
	
	
	/**
	* Build the student values
	*
	* @return void
	*/	
	public function build_student(  ){
		
		if( !isset( $this->student ) )
			return;
		
		
		foreach( $this->values as $key => $val )
			$this->values[ $key ] =   $this->student->get( $key ) ?? '';

		
	}	
	
	/**
	* Build the default message values
	*
	* @return void
	*/	
	public function build_message(  ){
						
		if( !isset( $_POST[ 'email_template' ] ) )
			return;
		
		$mess_id =  $_POST[ 'email_template' ];
		$message = get_post( $mess_id );
		$site_name = get_bloginfo( ); 

		
		$this->values[ 'email_subject' ] = $message->post_title ." - " .$site_name ;
		$this->values[ 'email_body' ] = do_shortcode( $message->post_content ); //Add a message filter to swap out templated items. 
		
		$this->values[ 'message_admin_notes' ] = __('The "', NBCS_TD). $message->post_title . __( '" notice has been sent.' );
		
		$this->values[ 'to_student_email' ] = $_POST[ 'user_email' ];
		$this->values[ 'cc_student_email' ] =  $_POST[ 'student_pay_email' ];
		
	}

	
	/**
	* Get Values
	*
	* @return array
	*/	
	public function get_values( ){
		
		return $this->values;
			
	}

		
	/**
	* Get Student Full Name
	*
	* @return string
	*/	
	public function get_student_full_name( ): string
	{
		
		return $this->values[ 'first_name' ].' '.$this->values[ 'last_name' ];
			
	}

	
	
}


?>
