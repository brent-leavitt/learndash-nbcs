<?php
namespace Doula_Course\App\Tmpl;

use Doula_Course\App\Clss\Assignment_Processor as Processor;
use Doula_Course\App\Clss\Assignment_Editor as Editor;
use Doula_Course\App\Clss\Message;


$message = new Message(); 
$processor = new Processor( $message ); 
  
if( isset( $_POST ) )
	$processor->process( $_POST ); 
	
print_pre( $message, "The Message Class called in the Assignment_Editor template file." ); 
	
//Add a messenger class as this point to process messages that were geneated by the Assignment Processor. 

	
	
	
//(These 3 action comments belongs in the Editor class)
// Check Student Meta to see if this assignment is not already completed. 

//post assignment: CPT loads the WYSIWIG Editor for the front end, 
//and commenting on Assignment CPT between student and professor for the assignment. 

//(21Jun22) These three properties need to be moved into the updated Assignment_Editor class. 
$empty_asmt = true;
$current_url = get_permalink();
$asmt_editable = true; 

/* $editor = new Assignment_Editor();

if( isset( $_POST ) ){
	
	$built = $editor->build( $_POST ); 
	//print_pre( $built, "Editor is built:" );
	
	if( $built ){
		$editor->save();
		print_pre( $editor->add_attachments, "Adding Attachment on line: ". __LINE__ .", Method: ".__METHOD__ );
		$editor->add_attachments();
	}
	
} */
	

 





//FIRST, process form requests. 
						
/* 	
$atch_msg = array();
$current_url = get_permalink();
$asmt_editable = true; 

$allowed_html = wp_kses_allowed_html( 'post' ); */




//REQUEST TO DELETE ATTACHMENT?
/* if( isset( $_GET['delete_atch'] )  ){
	$delete_atch_id = $_GET[ 'delete_atch' ];
	//Move this message to Attachment area. 
	
	$atch_msg[] = ( false === wp_delete_attachment( $delete_atch_id ) ) ? 
		"The file attachement failed to be deleted.":
		"The file attachment was successfully deleted.";
}	 */		

/* if( !empty( $_POST ) && ( 
	wp_nonce_field( 'edit_assignments', 'grape_vines' ) 
	|| wp_nonce_field( 'edit_assignments', 'tomato_vines' ) ) ){
	
	//if we get to this point something is being posted. 
	if( empty( $_POST['edit_assignment'] ) ){
		
		//The assignment form is empty, no assignment can be submitted. 
		$notices['empty'] = 'The assignment form is empty. Please complete your assignment and then save it as a dreft or submit it for grading.'; 
		
	} else { */
		
	/* 	$empty_asmt = false; 
		if( !empty( $_POST['student_id'] ) ){
		
			$sid = $_POST['student_id'];
			
			if( isset( $_POST['save_draft'] ) || isset( $_POST['submit_assignment'] ) ){
				
				//Do the same things if either of these buttons have been clicked.
				 
				
				$assignment = array(
					'post_type' => 'assignment',
					'post_author' => $sid
				); 
				
				//main insert post info.
				$asmt_meta = array(); //metadatea to be added. 
				
				//if assignment ID exists
				$assignment['ID'] = $asmt_id = ( $_POST['assignment_id'] )?: NULL;
			
			 
		
				//Post content
				$assignment['post_content'] = wp_kses( $_POST['edit_assignment'] , wp_kses_allowed_html( 'post' ) );
				
				$student_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta($current_user->ID) );
				
				$student_name = strtolower($student_meta['first_name']).ucfirst(strtolower($student_meta['last_name']));
				
				//Post name
				$assignment['post_name'] = $student_name.'_'.$post->post_name;
				
				//Post title
				$assignment['post_title'] = ucfirst($student_name).': '.$post->post_title; 
				
				//if course ID exists
				$assignment['post_parent'] = ( $_POST['post_id'] )?: NULL ;
				
			}
			
			//Set Post Status
			$asmt_status = get_post_status( $_POST['assignment_id'] );	
		
			if( isset( $_POST['save_draft'] ) ){ //Smaller to-do list for save draft. 
				
				//Post status  - if marked as incomplete and the "Draft" button is pressed then mark as still incomplete, else mark as draft. 
				
				$assignment['post_status'] = (  $asmt_status == 'incomplete' || $asmt_status == 'resubmitted' )?'incomplete':'draft';
				
			} elseif( isset( $_POST['submit_assignment'] ) ){ //This is to submit the assignment for grading
									
				//Post status - we need to determine if this is being submitted or resubmitted. 
								
				$assignment['post_status'] = ( $asmt_status == 'incomplete' ||  $asmt_status == 'resubmitted' )? 'resubmitted' : 'submitted';
				
				$instr_status = 0; //not seen.
				
			} 		
			
			
			//Insert or Update entry in the database. 
			if( isset( $assignment['ID'] ) ){
			
				$asmt_id = wp_update_post( $assignment ); 
				
			} else {
			
				$asmt_id = wp_insert_post( $assignment );
				
			} */
			
			//Update the Student's Grade Sheet
			
