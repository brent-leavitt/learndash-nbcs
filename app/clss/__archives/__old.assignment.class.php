<?php 

namespace Doula_Course\App\Clss;

//use Doula_Course\App\Clss\Assignment_Map;

/*
 *  New Beginnings Assignment PHP Class
 *	Updated 22 Dec 2021
 *
 *	The purpose of this class is to handle assignments beyond what is done 
 *	with the Assignment CPT created in WordPress. Particularly, it is to handle
 *	the evolving nature of course assignments and to give each assignment its
 *	proper placement relative to its own course and other courses. 
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Assignment' ) ){ 
	class Assignment { 

		
		private $assingment_id = 0; 
		
		private $material_id = 0; 
		
		private $status = 'draft'; 
		
		private $time_stamp = '2011-11-01 00:00:00';  
		
		private $completed = false; 
		
		private $student_id = 0; 
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct( $assignment_id, $material_id = 0, $status = 'draft', $time_stamp = '', $completed = false, $stduent_id = 0  ){
				
			$this->set_assignment_id( $assignment_id );
			$this->set_material_id( $material_id );
			$this->set_status( $status );
			$this->set_time_stamp( $time_stamp );
			$this->set_completed( $completed );
			$this->set_student_id( $student_id );				
				
		}
		
		
		/**
		 * set_assignment_id
		 *
		 *	Get's the ID for the assignment 
		 *
		 *	returns VOID
		 **/
		 
		public function set_assignment_id( INT $id ): VOID
		{
			$this->assignment_id = $id;		
		}
		
		
		/**
		 *  set_material_id
		 *
		 *	sets the material_id associciated with the assignment
		 *
		 *	returns VOID
		 **/
		 
		public function set_material_id( INT $id ): VOID
		{
			$this->material_id = $id;
				
		}
		
		
		/**
		 * set_status
		 *
		 *	Sets the status of the assignment
		 *
		 *	returns
		 **/
		 
		public function set_status( STRING $status ): VOID
		{
			$this->status = $status;
				
		}
		
		
		/**
		 * set_time_stamp
		 *
		 *	Set the date of the Assignment. 
		 *
		 *	returns VOID
		 **/
		 
		public function set_time_stamp( STRING $time_stamp ): VOID
		{
			$this->time_stamp = $time_stamp;
				
		}
		
		
		/**
		 * set_completed
		 *
		 * Sets the status of the assignment 
		 *
		 *	returns VOID
		 **/
		 
		public function set_completed( BOOL $completed ): VOID
		{
			$this->completed = $completed;
				
		}
		
		
		/**
		 * set_student_id
		 *
		 *
		 *
		 *	returns VOID
		 **/
		 
		public function set_student_id( INT $student_id ): VOID
		{
			$this->student_id = $student_id;
				
		}

		
		
		
		/**
		 * get_assignment_id
		 *
		 *	Get's the ID for the assignment 
		 *
		 *	returns
		 **/
		 
		public function get_assignment_id(  ){
			return $this->assignment_id;
				
		}
		
		
		/**
		 * 
		 *
		 *	gets the material_id associciated with the assignment
		 *
		 *	returns
		 **/
		 
		public function get_material_id(  ){
			return $this->material_id;
				
		}
		
		
		/**
		 * get_status
		 *
		 *	Gets the status of the assignment
		 *
		 *	returns
		 **/
		 
		public function get_status(  ){
			return $this->status;
				
		}
		
		
		/**
		 * get_time_stamp
		 *
		 *	Get the date of the Assignment. 
		 *
		 *	returns
		 **/
		 
		public function get_time_stamp(  ){
			return $this->time_stamp;
				
		}
		
		
		/**
		 * get_completed
		 *
		 * 
		 *
		 *	returns
		 **/
		 
		public function get_completed(  ){
			return $this->completed;
				
		}
		
		
		/**
		 * get_student_id
		 *
		 *
		 *
		 *	returns
		 **/
		 
		public function get_student_id(  ){
			return $this->student_id;
				
		}

			
		
		/**
		 * is_completed
		 *
		 *
		 *
		 *	returns
		 **/
		 
		public function is_completed(  ){
			return $this->completed;
				
		}

	

}
?>