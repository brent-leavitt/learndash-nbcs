<?php 

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Post_Types; 
use Doula_Course\App\Clss\Message; 

/*
	This is the file where we register our custom post types and set related parameters (such as post status) that we've created for the doula course. 
	
	Tracks and Materials are disabled in the LearnDash NBCS Plugin.
	
*/

if ( !defined( 'ABSPATH' ) ) { exit; }


/**
 *  Title: register_post_types
 *	
 *	Description: Using the Post_Types class this function creates custom post types (CPT) for our doula course plugin. 
 * 	
 *
 *	Returns NULL
 */	

function register_post_types()
{
	
	/* $track_args = [
		'post_type' => 'track', 			//
		'description' => 'This is the overall track of study', //Birth Doula track, Postpartum track, Basic Doula track, etc. 
		'hierarchical' => true,		//
		'exclude_from_search' => false, 	//
		'show_in_menu' => true,		//
		'menu_pos' => 52,			//
		'menu_icon' => 'welcome-learn-more',	//
		'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),		//
		'has_archive' => false,		//
	];

	$material_args = [
		'post_type' => 'material', 	//
		'description' => 'Learning Materials featured in the tracks of study.', 		//
		'hierarchical' => true,		//
		'exclude_from_search' => false, 	//
		'show_in_menu' => true,	//
		'menu_pos' => 53,			//
		'menu_icon' => 'book-alt',	//
		'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),		//
		'has_archive' => true,		//
	]; */

	$assignment_args = [
		'post_type' => 'assignment',//
		'description' => 'Student submitted content.', 		//
		'hierarchical' => false,		//
		'exclude_from_search' => true, 	//
		'show_in_menu' => true,	//
		'menu_pos' => 35,			//
		'menu_icon' => 'edit-page',	//
		'supports' => array( 'title', 'editor', 'page-attributes', 'revisions', 'comments' ),		//
		'has_archive' => true,		//
	];

	$certificate_args = [
		'post_type' => 'certificate', 			//
		'description' => 'The certification document for students.', 		//
		'hierarchical' => false,		//
		'exclude_from_search' => false, 	//
		'show_in_menu' => true,	//
		'menu_pos' => 36,			//
		'menu_icon' => 'awards',	//
		'supports' => array( 'title', 'editor', 'page-attributes', 'revisions', 'comments' ),		//
		'has_archive' => false,		//
	];
	
	$notification_args = [
		'post_type' => 'notification', 	//
		'description' => 'Templated notifications used to communicate with the students.', 		//
		'hierarchical' => false,		//
		'exclude_from_search' => false, 	//
		'show_in_menu' => true,	//
		'menu_pos' => 54,			//
		'menu_icon' => 'buddicons-pm',	//
		'supports' => array( 'title', 'editor', 'page-attributes', 'revisions', 'excerpt', 'author' ),		//
		'has_archive' => true,		//
	];

	
	$post_types = [
		/* 'track' => $track_args,
		'material' => $material_args, */
		'assignment' => $assignment_args,
		'certificate' => $certificate_args,
		'notification' => $notification_args
	];
	
	$results = [];
	foreach( $post_types as $type_key => $type_arg ){
		$type = new Post_Types( $type_arg );
		$results[] = register_post_type( $type->get_name(), $type->get_args() );
	}
		
}

add_action( 'init', 'Doula_Course\App\Func\register_post_types' ); 



/**
 *  revision_limit
 *	
 *	Limit the number of revisions for a given post type
 *	Presently, this function only limits assignemts to 5 revisions, 
 * 	but the code to easily be expanded to restrict other post types if needed. 
 * 	
 *
 *	Returns INT
 */	
 
function revision_limit( $num, $post ): INT
{ 
    
	return ( 'assignment' == $post->post_type )? 5 : $num;
    
}

add_filter( 'wp_revisions_to_keep', 'Doula_Course\App\Func\revision_limit', 10, 2 );



