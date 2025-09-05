<?php

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Grades\Grades;

if ( !defined( 'ABSPATH' ) ) { exit; }


/**
 *	This file contains functions and action hooks that control the the use of metaboxes 
 *	within assignment and material post types. 
 *
 * 	NOTE: the use of the Assignment class!
 *
 **/

 
/**
 * admin_meta_boxes
 *
 * sets up the addition and removal of metaboxes used in the assignments and materials CPTs. 
 *
 * return void
 */

function admin_meta_boxes(){
	global $post;

	//For our Assignments CPT editor. 
	if( strcmp( get_post_type( $post ), 'assignment' ) == 0 ) {
		
		//Remove default metaboxes 
		remove_meta_box('submitdiv', 'assignment', 'side');
		remove_meta_box('pageparentdiv', 'assignment', 'side');
		remove_meta_box('commentstatusdiv', 'assignment', 'normal');
		remove_meta_box('postcustom', 'assignment', 'normal');
		
		//Remove third-party metaboxes
		remove_meta_box('_kad_classic_meta_control', 'assignment', 'side');
		
		
		//Add additional metaboxes
		add_meta_box( 'asmt-status-div', __( 'Assignment Status' ), 'Doula_Course\App\Func\asmt_status_callback' , 'assignment', 'side', 'high' );
		add_meta_box( 'asmt-rubric-div', __( 'Rubric' ), 'Doula_Course\App\Func\asmt_rubric_callback' , 'assignment', 'side', 'default' );
		add_meta_box( 'asmt-student-div', __( 'Student Details' ), 'Doula_Course\App\Func\asmt_student_callback' , 'assignment', 'side', 'default' );
		add_meta_box( 'asmt-atch-div', __( 'Attachments' ), 'Doula_Course\App\Func\asmt_atch_callback' , 'assignment', 'side', 'low' );
		add_meta_box( 'asmt-content-div', __( 'Assessment' ), 'Doula_Course\App\Func\asmt_content_callback' , 'assignment', 'normal', 'low' );
			
	}
	
	//Adding Assignment Rubric MetaBox to Topic CPT. 
	if( strcmp( get_post_type( $post ), 'sfwd-topic' ) == 0 ) {
		
		
		//on = _sfwd-topic:sfwd-topic_lesson_assignment_upload meta data. 
		 $topic_meta = get_post_meta( $post->ID, '_sfwd-topic', true );  
		
		
		if( 
			!empty( $topic_meta[ 'sfwd-topic_lesson_assignment_upload' ] ) 
			&& strcmp( $topic_meta[ 'sfwd-topic_lesson_assignment_upload' ], 'on' ) == 0 
		){ 
			add_meta_box( 
				'assessment-rubric-div',
				__( 'Assignment Rubric' ), 
				'Doula_Course\App\Func\assessment_rubric_callback', 
				'sfwd-topic', 
				'normal', 
				'default' 
			); 
		}
		
	}
	
	
/* 	//Add additional metabox to the Track CPT editor
	add_meta_box( 'track-courses-div', __( 'Track Courses Editor' ), 'Doula_Course\App\Func\track_courses_callback' , 'track', 'side', 'default' ); */

	/* //Add metaboxes for the Material CPT editor 
	if( strcmp( get_post_type( $post ), 'material' ) == 0 ) {
		
		//1 = assignment material_type meta data. 
		if( intval( get_post_meta( $post->ID, 'material_type', true ) ) == 1 ) { 
			
			add_meta_box( 
				'assessment-rubric-div',
				__( 'Assignment Rubric' ), 
				'Doula_Course\App\Func\material_rubric_callback', 
				'material', 
				'normal', 
				'default' 
			); 
		}
	} */

}

if( is_admin() )
	add_action( 'add_meta_boxes', 'Doula_Course\App\Func\admin_meta_boxes' );


/**
 * asmt_status_callback
 *
 * The callback function that generates the HTML for the Assignment Status metabox. 
 *
 * return VOID
 */
 
