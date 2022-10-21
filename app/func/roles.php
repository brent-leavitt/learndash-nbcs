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


?>