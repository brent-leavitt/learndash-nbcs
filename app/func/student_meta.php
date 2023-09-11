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
 *	do_action: 'rcp_membership_post_activate' in plugins\restrict-content-pro\core\includes\memberships\membership-functions.php
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
	
	//Check if membership object. 
	if( strcmp( $data->get_object_type(), 'membership' ) !== 0 ) return;

	$student_id = $data->get_user_id();

	//If the user is not a student, then we do need to not assign them a trainer. 
	$user = get_user_by( 'id', $student_id ); 
	if( !in_array( 'student', (array) $user->roles ) ) return; 

	$trainers = nb_get_trainers();
	
	$last_trainer_id = get_option( 'last_trainer_assigned' );
	$trainer_ids = array_keys( $trainers ); 
	$next = array_search( $last_trainer_id ,$trainer_ids ) + 1;
	
	//0 index is no trainer ID, so reset goes to 1. 
	$next_trainer_id = ( count( $trainer_ids ) <= $next ) ? $trainer_ids[ 1 ] : $trainer_ids[ $next ];

	$updated = ( empty( get_user_meta( $student_id, 'student_trainer', true ) ) ) ?
					add_user_meta( $student_id, 'student_trainer', $next_trainer_id ):
					false ; 
	
	if( $updated !== false )
	{
		update_option( 'last_trainer_assigned', $next_trainer_id ); 
		do_action( 'nb_trainer_new_student', $student_id,  $next_trainer_id ); 
	}
}

add_action( 'rcp_membership_post_activate', 'Doula_Course\App\Func\assign_student_trainer', 10 , 2 );  




/**
 *  This adds an admin note to the student's file. 
 *	
 * @param 	int   	$student_id     User ID 
 * @param 	string  $note			Message to be recorded in the admin notes. 	     
 * @param 	string  $soure			ID of user/trainer who made the note. Default is 0 for system generated. 		
 *	
 * @return 	boolean
 */

function add_admin_student_note( $student_id, $note, $source = 0 )
{

	//Calls the classes that have be created to handle and process Admin_Notes. 

	//Returns true or false based on successful insertion into Admin Notes table. 

	return false; 
}




/**
 * Posts an admin note when a student cancels their their membership
 *	
 * @param 	int            	$membership_id 	ID of the membership.
 * @param 	RCP_Membership 	$membership    	Membership object.
 *	
 * @return 	boolean
 */

 function subscription_cancellation( $membership_id, $membership )
 {
	if ( !$membership->is_disabled() && ! $membership->was_upgraded() ) {
		// rcp_send_membership_email( $membership, 'cancelled' );
	}
 
	 return false; 
 }
 
 add_action( 'rcp_membership_post_cancel', 'Doula_Course\App\Func\subscription_cancellation', 10, 2  ); 




/**
 *   Posts an admin note when the student's subscription is suspended due to failed payment. 
 *	
 *	Reference: restrict-content-pro\core\includes\email-functions.php:471
 *
 * @param 	RCP_Member 				$member  	The member (RCP_Member object).
 * @param 	RCP_Payment_Gateway 	$gateway 	The gateway used to process the renewal.
 *	
 * @return 	boolean
 */

 function subscription_suspension(  RCP_Member $member, RCP_Payment_Gateway $gateway  )
 {
 
 
	 return false; 
 }
 
 add_action( 'rcp_recurring_payment_failed', 'Doula_Course\App\Func\subscription_suspension', 10, 2  ); 


/**
 *  Posts an admin note when the student subscription is expired.  
 *	
 * @param 	string 	$old_status			Prior status of membership, current is expired. 
 * @param 	int   	$membership_id     	Membership ID, can be used to find the user ID.  
 *	
 * @return 	boolean
 */

 function subscription_expired(  $old_status, $membership_id  )
 {
 
	if ( 'expired' == $old_status || 'new' == $old_status ) {
		return;
	}

	$membership = rcp_get_membership( $membership_id );

	//rcp_send_membership_email( $membership, 'expired' );
   
	$note = 'The student account has been marked as expired.'; //The note to send to the admin_student_note. 

	add_admin_student_note( $membership->get_user_id(), $note ); 
 }
 
 add_action( 'rcp_transition_membership_status_expired', 'Doula_Course\App\Func\subscription_expired', 10, 2  ); 




/**
 *   
 *	
 * @param 	int   	$_     User ID 
 *	
 * @return 	boolean
 */

 function subscription_reactivation( $payment_id )
 {
 
 	/**
	 * @var RCP_Payments $rcp_payments_db
	 */
	global $rcp_payments_db;

	$payment = $rcp_payments_db->get_payment( $payment_id );

	//error_log( "The ". __FILE__ ."::". __METHOD__ ." has been called. Here is the value of PAYMENT var. ". var_export( $payment, true ) );
				
	/*
	$user_info = get_userdata( $payment->user_id );

	if( ! $user_info ) {
		return;
	}
	*/
	//Need to assess if the account was previously inactive. 
	
	
	return false; 



 }
 
add_action( 'rcp_update_payment_status_complete', 'Doula_Course\App\Func\subscription_reactivation', 10, 1  ); 
//or

//https://help.ithemes.com/hc/en-us/articles/360051739814-rcp-membership-post-renew 
/*
    $expiration (string) - New membership expiration date, in MySQL format.
    $membership_id (int) - ID of the membership.
    $membership (RCP_Membership) - Membership object.
*/ 
add_action( 'rcp_membership_post_renew', 'Doula_Course\App\Func\subscription_reactivation', 10, 3  ); 




?>