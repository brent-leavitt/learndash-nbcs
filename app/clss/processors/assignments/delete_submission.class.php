<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;

use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action;
use Doula_Course\App\Clss\Grades\Grades; 

/*
 *  
 *	Updated 7 Jun 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Delete_Submission' ) ){ 
	class Delete_Submission implements Assignment_Processor_Action  { 

		
		
		

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		
		/**
		 *  $post_id
		 *
		 *  The ID of the post to be deleted or moved to trash. 
		 *
		 *	INT
		 */
		private $post_id; 
		
		
		/**
		 *  $trash
		 *
		 *  Trashed post data. 
		 *
		 *	OBJ
		 */
		private $trash; 
		
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct(  ){
				
			add_action( 'delete_post', array( $this, 'remove_assignment_grade' ), 10, 2 );
				
		}
		
		
		/**
		 * 	init
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		 
		public function do_action( $student_id, $post ) {
			
			$this->post_id = $post[ 'post_id' ];
			
			if( isset( $post[ 'delete_assignment' ] ) ){
				
				$this->trash = $this->trash();
				
				$this->set_notices(); 
				
			}
			
		}	

		
		
		/**
		 * 	set_messages
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function set_messages( $messages )
		{

			$this->messages = $messages;			
		
		}
		
		
		
		
		/**
		 * 	trash
		 *
		 *	(descrip)
		 *
		 *	returns POST OBJ| false | null
		 **/
		 
			 
		private function trash()
		{

			return wp_delete_post( $this->post_id ); 
		
		}	
		
		
			
		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			
			if( !empty( $this->trash ) ){
				
				//Assignmnet was deleted. There is post_data available for processing if desired. 
				
				$this->messages->add( 'student' , 'notice', __( 'The assignment has been successfully discarded.', NBCS_TD ) );
			
			} else {
				
				//Nothing was deleted. 
				
				$this->messages->add( 'student' , 'notice', __( 'There was no data available for deletion. Sorry!', NBCS_TD ) ); 
				
			}	
				
			//Check Submission class for notices. 
			
		}		
		
		
		/**
		 * 
		 * 
		 * This is a callback function on the 'delete_post' hook called in this class's constructor.
		 * 
		 * return BOOL
		 */
		 
		 
		public function remove_assignment_grade( $post_id, $post )
		{	
			if( strcmp( $post->post_type, 'assignment') !== 0 )
				return false; 
			
			$student_id = $post->post_author; 
			//Make sure it is the current user who is deleting their post or an admin. 
				//Admin or current_user
			if ( ! current_user_can( 'edit_course', $post_id ) && ( (int)$student_id !== (int)get_current_user_id() ) )
				return false;

			
			$grades = new Grades(); 
			$grades->build( $student_id );
			
			$grades->delete_grade( $post->post_parent ); 
			$grades->update_grades(); 
			
			return true;
			
		}

		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function _()
		{

			
		
		}



	}

}
?>