function asmt_status_callback( $post ){
	
	set_meta_nonce( 'asmt_meta_box' );
	
	$instr_status = get_post_meta( $post->ID, 'instructor_status', true );
	
	$status_array = array( 'draft', 'submitted', 'incomplete', 'resubmitted', 'completed' );
	
	$instr_stat_array = array(
		0 => 'Not seen',
		1 => 'Seen, not graded',
		2 => 'Graded'
	);
	
	echo "<p>";
	_e( 'The assignment is', NBCS_TD );
	
	echo '<br><select id="asmt_post_status" name="asmt_post_status" >';
	
	foreach( $status_array as $psa_key ){
		
		echo '<option value="'. $psa_key .'"';
		echo ( strcmp( $psa_key, $post->post_status ) == 0 )? 'selected' : '';
		echo '>'.ucfirst($psa_key).'</option>';	
		
	}	
	
	echo '</select></p><p>';
	
	
	_e( 'and the instructor has', NBCS_TD );
	
	echo '<br><select id="asmt_instr_status" name="asmt_instr_status" >';
	
	foreach($instr_stat_array as $isa_key => $isa_val){
		echo '<option value="'.$isa_key.'"';
		echo ( strcmp( intval( $isa_key ), intval( $instr_status ) ) == 0 )? 'selected' : '' ;
		echo '>'.$isa_val.'</option>';
	}	
	
	echo '</select></p>';
	
	echo '
	<div id="major-publishing-actions">
		<div id="publishing-action">
			<span class="spinner"></span>
			<input type="submit" value="Save" accesskey="p" id="publish" class="button button-primary button-large" name="save">				
		</div>
		<div class="clear"></div>
	</div>';		
	
}


/**
 * asmt_rubric_callback
 *
 * The callback function that generates the HTML for the Assignment Rubric metabox. 
 *
 * return VOID
 */

function asmt_rubric_callback( $post ){
	
	//Get ID of material assignment associated with submitted assigment.
	$assessment_id = $post->post_parent;
	$rubric = get_post_meta( $assessment_id, 'assessment_rubric', true );

	echo "{$rubric} <hr><p><a href='/wp-admin/post.php?post={$assessment_id}&action=edit#assessment-rubric-div' target='_blank'>Edit rubric</a></p>";

}


/**
 * asmt_atch_callback
 *
 * The callback function that generates the HTML for the Assignment Attachements metabox. 
 *
 * return VOID
 */

function asmt_atch_callback( $post ){

	//call the attachments object
	$attachments = get_children( [
		'post_parent' => $post->ID,
		'post_type'   => 'attachment'
	], OBJECT );
	
	
	if( !empty( $attachments ) ){
		
		echo "<ul>";
			foreach( $attachments as $atch )
				echo "<li><a href='".wp_get_attachment_url( $atch->ID )."' target='_blank'>{$atch->post_title}</a></li>";
		echo "</ul>";
		
	} else {
		
		echo "<p>No attachments are linked to this assignment.</p>";
		
	}
}


/**
 * asmt_content_callback
 *
 * The callback function that generates the HTML for the actual Course Assignment metabox. 
 *
 * return VOID
 */

function asmt_content_callback( $post ){
	
	//Get ID of the course material tagged as an assignment, which is associated with student-submitted assigment.
	$assessment_id = $post->post_parent;
	$asmt_content = get_post( $assessment_id );
	
	echo '<h1>'. $asmt_content->post_title .'</h1>'. $asmt_content->post_content;
	echo "<hr><p><a href='/wp-admin/post.php?post={$assessment_id}&action=edit' target='_blank'>Edit assessment contents</a></p>";

}


/**
 * asmt_student_callback
 *
 * The callback function that generates the HTML for the actual Course Assignment metabox. 
 *
 * return VOID
 */

