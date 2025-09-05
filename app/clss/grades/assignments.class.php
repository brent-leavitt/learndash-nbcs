<?php 

namespace Doula_Course\App\Clss\Grades;

use Doula_Course\App\Clss\Interfaces\Assignment_Interface;

/*
 *  
 *	Updated 3 Feb 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Assignments' ) ){ 
	class Assignments { 

		
		private $assignments = []; 
		
		
		

		
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
		 *	builds the array of classes that are loaded into the assignments property. 
		 *
		 *	returns VOID
		 **/
		 
		public function build( array $asmt_map, array $tracks ): VOID
		{

			$map = array_intersect_key( $asmt_map, $tracks );
			
			foreach( $map as $asmt_key => $asmt_value ) {
				if( is_array( $asmt_value ) && !empty( $asmt_value )  )
					$this->assignments[] = $this->set_section( $asmt_key, $asmt_value );
				//Should not have any child (assignment) elements at this level, only sections
			}
		}
	
		
		/**
		 * 	set_section
		 *
		 *	(descrip)
		 *
		 *	returns OBJECT
		 **/
		 
		public function set_section( $id, $children ): OBJECT
		{
			
			//$asmt_key is a section
			$section = new Section(); 
			
			//Build a section. 
			$section->build( $id, $children ); 
			
			return $section; 
			
		}
				
		
		
		
		/**
		 * 	get_assignments
		 *
		 *	(descrip)
		 *
		 *	returns ARRAY
		 **/
	 
		public function get_assignments(  ): ARRAY
		{
			
			return $this->assignments;
		}
			
		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
/* 		 
		public function (  ): VOID
		{}
			 */


	}

}
?>