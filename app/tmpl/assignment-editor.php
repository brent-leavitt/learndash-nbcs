<?php
namespace Doula_Course\App\Tmpl;

use Doula_Course\App\Clss\Assignment_Processor as Processor;
use Doula_Course\App\Clss\Assignment_Editor as Editor;
use Doula_Course\App\Clss\Message;
use Doula_Course\App\Clss\Grades\Grades;


if( isset( $_POST ) ){
	//print_pre( $_POST, 'This is being called from line '. __LINE__ .' in the Assignment-editor.php template' ); 
	$message = new Message(); 
	$processor = new Processor( $message );
	$processor->process( $_POST ); 
}
	
$student_id =  $current_user->ID;
$material_id = $post->ID;
$current_url = get_permalink();
$empty_asmt = true;
$asmt_editable = true;

//Load the student assignment:
$asmt_args = array(
	'post_type' => 'assignment',
	'post_status' => array( 'draft', 'submitted', 'incomplete', 'resubmitted', 'completed' ),
	'author' => $student_id,
	'post_parent' => $material_id
);
	
$asmt_query = new \WP_Query( $asmt_args );
$asmt_id = ( isset( $asmt_query->post->ID ) )? $asmt_query->post->ID : 0;

//Load the students grades:
$grades = new Grades(); 
$grades->build( $student_id );
$grade = $grades->get_grade_by_id( $material_id );
$grade_is_set = ( $grade )? true : false ;
$asmt_status = ( !empty( $grade ) )? $grade->get_status(): 'draft' ; //Display Assignment Status Notices:  
$instr_status = ( !empty( $grade ) )? $grade->get_instr_status() : get_post_meta($asmt_id, 'instructor_status', true);

//print_pre( $grade, "Grade is set and value is as follows, Called on line ". __LINE__ );
//print_pre( $_POST, "The POST called in the Assignment_Editor template file.". __LINE__ ); 
//print_pre( $message, "The Message Class called in the Assignment_Editor template file." ); 
/* 
print_pre( $asmt_id, "assignment ID is " ); 
print_pre( $asmt_status, "assignment status is " ); 
print_pre( $instr_status, "Instructor status is " );  */

//Display messages from Message Class: (pending)


switch( array( $asmt_status, $instr_status ) ){
	case array('draft', NULL ): //draft, not yet submitted
		$status_message = '<strong>Instructions:</strong> Use the assignment editor below to compose and submit your assignment. To save your assignment before you are ready to submit it, click "Save Draft." ';
		$status_class = '';
		break;
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
		$status_message = 'The status for this assignment was not available. Contact your trainer for furthere questions.';
		$status_class = '';
		break;
}


//Don't load Assignment Editor if grade is already set, but assignment doesn't exist. 
if( $grade_is_set && ( $asmt_id == 0 ) ){
	if( $instr_status == 2 ){
		
		switch( $asmt_status ){ 
			case "incomplete": 
				$status_message = 'A grade has manually been recorded for this assignment, but it was marked as "Incomlpete." Contact your trainer directly for further instructions on how to complete this assignment.';
				$status_class = 'alert';
				break; 
			case "complete": 
				$status_message = 'This assignment has already been marked as "complete" by your trainer. No further action is required. If you have questions, contact your trainer directly.';
				$status_class = 'cleared';
				break; 
			case "submitted": 
			case "resubmitted": 
			default: 
				$status_message = 'A grade has already been recorded for this assignment. Contact your trainer directly for further questions regarding this assignment.';
				$status_class = 'pending';
				break; 
			
		}
	}	 
}

if( isset( $status_class ) && isset( $asmt_status ) && isset( $status_message ) ){
	echo "
	<div class='asmt_status_box {$status_class}'>
		<h4>Status: <em>{$asmt_status}</em></h4>
		<p class='asmt_detail_status'>{$status_message}</p>
	</div><!-- end .ASMT_STATUS_BOX -->
	";
}



if( $grade_is_set && ( $asmt_id == 0 ) ):	
	
	
	
	echo "<div id='no_editor'><p>No additional action required for this assignment. Please move on.</p></div>"; 
	
