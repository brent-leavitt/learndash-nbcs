<?php 

namespace Doula_Course\App\Clss\Grades;

use Doula_Course\App\Clss\Grades\Assignments;

/*
 *  
 *	Updated 17 Aug 2022
 *
 *	
 *	
 *	For reference:
 *		
		Updated for LearnDash
		
		//Sample meta data: 
		
		postmeta for a course
		meta_key = ld_course_steps
		meta_value = a:7:{
			s:5:"steps";a:1:{
				s:1:"h";a:2:{
					s:12:"sfwd-lessons";a:1:{
						i:685;a:2:{
							s:10:"sfwd-topic";a:5:{
								i:687;a:1:{s:9:"sfwd-quiz";a:0:{}}
								i:689;a:1:{s:9:"sfwd-quiz";a:0:{}}
								i:691;a:1:{s:9:"sfwd-quiz";a:0:{}}
								i:695;a:1:{s:9:"sfwd-quiz";a:0:{}}
								i:692;a:1:{s:9:"sfwd-quiz";a:0:{}}
							}s:9:"sfwd-quiz";a:0:{}}}s:9:"sfwd-quiz";a:0:{}}}
			s:9:"course_id";i:682;
			s:7:"version";s:7:"4.3.0.2";
			s:5:"empty";b:0;
			s:22:"course_builder_enabled";b:1;
			s:27:"course_shared_steps_enabled";b:0;
			s:11:"steps_count";i:6;
		}
		
		
		
		end result is an array like this: 
		
		$array = [
			123 => [ // course
				234 => [  // lesson 
					345, //topic with assignment
					456,
					567
				],
				678 => [
					321,
					432, 
					543
				]
			]
		
		]
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Create_Assignments_Map' ) ){ 
	class Create_Assignments_Map { 

		
		
		private $map = []; //Databse Array
				

		
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
		 
		public function build(  ): VOID
		{
			$courses = $this->get_courses();		
			
			if( !empty( $courses ) )
				$this->map = $this->set_children( $courses );
		}
		
		
		/**
		 * 	set_children
		 *
		 *	sets an array of children for current request.
		 *
		 *	returns ARRAY
		 **/
		 
		private function set_children( array $ids ): ARRAY
		{
			
			global $post; 
			
			$arr = []; 
			
			foreach( $ids as $id ){
				$arr[ $id ] = $this->get_course_lessons( $id ); 
			}
			
			return $arr;
			
		}
		
		/**
		 * 	get_course_lessons
		 *
		 *	returns an array of all lessons that feature topics with an assignment_upload enabled. 
		 *
		 *	returns ARRAY
		 **/
		 
		private function get_course_lessons( int $id ): ARRAY
		{
			global $post; 
			
			$course_meta = get_post_meta( $id, 'ld_course_steps', true );
			
			$arr = []; 

			foreach( $course_meta[ 'steps' ][ 'h' ][ 'sfwd-lessons' ] as $lesson => $topics_arr  ){
				
				$arr[ $lesson ] = []; //lesson ID. 
				
				foreach( $topics_arr[ 'sfwd-topic' ] as $topic_id  => $topic_arr ){
					
					if( $this->is_assignment( $topic_id ) )						
						$arr[ $lesson ][] = $topic_id;
					
				}
			}
			
			return $arr ?: [];
		}
			
			
		/**
		 * 	get_courses
		 *
		 *	(descrip)
		 *
		 *	returns
		 **/
		 
		private function get_courses(  )
		{
			global $post; 
			
			$args = [
				'post_type' 	=> 'sfwd-courses',
				'fields'		=> 'ids',
				'numberposts' 	=> -1,
				'order' 		=> 'ASC',
			];
			
			$courses = get_posts( $args );
			return $courses ?: NULL;
		}
			
		
		/**
		 * 	is_assignment
		 *
		 *	Checks if current material is an assignment. 
		 *
		 *	returns BOOL
		 **/
		 
		private function is_assignment( $id ): BOOL
		{
		
			$topic_meta = get_post_meta( $id, '_sfwd-topic', true );  
					
			return( 
				!empty( $topic_meta[ 'sfwd-topic_lesson_assignment_upload' ] ) 
				&& strcmp( $topic_meta[ 'sfwd-topic_lesson_assignment_upload' ], 'on' ) == 0 
			);
		}
			
		
		/**
		 * 	get_map
		 *
		 *	get the generated map. Must be called after $this->build() to have map loaded. 
		 *
		 *	returns ARRAY
		**/
		 
		 
		public function get_map(  ): ARRAY
		{
			return $this->map;
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