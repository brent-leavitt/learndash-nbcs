<?php 

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }

 

/**
 *  call_required
 *	
 *	A function to help consolidate required calls.
 * 	
 *	Returns VOID
 */	

function call_required( array $required, $prefix = ''){
	foreach( $required as $name )
		require_once( $prefix . $name . '.php' );
}


if( is_admin() ){
	call_required( [
		'dashboard',	//Add admin dashboard widgets.
		'func', 		//Add admin only functions.
		'metaboxes', 	//Add admin metabox widgets for CPTs. 
		'course', 		//Add admin functions related to courses. 
	], 'admin_' );
}

if(  !is_admin()  )
	call_required( ['non_admin'] );

call_required([
	'activate', 	//Activation Functionality
	'crons', 		//Add Crons Functionality
	'email', 		//Add Additional Email Functionality
	'helper', 		//Add helper functions.
	//'login', 		//Login Screen Customization
	//'menus', 		//Add Menus based on user permissions
	'pages', 		//Add Custom Page Functionality (Shortcodes)
	'post_types', 	//Add Custom Post Types.
	'post_status', 	//Add Custom Post Status. 
	'query_vars', 	//Add Additional Automated Triggers (IPN and Crons)
	'register', 	//Add Extra Registration Fields to RCP forms.  
	'roles', 		//Add Roles. 
	'shortcodes', 	//Add Shortcodes.
	'student_meta', //Add Student Meta actions.
	'widget', 		//Add Widgets
]);




?>