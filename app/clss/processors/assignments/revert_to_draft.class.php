<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;

use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action;
use Doula_Course\App\Clss\Submission;
use Doula_Course\App\Clss\Grades\Grades;


/*
 *  
 *	Updated 21 Jun 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Revert_To_Draft' ) ){ 
	class Revert_To_Draft implements Assignment_Processor_Action  { 

		
		
		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		
		/**
		 *  $post
		 *
		 * 	Post data sent from server	
		 *
		 *	ARRAY
		 */
		
		private $post; 
				

		/**
		 *  $submission
		 *
		 * 	An assignment submission object 
		 *
		 *	OBJECT
		 */
		
		private $submission; 
		
		
		/**
		 *  $student_id
		 *
		 * 	User ID
		 *
		 *	INT
		 */
		
		private $student_id; 
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct(  ){
				
				
				
		}
		
		
		/**
		 * 	init
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		 
		public function do_action( $student_id, $post ) {

			//update_post
			
			if( strcmp( $student_id, $post[ 'student_id' ] ) !== 0 )
				wp_die( 'Not the same user who clicked the submit button.' ); 
				
			
			if( isset( $post['save_draft'] ) ){
			
				$this->student_id = $student_id; 
				
				$this->post = $post; 
			
				$this->submission = new Submission(); 
				
				$this->submission->build( $student_id, $post );
				
				$this->save();
				
			}		
			
			$this->set_notices();
			
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
		 *  set_grade
		 *
		 *	This sets grades, but can also set grades when they are update.d 
		 *	
		 *	return BOOL
		 */
				
		
		private function set_grade( $id, $args ): BOOL
		{
						
			$grades = new Grades(); 
			
			$grades->build( $this->student_id ); 
			
			$grades->add_grade( $args,  $id ); 
			
			return $grades->update_grades();
			
		}	

		
		
		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			if( true ) //Check updated status of post. 
				$this->messages->add( 'student' , 'notice', __( 'The assignment has been reverted back to "draft" status. Please continue editting your work.', NBCS_TD ) );
			//Check Submission class for notices. 
			
		}		
		
		
		
		/**
		 *  save
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		public function save(){
			
			
			$this->submission->set_status( 'draft' );
			
			$args = $this->submission->get_args(); 
			
			$id = $this->update( $args ); 
			
			if( !empty( $id ) )
				$result = $this->set_grade( $id, $args );
			
			return ( isset( $result ) )? $result : FALSE ; 
			
		}	
			
		
		
		/**
		 *  update
		 *  
		 *	
		 *
		 *	return INT //submission_id
		 */
				
		
		private function update( $args ): INT
		{
				
			if( !empty( $args[ 'ID' ] ) ){
			
				return wp_update_post( $args ); 
				
			}
			
			return 0; 
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