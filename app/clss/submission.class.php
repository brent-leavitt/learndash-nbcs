<?php 

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to handle student-generated assignment submissions. 
	 * 
	 *
	 *
	 */


if( !class_exists( 'Submission' ) ){
	class Submission{
		
		//Properties
		// - post_type
		// - post_author
		// - author_name
		// - post_title
		// - post_name
		// - post_id
		// - post_parent
		// - post_content
		// - post_status
		// - post_meta
		
		private $author_name, $title, $name, $parent_id, $content;
		
		private $id = null; 
		
		private $type = 'assignment';	

		private $author = 0;	

		private $meta = [];	

		private $status = 'draft';	
		
				
		/**
		 *  __construct
		 *
		 *	
		 */
		
		public function __construct( ){
			
		
		}

		

		/**
		 *  build
		 *
		 *	
		 *
		 *	return VOID
		 */
		 
		 
		public function build( int $author_id, array $post ): VOID 
		{
			
			//print_pre( $post, "SUBMISSION:BUILD, This is the post data being received to process" ); 
			
			if( !empty( $post[ 'assignment_id' ]  ) )
				$this->set_id( $post[ 'assignment_id' ] );
			
			$this->author = $author_id; 
			
			$this->set_content( $post[ 'edit_assignment' ] );
			
			$this->set_author_name(); 
			
			$this->set_name(  $post[ 'post_name' ] );
			
			$this->set_title(  $post[ 'post_title' ] );
			
			//May be buggy if Not Set.
			$this->set_parent( $post[ 'post_id' ] ); 
			
			/* $button = ( isset( $post[ 'save_draft' ] ) )? "draft" : ( (  isset( $post[ 'submit_assignment' ] ) )? "submit": NULL );
			$this->set_status( $button  ); 
			 */
		}
			

		
		
		/**
		 * set_id 
		 *
		 *	Sets the submission ID, same as post_ID. Can be declared NULL if empty. 
		 *
		 *	return  VOID
		 */
				
		
		private function set_id( $id ): VOID
		{
			$this->id = $id ?: NULL ;
			
		}			
		
		/**
		 *  set_content
		 *
		 *  Sets the main content of the assignment. 
		 *
		 *	return VOID
		 */
				
		
		private function set_content( STRING $content ): VOID
		{
			$this->content = wp_kses( $content , wp_kses_allowed_html( 'post' ) );
			
		}			
		
		/**
		 *  set_author_name
		 *  
		 *	Sets the student's name for use in the post name and title. 
		 *
		 *	return VOID
		 */
				
		
		private function set_author_name(){
			
			$meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $this->author ) );
				
			$this->author_name = strtolower( $meta['first_name'] ).ucfirst( strtolower( $meta['last_name'] ) );
			
		}			
		
		/**
		 *  set_name
		 *
		 *	Sets the URL friendly version of the assignment submission name. 
		 *
		 *	return 
		 */
				
		
		private function set_name( STRING $name ){
			
			$this->name = strtolower( $this->author_name .'_'. $name );
		}			
		
		/**
		 *  set_title
		 *
		 *	Sets the visibe title of the submission. 
		 *
		 *	return 
		 */
				
		
		private function set_title( $title ){
			
			$this->title = ucfirst( $this->author_name ) .': '. $title; 
			
		}			
		
		/**
		 *  set_parent
		 *
		 *	Sets the parent ID for the submission. May Be Buggy. 
		 *
		 *	return 
		 */
				
		
		private function set_parent( $parent ){
			
			$this->parent_id = ( $parent )?: NULL ;
		}			
		
		
		
		/**
		 *  set_status
		 *
		 *
		 *	return 
		 */
				
		
		public function set_status( $button ){
			
			if( empty( $button ) )
				return;
			
			$current_status = get_post_status( $this->id );	
			
			if( $button == 'draft' ){
				$this->status = (  $current_status == 'incomplete' || $current_status == 'resubmitted' ) ? 'incomplete' : 'draft' ;
			} elseif( $button = 'submit' ){
				$this->status = (  $current_status == 'incomplete' ||   $current_status == 'resubmitted' ) ? 'resubmitted' : 'submitted' ;
				
				$this->meta[ 'instr_status' ] = 0; //not seen.
			}	
				
		}			
		
		/**
		 *  get_args
		 *
		 *	Returns an array of arguments for submission into the database.
		 *
		 *	return ARRAY
		 */
				
		
		public function get_args(): ARRAY
		{
			return [
				'ID' => $this->id,
				'post_author' => $this->author,
				'post_content' => $this->content,
				'post_name' => $this->name,
				'post_title' => $this->title,
				'post_type' => $this->type,
				'post_status' => $this->status,
				'post_parent' => $this->parent_id,
				'post_meta' => $this->meta
			]; 		
			
		}			
		
		/**
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		private function __(){
			
			
		}	
		
		
		/**
		 *  
		 *
		 *
		 *	return 
		
				
		
		public function __(){
			
			
		}	
		 */
	}
}