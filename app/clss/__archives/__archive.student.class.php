<?php 

/*
 *  Student
 *	Created on 25 Mar 2021
 *
 *	This creates a student object based off of the WP_User object.
 *
 */

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; } 
 
//check if WP_user is set.  
 
 
class Student extends \WP_User 
{ 

	
	
	
	/*
	* 
	* Honestly not sure what needs to be extended about the WP_User class, but seems like the right thing to do. 
	*
	*
	*/
	
	public function is_loaded(){
		
	/* 	\print_pre( $this );
		\print_pre( $this->get( 'billing_type' ) );
		\print_pre( $this->get( 'student_country' ) );
		\print_pre( $this->get( 'display_name' ) ); */
		
		
	}
	
	
	
}
?>