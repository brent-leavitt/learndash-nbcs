<?php

namespace Doula_Course\App\Clss;

use function Doula_Course\App\Func\set_doula_course_roles;
use function Doula_Course\App\Func\remove_roles;

if ( ! defined( 'ABSPATH' ) ) { exit; }

//use Doula_Course\Clss\Editor as Editor;


 /**
 *	
 **/
 
if( !class_exists( 'Course' ) ){
	class Course{

		 /**
		 *	
		 **/


		 /**
		 *	
		 **/

		 public function __construct()
		 {
		
			$this->autoload();
			add_action( 'doula_course_activate', array( $this, 'activate' ) );
			add_action( 'init', array( $this, 'init' ) ); 
			
		 } 
		 
		 
		 /**
		 *	
		 **/

		 public function go(){
			//NBDT Course Functions
			require_once( DOULA_COURSE_PATH.'app/func/requires.php' );
			
			//$nbEditor = new Editor();
			
		 } 
		 		 
		 
		 /**
		 *	init
		 *	
		 *	Initialization Actions to be set by the plugin. 
		 *	
		 *	
		 *	return void
		 **/

		 public function init( ): void
		 {
		
			\register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
			
		 } 
		 		 		 
		 
		 /**
		 *	Activate
		 *	
		 *	One-time actions to be taken at the time of activation.
		 *	
		 *	
		 *	return void
		 **/

		 public function activate( ): void
		 {
			
			//set_doula_course_roles();
			
		 } 
		 
		 		 
		 /**
		 *	Deactivate
		 *	
		 *	One-time actions to be taken at the time of de-activation.
		 *	
		 *	
		 *	return void
		 **/

		 public function deactivate( ): void
		 {
			//remove_roles();
			
			
		 } 
		 
		  
		 
		 /**
		 *	This loads all classes, as needed, automatically! Slick!
		 **/

		private function autoload(){
			 
			 
			spl_autoload_register( function( $class ){

				$path = strtolower( substr( str_replace( '\\', '/', $class), 0 ) );				
				$path = str_replace( 'doula_course/', '', $path );				
				$path = DOULA_COURSE_PATH. $path . '.class.php';
				
				if (file_exists($path))
					require $path;
				
			} );
		 } 
	}//end Class Course
}
?>