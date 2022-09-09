<?php 

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * These functions are only loaded for non-administrators. 
 *
 *
 *
 */



/**
 *  remove_admin_bar
 *	
 *	Disable the default admin bar for all users except administrators. 
 *	
 * 	
 *	Returns
 */	

function remove_admin_bar() {
	if ( !current_user_can( 'administrator' ) && !is_admin() )
		show_admin_bar( false );
}

add_action('after_setup_theme', 'Doula_Course\App\Func\remove_admin_bar');

?>
