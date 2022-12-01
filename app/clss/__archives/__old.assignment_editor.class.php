<?php 
//This is to be significantly overhauled. 
namespace Doula_Course\App\Clss;

use Doula_Course\App\Clss\Grades\Grades;
//Also uses Submission class

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to manage the assignment editor
	 * 
	 *
	 *
	 */


if( !class_exists( 'Assignment_Editor' ) ){
	class Assignment_Editor{
		
		
/*

$empty_asmt = true;	
$atch_msg = array();
$current_url = get_permalink();
$asmt_editable = true; 
$allowed_html = wp_kses_allowed_html( 'post' );
*/
		
		/**
		 *  $empty_asmt
		 *
		 *  Assignment Editor has been submitted without any text and is therefore empty. 
		 *
		 *	BOOL
		 */
		
		private $empty_asmt = true;	
		
		/**
		 *  $editable
		 *
		 *  Whether or not the assignment is in an editable state. 
		 *
		 *	BOOL
		 */
		
		private $editable = true;	
			
		/**
		 *  $add_attachments
		 *
		 *  Are we adding attachments?
		 *
		 *	BOOL
		 */
		
		private $add_attachments = false;	
		
		
		
		/**
		 *  $notice = array();
		 *
		 *  Notices. General assignment editor notices.  
		 *
		 *	ARRAY
		 */
		
		private $notice = array(); 
		
		
		/**
		 *  $atch_msg = array();
		 *
		 *  Attachment Messages. These are messages to be communcated to the user as it pertains particularly to assignments. 
		 *
		 *	ARRAY
		 */
		
		private $atch_msg = array(); 
		
		
		
		/**
		 *  $current_url
		 *
		 * 	The current assignment url for use in messaging. 
		 *
		 *	str
		 */
		
		private $current_url = ''; 
		

		/**
		 *  $post
		 *
		 * 	Post data sent from server	
		 *
		 *	ARRAY
		 */
		
		private $post; 
				

		/**
		 *  $submission
		 *
		 * 	An assignment submission object 
		 *
		 *	OBJECT
		 */
		
		private $submission; 
		
		
		/**
		 *  $student_id
		 *
		 * 	User ID
		 *
		 *	INT
		 */
		
		private $student_id; 
		
		
		
		
				
		/**
		 *  __construct
		 *
		 *	
		 */
		
		public function __construct( ){
			
			$this->current_url = get_permalink();
		
		}

		

		/**
		 *  build
		 *
		 *	
		 *
		 *	return BOOL
		 */
		 
		 
		public function build( $post ): BOOL
		{
			 
			$this->delete_attachments(); 

			$this->verify( $post );

			$result = $this->set_submission(); 

			$this->set_notices(); 
			
			return $result; //If returns false, submission is not set. 
			
			 
		}
				
		/**
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		private function verify( $post ){
			
			if( !empty( $post ) ) {
				if( wp_nonce_field( 'edit_assignments', 'grape_vines' ) || wp_nonce_field( 'edit_assignments', 'tomato_vines' ) )
					$this->post = $post;
				
				if( isset( $post['asmt_atchmnts_nonce'], $post['post_id'] ) && wp_verify_nonce( $post['asmt_atchmnts_nonce'], 'asmt_atchmnts' ) ) {
					$this->post = $post;
					$this->add_attachments = true;
					print_pre( $post, "Attachment has passed validation on line: ". __LINE__ .", Method: ".__METHOD__ );
					print_pre( $this->add_attachments, "Add_Attachment property: ". __LINE__ .", Method: ".__METHOD__ );
				}		
			}
				
			$this->empty_asmt = ( empty( $post['edit_assignment'] ) );
			
			$this->set_student(); 
			
		}	

		
		/**
		 *  set_student
		 *
		 *  Sets the student_id property. 
		 *
		 *	return VOID
		 */
				
		
		private function set_student(): VOID
		{
						
			$this->student_id = get_current_user_id();		
			
		}	
		

		
		/**
		 *  set_submission
		 *
		 *  
		 *
		 *	return BOOL
		 */
				
		
		private function set_submission(): BOOL
		{
			
			if( $this->empty_asmt )
				return false; 
			
			if( strcmp( $this->student_id, $this->post[ 'student_id' ] ) !== 0 )
				wp_die( 'Plagerism?! Not the same user who clicked the submit button.' ); 
			
			if( isset( $this->post['save_draft'] ) 
				|| isset( $this->post['submit_assignment'] ) ){
			
				$this->submission = new Submission(); 
				
				$this->submission->build( $this->student_id, $this->post );
				
			
			}		
			
			return true;
		}	
		

		/**
		 *  set_grade
		 *
		 *	This sets grades, but can also set grades when they are update.d 
		 *	
		 *	return BOOL
		 */
				
		
		private function set_grade( $id, $args ): BOOL
		{
						
			$grades = new Grades(); 
			
			$grades->build( $this->student_id ); 
			
			$grades->add_grade( $id, $args ); 
			
			return $grades->update();
			
		}	

		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			if( $this->empty_asmt )
				$this->notices[] = __( 'The assignment form is empty. Please complete your assignment and then save it as a draft or submit it for grading.', NBCS_TD );
			
			//Check Submission class for notices. 
			
		}		
			
			
		/**
		 *  get_name
		 *
		 *
		 *	return string
		 */
				
		
		public function get_name(): string
		{
			
			return $this->name;
			
		}		
			

		/**
		 *  get_args
		 *
		 *
		 *	return array
		 */
				
		
		public function get_args(): array
		{
			
			return $this->args;
			
		}		

		/**
		 *  delete_attachments
		 *
		 *	
		 *
		 *	return 
		 */
				
		
		private function delete_attachments(){
			
			if( isset( $_GET['delete_atch'] )  ){
				$delete_atch_id = $_GET[ 'delete_atch' ];
				//Move this message to Attachment area. 
				
				$this->atch_msg[] = ( false === wp_delete_attachment( $delete_atch_id ) ) ? 
					"The file attachement failed to be deleted.":
					"The file attachment was successfully deleted.";
			}	
			
		}

		/**
		 *  add_attachments
		 *
		 *	
		 *
		 *	return 
		 */	
		
		public function add_attachments(){
			
			print_pre( $this->add_attachments, "Adding Attachment on line: ". __LINE__ .", Method: ".__METHOD__ );
			
			if( $this->add_attachments ){
				
				print_pre( $_FILES, "Adding Attachment on line: ". __LINE__ .", Method: ".__METHOD__ );
			
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
						foreach( $_FILES as $file => $array )              
							$attachment_ids[] = media_handle_upload( $file, $this->post['post_id'] ); 
					} 
				} 
				
				foreach( $attachment_ids as $aid )
					$this->atch_msg[] = ( is_wp_error( $aid ) )? "Failed to upload attachments. Try again!" : "Attachments were successfully uploaded! Hoorah!";
			}
		}	
		
		
		/**
		 *  save
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		public function save(){
			
			
			$status = ( isset( $this->post[ 'save_draft' ] )  ? 'draft' : ( isset( $this->post[ 'submit_assignment' ] ) ? 'submit' : NULL ) );
			
			$this->submission->set_status( $status );
			
			$args = $this->submission->get_args(); 
			
			$id = $this->update( $args ); 
			
			if( !empty( $id ) )
				$result = $this->set_grade( $id, $args );
			
			return ( isset( $result ) )? $result : FALSE ; 
			
		}	
			
		
		
		/**
		 *  update
		 *  
		 *	
		 *
		 *	return INT //submission_id
		 */
				
		
		private function update( $args ): INT
		{
			
			
			
			if( !empty( $args[ 'ID' ] ) ){
			
				return wp_update_post( $args ); 
				
			} else {
			
				return wp_insert_post( $args );
				
			}
			
			return 0; 
		}			
		
		
		/**
		 *  
		 *
		 *
		 *	return 
		
				
		
		public function __(){
			
			
		}	
		 */
			
		
		
		/**
		 *  
		 *
		 *
		 *	return 
		
				
		
		public function __(){
			
			
		}	
		 */
		
		/**
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		private function __(){
			
			
		}	
	}
}