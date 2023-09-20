<?php

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Roles;
use Doula_Course\App\Clss\Capabilities;

if ( !defined( 'ABSPATH' ) ) { exit; }


function add_roles( ){
	
	$roles = new Roles(
		[
			'trainer' => [
				'edit_posts',
				'edit_users',
				'list_users',
				'read',
				'student',
				'upload_files',
				'read_assignment',
				'edit_assignment',
				'delete_assignment',
				'publish_assignment',
				'edit_published_assignment',
				'read_assignments',
				'edit_assignments',
				'edit_others_assignments',
				'publish_assignments',
				'edit_published_assignments',
				'edit_private_assignments',
				'delete_assignments',
				'read_certificates',
				'edit_certificates',
				'edit_others_certificates',
				'publish_certificates',
				'edit_published_certificates',
				'edit_private_certificates',
				'delete_certificates',
				'read_notifications',
				'edit_notifications',
				'edit_others_notifications',
				'publish_notifications',
				'edit_published_notifications',
				'edit_private_notifications',
				'delete_notifications',
			],
			'student' => [
				'read',
				'student',
				'upload_files',
				'edit_assignments',
				'delete_assignments',
				'publish_assignments',
				'edit_published_assignments',
			],
			'alumnus' => [
				'read',	
				'upload_files',
				'edit_assignments',
				'delete_assignments',
				'publish_assignments',
				'edit_published_assignments',
			],
			'reader' => [
				'read',
				
			],
			'inactive' => [
				'inactive',
			],
		]
	);

	$roles->build();
	
	foreach( $roles->get_roles() as $role ){
		
		add_role( $role['name'], $role['u_name'], $role['caps'] );
	}
	
	//Move all WP functionality into this function, keep out of class. 
}

function add_caps( ){
	
	//this is a little lazy, sending the whole args array when you only need to two values. 
	$caps = new Capabilities([
		'assignment',	
		'certificate',
		'message',
		
	]);

	$admin = get_role( 'administrator' );

	foreach( $caps->get_caps() as $cap ){
		if( !$admin->has_cap( $cap ) )
			$admin->add_cap( $cap );
	}	
	
}


function remove_roles( ){
	
	$roles = [
		'trainer',
		'student',
		'alumnus',
		'reader',
		'inactive',
	];
	
	foreach( $roles as $role ){
		
		remove_role( $role );
	}
	
	//exit( print_pre( $roles ) );
}


function remove_caps( ){
	
	
	$caps = new Capabilities([
		'assignment',	
		'certificate',
		'message',
	]);

	$admin = get_role( 'administrator' );
	
	$results = [];
	
	foreach( $caps->get_caps() as $cap ){
		if( $admin->has_cap( $cap ) ){
			$admin->remove_cap( $cap );
			$results[] = $cap.' removed.';
		}
			
	}	
	
	//exit( print_pre( $results ) );
	
}

add_action( 'doula_course_activate',  'Doula_Course\App\Func\add_roles' );
//add_action( 'doula_course_activate',  'Doula_Course\App\Func\add_caps' );
add_action( 'doula_course_deactivate',  'Doula_Course\App\Func\remove_roles' );
//add_action( 'doula_course_deactivate',  'Doula_Course\App\Func\remove_caps' );




/*
*  Sets the default role to inactive when all roles are removed by Restrict Content Pro
*
*/

function nb_set_inactive_when_role_not_set( $old_status, $new_status, $membership_id ) {
    // Get the membership
	$membership = rcp_get_membership( $membership_id );
	rcp_log( "Running the 'nb_set_inactive_when_role_not_set' function from the 'rcp_membership_post_disable' action hook." );

   	//print_pre( $membership_obj, 'The Membership object'); 
	
	// Get the user
    $user = new WP_User($membership->get_user_id());
	rcp_log( sprintf( 'Setting role to inactive for user #%d', $user->ID )); 
	
    // Check if the user has no roles
    if( empty( $user->roles ) ){
		rcp_log( "User's roles are empty! Proceeding to update." ); 
		
		// Add the 'Inactive' role
        $result = $user->add_role( 'inactive' );  // replace 'Inactive' with the role you want to assign
		rcp_log( sprintf( 'The result of Add User Role: %s', $result ) ); 

    } else {
		rcp_log( "The user's role was not empty, so it was not updated." );

	}
}

/**
 * Action "rcp_transition_membership_status" will run.
 *
 * @see   \RCP\Database\Query::transition_item()
 *
 * @param string $old_status    Old membership status.
 * @param string $new_status    New membership status.
 * @param int    $membership_id ID of the membership.
 *
 * @since 3.0
 */

add_action( 'rcp_transition_membership_status', 'nb_set_inactive_when_role_not_set', 20, 3 );




?>