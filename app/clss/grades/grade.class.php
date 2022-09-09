<?php 

namespace Doula_Course\App\Clss\Grades;

/*
 *  
 *	Updated 20 May 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Grade' ) ){ 
	class Grade { 

		
		private $material_id = 0; 							//Course Work
		private $assignment_id = 0; 						//Student Created Assignment
		private $status = 'draft'; 							//Status of student submitted assignment.
		private $instructor_status = 0;						//Status of instructor interaction with assignment.
		private $submission_date = '0000-00-00 00:00:00'; 	//Date Assignment was first submitted
		private $last_updated = '0000-00-00 00:00:00'; 		//Date Assignment was last resubmitted 
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct(  ){
				
				
				
		}
		
		
		/**
		 * 	build
		 *
		 *	descrip: Propogates the class with information about the grade.
		 *
		 *	returns VOID
		 **/
		 
		public function build( $arr ): VOID
		{
			extract( $arr ); 
						
			$this->set_material_id( $material_id ); 			//Course Work
			$this->set_assignment_id( $assignment_id ); 			//Student Created Assignment
			$this->set_status( $status ); 						//Status of student submitted assignment.
			$this->set_instructor_status( $instructor_status ); 	//Instructor's Status 
			$this->set_submission_date( $submission_date ); 		//Original submission date
			
			$updated = ( !empty( $last_updated ) )? $last_updated : NULL ; 
			$this->set_last_updated( $updated ); 			//Date assignment was last updated on. 
		}
			

				
		
		/**
		 * 	get_material_id
		 *
		 *	descrip: Get the parent or reference assignment for this grade. 
		 *
		 *	returns INT
		 **/
		 
		public function get_material_id( ): INT
		{
			return $this->material_id;
		}
				
		
		/**
		 * 	get_assignment_id
		 *
		 *	descrip: This returns the ID of the student submitted assignment. 
		 *
		 *	returns INT
		 **/
		 
		public function get_assignment_id( ): INT
		{	
			return $this->assignment_id;
		}

		
		/**
		 * 	get_status
		 *
		 *	descrip: This returns the status of the current graded assignment
		 *
		 *	returns STRING
		 **/
		 
		public function get_status(  ): STRING
		{
			return $this->status;
		}
			
		
		/**
		 * 	get_instr_status
		 *
		 *	descrip: This returns the instructor status of the current graded assignment
		 *
		 *	returns STRING
		 **/
		 
		public function get_instr_status(  ): STRING
		{
			return $this->instructor_status;
		}
			
		
		/**
		 * 	get_submission_date
		 *
		 *	This gets the original submission date for the assignment
		 *
		 *	returns STRING
		 **/
		 
		public function get_submission_date(  ): STRING
		{
			return $this->submission_date; 
		}
		
					
		
		/**
		 * 	get_last_updated
		 *
		 *	This gets the last_modified date of the assignment. 
		 *
		 *	returns STRING
		 **/
		 
		public function get_last_updated(  ): STRING
		{
			return $this->last_updated; 
		}
		
			
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function get_( ): VOID
		{
		
			
		}

	
		
				
		/**
		 * 	set_material_id
		 *
		 *	Sets the ID from the course material used to complete the assignment
		 *
		 *	returns VOID
		 **/
		 
		private function set_material_id( INT $mat_id ): VOID
		{
			$this->material_id  = $mat_id;
		}
			
		

		/**
		 * 	set_assignment_id
		 *
		 *	Sets the ID for the particular assignment being graded. 
		 *
		 *	returns VOID
		 **/
		 
		private function set_assignment_id( INT $id ): VOID
		{
			$this->assignment_id = $id; 				
		}
			
		
		
		
		/**
		 * 	set_status
		 *
		 *  descrip: sets the status of the grade
		 *
		 *	returns VOID
		 **/
		 
		private function set_status( string $status ): VOID
		{	
			global $wp_post_statuses;
						
			if( array_key_exists( $status, $wp_post_statuses ) )
				$this->status = $status;
			
		}
		
		
		/**
		 * 	set_instructor_status
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		private function set_instructor_status( string $instructor_status ): VOID
		{
			$this->instructor_status = $instructor_status;
			
		}
		
						
		/**
		 * 	set_submission_date
		 *
		 *	This is the date the assignment was originally submitted. May be the same as last_updated
		 *
		 *	returns VOID
		 **/
		 
		private function set_submission_date( string $date  ): VOID
		{
			
			$this->submission_date = $date; 		
			
		}
			
		
				
		/**
		 * 	set_last_updated
		 *
		 *	The last date that this assignment was updated on. May be the same as the submission_date
		 *
		 *	returns VOID
		 **/
		 
		private function set_last_updated( $updated ): VOID
		{
			
			$this->last_updated =  $updated ?: $this->submission_date; 	
			
		}
			
		
				
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		private function set_(  ): VOID
		{}
			

		
		
		/**
		 * 	update_asmt
		 *
		 *	descrip: Updates the grade for a given student submitted assignment. 
		 *
		 *	returns boolean
		 **/
		 
		public function update_asmt( ): BOOL
		{
			//Get post_status. 
			$current_status = ( !empty( $this->assignment_id ) )? get_post_status( $this->assignment_id ) : '' ;
			
			if( ( !empty( $this->status ) ) && ( strcmp( $this->status, $current_status ) !== 0 ) ){
								
				//submitted_assignment.
				$updated = 	wp_update_post([
								'ID' => $this->assignment_id,
								'post_status' => $this->status
							]);
							
				if( $updated )
					return true;
			}
			
			return false; 
			
		}	

		
		
		/**
		 * 	update_student_meta
		 *
		 *	descrip: Updates the grade for a given student submitted assignment. 
		 *
		 *	returns boolean
		 **/
		 
		public function update_student_meta( ): BOOL
		{
			$update = false;
			//Update Instructor status: 
			$instructor_status = ( !empty( $this->assignment_id ) )? get_post_meta( $this->assignment_id, 'instructor_status', true ) : 0 ;

			if( strcmp( $this->instructor_status, $instructor_status ) !== 0 ){
				$this->set_instructor_status( $instructor_status );
				$update = true;
			}		
			
			//Get post_status. 
			$current_status = ( !empty( $this->assignment_id ) )? get_post_status( $this->assignment_id ) : '' ;
			
			if( ( !empty( $this->status ) ) && ( strcmp( $this->status, $current_status ) !== 0 ) ){
				$this->set_status( $current_status );
				$update = true;
			}
				
			return $update; 
			
		}	

		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function _(  ): VOID
		{}
			

	}
	
}
?>