function asmt_student_callback( $post ){
	
	//Get ID of student who submitted assigment.
	$student_id = $post->post_author;
	$student = get_user_by( 'id', $student_id );
	
	$grades = new Grades(); 
	$grades->build( $student_id ); 
	
	$course_id = $grades->get_course_id_from_topic_id( $post->post_parent ); 
	
	
	//Customer info: 
	$customer = rcp_get_customer_by_user_id( $student_id ); 
 
	//build student progress summary string: ex. 45% (20/45) 
	/*$totalAsmt = ( !empty( $prg_arr['totalAsmt']) )? $prg_arr['totalAsmt'] : 0 ;
	$completedAsmts = ( !empty( $prg_arr['completedAsmt']) )? $prg_arr['completedAsmt'] : 0 ;
	$asmt_progress = $percentComplete."% (".$completedAsmts."/".$totalAsmt.")"; */
	if( method_exists( $customer, 'get_id' ) )
		$customer_id = $customer->get_id(); 
		
	$active  = in_array( 'student', $student->roles );
	
	if( !$active && isset( $customer_id ) )
		echo "<div class='error'><p><strong>This student's account is marked as inactive! Go to student's <a href='/wp-admin/admin.php?page=rcp-customers&customer_id={$customer_id}&view=edit' target='_blank'>Customer Details</a> page for more information.</strong></p></div>";
	 
	echo "<strong>Display Name:</strong> {$student->display_name}<br>
		  <strong>Full Name:</strong> {$student->first_name} {$student->last_name}<br>
		  <strong>Location:</strong> {$student->student_city}, {$student->student_state}, {$student->student_country}<br><br>
		  
		  <strong>Account Status:</strong> <strong><span style='color:";
		  echo ( $active )? "green'>".__( 'ACTIVE', NBCS_TD ) : "red'>".__( 'INACTIVE', NBCS_TD );
		  echo"</span></strong><br>
		  <strong>Start Date:</strong> {$student->user_registered}<br><br>";
		  //<strong>Assignments Progress:</strong> {$asmt_progress}<br>
		  echo"<strong>Course Progress:</strong>";
		  echo do_shortcode('[learndash_course_progress user_id="'. $student_id .'" course_id="'. $course_id .'" ]');
			
	echo "  <hr>
		  Go to <a href='/wp-admin/admin.php?page=edit_student&student_id={$student_id}' target='_blank'>Student Profile</a> | <a href='/wp-admin/admin.php?page=rcp-customers&customer_id={$customer_id}&view=edit' target='_blank'>Subscriptions</a><br>";	
		  
}



/**
 * track_courses_callback
 *
 * The callback function that allows for courses to be assigned to their respective tracks. 
 *
 * return VOID
 */

function track_courses_callback( $post ){
	
	//Set the number of select dropdown boxes to display. 
	$num_dropdowns = ( isset( $_GET[ 'course_dropdowns' ] ) )? $_GET[ 'course_dropdowns' ] : 3;
		
	//Load 'track_courses' metadata. 
	$courses = get_post_meta( $post->ID, 'track_courses', true );
	
	//update dropdown count if there are already courses set. 
	if( !empty( $courses ) ){	
		$courses_set = count( $courses );
		$num_dropdowns = ( $num_dropdowns < $courses_set )? $courses_set : $num_dropdowns;
	}else{
		$courses = []; 
	}
	

	//Load all available courses: 		
	$avail_posts = get_posts( [
			'post_type' => 'material',
			'meta_key' => 'material_type',
			'meta_value' => '4', //course manual
			'meta_compare' => '='
	]);
	
	$avail_courses = [];
	foreach( $avail_posts as $manual )
		$avail_courses[ $manual->ID ] = $manual->post_title;
		
	//print_pre( $avail_courses, "Available Manuals" );
		
	
	
	
	echo __('Please assign courses that are a part of this learning track.'); 
	
	echo build_courses_dropdown( $courses, $avail_courses, $num_dropdowns );
	
}
	
	
	

/**
 * build_courses_dropdown
 *
 * Build the dropdown metabox for Course Tracks. 
 *
 * return VOID
 */

	 
function build_courses_dropdown( array $courses, $available, $num_dropdowns = 3 ) {	
	
	$output = '';

	for($i=0; $i<$num_dropdowns; $i++ ){
		
		$course_id = ( isset( $courses[ $i ] ) )? $courses[ $i ] : NULL; //Course ID. 
			
		$output .= '<p><select name="course_tracks_'. $i .'" id="course_tracks_'. $i .'">';
			
		//If there is no course ID then the default message should be displayed. 
		$output .= ( empty($course_id) )? 
			'<option disabled selected> -- course not set -- </option>' : 
			''; 
			
		foreach( $available as $avail_id => $avail_title  ){
			$output .= '<option value="'.$avail_id.'"';
			$output .= ( strcmp( $course_id, $avail_id ) == 0 )? ' selected' : '';
			$output .= '>'. $avail_title .'</option>'; 			
		}
		
		$output .= '</select><span class="result"></span></p>';	
	}
	
	//Add an additional course_dropdown box
	$add_hyperlink 	= '&course_dropdowns='.( $num_dropdowns + 1 );	
	$current_page 	= get_current_admin_url();
	
	$output .= '<p><a href="'. $current_page.$add_hyperlink .'">'.__( 'Add Course' ).'</a></p>';
	
	return $output;
}		
	
	
/**
 * Get the base URL of the current admin page, with query params.
 *
 * @return string
 */

 function get_current_admin_url(): string 
 {

    return admin_url(sprintf(basename($_SERVER['REQUEST_URI'])));
	
 }



	