/**
 *  Title: edit_material_columns
 *	
 *	Description: This allows us to add custom columns (column headers) to the material custom post type. 
 * 	
 *	reference: https://developer.wordpress.org/reference/hooks/manage_screen-id_columns/
 *  
 *	Returns Array
 */	

/* 
function edit_material_columns( $columns ) {

	$date_column = array_splice( $columns, -1 );

	$type_column = array(
		'material_type' => __( 'Type' )
	);
	
	return  $columns + $type_column + $date_column;
}

add_filter( 'manage_edit-material_columns', 'Doula_Course\App\Func\edit_material_columns' ) ;

  */

/**
 *  Title: manage_material_columns
 *	
 *	Description: figures out what to display in the custom columns on the materials overview screen. 
 *	Echoes out results onto screen. 
 * 	
 *	Returns VOID
 */	

/* function manage_material_columns( $column, $post_id ) {
	//global $post;
	
	// If displaying the 'material_type' column.
	if( $column === 'material_type' ){
	
		// Get the post meta. 
		if( !metadata_exists( 'post', $post_id, 'material_type' ) ){
			
			echo __( '( not set )' );
			return;
		}
			
		$type = get_post_meta( $post_id, 'material_type', true );
	
		switch(	$type ){
			
			case 0:	
				echo __( 'Content' );
				break;
				
			case 1:
				echo __( 'Assignment' );
				break;
				
			case 2: 
				echo __('Section');
				break;
				
			case 3:
				echo __( 'Other' );
				break;
					
			case 4:
				echo __( 'Manual' );
				break;
			
			default:
				echo __( '( not set )' );
				break;
		}
	}
}

add_action( 'manage_material_posts_custom_column', 'Doula_Course\App\Func\manage_material_columns', 10, 2 );

  */

/**
 *  Title: materials_sortable_columns
 *	
 *	Description: Adds the material_type to the array of columns to be displayed for the materials CPT
 * 	
 *	https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095	
 *		
 *	Returns ARRAY
 */	
/*  
function materials_sortable_columns( array $columns ) {
	
	$columns[ 'material_type' ] = 'material_type';
	
	return $columns;
}

add_filter ( 'manage_edit-material_sortable_columns', 'Doula_Course\App\Func\materials_sortable_columns' );

  */

/**
 *  Title: set_overview_column_order
 *	
 *	Making custom added columns sortable. But only returns columns where the metadata exists. 
 * 	
 *	https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095	
 *		
 *	Returns NULL
 */	
/*  
function set_material_column_order( $query ) {
	
	if( ! is_admin() )
		return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'material_type' == $orderby ) {
        $query->set( 'meta_key', 'material_type' );
        $query->set( 'orderby', 'meta_value' );
    }
	
	//can expand to other meta_values by simply expanding the if clause, maybe setting to a switch. 
}

add_filter ( 'pre_get_posts', 'Doula_Course\App\Func\set_material_column_order' );

  */
 
/**
 *  Title: save_material_type_data
 *	
 *	Description: Add to our admin_init function 
 * 	
 *
 *	Returns NULL
 */	
   
/*  
function save_material_type( $post_id ) 
{     
	// verify if this is an auto save routine.         
	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )          
		return; 
	
	// Check permissions     
	if( !current_user_can( 'edit_page', $post_id ) )             
		return;     
	
	// Authentication passed now we save the data       
	if( isset( $_POST[ 'material_type' ] ) && ( $post->post_type != 'revision' ) )
	{
		$mat_type = esc_attr( $_POST[ 'material_type' ] );
		if( !empty( $mat_type ) )
			update_post_meta( $post_id, 'material_type', $mat_type);
		else
			delete_post_meta( $post_id, 'material_type');
	}
}
 
add_action('save_post_material', 'Doula_Course\App\Func\save_material_type'); 

 */
/** MOVE TO DIFFERENT FILE.

 *  Title: material_loop
 *	
 *	Description: This function is called twice in each loop. This should be cleaned up and maybe even simplified into multiple functions. 
 * 	
 * 
 *
 *	Returns VOID
 */	 
