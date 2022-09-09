<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;


use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action;
use Doula_Course\App\Clss\Submission;
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
 
 
if( !class_exists( 'Save_Submission' ) ){ 
	class Save_Submission implements Assignment_Processor_Action  { 

		
		

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		/**
		 *  $empty_asmt
		 *
		 *  Assignment Editor has been submitted without any text and is therefore empty. 
		 *
		 *	BOOL
		 */
		
		private $empty_asmt = true;			
		

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
				wp_die( 'Plagerism?! Not the same user who clicked the submit button.' ); 
			
			//save attachment only button was clicked. 
			if( isset( $post[ 'save_attachment_only' ] ) )
					$post[ 'edit_assignment' ] = $post[ 'attachment_only' ];
			
			//checking to see if assignment field is empty. 
			if( !empty( $post[ 'edit_assignment' ] ) )	
				$this->empty_asmt = false; 
			
			//if any of the three save buttons have been clicked:
			if( isset( $post[ 'save_draft' ] ) 
				|| isset( $post[ 'save_attachment_only' ] )
				|| isset( $post[ 'submit_assignment' ] ) ){
			
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
		 *	This sets grades, but can also set grades when they are updated. 
		 *	
		 *	return BOOL
		 */
				
		
		private function set_grade( $id, $args ): BOOL
		{
						
			$grades = new Grades(); 
			
			$grades->build( $this->student_id ); 
			
			$grades->add_grade( $id, $args ); 
			
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
			if( $this->empty_asmt )
				$this->messages->add( 'student' , 'notice', __( 'The assignment form is empty. Please complete your assignment and then save it as a draft or submit it for grading.', NBCS_TD ) );
			else
				$this->messages->add( 'student' , 'notice', __( 'We are assuming that the assignment has been saved.', NBCS_TD ) );
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
			
			
			$status = ( isset( $this->post[ 'save_draft' ] )  ? 'draft' : ( isset( $this->post[ 'submit_assignment' ] ) ? 'submit' : NULL ) );
			
			$this->submission->set_status( $status );
			
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
				
			} else {
			
				return wp_insert_post( $args );
				
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