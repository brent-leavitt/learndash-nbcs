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
 
 
if( !class_exists( 'Assignment' ) ){ 
	class Assignment{ 

		
		private $id; 
		private $title; 
		private $status; 
		private $start_date; 
		private $end_date = 0; 
		private $replaced_by = 0; 
		
		

		
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
		 *	Builds the assignment Object. 
		 *
		 *	returns VOID
		 **/
		 
		public function build( int $id ): VOID
		{
			//sets the ID. 
			$this->id = $id; 
			
			$post = get_post( $id );
			
			//set the title.
			$this->title = $post->post_title;
			
			//set start_date;
			$this->start_date = $post->post_date;			
			
			//set status.
			$this->status = $post->post_status;
			
			//set end_date and replaced_by if available;
			$this->set_meta( [ 'end_date', 'replace_by' ] );
			
		}
			

		/**
		 * 	set_meta
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		public function set_meta( $meta ): VOID
		{
			foreach( $meta as $key ){
				if( metadata_exists( 'post', $this->id, $key  ) )
					$this->$key = get_post_meta( $this->id, $key, true );
			}
			
			
		}
		
		
		/**
		 * 	get_id
		 *
		 *	gets the id of this assignment. 
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
		 *	gets the title of the assignment. 
		 *
		 *	returns STRING
		 **/
		 
		public function get_title(  ): STRING
		{
			return $this->title;
			
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