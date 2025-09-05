<?php 

namespace Doula_Course\App\Func;

//To enable namespacing, add_actions need attention.

use Doula_Course\App\Clss\Grades\Create_Assignments_Map as Create_Map;
use Doula_Course\App\Clss\Grades\Grades;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * These functions are only loaded on the admin pages. A check is in place to call to see if admin pages are being loaded. 
 */


/**
 * 
 * 
 * This updates the assignment map when a new course/program is added to the system. 
 * 
 * return BOOL
 */
 
function update_assignments_map( $post_id = 0, $post = null, $update = false )
{
	if ( ! current_user_can( 'edit_course', $post_id ) )
		return false;
	
	// course post type is "sfwd-courses"
	if ( strcmp(  get_post_type( $post_id ), "sfwd-courses" ) !== 0 ) 
		return false;
	
	// Remove our save_Post hook to prevent recursive save loops.
	remove_action( 'save_post', 'Doula_Course\App\Func\update_assignments_map', 50, 3 );
		
	$option = NBCS_PREFIX.'assignments_map'; 
	
	$creator = new Create_Map(); 
	$creator->build();
	
	$map = $creator->get_map();	
	update_option( $option, $map );
	
	return true;
	
}

add_action('save_post', 'Doula_Course\App\Func\update_assignments_map', 50 ); 






?>
