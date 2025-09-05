<?php 

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Post_Stati as Post_Stati; 

if ( !defined( 'ABSPATH' ) ) { exit; }

/*
	This is where we register post status for the custom post types that we've created for the doula course. 
*/


/**
 *  register_post_statuses
 *
 *	Registering additional Post Statuses, which are NOT dependent upon Custom Post Types. 
 *	So for any CPT where an additional status is needed, we can just add it to the list found below. 
 *	The same status can be used in multiple, different post types. 
 *
 *	Returns VOID 
 */		

//
function register_post_statuses():VOID
{
	
	$post_stati = [
		'submitted'=>[],
		'incomplete'=>[],
		'resubmitted'=>[],
		'completed'=>[],
		'active'=>[],
		'inactive'=>[],
		'suspended'=>[]
	];
	
	foreach( $post_stati as  $name => $args){
		$status = new Post_Stati( $name, $args );
		register_post_status( $status->get_name(), $status->get_args() );	
	}

	/* global  $wp_post_statuses;	
	echo "<p>A list of all available Post Statuses:</p>";	
	print_pre( $wp_post_statuses );	 */
	
}

add_action( 'init', 'Doula_Course\App\Func\register_post_statuses' ); 




/**
 *  enable_custom_status_comments
 *	
 *	Enable commenting on assignments in status state other than Published. 
 * 	
 *
 *	Returns VOID 
 */	

function enable_custom_status_comments():VOID
{
    if( isset( $_GET['post'] ) ) 
    {
        $post_id = absint( $_GET['post'] ); 
        $post = get_post( $post_id ); 
		
		//Statuses to allow for commenting purposes.
		$post_status = [
			'submitted',
			'incomplete',
			'resubmitted',
			'completed'
		];
		
        if ( in_array( $post->post_status, $post_status  ) )
            add_meta_box(
                'commentsdiv', 
                __('Comments'), 
                'post_comment_meta_box', 
                'assignment', 
                'normal', 
                'core'
            );
    }
}

add_action( 'admin_init', 'Doula_Course\App\Func\enable_custom_status_comments' );


/**
 *  cpt_overview_status
 *	
 *	This adds custom post status (as listed in the array below) onto the individual posts on 
 *	the overview screens. 
 * 	
 *
 *	Returns array
 */	

function cpt_overview_status( $states ):ARRAY
{
	global $post;

	//If this is a custom status as listed in the status arr below, this will return empty. Not sure why. 
	$query = get_query_var( 'post_status' );

	$status_arr = [
		'submitted', 
		'incomplete',
		'resubmitted',
		'completed',
		'inactive',
		'active',
		'suspended'
	];
	
	//If the query is empty, it won't be found in the custom status array, so we will need to add it.
	if( !in_array( $query, $status_arr ) ){
		
		//get the current status of the post, which should in the array.
		if( !empty( $post->post_status ) )
			$state = ( in_array( $post->post_status, $status_arr ) )? $post->post_status : NULL;
		
		//And then return it in an uppercase state, if not empty.
        if( !empty( $state ) )
			return [ ucfirst( $state ) ];
		
    }
    return $states; 
}

add_filter( 'display_post_states', 'Doula_Course\App\Func\cpt_overview_status' );


?>