/*			$msg = new Message();
			
			if( isset( $asmt_id ) ){
				$grades = new Grades();	
				$grades->build( $sid ); 
				
				$status_array = array([
					'asmt_key' => $asmt_id,
					'asmt_status' => $assignment['post_status']
				]);
				
				$updated_asmts = $grades->update( $status_array );
				//should send admin notice if not successfully updated. 
				if( !updated_asmts ){
					$admin_sub = 'Failed to update the student_grade metadata from course-assignment template';
					$admin_msg = "This message originated from course-assignment.php template file in the doula-training plugin, line 149. \r\n The asmt key is: 'asmt_key' => $asmt_key, 'asmt_status' => $new_p_status \r\n The Student Grades Metadata failed to update. Please investigate the issue.";
					
					$msg->admin_notice( $admin_sub, $admin_msg );
				}
			}
			
			//Reset instructor status on newly submitted assignments. 
			if( isset( $instr_status ) ){
				$post_meta_result = add_post_meta($asmt_id, 'instructor_status', $instr_status, true);
				
				if( !$post_meta_result )
					update_post_meta($asmt_id, 'instructor_status', $instr_status);
				
				if( ( $instr_status === 0 ) && ( isset( $_POST['submit_assignment'] ) ) ) //Instructor hasn't seen this. Send a message. 
					$mess_sent = $msg->assignment_submitted( $asmt_id ); //returns true or false. 
					
			}
		}
	}
	

	
} */

/* if ( isset( $_POST['asmt_atchmnts_nonce'], $_POST['post_id'] ) && wp_verify_nonce( $_POST['asmt_atchmnts_nonce'], 'asmt_atchmnts' ) ) {
	//echo "<p>NONCES are being summoned to upload the file attachments!</p>";
	// The nonce was valid and the user has the capabilities, it is safe to continue.

	// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	$files = $_FILES[ "asmt_atchmnts" ];  
	$attachment_ids = array();	
	
	// Let WordPress handle the upload.
	// Set up to handle multiple uploads at once. 
	// Remember, 'asmt_atchmnts' is the name of our file input in our form above.
	
	foreach( $files[ 'name' ] as $key => $value ){            
		if( $files[ 'name' ][ $key ] ){ 
			$file = array( 
				'name' => $files[ 'name' ][ $key ],
				'type' => $files[ 'type' ][ $key ], 
				'tmp_name' => $files[ 'tmp_name' ][ $key ], 
				'error' => $files[ 'error' ][ $key ],
				'size' => $files[ 'size' ][ $key ]
			); 
			
			$_FILES = array( "asmt_atchmnts" => $file ); 
			foreach( $_FILES as $file => $array ) {              
				$attachment_ids[] = media_handle_upload( $file, $_POST['post_id'] ); 
			}
		} 
	} 
	
	foreach( $attachment_ids as $aid ){
		if( isset( $atch_msg ) )
			$atch_msg[] = ( is_wp_error( $aid ) )? "Failed to upload attachments. Try again!" : "Attachments were successfully uploaded! Hoorah!";
	}
	
}  */

