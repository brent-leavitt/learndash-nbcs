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
 
class Student_Values implements Form_Values_Interface
{
   

	private $values = [
		//Any needed form values will appear here. 
		'user_login' => '',
		'display_name' => '',
		'first_name' => '',
		'last_name' => '',
		'student_address' => '',
		'student_address2' => '',
		'student_city' => '',
		'student_state' => '',
		'student_postalcode' => '',
		'student_country' => '',
		'student_phone' => '',
		'user_registered' => '',
		'user_email' => '',/* 
		'student_phone_visible' => '',
		'student_facebook' => '',
		'student_facebook_visible' => '',
		'student_pay_email' => '',
		'student_pay_service' => '',
		'student_pay_id' => '',
		'last_payment_received' => '',
		'student_status' => '',
		'billing_type' => '',
		'student_tracks' => [],
		'certificate_id' => '',
		'certification_date' => '',
		'certificaiton_last_update' => '', */
		'student_trainer'=>'',
		'admin_notes' => '',
		
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

		$this->student = new Student( $sid );
		$this->build_values();
		
	}
		
		
	/**
	* Set Student Status Based on Role
	*
	* @return int
	*/	
	private function set_status(  ): int
	{
		
		$status_arr = [
			[ 'student_inactive', 0 ],
			[ 'alumnus_inactive', 0 ],
			[ 'student_active', 1 ],
			[ 'alumnus_active', 1 ]
		];
		
		$role_arr = $this->student->roles;
		
		
		foreach( $status_arr as $status ){
			if( in_array( $status[ 0 ] , $role_arr, true ) )
				return $status[ 1 ];
		}
		
		return 0; 
		
	}
	
	
	
	/**
	* Build the new student page
	*
	* @return void
	*/	
	public function build_values(  ){
		
		if( !isset( $this->student ) )
			return;
		
		
		foreach( $this->values as $key => $val )
			$this->values[ $key ] =   $this->student->get( $key ) ?? '';
		
		$this->values[ 'student_status' ] = $this->set_status();
		
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