/*  
function material_loop( &$obj ) {
    if( get_query_var('post_type') == 'material' ) {
		global $post, $current_user;
				
		// Start output buffering at the beginning of the loop and abort
		if ( 'loop_start' === current_filter() )			
			return ob_start(); //Stops here on first time (loop start) and returns the ob_start
		
		// At the end of the loop, we end the buffering and save into a var
		$loop_content = ob_get_clean();
		
		$active = current_user_can('student_current');
			
		if( !$active ){
			 wp_redirect( home_url()."/inactive-student-notice" ); 
			 exit;
			
		} else {
		
			if ( is_archive() || is_search() ) {
				if ( is_main_query() ){
					if( is_archive() )
						$obj->max_num_pages = 0; //Kill pagination on archives. 
				
					include( plugin_dir_path( __FILE__ ) . "../tmpl/course-overview.php" );
				}
			 } else { //if not archive or search result, probably single. 
				
				$type_num = intval( get_post_meta($post->ID, 'material_type', true) );
				$type = 'content';
				
				switch($type_num){
					case 5: 
					case 4: 	
					case 2: 
						$type = 'section';
						break;
					case 1: 
						$type = 'assignment';
						break;
					case 0:
					default: 
						$type = 'content';
						break;
				}
		
				 
				if( is_main_query() ){
					include( plugin_dir_path( __FILE__ ) . "../tmpl/course-functions.php" );
					include( plugin_dir_path( __FILE__ ) . "../tmpl/course-$type.php" );
				}
				
			}// endif;
		}
    }	
}

add_action( 'loop_start', 'Doula_Course\App\Func\material_loop' );
add_action( 'loop_end', 'Doula_Course\App\Func\material_loop' );
 
 */


/**
 *  Title: cpt_shortlinks
 *	
 *	Description: Allow shortlinks to be retrieved for pages and custom post types
 * 	
 * 	ex: ?p=11234 which is the ID for the post type in the URL query parameter. 
 *
 *	Returns STRING
 */	
 
if( !function_exists( 'cpt_shortlinks' ) ) {

	function cpt_shortlinks( $shortlink, $id, $context, $allow_slugs=true ) {
		
		 //If query is the context, we probably shouldn't do anything
		if( 'query' == $context )
			return $shortlink;
		
		// If this is a standard post, return the shortlink that was already built
		$post = get_post( $id );
		if( 'post' == $post->post_type )
			return $shortlink;

		// Retrieve the array of publicly_queryable, non-built-in post types
		$post_types = get_post_types( array( 'public'   => true, '_builtin' => false ) );
		if( in_array( $post->post_type, $post_types ) || 'page' == $post->post_type )
			$shortlink = home_url('?p=' . $post->ID);

		return $shortlink;
	}
}
add_filter( 'get_shortlink', 'Doula_Course\App\Func\cpt_shortlinks', 10, 4 );
 



/**
 *  Title: asmt_comments
 *	
 *	Description: Messaging actions on comments submitted for assignments
 * 	
 *	Probably should NOT be in this file.
 *
 *	Returns 
 */	
 

function asmt_comments( $comment_id, $comment_obj ){	
	
	if( strcmp( get_post_type( $comment_obj->comment_post_ID ), 'assignment' ) == 0 ){
	
		$msg = new Message();
		
		$msg->comment_notify( $comment_obj );
		
	}
}

add_action( 'wp_insert_comment', 'Doula_Course\App\Func\asmt_comments' ,10 ,2 );




/**
 * 	get_available_tracks
 *
 *	Loads all available tracks from the database. 
 *
 *	returns array
 **/
 
 /* 
function get_available_tracks(  ): ARRAY
{
	global $post; 
	
	$posts = get_posts([
		'post_type' => 'track',
		'post_status' => 'publish',
		'numberposts' => -1  //loads all. 
	]);
	
	$available = [];
	foreach( $posts as $track )
		$available[ $track->ID ] = $track->post_title; 
		
	return $available;	
}
 */
 
?>