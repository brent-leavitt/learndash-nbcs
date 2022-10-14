<?php

if ( !defined( 'ABSPATH' ) ) { exit; }

/***
*	HELPER FUNCTIONS and FILTERS 
*
*	Note: No namespace is declared here to allow for global access. 
*
*	Created on 24 Jul 2013
*	Updated on 03 Jan 2022
*
****/

/**
 *  print_pre
 *
 *	A helper function that prints out a an array of data in human-readable format.
 *
 *	returns VOID
 **/
 
function print_pre( $arr = array(), string $title = "No Title" ): VOID
{
	
	$output = $title.": "; 

	$output .= '<br><pre>';
	 
	
	$output .= var_export( $arr, true ); //true to return the array as a string instead of printing it out on the screen. 

	$output .= '</pre>';

	echo $output;
		
}

/**
 *  option_exists
 *
 *	A helper function that checks the databse to see if a wordpress option exists.
 *
 *	returns BOOL
 **/
 
function option_exists( $name, $site_wide = false ): BOOL
{
    global $wpdb; 
	
	//should check the global variables first before querying the database. 
	$result = $wpdb->query( $wpdb->prepare( "SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='%s' LIMIT 1", $option_name ) );
	
	return ( !empty( $result ) )? true : false; 
}


/**
 *  nb_get_trainers
 *
 *	Disables unneeded functionality to allow other code to work properly. 
 *
 *	returns array of user_id => names for existing trainers. 
 **/

function nb_get_trainers( ): ARRAY
{
	global $trainers; 
	
	if( isset( $trainers ) )
		return $trainers;
	
	$trainer_objs = get_users( [
		'role' => 'trainer', 
		'orderby' => 'ID'
	] );
	
	$trainers = [ 0 => '(No Trainer)'];
	
	foreach( $trainer_objs as $trainer )	
		$trainers[ $trainer->ID ] = $trainer->user_firstname.' '.$trainer->user_lastname; 
	
	return $trainers; 
	
}

/**
 *  disable_stuff
 *
 *	Disables unneeded functionality to allow other code to work properly. 
 *
 *	returns boolean (false)
 **/

function disable_stuff( $data ): BOOLEAN
{
	
	return false;
	
}


/* Unneeded SEO functionality causing second page load. 
Diasable so that bookmarking tool works correctly. */

add_filter( 'index_rel_link', '\disable_stuff' );
add_filter( 'parent_post_rel_link', '\disable_stuff' );
add_filter( 'start_post_rel_link', '\disable_stuff' );
add_filter( 'previous_post_rel_link', '\disable_stuff' );
add_filter( 'next_post_rel_link', '\disable_stuff' );


/**
 *  nb_count_students
 *
 *	Tallies the total number of students and alumni for use in the trainer students tables. 
 *	
 *	
 *	
 *
 *	returns array
 **/
function nb_count_students( int $trainer = 0 ):ARRAY
{
	$arr = []; 
	
	//Get My Students. 
	$my_students = [];
	$student_objs = get_users( [
		'role' => 'student', 
		'orderby' => 'ID'
	] );

	foreach( $student_objs as $student ){
		if( $student->has_prop( 'student_trainer' ) ){
			if( $trainer == $student->get( 'student_trainer' ) )
				$my_students[] = $student->ID;		
		}
	}
	
	//Get individual type counts. 	
	$user_count = count_users(); 
	$counts = $user_count[ 'avail_roles' ];
		
	//Get all users
	
	$all_users = get_users([
		'role__in' => [ 'student', 'alumnus', 'inactive' ]
	]);
		
		
	$arr[ 'my_students' ] 	= count( $my_students ); //all active students of current trainer. 
	$arr[ 'all_students' ] 	= $counts[ 'student' ] ?? 0;  //all active students. 
	$arr[ 'alumni' ] 		= $counts[ 'alumnus' ] ?? 0;  //all active alumni
	$arr[ 'inactive' ] 		= $counts[ 'inactive' ] ?? 0;  //all inactive students and alumni.
	$arr[ 'all_users' ] 	= count( $all_users );  //all students, alumni, and inactive (student or alumni)
	
	return $arr; 
}



/*
*
*
* For use in ADMIN areas only. Returns the ID of the current user if they are a trainer, else it looks for it in a $_GET parameter. 
* If no trainer is set, then the current user will be loaded. 
*  
* Return $trainer - a trainer/user ID. 
*/

function nb_get_current_trainer_id(){
	
	$user = wp_get_current_user();
	$roles = ( array ) $user->roles;
	
	//Load current user as trainer, if trainer is set. 
	$trainer = ( in_array( 'trainer', $roles ) ) ? $user->ID : 0; 	
	
	//Allow for URL override if the trainer paramater is set, so that other trainers can 
	$trainer = $_GET[ 'trainer' ] ?? get_current_user_id(); 
	
	return intval( $trainer ); 
}


?>