/**
 * asmt_save_meta_box_data
 *
 * When the assignemnt post type is saved, saves the additional data stored in metabox fields.
 *
 * return VOID
 */

	 
function save_asmt_meta_box_data( $post_id ) {			
	global $post;

	if( !security_checks( 'asmt_meta_box' ) ) return;
	
	// Make sure that it is set.
	if ( ! isset( $_POST['asmt_instr_status'] ) || ! isset( $_POST['asmt_post_status'] ) ) return;
	
	$update = false; 
	
	//update instructor status on the assignment. 
	$instr_status = get_post_meta( $post->ID, 'instructor_status', true );
	$new_instr_status =  $_POST['asmt_instr_status'];
	
	if( strcmp( $instr_status, $new_instr_status ) != 0 ) {
		
		update_post_meta( $post_id, 'instructor_status', $new_instr_status, $instr_status );
		
		$update = true; 
	}

	//Check and update post status
	$p_status = $post->post_status;
	$new_p_status = $_POST['asmt_post_status'];
		
	if( strcmp( $p_status, $new_p_status ) !== 0 ){ 
		
		remove_action('save_post', 'Doula_Course\App\Func\save_asmt_meta_box_data'); // prevent loop infinitely
		
		$p_data = [
			'ID' => $post_id,
			'post_status' => $new_p_status 
		];
		
		$update_id = wp_update_post( $p_data ); // Then update post
			
		add_action('save_post', 'Doula_Course\App\Func\save_asmt_meta_box_data'); // re-hook this function
		
		$update = true; 
	}	
		
	if( $update ){ 
		
		$update_student_meta = false; 
		$grades = new Grades();
		$grades->build( $post->post_author ); 

		//If assignment doesn't exist, add it. 
		// if( ! $grades->assignment_exists( $post_id ) ){
		// 	$args = [
		// 		'post_parent' => $post->post_parent,
		// 		'post_status' => $new_p_status,
		// 		'post_meta' => [ 'instr_status'=> $new_instr_status ]
		// 	];

		// 	$grades->add_grade( $args,  $post_id ); 	

		// }
		
		//If the grade already exists (which it should at this point), set the grade variable. 
		$grade = $grades->get_grade_by_id( $post->post_parent); 		
		
		//If we are successful at updating the student meta, then let's update the student's complete file. 
		if( $grade->update_student_meta() )	
			$update_student_meta = $grades->update_student(); 
	
		//I don't know that this works. 
		if( !$update_student_meta ){

			$subject = 'Failed to update the student_grade metadata.';
			$message = "This message originated from ".__METHOD__.", ".__FILE__.": Line ".__LINE__.". \r\n The asmt key is: 'asmt_key' => {$post->post_parent}, 'asmt_status' => $new_p_status \r\n The Student Grades Metadata failed to update. Please investigate the issue.";
			
			/* $msg = new Message();
			$msg->admin_notice( $subject, $message ); */
		}  		

		//If draft, we're done here. 
		if( strcmp( $new_p_status, 'draft' ) == 0  ) return; 

	    //These check prevents the hook at the end from firing twice
		if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;  
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return;
		
		//Should only fire once!
		do_action( "nb_assignment_{$new_p_status}", $post_id, $post );
	}		
}

add_action( 'save_post', 'Doula_Course\App\Func\save_asmt_meta_box_data' );


/**
 * material_type_select
 *
 * The drop down box that is added to the main "Publish" metabox that determines 
 * the type of material being published for Material CPTs. 
 *
 * return VOID
 */

function material_type_select() {
	global $post;
	if( get_post_type( $post ) == 'material' ) {
		
		echo '<div class="misc-pub-section misc-pub-section-last" style="border-top: 1px solid #eee;">';
		set_meta_nonce( 'material_type' );
		
		$m_type = get_post_meta( $post->ID, 'material_type', true );
		$m_type_arr = array(
			0=>'content',
			1=>'assignment',
			2=>'section',
			3=>'other',
			4=>'manual'
		);
		
		echo 'Material Type: <select id="material_type" name="material_type" >';
		
		foreach($m_type_arr as $mta_key => $mta_val){
			echo '<option value="'.$mta_key.'"';
			echo ( strcmp( $mta_key, $m_type ) == 0 )? ' selected ' : '';
			echo '>'.ucfirst($mta_val).'</option>';
		}	
		
		echo '</select>';			
		echo '</div>';
	}
}

