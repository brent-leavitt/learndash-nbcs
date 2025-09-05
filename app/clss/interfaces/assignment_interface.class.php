<?php 

namespace Doula_Course\App\Clss\Interfaces;


/*
 *  
 *	Updated 25 Feb 2022
 *
 *	Assignment_Interface
 *	
 *	This defines the rules for assignment elements in the assignments_map tree. 
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !interface_exists( 'Assignment_Interface' ) ){ 
	interface Assignment_Interface { 

		
		
		/**
		 * 	load
		 *
		 *	(descrip)
		 *
		 **/
		 
		public function build( );
			
			
		/**
		 * 	load
		 *
		 *	(descrip)
		 *
		 **/
		 
		public function load( );
			

		
		
		/**
		 * 	add
		 *
		 *	(descrip)
		 *
		 **/
		 
		public function add( );
			

		
		
		/**
		 * 	update
		 *
		 *	(descrip)
		 **/
		 
		public function update( );
			

		
		
		/**
		 * 	deprecate
		 *
		 *	(descrip)
		 *
		 **/
		 
		public function deprecate( );
			

	}
}
?>