//END form request processing scripts	


//Load the student assignment:


$asmt_args = array(
	'post_type' => 'assignment',
	'post_status' => array( 'draft', 'submitted', 'incomplete', 'resubmitted', 'completed' ),
	'author' => $current_user->ID,
	'post_parent' => $post->ID
);
	
$asmt_query = new \WP_Query( $asmt_args );
	

//Display Assignment Status Notices: 

	if( isset( $asmt_query->post->ID ) ){
		$asmt_id = $asmt_query->post->ID;
		$empty_asmt = false;
		
		$asmt_status = ( empty( $assignment['post_status'] ) )? get_post_status($asmt_id): $assignment['post_status'] ;
		$instr_status = ( !isset( $instr_status ) )? get_post_meta($asmt_id, 'instructor_status', true): NULL;
		
		//$status_array = array( $asmt_status, $instr_status );
		
		//print_pre( $status_array ); 
		
		switch( array( $asmt_status, $instr_status ) ){
			case array('draft', NULL ): //draft, not yet submitted
			case array('draft', 0 ): 
				$status_message = 'A draft version of this assignment has been saved, but has not yet been submitted to the instructor.';
				$status_class = '';
				break;
			case array('submitted', 0 ): //submitted and not seen
				$status_message = 'Your assignment has been saved and submitted for grading by the instructor, but the instructor has not yet seen or graded it. <em>If you wish to continue working on your assignment before submitting it for review, please click on the "save draft" button instead.</em>'; 
				$asmt_editable = false; 
				if( isset( $mess_sent ) && ( $mess_sent == true ) )
						$status_message .= "<br><br>A receipt of your assignment has been sent to your email account on file. Please retain for your personal records.";
				$status_class = 'pending';
				break;
			case array('submitted', 1): //submitted and seen by the instructor, but not graded
				$status_message = 'This assignment has been submitted and seen by the instructor, but is still pending review and grading.';
				$status_class = 'pending'; 
				$asmt_editable = false; 
				break;
			case array('incomplete',  0):  //unseen but already marked as incomplete
				$status_message = 'A copy of your revised assignment has been saved, but not resubmitted to the instructor. Please continue working on your assignment and resubmit your revised assignment to be graded when ready.';
				$status_class = 'alert';
				break;
			case array('incomplete',  2):  //graded and marked as incomplete
				$status_message = 'This assignment has been graded and marked as incomplete by the instructor. Your attention is needed. Please see comments below for feedback from the instructor.';
				$status_class = 'alert';
				break;
			case array('resubmitted', 0): //resubmitted and not seen
				$status_message = 'This assignment has been re-submitted to the instructor, but the instuctor has not yet seen the latest revisions made to the assignment. <em>If you would like to continue working on the assignment before submitting it for review, please click on the "save draft" button instead.</em>';
				if( isset( $mess_sent ) && ( $mess_sent == true ) )
						$status_message .= "<br><br>A receipt of your assignment has been sent to your email account on file. Please retain for your personal records.";
				$status_class = 'pending';
				$asmt_editable = false; 
				break;
			case array('resubmitted', 1): //resubmitted and seen by the instructor, but not graded
				$status_message = 'This assignment has been resubmitted and seen by the instructor, but it has not received additional review and grading.';
				$status_class = 'pending';
				$asmt_editable = false; 
				break;
			case array('completed', 2 ): //graded and marked as complete
				$status_message = 'This assignment has been submitted and marked as complete by the instructor!';
				$status_class = 'cleared';
				$asmt_editable = false; 
				break;
			default:
				$status_message = 'You have not begun work on this assignment.';
				$status_class = '';
				break;
		}
	}
					



	if( $empty_asmt )
		$notices['instr'] = '<strong>Instructions:</strong> Use the assignment editor below to compose and submit your assignment. To save your assignment before you are ready to submit it, click "Save Draft." '; 
		
	if( isset( $notices ) ){
		echo "<div class='asmt_notices'>";
		foreach($notices as $nkey => $notice)
			echo "<p class='$nkey'>$notice</p>";
		
		echo "</div>";			
	}
	if( isset( $status_class ) && isset( $asmt_status ) && isset( $status_message ) ){
		echo "
		<div class='asmt_status_box {$status_class}'>
			<h4>Assignment Status: <em>{$asmt_status}</em></h4>
			<p class='asmt_detail_status'>{$status_message}</p>
		</div><!-- end .ASMT_STATUS_BOX -->
		";
	}
	
	if( $asmt_query->have_posts()){ 
	
		$content = $asmt_query->post->post_content;
		
	} else {
	
		$content = NULL;
	}?>
				
	
	
	<form action="<?php echo $current_url; ?>#asmt-editor" method="POST">
	
		<?php wp_nonce_field('save_submission','save_submission_nonce');?>
		<input type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
		<input type="hidden" name="student_id" value="<?php echo $current_user->ID; ?>" />
		<input type="hidden" name="post_title" value="<?php echo $post->post_title; ?>" />
		<input type="hidden" name="post_name" value="<?php echo $post->post_name; ?>" />
	
	<?php
	//Set the Assignment ID only if it is already set, or that is if it already exists. 
	
	if( isset( $asmt_id ) ){
		echo '<input type="hidden" name="assignment_id" value="'.$asmt_id.'" />';
	}
	$editor_id = 'edit_assignment';
	
	if( $asmt_editable ){
	
		$settings = array( 'media_buttons' => true , );
		wp_editor( $content, $editor_id, $settings );
	
		
		echo'
			<input type="submit" id="save_draft" name="save_draft" value="Save Draft" />
			<input type="submit" id="submit_assignment" class="button-primary" name="submit_assignment" value="Submit" />
		';
		
	} else {
		add_filter( 'tiny_mce_before_init', function( $args ) {

			if ( 1 == 1 )
				 $args['readonly'] = 1;
			return $args;
			
		} );
		
		$settings = array( 
			'media_buttons' => false , 
			
			);
		//$editor_id = 'view_assignment';
		//echo "Not editable!";
		//wp_editor( $content, $editor_id, $settings );
		
		echo "<textarea name='". $editor_id ."' id='". $editor_id ."' readonly='readonly' >". $content ."</textarea>";
		
		
	
		if( isset( $asmt_status ) && ( $asmt_status == 'completed' ) ){
			echo "<em>This assignment is marked as completed. No further changes can be made.</em>";
		} else {
			echo "<em>This assignment is pending review and cannot be edited. To continue editing, revert to \"draft\" status.</em>";
			wp_nonce_field('revert_to_draft','revert_to_draft_nonce');
			echo '<input type="submit" id="save_draft" name="save_draft" value="Revert to Draft" />';
		}
		
		
	}
	
	//If an assignment has already been created close this form. 
	echo ( !empty( $asmt_id ) )? '</form>': ''; 
			
	echo "<hr>";
	
	
	
