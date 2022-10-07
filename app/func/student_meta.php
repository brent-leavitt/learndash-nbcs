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




/**
 *	assign_student_trainer
 *
 *	do_action: 'rcp_new_membership_added' in plugins\restrict-content-pro\core\includes\memberships\membership-functions.php
 *	
 * @param int          $membership_id      Membership ID, not needed. 
 * @param arr          $data		       Data, $data[ 'user_id' ] is needed value. 
 *	
 *	
 * 	return 
 */

function assign_student_trainer( $membership_id, $data ){
	
	$student_id = $data[ 'user_id' ];
	
	$trainers = nb_get_trainers(); 
	
	$last_trainer_id = get_option( 'last_trainer_assigned' );
	
	$trainer_ids = array_keys( $trainers ); 
	
	$next = array_search( $last_trainer_id ,$trainer_ids ) + 1;
	
	//0 index is no trainer ID, so reset goes to 1. 
	$next_trainer_id = ( count( $trainer_ids ) <= $next ) ? $trainer_ids[ 1 ] : $trainer_ids[ $next ];

	$updated = ( empty( get_user_meta( $student_id, 'student_trainer', true ) ) ) ?
					add_user_meta( $student_id, 'student_trainer', $next_trainer_id ):
					false ; 
	
	if( $updated !== false ){
		
		update_option( 'last_trainer_assigned', $next_trainer_id ); 
		
		do_action( 'nb_student_trainer_assigned', $student_id, $next_trainer_id );
	}
}

add_action( 'rcp_new_membership_added', 'Doula_Course\App\Func\assign_student_trainer', 10 , 2 );  


?>