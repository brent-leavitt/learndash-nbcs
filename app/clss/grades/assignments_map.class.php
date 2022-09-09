<?php 

namespace Doula_Course\App\Clss\Grades;

use Doula_Course\App\Clss\Grades\Assignments;
use Doula_Course\App\Clss\Grades\Create_Assignments_Map as Creator;

/*
 *  
 *	Updated 22 Aug 2022
 *	
 *	Logic: The assignments_map is relative to all available assignment within the systme. 
 *	The student_id and tracks are relative to the user.  
 *	Assignments is then a detailed representation of what assignments are actually relavant to the user. 
 *	There is then a filtering of assignments based on user-enrolled tracks (programs). 
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Assignments_Map' ) ){ 
	class Assignments_Map { 

		
		
		private $assignments_map = []; //Databse Array
		
		private $assignments; //Assignments Object
		
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct( ){

			
		}
		
		
		/**
		 * 	build
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function build( ): VOID
		{
				
			$this->set_assignments_map();	
			
		}
		
		
		
		/**
		 * 	set_assignments_map
		 
		 *	sets the assignments_map array from the options table in the database. 
		 *
		 *	returns VOID
		 **/
		 
		public function set_assignments_map(  ): VOID
		{
			$option = NBCS_PREFIX.'assignments_map'; 
			$map = get_option( $option ); 
			
			
			
			//Only if empty are we creating an assignment map. 
			if( empty( $map ) ){
				
				 
				$creator = new Creator(); 
				$creator->build();
				
				$map = $creator->get_map();	
				update_option( $option, $map );
				
			}			
			
			$this->assignments_map = $map;
		}
			
	
		
		/**
		 * 	set_assignments
		 *
		 *	sets the assignments object tree, based off of tracks that are set by the user's meta. 
		 *
		 *	returns VOID
		 **/
		 
		public function set_assignments( array $tracks ): VOID
		{
			//filter assignments_map by student. 
			
			$assignments = new Assignments();	  
			$assignments->build( $this->assignments_map, $tracks ); 
			$this->assignments = $assignments->get_assignments();
			//We may need to differentiate for active and inactive tracks here. 
			 
		}
		
		

		
		
		/**
		 * 	get_course
		 *
		 *	(descrip)
		 *
		 *	returns OBJECT
		 **/
		 
		public function get_course( $id )//: OBJECT
		{
			foreach( $this->assignments as $course ) 
				if( strcmp( $course->get_id(), $id ) == 0  )
					return $course; //section class
				
			
		}
			
		
		/**
		 * 	get_course_id_from_assignment_id
		 *
		 *	This will work tentative. Eventually, assignments will have to have course_id added to the meta data,
		 *  because assignments can be assigned to multiple courses. No, this still would work. We'd need to look for 
		 *  the assignment in all courses and return all course ids. 
		 *
		 *	returns OBJECT
		 **/
		 
		public function get_course_id_from_assignment_id( INT $asmt_id ): INT
		{
			if( !empty( $this->assignments_map ) ){
				foreach( $this->assignments_map as $course_id => $lessons ){
					foreach( $lessons as $asmt_arr ){
						if( in_array( $asmt_id, $asmt_arr ) ){
							return $course_id; 
						}	
					}
				} 
			}
			
			return 0; 
		}
			

		
	
		
		
		
		
		/**
		 * 	get_assignments_map
		 *
		 *	Detailed view of the course assignments, dynamically generated. 
		 *
		 *	returns ARRAY
		 **/
		 
		public function get_assignments_map(  ): ARRAY
		{
			
			return $this->assignments;
		}
			
		
		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 
		 
		public function (  ): VOID
		{}
			
		**/
		


	}

}
?>