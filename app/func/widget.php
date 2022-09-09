<?php
//Widgets for NB Doula Courses

namespace Doula_Course\App\Func;

if ( ! defined( 'ABSPATH' ) ) { exit; }


//add the Student Progress widget. 

add_action('widgets_init', function(){ 
	register_widget( 'Doula_Course\App\Clss\Student_Progress' );
} );


/**
 *  set_course_bookmark
 *	
 *	Course bookmarkig function, can only bookmark one location.
 * 	
 *	Returns VOID
 */	

function set_course_bookmark(){
	
	 global $current_user, $post;

    // Make sure we are on a singular course post type page, if not, bail
    if ( !is_singular( 'material' ) )
        return;

    // Make sure we have a logged in user
    if ( !is_user_logged_in() )
        return; 

    // Great, we are on a single course post page and user is logged in, lets continue

    $sid = $current_user->ID;
    $bookmark_id = $post->ID;

    $updated = update_user_meta( $sid, 'course_bookmarks', $bookmark_id );
	
}

add_action( 'template_redirect', 'Doula_Course\App\Func\set_course_bookmark' );
?>