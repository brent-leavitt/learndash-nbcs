<?php 

namespace Doula_Course\App\Clss\Interfaces;


/*
 *  
 *	Updated 7 June 2022
 *
 *	Assignment_Processor_Action
 *	
 *	 
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !interface_exists( 'Assignment_Processor_Action' ) ){ 
	interface Assignment_Processor_Action { 

		
		
		/**
		 * 	do_action
		 *
		 *	(descrip)
		 *
		 **/
		 
		public function do_action( $id, $post );
			
		
		/**
		 * 	set_messages
		 *
		 *	(descrip)
		 *
		 *
		 **/
		 
		public function set_messages( $messages );
			
		

	}
}
?>