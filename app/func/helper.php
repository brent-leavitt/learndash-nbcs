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
	
	$output = !empty( $title )? $title.": " : ''; 

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
	$trainer = $_GET[ 'trainer' ] ?? $trainer; 
	
	return intval( $trainer ); 
}

/**
 * Get the user's roles
 * @since 1.0.0
 */
function nb_get_current_user_roles() 
{
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		return $roles; // This returns an array
	} 
	
	return array();
}


/**
 * Encodes a json string if not already so encoded. 
 *
 * @since     1.0.0
 * @param     mixed  	$mixed   //data to be encoded in a json string. 
 * @return    string   //A JSON encoded string
 */

 function maybe_json_encode( $mixed )
 {

	if( is_json( $mixed ) ) return $mixed; 

	return json_encode( $mixed );

 }


/**
 * Decodes a json string if not already decoded. 
 *
 * @since     1.0.0
 * @param     mixed    $mixed   //possibly an encoded json string, or something else. 
 * @return    mixed    //if it was an encoded json string, the unencoded version of that. 
 */

 function maybe_json_decode( $mixed )
 {

    if( !is_json( $mixed ) ) return $mixed; 

	return json_decode( $mixed, true ); 

 }


/**
 * Check if incoming data is in JSON format, or not. 
 * credit: https://stackoverflow.com/a/45241792/356958
 * 
 * @since     1.0.0
 * @param     int    $sid   //student ID
 * @return    int    $tid   //trainer ID
 */

 function is_json( $value ) {
    // Numeric strings are always valid JSON.
    if ( is_numeric( $value ) ) { return true; }

    // A non-string value can never be a JSON string.
    if ( ! is_string( $value ) ) { return false; }

    // Any non-numeric JSON string must be longer than 2 characters.
    if ( strlen( $value ) < 2 ) { return false; }

    // "null" is valid JSON string.
    if ( 'null' === $value ) { return true; }

    // "true" and "false" are valid JSON strings.
    if ( 'true' === $value ) { return true; }
    if ( 'false' === $value ) { return true; }

    // Any other JSON string has to be wrapped in {}, [] or "".
    if ( '{' != $value[0] && '[' != $value[0] && '"' != $value[0] ) { return false; }

    // Verify that the trailing character matches the first character.
    $last_char = $value[strlen($value) -1];
    if ( '{' == $value[0] && '}' != $last_char ) { return false; }
    if ( '[' == $value[0] && ']' != $last_char ) { return false; }
    if ( '"' == $value[0] && '"' != $last_char ) { return false; }

    // See if the string contents are valid JSON.
    return null !== json_decode( $value );
}






/**
 * Adds an admin note to the student's admin notes file. 
 * 
 * @since     1.0.0
 * @param     int    $student_id   	//obviously, the student ID
 * @param     string $note   		//Message to be recorded
 * @param     int    $source  		//user_id recorded as source, 0 = system, -1 = old admin notes. Should usually be 0. 
 * @return    bool   //successfully upated the 
 */

 function nb_add_admin_student_note( int $student_id, string $note, int $source ): bool
 {
	//Build out the admin note to add. 
	$insert_arr = [
		'uid'	=> $source,
		'date' 	=> current_time( 'mysql' ),
		'note'	=> $note
	];

	//Get the admin notes meta key for the student. 
	$results = get_user_meta( $student_id, 'admin_notes', true );
	$admin_notes = ( !empty( $results ) )? $results : []; 
	$admin_notes[] = $insert_arr;
	
	//append back to the meta key and insert it back into the databse. 
	return ( update_user_meta( $student_id, 'admin_notes', $admin_notes  ) )? true : false ;

	

 }
?>