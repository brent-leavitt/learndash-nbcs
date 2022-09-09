<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;

use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action;

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
 
 
if( !class_exists( 'Delete_Submission' ) ){ 
	class Delete_Submission implements Assignment_Processor_Action  { 

		
		
		

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		
		/**
		 *  $post_id
		 *
		 *  The ID of the post to be deleted or moved to trash. 
		 *
		 *	INT
		 */
		private $post_id; 
		
		
		/**
		 *  $trash
		 *
		 *  Trashed post data. 
		 *
		 *	OBJ
		 */
		private $trash; 
		
		
		

		
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
			
			$this->post_id = $post[ 'post_id' ];
			
			if( isset( $post[ 'delete_assignment' ] ) ){
				
				$this->trash = $this->trash();
				
				$this->set_notices(); 
				
			}
			
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
		 * 	trash
		 *
		 *	(descrip)
		 *
		 *	returns POST OBJ| false | null
		 **/
		 
			 
		private function trash()
		{

			return wp_delete_post( $this->post_id ); 
		
		}	
		
		
			
		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			
			if( !empty( $this->trash ) ){
				
				//Assignmnet was deleted. There is post_data available for processing if desired. 
				
				$this->messages->add( 'student' , 'notice', __( 'The assignment has been successfully discarded.', NBCS_TD ) );
			
			} else {
				
				//Nothing was deleted. 
				
				$this->messages->add( 'student' , 'notice', __( 'There was no data available for deletion. Sorry!', NBCS_TD )  ); 
				
			}	
				
			//Check Submission class for notices. 
			
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