else:	

	
	$content = ( $asmt_query->have_posts())? $asmt_query->post->post_content : NULL ; 

	?>
				
	

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
		//Assignment is not editable, read only. Don't display form. 
		
		echo "<div id='readonly_assignment' >". $content ."</div>";
		
		
	
		if( isset( $asmt_status ) && ( $asmt_status == 'completed' ) ){
			echo "<em>This assignment is marked as completed. No further changes can be made.</em>";
		} else {
			echo '<p><em>This assignment is pending review and cannot be edited. To continue editing, revert to "draft" status. </em></p>';
			wp_nonce_field('revert_to_draft','revert_to_draft_nonce');
			echo "<input type='hidden' name='edit_assignment' value='".esc_html( $content )."' />"; 
			echo '<input type="submit" id="save_draft" name="save_draft" value="Revert to Draft" />';
		}
		
		
	}
	
	//If an assignment has already been created close this form. 
	echo ( !empty( $asmt_id ) )? '</form>': ''; 
		


	//echo "<hr>";


		
	//Add Attachment Functionality:
	//Check if assignments are attached? An Assignment ID must be created first. 
	if( isset( $atch_msg ) && !empty( $atch_msg ) ) {
		echo "<div class='asmt_status_box'><ul>";
		foreach( $atch_msg as $a_msg )
			echo "<li><em>".$a_msg."</em></li>";
		echo "</ul></div>";
	}
		

		
	if( empty( $asmt_id ) ){
		
		?>
		<hr>
		<p>An assignment should first be created before you can include attachments. Begin working on your assignment and click "Save Draft", or click below to add attachments without a written assignment.</p> 
		
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
		
		
		?>
			
		<div class="ld-table-list ld-assignment-list" id="attachments" >
			<div class="ld-table-list-header ld-primary-background">
				<div class="ld-table-list-title">
					<span class="ld-item-icon">
						<span class="ld-icon ld-icon-assignment"></span>
					</span>
					<?php esc_html_e( 'Attachments', 'learndash' );  ?>	
				</div>
			</div> <!--/.ld-table-list-header-->
			
			<div class="ld-table-list-items">

			<?php	
			/** This action is documented in themes/ld30/templates/assignment/listing.php */
		
				if ( ! empty( $attachments ) ) :

					$delete_attach_nonce = wp_create_nonce( 'delete_attachment' ); 
					
					echo "<p>The following attachments are linked to this assignment:</p>";
					
					foreach( $attachments as $atch ):
					
						echo "
						<div class='ld-table-list-item'>
							<div class='ld-table-list-item-preview'>
								<div class='ld-table-list-title'>";
							
								
								
									echo "<a href='".wp_get_attachment_url( $atch->ID )."' target='_blank'>
									<span class='ld-item-icon'>
										{$atch->post_title}
										<span class='ld-icon ld-icon-download' aria-label='". esc_html__( 'Download Assignment', 'learndash' ). "'></span>
									</span></a>"; 
									
									if( $asmt_editable ):
									?>
											
									<form id="delete_attachment_form_<?php echo $atch->ID; ?>" method="post" action="<?php echo $current_url; ?>#attachments">
										<input type="hidden" name="atch_id" id="atch_id" value="<?php echo $atch->ID; ?>" />
										<input type="hidden" name="delete_attachment_nonce" id="delete_attachment_nonce" value="<?php echo $delete_attach_nonce; ?>" />
										<input class="delete-attachment button-secondary" aria-label="<?php esc_html_e( 'Delete Attachment', 'learndash' ); ?>" id="delete_attachment_<?php echo $atch->ID; ?>" name="delete_attachment" type="submit" value="X" title="Remove Attachment" />
									</form>
								
									<?php
									endif;
						
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

					echo "<p><i>". esc_html_x( 'No attachments are linked to this assignment.', 'No assignments message', 'learndash' ) . "</i></p>";

				endif;


				if( $asmt_editable ):
				
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

				<?php endif; // end if asmt_editable ?>
				</div> <!--/.ld-table-list-items-->


			<div class="ld-table-list-footer"></div>
		</div>
		
	<!-- END SOURCE TEMPLATE -->	
		
		<hr>
		<div id="fresh-start">
			<h4>Need a fresh start?	<small>Go ahead and clear your submission. <em>(Careful: This action cannot be undone!)</em></small></h4>

			<form id="delete_assignment" method="post" action="<?php echo $current_url; ?>#asmt-editor">
				<input type="hidden" name="post_id" id="post_id" value="<?php echo $asmt_id; ?>" />
				<?php wp_nonce_field( 'delete_submission', 'delete_submission_nonce' ); ?>
				<input id="delete_assignment_submit" class="button button-secondary" name="delete_assignment" type="submit" value="Delete Assignment" />
			</form>
		</div><!-- end #fresh-start -->
		<?php 		
	} //Has assignments
endif; // end if( $grade_is_set && ( $asmt_id == 0 ) )
	

//Add commenting functionality: 

//We can't comment if the post has not been submitted. 
if( !empty( $asmt_status ) ){
	if( $asmt_status !== 'draft' && ( !empty( $asmt_id ) ) ){ 
	
		echo "<hr>";
		echo "<div class='commentlist'>";
		$asmt_comments = get_comments('post_id='.$asmt_id.'&order=ASC'); 
		
		if( !empty( $asmt_comments ) ){
			echo "<p class='info-right'>(Feedback is listed oldest to newest.)</p>";
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
			echo "<div id='comment-form-wrapper' >";
				comment_form( $defaults, $asmt_id );		
			echo "</div><!-- comment-form-wrapper -->"; 
			
		} else {
			echo"<p><em>No feedback from the course instructor has been posted for your assignment yet. Feedback for specific assignments will appear here when available. Thank you.</em></p>"; 
		}
		
		echo "</div><!-- end .commentlist --> ";				
	
	} //end if $asmt_status !== 'draft' 				
} //end if !empty($ asmt_status)		
?>
