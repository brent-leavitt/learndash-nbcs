<?php

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }


/**
 *	Updated on 22 Aug 2022	
 *
 *	This file action hooks to interface with learndash course actions
 *
 * 	NOTE:
 *
 **/

 
/**
 *	update_student_course_access 
 *
 *	do_action: 'learndash_update_course_access' in plugins\sfwd-lms\includes\course\ld-course-user-functions.php
 *	
 * @param int          $user_id            User ID.
 * @param int          $course_id          Course ID.
 * @param boolean      $remove             Whether to remove course access from the user.
 *	
 *	
 * 	return 
 */

function update_student_course_access( $user_id, $course_id, $no_use_list, $remove ){
	
	$updated = []; 
	
	$tracks = get_user_meta( $user_id, 'student_tracks', true ) ?: [] ; 
	
	//Add track
	if( !$remove )
		$updated = array_unique( array_merge( $tracks, [ $course_id ] ), SORT_NUMERIC );  
	
	//Remove a track 
	else
		$updated = array_diff( $tracks, [ $course_id ] );  

	update_user_meta( $user_id, 'student_tracks', $updated, $tracks ); 
		
}
	

add_action( 'learndash_update_course_access', 'Doula_Course\App\Func\update_student_course_access', 10 , 4 );  


?>