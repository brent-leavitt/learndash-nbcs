<?php

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }


function activate_doula_course(){
	
	global $wpdb; 
	$installed_version = get_option( 'doula_course_version' );
	
	if( $installed_version != DOULA_COURSE_VERSION ){

		/* $charset_collate = $wpdb->get_charset_collate();
	    //Nothing to do here. 
		$sql = "";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "doula_course_version", DOULA_COURSE_VERSION );
		
		wp_mail( 'brent@trainingdoulas.com', 'Plugin Activated', 'The doula course plugin has been actviated and the database has been updated!' );
		 */
	}
	
	//add 'last_trainer_assigned' option to the database. 
    $trainers = get_users( [
        'role'    => 'trainer',
        'orderby' => 'ID',
    ] );
	
	add_option( 'last_trainer_assigned', $trainers[0]->ID );

}

add_action( 'doula_course_activate', 'Doula_Course\App\Func\activate_doula_course' );

?>