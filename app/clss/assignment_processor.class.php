<?php 

namespace Doula_Course\App\Clss;

use Doula_Course\App\Clss\Processors\Assignments\Assignment_Processor_Context as Context;
use Doula_Course\App\Clss\Processors\Assignments\Delete_Attachment;
use Doula_Course\App\Clss\Processors\Assignments\Delete_Submission;
use Doula_Course\App\Clss\Processors\Assignments\Revert_To_Draft;
use Doula_Course\App\Clss\Processors\Assignments\Save_Submission;
use Doula_Course\App\Clss\Processors\Assignments\Save_Attachment;
//Also uses Submission class

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to process information from the assignment editor
	 * 
	 *
	 *
	 */


if( !class_exists( 'Assignment_Processor' ) ){
	class Assignment_Processor{
		

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		/**
		 *  $post
		 *
		 * 	Post data sent from server	
		 *
		 *	ARRAY
		 */
		
		private $post; 
		
		
		/**
		 *  $context
		 *
		 *  The context of the action to be taken by the processor
		 *
		 *	OBJ
		 */
		
		private $context;
		
		
		/**
		 *  $actions
		 *
		 *  A list of actions to be taken, assessed by the incoming post data. 
		 *
		 *	ARRAY
		 */
		
		private $actions = array(); 
		

		

	
				

		/**
		 *  $submission
		 *
		 * 	An assignment submission object 
		 *
		 *	OBJECT
		 */
		
		private $submission; 
		
		
		/**
		 *  $student_id
		 *
		 * 	User ID
		 *
		 *	INT
		 */
		
		private $student_id; 
		
		
		
		
				
		/**
		 *  __construct
		 *
		 *	
		 */
		
		public function __construct( $messages ){
			
			//attach message class
			$this->messages = $messages; 
			
		}

		

		/**
		 *  process
		 *
		 *	Processes submitted data. 
		 *
		 *	return BOOL
		 */
		 
		 
		public function process( $post ): BOOL
		{
			//verify actions to be taken by submitted data. 
			if( !empty( $post ) ) 
				$action = $this->verify( $post );

			if( !$this->actions )
				return false; 
			
			//do actions 
			
			return $this->do_actions(); //
			 
		}
				
		/**
		 *  verify
		 *
		 *  Builds a list of actions to be taken. 
		 *
		 *	return BOOL
		 */
				
		
		private function verify( $post ){
			
			
			$actions = [
				'delete_attachment',
				'delete_submission',
				'revert_to_draft',
				'save_submission',
				'save_attachment'
			];
			
			foreach( $actions as $action ){
				if( isset( $post[ $action.'_nonce' ] ) && wp_verify_nonce( $post[ $action.'_nonce' ], $action ) )
					$this->actions[] = str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $action ) ) ); 	
			}
			
	
				
			if( !empty( $this->actions ) ){
				$this->context = new Context(); 
				$this->post = $post;
				$this->set_student();				
			}
	
			
		}	

		
		/**
		 *  set_student
		 *
		 *  Sets the student_id property. 
		 *
		 *	return VOID
		 */
				
		
		private function set_student(): VOID
		{
						
			$this->student_id = get_current_user_id();		
			
		}	
		

		
		
		
		/**
		 *  do_actions
		 *  
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		public function do_actions(){
			
			foreach( $this->actions as $key => $action ){
				$action = "Doula_Course\App\Clss\Processors\Assignments\\".$action; //(22Jun22)Not sure why this is requiring a full namespace. 
				$this->context->set_action( new $action );
				$this->context->set_messages( $this->messages ); 				
				$this->context->do_action( $this->student_id, $this->post );
				unset( $this->actions[ $key ] );
			}
			
			
			//If all actions have been processed and unset, then we're done and return true, else false. 
			return ( !$this->actions )? true : false;
		}	
		
			
		
		
		/**
		 *  
		 *
		 *
		 *	return 
		
				
		
		public function __(){
			
			
		}	
		 */
		
		/**
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		private function __(){
			
			
		}	
	}
}