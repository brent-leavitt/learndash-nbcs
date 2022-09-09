<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;

use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action as Action;

/*
 *  
 *	Updated 2 Jun 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Assignment_Processor_Context' ) ){ 
	class Assignment_Processor_Context { 

			
		/**
		* action
		* 
		* the action object being called by the context.  
		*
		* OBJ
		*/	
		
		private $action; 
		
		

		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct(  ){
				
		}

		
		
		/**
		 * 	set_action 
		 *
		 *	Sets the specific action object to handled by this context. 
		 *
		 *	returns VOID
		 **/
		 
		public function set_action( Action $action )
		{
		
			$this->action = $action;
		}
		
		
		
		
		/**
		 * 	set_messages
		 *
		 *	adds messages object 
		 *
		 *	returns VOID
		 **/
		 
			 
		public function set_messages( $messages )
		{
			$this->action->set_messages( $messages ); 
			
		}
				
		
		
		
		/**
		 * 	do_action
		 *
		 *  sends 
		 *
		 *	returns VOID
		 **/
		 
		
		public function do_action( INT $id, ARRAY $post ){
		
			print_pre( $this->action, __LINE__ . __CLASS__ ); 
			echo "USER ID is: { $id }";
			print_pre( $post, "The submitted post" ); 
			$this->action->do_action( $id, $post ); 
		
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