add_action( 'post_submitbox_misc_actions', 'Doula_Course\App\Func\material_type_select' );


/**
 * save_material_type_select
 *
 * Saves the "material type" drop down box option for the Material CPTs. 
 *
 * return VOID
 */

function save_material_type_select( $post_id ) {
	
	if( !security_checks( 'material_type' ) ) return;
	
	if ( isset( $_POST[ 'material_type' ] ) ){
		
		update_post_meta( 
			$post_id, 
			'material_type', 
			$_POST[ 'material_type' ], 
			get_post_meta( $post_id, 'material_type', true ) 
		);
		
	}
}

add_action( 'save_post', 'Doula_Course\App\Func\save_material_type_select' );



/**
 * material rubric callback
 *
 * This setups the Rubric Metabox for Materials (specifically the "assignment" material_type's) 
 *
 * return VOID
 */

function assessment_rubric_callback( $post ){
	
	set_meta_nonce( 'assessment_rubric' );
			
	$assessment_rubric = get_post_meta( $post->ID, 'assessment_rubric', true );

	$rubric_settings = array( 
		'teeny' => true, 
		'media_buttons' => false,
		'tinymce' => true,
		'quicktags' => true, 
		'default_editor' => 'html'
		
	);
	
	wp_editor( $assessment_rubric, 'assessment-rubric-editor', $rubric_settings );
	
}


/**
 * save_assessment_rubric_callback
 *
 * Saves the Material Rubric
 *
 * return VOID
 */


function save_assessment_rubric_callback( $post_id ) {			
	global $post;

	if( !security_checks( 'assessment_rubric' ) ) return;
	
	if ( ! isset( $_POST['assessment-rubric-editor'] ) )  return;
	
	update_post_meta( 
		$post_id, 
		'assessment_rubric', 
		$_POST['assessment-rubric-editor'], 
		get_post_meta( $post_id, 'assessment_rubric', true ) 
	);

}

add_action( 'save_post', 'Doula_Course\App\Func\save_assessment_rubric_callback' );


/**
 * set_meta_nonce
 *
 * Sets Nonce for Admin Metaboxes.
 *
 * return VOID
 */

function set_meta_nonce( STRING $val ): VOID
{
	wp_nonce_field( $val, $val.'_nonce' );
	
} 
 
 

/**
 * security_checks
 *
 * Runs through security checks before saving data. 
 *
 * return BOOLEAN
 */

function security_checks( STRING $val ): bool
{
	// Check if our nonce is set.
	if ( ! isset( $_POST[ $val.'_nonce'] ) )
		return false;

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST[ $val.'_nonce'], $val ) )
		return false;

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return false;
	
	// Check the user's permissions.
if ( ! current_user_can( 'edit_post', $_POST[ 'ID' ] ) )
		return false;
	
	return true;
} 
 
 
/**
 * load_scripts
 *
 * Load javascript files for AJAX calls.
 *
 * return VOID
 */

function load_track_courses_script( $hook ): VOID
{
	
	//die if not admin.
	if( strcmp( get_post_type(), 'track' ) !== 0 )
		return;
	
	wp_enqueue_script( 
		'track-courses-script', 
		plugins_url( '/js/track_courses_script.js', __FILE__ ), 
		array( 'jquery' ) 
	);
	
	wp_localize_script( 
		'track-courses-script', 
		'ajax_object',
        array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ), 
			'nonce'  => wp_create_nonce( 'track-courses-nonce' )
		) 
	);
	
} 
  
//Adding AJAX functionality to track metaboxes. 
add_action( 'admin_enqueue_scripts', 'Doula_Course\App\Func\load_track_courses_script' );
 
 
/**
 * do_track_courses_action
 *
 * PHP response to the AJAX script for track_courses metabox
 *
 * return VOID
 */

