<?php 

namespace Doula_Course\App\Clss\Processors\Assignments;


use Doula_Course\App\Clss\Interfaces\Assignment_Processor_Action;

/*
 *  
 *	Updated 23 Jun 2022
 *
 *	
 *	
 *	
 *	
 *
 */
 

if ( ! defined( 'ABSPATH' ) ) { exit; }
 
 
if( !class_exists( 'Save_Attachment' ) ){ 
	class Save_Attachment implements Assignment_Processor_Action  { 

		
		
	

		/**
		 *  $messages
		 *
		 *  Messages object that handles communication between objects. 
		 *
		 *	OBJ
		 */
		
		private $messages;	
		
		
		/**
		 *  $attachment_ids
		 *
		 *  IDs for all successfully submitted assignments. 
		 *
		 *	ARR
		 */
		
		private $attachment_ids = [];			
		

		/**
		 *  $post
		 *
		 * 	Post data sent from server	
		 *
		 *	ARRAY
		 */
		
		private $post; 
				

		/**
		 *  $attachment
		 *
		 * 	(Not yet created???)
		 *
		 *	OBJECT
		 */
		
		//private $attachment; 
		
		
		/**
		 *  $student_id
		 *
		 * 	User ID
		 *
		 *	INT
		 */
		
		private $student_id; 

		
		
		
		//Then Methods

		/**
		 * _construct
		 *
		 * @since 0.1
		 **/
		public function __construct(  ){
				
				
				
		}
		
		
		/**
		 * 	init
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		 
		public function do_action( $student_id, $post ) {

			//update_post
			
			if( strcmp( $student_id, $post[ 'student_id' ] ) !== 0 )
				wp_die( 'Weird?! Not the same user who clicked the submit button.' ); 
			
			
			if( isset( $post['save_attachment'] ) ){
			
				$this->student_id = $student_id; 
				
				$this->post = $post;
					
				$this->save();
				
			}		
			
			$this->set_notices(); 
			
		}	

		
		
		/**
		 * 	set_messages
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function set_messages( $messages )
		{

			$this->messages = $messages;			
		
		}

		
		
		
		/**
		 *  set_notices
		 *
		 *
		 *	return VOID
		 */
				
		
		private function set_notices(): void
		{
			
			foreach( $this->attachment_ids as $aid ){
				if ( is_wp_error( $aid ) )
					$this->messages->add( 'student' , 'warning', "Failed to upload attachment #{$aid}. Try again!" );
				else 
					$this->messages->add( 'student' , 'notice', "Success! Attachment #{$aid} was successfully uploaded!" );			
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
			
			// These files need to be included as dependencies when on the front end.
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );

			$files = $_FILES[ "uploadfiles" ];

			// Let WordPress handle the upload.
			// Set up to handle multiple uploads at once. 
			// Remember, 'uploadfiles' is the name of our file input in our form above.

			foreach( $files[ 'name' ] as $key => $value ){            
				if( $files[ 'name' ][ $key ] ){ 
					$file = array( 
						'name' => $files[ 'name' ][ $key ],
						'type' => $files[ 'type' ][ $key ], 
						'tmp_name' => $files[ 'tmp_name' ][ $key ], 
						'error' => $files[ 'error' ][ $key ],
						'size' => $files[ 'size' ][ $key ]
					); 
					
					$_FILES = array( "uploadfiles" => $file ); 
					foreach( $_FILES as $file => $array ) {              
						$this->attachment_ids[] = media_handle_upload( $file, $this->post['post_id'] ); 
					}
				} 
			} 
		}	
			
		
		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function _()
		{

			
		
		}



	}

}
?>