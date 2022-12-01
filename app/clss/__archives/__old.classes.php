<?php 


namespace Doula_Course\App\Clss;


if ( ! defined( 'ABSPATH' ) ) { exit; }

//Autoload classes as they are needed. 

	/**
	 * This loads all classes, as needed, automatically! Slick!
	 *	
	 */
	 
/* 	private function autoload_classes( ){

		spl_autoload_register( function( $class ){
			
			$path = __DIR__ .'/'. substr( str_replace( '\\', '/', $class), 0 ) . '.php';
			
			if( file_exists( $path ) )	require_once $path;
			
		} );
	}
 */



// Loads a list of all available and applicable classes for
// New Beginnings Doula Training interface. 

//Transactions
require_once('transaction.class.php');

//Students 
require_once('student.class.php');

//Admin Tables
require_once('tables.class.php');

//Admin Editors
require_once('editor.class.php');

//Assignment Map
include_once('assignment_map.class.php');

//Assignment 
include_once('assignment.class.php');

//Messages
include_once('message.class.php');

//Walker for Manuals
include_once('section_list.class.php');




?>