//Add Attachment Functionality:
//Check if assignments are attached? An Assignment ID must be created first. 
if( isset( $atch_msg ) && !empty( $atch_msg ) ) {
	echo "<div class='asmt_status_box'><ul>";
	foreach( $atch_msg as $a_msg )
		echo "<li><em>".$a_msg."</em></li>";
	echo "</ul></div>";
}
	

	
if( empty( $asmt_id ) ){
	
	?>An assignment should first be created before you can include attachments. Begin working on your assignment and click "Save Draft", or click below to add attachments without a written assignment.</p> 
	
		<input type="hidden" name="attachment_only" value="[No written assignment; see attachments]" />				
		<input type="submit" id="save_attachment_only" name="save_attachment_only" value="Add Attachments Only" />
	</form>
	<?php
}else{
	
	//Assignment ID is set: Add or edit attachments. 
	//Are there attachments associated with this assignment.
	$attach_args = array(
		'post_parent' => $asmt_id,
		'post_type'   => 'attachment'
	);
	$attachments = get_children( $attach_args, OBJECT );
	
	if( $asmt_editable ):
	?>
		
	<div class="ld-table-list ld-assignment-list">
		<div class="ld-table-list-header ld-primary-background">
			<div class="ld-table-list-title">
				<span class="ld-item-icon">
					<span class="ld-icon ld-icon-assignment"></span>
				</span>
				<?php esc_html_e( 'Attachments', 'learndash' );  ?>	
			</div>
			<div class="ld-table-list-columns">
				<div class="ld-table-list-column ld-assignment-column-approved">
						0/0 Approved					
				</div>
			</div>
		</div> <!--/.ld-table-list-header-->
		
		<div class="ld-table-list-items">

		<?php	
		/** This action is documented in themes/ld30/templates/assignment/listing.php */
		
		/*** START NEW CODE ***/
			if ( ! empty( $attachments ) ) :

				$delete_attach_nonce = wp_create_nonce( 'delete_attachment' ); 
				
				echo "<p>The following attachments are linked to this assignment:</p>";
				
				foreach( $attachments as $atch ):
				
					
					echo "
					<div class='ld-table-list-item'>
						<div class='ld-table-list-item-preview'>
							<div class='ld-table-list-title'>";
						
							if( $asmt_editable ):
							?>
										
								<form id="delete_attachment" method="post" action="<?php echo $current_url; ?>#attachments">
									<input type="hidden" name="atch_id" id="atch_id" value="<?php echo $atch->ID; ?>" />
									<input type="hidden" name="delete_attachment_nonce" id="delete_attachment_nonce" value="<?php echo $delete_attach_nonce; ?>" />
									<input class="ld-icon ld-icon-delete" aria-label="<?php esc_html_e( 'Delete Attachment', 'learndash' ); ?>" id="delete_attachment" name="delete_attachment" type="submit" value="REMOVE X" />
								</form>
							
							<?php
							endif;
							
								echo "<a href='".wp_get_attachment_url( $atch->ID )."' target='_blank'>
								<span class='ld-item-icon'>
									<span class='ld-icon ld-icon-download' aria-label='". esc_html_e( 'Download Assignment', 'learndash' ). "'></span>
								</span>
								{$atch->post_title}</a>"; 
							
					
					
					echo "
							</div><!-- .ld-table-list-title -->
						</div><!-- .ld-table-list-item-preview -->
					</div><!-- .ld-table-list-item -->
					";
				endforeach;
				
				if( $asmt_editable ):
					echo '<p>To upload additional attachments, click the "Browse" button below.</p>'; 
				endif;
				
			else :

				esc_html_x( 'No attachments are linked to this assignment.', 'No assignments message', 'learndash' );

			endif;


	
/**
 * Identify the max upload file size. Compares the server enviornment limit to what's configured through LD
 *
 * @var $php_max_upload (int)
 */
$php_max_upload = ini_get( 'upload_max_filesize' );

if ( isset( $post_settings['assignment_upload_limit_size'] ) && ! empty( $post_settings['assignment_upload_limit_size'] ) ) {
	if ( learndash_return_bytes_from_shorthand( $post_settings['assignment_upload_limit_size'] ) < learndash_return_bytes_from_shorthand( $php_max_upload ) ) {
		$php_max_upload = $post_settings['assignment_upload_limit_size'];
	}
}

/**
 * Set the upload message based on upload size limit and limit of approved file extensions
 *
 * @var $upload_message (string)
 */

$upload_message = sprintf(
	// translators: placeholder: PHP file upload size.
	esc_html_x( 'Maximum upload file size: %s', 'placeholder: PHP file upload size', 'learndash' ),
	$php_max_upload
);

if ( isset( $post_settings['assignment_upload_limit_extensions'] ) && ! empty( $post_settings['assignment_upload_limit_extensions'] ) ) {
	$limit_file_exts = learndash_validate_extensions( $post_settings['assignment_upload_limit_extensions'] );
	if ( ! empty( $limit_file_exts ) ) {
		$upload_message .= ' ' . sprintf(
			// translators: placeholder: Comma separated list of file extentions.
			esc_html_x( 'Allowed file types: %s', 'placeholder: Comma separated list of file extentions', 'learndash' ),
			implode( ', ', $limit_file_exts )
		);
	}
}

/**
 * Check to see if the user has uploaded the maximium number of assignments
 *
 * @var null
 */

if ( isset( $post_settings['assignment_upload_limit_count'] ) ) {
	$assignment_upload_limit_count = intval( $post_settings['assignment_upload_limit_count'] );
	if ( $assignment_upload_limit_count > 0 ) {
		$assignments = learndash_get_user_assignments( $course_step_post->ID, $user_id );
		if ( ! empty( $assignments ) && count( $assignments ) >= $assignment_upload_limit_count ) {
			return;
		}
	}
}
?>
				<div class="ld-file-upload">

					<div class="ld-file-upload-heading">
						<?php esc_html_e( 'Upload Assignment', 'learndash' );  ?>

						<span><?php echo esc_html( '(' . $upload_message . ')' ); ?></span>		
					</div>
					
					<form id="asmt_atchmnts" class="ld-file-upload-form" method="post" action="<?php echo $current_url; ?>#attachments" enctype="multipart/form-data" accept-charset="utf-8">
						<?php wp_nonce_field( 'save_attachment', 'save_attachment_nonce' ); ?>
						<input type="file" class="ld-file-input" name="uploadfiles[]" id="uploadfiles">

						<label for="uploadfiles">
							<strong>Browse</strong>
							<span>No file selected</span>
						</label>
						
						<input id="save_attachment"  class="ld-button" name="save_attachment" type="submit" value="Attach" />
						<input type="hidden" name="MAX_FILE_SIZE" value="33554432">
						<input type="hidden" name="student_id" value="<?php echo $current_user->ID; ?>" />
						<input type="hidden" name="post_id" id="post_id" value="<?php echo $asmt_id; ?>" />
					</form>
							
					<div class="ld-file-upload-message">
						 
						 <!-- messages go here about the upload process. -->
						 
					</div>

					
				</div> <!--/.ld-file-upload-->


			</div> <!--/.ld-table-list-items-->


		<div class="ld-table-list-footer"></div>
	</div>
	
<!-- END SOURCE TEMPLATE -->	
	
	<hr>
	<p>Need a fresh start? Go ahead and clear your submission. (Careful: This action cannot be undone!)</p>
	<form id="delete_assignment" method="post" action="<?php echo $current_url; ?>#delete_assignment">
		<input type="hidden" name="post_id" id="post_id" value="<?php echo $asmt_id; ?>" />
		<?php wp_nonce_field( 'delete_submission', 'delete_submission_nonce' ); ?>
		<input id="delete_assignment" name="delete_assignment" type="submit" value="Delete Assignment" />
	</form>
	<?php 
	endif; //if( $asmt_editable ), started on line 411. 
	
}


