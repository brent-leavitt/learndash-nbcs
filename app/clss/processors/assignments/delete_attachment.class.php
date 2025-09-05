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
 
 
if( !class_exists( 'Delete_Attachment' ) ){ 
	class Delete_Attachment implements Assignment_Processor_Action  { 
		
		

	

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		
		/**
		 *  $post_ids
		 *
		 *  Array of post ID's to be deleted. 
		 *
		 *	ARR
		 */
		private $atch_ids = []; 
		
		
		/**
		 *  $trash
		 *
		 *  Trashed post data. 
		 *
		 *	ARR
		 */
		private $trash = []; 
		
		
		

		
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
			
			$this->atch_ids[] = $post[ 'atch_id' ];
			
			if( isset( $post[ 'delete_attachment' ] ) ){
				
				$this->trash();
	
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
			foreach( $this->atch_ids as $atch_id )
				$this->trash[] = wp_delete_attachment( $atch_id, true ); //true, we're doing a hard delete of the attachment from the server.
			
		}	
		
		
			
		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			
			if( !empty( $this->trash ) && ( $this->trash[ 0 ] !== NULL ) ){
				
				//Assignmnet was deleted. There is post_data available for processing if desired. 
				foreach( $this->trash as $item )
					$this->messages->add( 'student' , 'notice', "The following attachment has been deleted: ". $item->ID .", ". $item->post_title  );
			
			} else {
				
				//Nothing was deleted. 
				
				$this->messages->add( 'student' , 'warning', __( 'There were no attachments available for deletion. Sorry!', NBCS_TD )  ); 
				
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