function do_track_courses_action( ): VOID
{
	if ( ! check_ajax_referer( 'track-courses-nonce', 'nonce', false ) ) {

		wp_send_json_error( 'Invalid security token sent.' );
		wp_die();
	}
	
	//print_pre( $_POST , ' POST:' );
	
	//assigning values from passed post data. 
	$track_id = intval( $_POST [ 'track_id' ] ); 
	$update = [
		intval( $_POST[ 'course_position' ] ) => intval( $_POST[ 'course_id' ] )
	];
	
	//print_pre( $update, 'THE UPDATE ARRAY built from post data, LINE '.__LINE__ );
	
	//retreive current metadata, if any. 
	$tc_meta = get_post_meta( $track_id, 'track_courses', true );
	
	//update track_courses array with passed metadata. 
	if( !empty( $tc_meta ) )
		$update = array_replace( $tc_meta, $update );	
	
	//print_pre( $update, 'THE UPDATE ARRAY after array_replace, LINE '.__LINE__ );		
	
	//update the metadata for the track in the database. 
	$result = update_post_meta( $track_id, 'track_courses', $update, $tc_meta  );
	
	//print_pre( $result, 'THE UPDATE_POST_META RESULT, LINE '.__LINE__ );
	echo ( $result )? "The database was updated!" : "The database failed to update.";
	
	wp_die();
}

//adding PHP response function to AJAX script from track_courses metabox
add_action( 'wp_ajax_track_courses_action', 'Doula_Course\App\Func\do_track_courses_action' );

 

/**
 * Remove the "restrict this content" meta box from the "assignments" post type.
 * 
 * @param array $post_types List of post types the meta box should not be added to.
 * 
 * @return array
 */
function exclude_post_types_from_rcp( $post_types ) {
    // We do NOT want the meta box displayed on the "assignment" post type.
   array_push( $post_types, 'assignment', 'certificate', 'notification' );
    
    return $post_types;
}

add_filter( 'rcp_metabox_excluded_post_types', 'Doula_Course\App\Func\exclude_post_types_from_rcp' ); 
 
 
 
 

/**
 * Remove LD Course Grid Settings meta boxes from the "assignments" post type.
 * 
 * @param array $post_types List (associative array) of post types the meta box should be added to. Filtering out the "assignment" key
 * 
 * @return array
 */
function exclude_post_types_from_ld_course_grid( $post_types ) {
	$rm_post_types = [ 'assignment', 'certificate', 'notification' ];

    foreach( $rm_post_types as $type)
	{
		unset( $post_types[ $type ] );
	}
   
    return $post_types;
}
 
add_filter( 'learndash_course_grid_post_types', 'Doula_Course\App\Func\exclude_post_types_from_ld_course_grid' );  



/**
 * Remove Kadence theme Post Settings meta boxes from the "assignments" post type.
 * 
 * @param array $post_types numeric array of post types the meta box should be added to. Filtering out the "assignment" value
 * 
 * @return array
 */
function exclude_post_types_from_kadence( $post_types ) {
    
	$rm_post_types = [ 'assignment', 'certificate', 'notification' ];

    foreach( $rm_post_types as $type)
	{
    	unset( $post_types[ array_search( $type, $post_types ) ] );
	}

    return $post_types;
}
 
add_filter( 'kadence_classic_meta_box_post_types', 'Doula_Course\App\Func\exclude_post_types_from_kadence' );  




function nb_record_completed_in_learndash( $new_status, $old_status, $post ){
	if ( $old_status == $new_status || $old_status != 'completed' && $new_status != 'completed' || $post->post_type != 'assignment' )
		return;
	
	$step_id = $post->post_parent;
	$student_id = $post->post_author;
	
	$grades = new Grades(); 
	$grades->build( $student_id );
		
	$course_id = $grades->get_course_id_from_topic_id( $step_id );
	$grade_status = $grades->get_grade_status( $step_id ); 
	

	//If step is marked as completed, but the new status is not completed. 
	if( learndash_user_progress_is_step_complete( $student_id, $course_id, $step_id ) && $new_status != 'completed'  )	
		$incomplete = learndash_process_mark_incomplete( $student_id, $course_id, $step_id);
	 elseif(  ! learndash_user_progress_is_step_complete( $student_id, $course_id, $step_id ) && $new_status == 'completed'  )
		$completed = learndash_process_mark_complete( $student_id, $step_id, false, $course_id ); 
	
	
}

add_action( 'transition_post_status', 'Doula_Course\App\Func\nb_record_completed_in_learndash', 10, 3  ); 

?>