//Add commenting functionality: 

//We can't comment if the post has not been submitted. 
if( !empty( $asmt_status ) ){
	if( $asmt_status !== 'draft' && ( !empty( $asmt_id ) ) ){ 
	
		echo "<hr>";
		echo "<div class='commentlist'>";
		$asmt_comments = get_comments('post_id='.$asmt_id.'&order=DESC'); 
		
		if( !empty( $asmt_comments ) ){
			echo "<p class='info-right'>(Feedback is listed newest to oldest.)</p>";
			echo "<h3>Instructor Feedback</h3>";
			$comment_parms = array( 'reply_text' => '', 'avatar_size' => 0 , 'style' => 'div' );
			wp_list_comments( $comment_parms, $asmt_comments ); 
			
			$defaults = array(
				'title_reply' => 'Reply to Instructor Feedback',
				'label_submit'      => __( 'Post Reply' ),
				'logged_in_as' => '',
				'comment_field' =>  '
				<input type="hidden" name="redirect_to" value="'.$current_url.'" />
				<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>'
			);
			
			comment_form( $defaults, $asmt_id );		
		
		} else {
			echo"<p><em>No feedback from the course instructor has been posted for your assignment yet. Feedback for specific assignments will appear here when available. Thank you.</em></p>"; 
		}
		
		echo "</div><!-- end .commentlist --> ";				
	
	} //end if $asmt_status !== 'draft' 				
} //end if !empty($ asmt_status)		
?>
