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
 
 
if( !class_exists( 'Section' ) ){ 
	class Section { 

		
		private $id; 
		private $title; 
		//private $parent_id = 0; 
		private $children = []; 
		

		
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
		 *	builds the section. Adds children. 
		 *
		 *	returns VOID
		 **/
		 
		public function build( int $id, array $children ): VOID
		{
			//Set up Section ID.
			$this->id = $id; 
			
			//Set Title
			$this->set_title();
			
			//Set children.
			$this->set_children( $children );
			
			
		}
			
		
		
		/**
		 * 	set_children
		 *
		 *	Sets Children to the Section.  
		 *
		 *	returns VOID
		 **/
		 
		public function set_children( array $children ): VOID
		{
			
			foreach( $children as $key => $val ){
			
				if( is_array( $val ) ){
					$obj = new Section();
					$obj->build( $key, $val );
				}else{
					$obj = new Assignment();
					$obj->build( $val );
				}
				
				$this->children[] = $obj;
			}			
		}
			
		
		/**
		 * 	set_title
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function set_title(  ): VOID
		{
			
			$this->title = get_the_title( $this->id );
			
		}
			

				
		/**
		 * 	get_id
		 *
		 *	gets the id of this section. 
		 *
		 *	returns INT
		 **/
		 
		public function get_id(  ): INT
		{
			return $this->id;
			
		}
				
		/**
		 * 	get_title
		 *
		 *	gets the title of this section. 
		 *
		 *	returns STRING
		 **/
		 
		public function get_title(  ): STRING
		{
			return $this->title;
			
		}
				
		/**
		 * 	get_children
		 *
		 *	gets the childern of this section. 
		 *
		 *	returns ARRAY
		 **/
		 
		public function get_children(  ): ARRAY
		{
			return $this->children;
			
		}
			
				
		/**
		 * 	load
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function load(  ): VOID
		{}
			

		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function add(  ): VOID
		{}
			

		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function update(  ): VOID
		{}
			

		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function deprecate(  ): VOID
		{}
		
		
	}
}
?>