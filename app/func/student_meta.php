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

 

/*-----------------*/
//A function to get all the meta data for the student to display and manipulate. 
function get_student_meta(){
	global $current_user;
	return ( $current_user->ID != null)? get_userdata( $current_user->ID ):NULL; 
	
}
 
 
 
 
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
 *  This plugin may require additional filtering based on the type of membership being created.
 *	For example, no trainer should be assigned to reader accounts. 
 *
 *	do_action: 'rcp_new_membership_added' in plugins\restrict-content-pro\core\includes\memberships\membership-functions.php
 *	
 * @param int          $membership_id      Membership ID, not needed. 
 * @param arr          $data		       Data, $data[ 'user_id' ] is needed value. 
 *	
	Membership Data:

		array (
		  'customer_id' => '1',
		  'user_id' => '15',
		  'object_id' => 1,
		  'object_type' => 'membership',
		  'currency' => 'USD',
		  'initial_amount' => '0.00',
		  'recurring_amount' => '0.00',
		  'created_date' => '2022-10-07 00:00:00',
		  'expiration_date' => '2022-10-08 23:59:59',
		  'auto_renew' => 0,
		  'times_billed' => 0,
		  'maximum_renewals' => 0,
		  'status' => 'active',
		  'signup_method' => 'manual',
		  'disabled' => 0,
		  'gateway_customer_id' => '',
		  'gateway_subscription_id' => '',
		  'gateway' => 'manual',
		  'trial_end_date' => '2022-10-08 23:59:59',
		)

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