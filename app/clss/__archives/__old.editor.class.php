<?php 

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
 *  New Beginnings Editor PHP Class
 *	Created on 18 July 2013
 *  Updated on 15 Dec 2020
 *
 *	The purpose of this class is to handle recurring processes related to 
 *	editor pages for the New Beginnings Doula Training website. 
 *
 */

 
 
class Editor{ 

/* 	private static $log_dir_path = '';
	private static $log_dir_url  = ''; */
	
	public function __construct(){
				
		$this->init();
	
	}
	

	/**
	 * Initialization
	 *
	 * @since 1.0
	 **/
	public function init() {
		//add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		//add_action( 'init', array( __CLASS__, 'process_student' ) );
		

	}

	/**
	 * Add administration menus
	 * Two Types of admin pages: menu and submenu
	 * @since 1.0
	 **/
	public function add_admin_pages() {
	
		//STUDENT Editor Pages
		add_menu_page('Students Overview', 'Students', 'edit_users', 'students',  array( $this , 'load_students_overview' ) , 'dashicons-heart', 50 );
		add_submenu_page( 'students', 'Add New Student', 'Add New', 'edit_users', 'add_student', array( $this , 'load_new_student_editor' ) );
		add_submenu_page( NULL, 'Email Student', 'Auto Emails', 'edit_users', 'email_student', array( $this , 'load_email_student_editor' ) );
		add_submenu_page( 'students', 'Import Students', 'Import Trxn', 'edit_users', 'import_transaction', array( $this , 'load_import_transaction_editor' ) ); // We may do away with this one. 
		add_submenu_page( NULL, 'Edit Student', '', 'edit_users', 'edit_student', array( $this , 'load_student_editor' ) );
		
		//TRANSACTION Editor Pages
		add_submenu_page( 'students', 'Location Search', 'Location', 'edit_users', 'location_search', array( $this ,'load_location_search' ) );
		add_submenu_page( NULL, 'Add New Transaction', '', 'edit_users', 'add_transaction',  array( $this , 'load_new_transaction' ) );
		add_submenu_page( NULL, 'Edit Transaction', '', 'edit_users', 'edit_transaction',  array( $this , 'load_transaction_editor' ) );
		
		//DEV TEST WINDOW
		
		//if( ( substr( get_bloginfo('url'), 7, 6 ) ) === 'crsdev' ){
			add_submenu_page( 'students', 'Test Window', 'Test Window', 'edit_users', 'test_window',  array( $this , 'load_test_window_editor' ) );
		//}
		
		//Student Maintenance Window
		add_submenu_page( 'students', 'Maintenance Tools', 'Maintenance', 'edit_users', 'maintenance',  array( $this , 'load_maintenance_editor' ) );
		
		//Admin Messages Manager page
		add_submenu_page( NULL, 'Message Manager', '', 'edit_users', 'admin_messages',  array( $this , 'load_admin_messages_manager' ) );
		
		//ASSIGNMENTS Editor page
		add_submenu_page( NULL, 'Edit Grades', '', 'edit_users', 'edit_grades',  array( $this , 'load_grades_editor' ) );
		
		//ASSIGNMENT MAP editor
		add_submenu_page( 'edit.php?post_type=assignment', 'Assignments Map Manager', 'Map Manager', 'edit_users', 'assignment_map',  array( $this , 'load_assignment_map_manager' ) );
		
		
	}
	
	/*
	 * LOAD STUDENTS OVERVIEW
	 *
	 * @since 1.0
	 **/		

	public function load_students_overview(){
		
		if (!current_user_can('edit_users'))
			wp_die(__('You do not have sufficient permissions to access this page.'));
			
		//global $student_type;
		
		if( !isset ( $_GET['role'] ) ){
			$student_type = NULL;
		} else {
			$student_type = $_GET['role'];	
		}
		
		//Start OUTPUT 
		
		$this->student_overview_header();
		
	
		
		if( !class_exists('Students_Tables')){ //This should be available because the Tables class is loaded before the editor class...
			echo "Problems, please fix them.";
		} else {
			$students_list = new Students_Tables();
			
			$students_list->prepare_items( $student_type );
		
			$students_list->display();
		} 
		

		$this->admin_footer(); 
	
		//End OUTPUT
	
	}
	
		
	/*
	 * LOAD ASSIGNMENT MAP MANAGER
	 *
	 * @since 2.0
	 **/

	public function load_assignment_map_manager(){
		
		$this->admin_header('Assignments Map Manager'); 
		
		$asmt_map = new Assignment_Map();
		//print_pre( $asmt_map->asmt_map );
		$asmt_map->asmt_map_manager();
		//assignment_map_manager(); 
		
		$this->admin_footer(); 
	}



	
		
	/*	
	
	 * LOAD NEW STUDENT EDITOR
	 *
	 * @since 1.0
	 **/

	public function load_new_student_editor(){

		$this->load_student_form( 'Add New Student' ); //Title is the minimum variable we need to use this function. 
		
	}



	/*
	 * LOAD STUDENT EDITOR
	 *
	 * @since 1.0
	 **/

	public function load_student_editor(){
		
		//Current User has permission to Edit Students... 
		if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
		
		$errors = null;
		$message = null;
		$updated = false; 
		$sid = $_REQUEST['student_id'];
		$student = get_userdata($sid); 
		
		if( !empty($_POST) || wp_verify_nonce($_POST['trees_and_flowers'],'edit_student') ) {
			
			$nbStud = new Student();

			//We need to run a check to see if new user data is being entered. Probably need to also prepare data to be processed via the Student Class. 
			
			if( !isset( $_REQUEST['student_id'] ) ){//We are inserting a new student's information.
				if( isset($_POST['trees_and_flowers']) && ( $_POST['_wp_http_referer'] == '/wp-admin/admin.php?page=add_student' ) )
				//This is a new student being submitted via the add_student page. Most values will be prepared for submission on that page. 
				
				$sPost = $nbStud->student_post;//an array to prep values for add_student
				
				foreach($_POST as $sPostKey => $sPostVal){
					if( array_key_exists($sPostKey, $sPost) ){
						//echo "sPostKey is $sPostKey and sPostVal is $sPostVal. <br>";
						$sPost[$sPostKey] = $sPostVal;
					}
				}
				
				//Set user_login, user_nicename, nickname
				$sPost['user_login'] = $_POST['first_name'].$_POST['last_name'];
				$sPost['user_nicename'] = strtolower($_POST['first_name'].'-'.$_POST['last_name']);
				$sPost['nickname'] = $_POST['first_name'].$_POST['last_name'];
				$sPost['user_pass'] = wp_generate_password( 12, false );
				
				$student = $nbStud->add_student($sPost);//This is where all the processing happens. 
				
				if( is_a( $student, 'WP_User') ){
				
					$added = true;
				
				} elseif( is_wp_error( $student ) ) {
				
					$errors = $student;
					$student = null; //We need to empty this out, because the form will want to use it. 
					
				}
				
				$message = ( isset($added) && ($add == true) )? "We've added a new student account for $student->display_name." : null;
			
			} else {  //We are UPDATING student information that has been passed. 
				
				//We need the current student data to compare to updated data. 
				
				//Here's a couple of more security checks. 
				if( isset($_POST['trees_and_flowers']) && ( $_POST['_wp_http_referer'] == '/wp-admin/admin.php?page=edit_student&student_id='.$sid ) ){
					
					$_POST['ID'] = $_GET['student_id'];
					$updated_student = $nbStud->update_student($_POST, 1, 1); //override set, don't display update detail messages. 
					
				/* 	echo "<br><br> Did you catch that:<pre> ";
					var_dump( $_POST );
					echo "</pre><br><br>"; */
					if( is_a($updated_student, 'WP_User') ) {// New Method from Student class, needs to be created. 
						$updated = true;
						$student = $updated_student;
					}
					//Do we want to send an update message? we could. 
					$message = ( isset($updated) && ($updated === true) )? "We've updated the student account for $student->display_name." : "There was nothing new to update. Thanks anyways.";

				}				
			}// end nonce else. 
		}
		
		$studTitle = "Student Editor: <em>". $student->display_name ."</em>"; //Title for student editor.  
		$this->load_student_form( $studTitle, 'add_student', $student, $message, $errors );//Load the Student Form. 
	}


	/*
	 * LOAD STUDENT FORM
	 *
	 * @since 1.0
	 **/	
	 
	
	public function load_student_form( $studTitle, $newAction = null, WP_User $student = null, $message = null, WP_Error $errors = null ){
		
 		if( isset($_REQUEST['student_id']) ){
			$sid = $_REQUEST['student_id'];
		} elseif( is_object($student) ) {
			$sid = $student->ID;
		} else {
			$sid = null;
		} 
		
		
		$this->admin_header($studTitle, $newAction); 
		
		if( !isset($errors) &&( is_a( $errors, 'WP_Error') ) ){
			echo '<div class="errors" id="erros"><p>There are errors. I still need to improve upon this.</p></div>';
		}
		
		if($message != null)
			echo '<div class="updated" id="message"><p>'.$message.'</p></div>';
		
		//View Grades for this student
		
		
		if( !empty( $sid ) ){
			echo "<p><a href='/wp-admin/admin.php?page=edit_grades&amp;student_id=$sid' target='_blank'>View Grades</a> | 
			<a href='/wp-admin/admin.php?page=location_search&amp;student_id=$sid' target='_blank'>Find Nearby Students and Alumni</a></p>";
		
			echo '<h3>Student Transaction Records</h3>';
					
			if( class_exists('Transaction_Tables') ){ //This should already be loaded at this point.
			
				$transaction_list = new Transaction_Tables();
				
				$transaction_list->prepare_items();
			
				$transaction_list->display();
			}		
		}
		
		
		/* echo "<h3>Student Photos</h3>";
		echo get_avatar( $sid );
		 */
		
		echo'<form method="post" action="admin.php?page=edit_student';
		if($sid != null)
			echo '&student_id='.intval($sid);
		echo'">';	
		
		
		wp_nonce_field('edit_student','trees_and_flowers');
			
		
		echo'<div id="student_visible" class="switch">
			  <label for="student_visible" >Publicly Visible:</label>
			  <input name="student_visible" type="checkbox"';
		echo ($student->student_visible == 'on')? ' checked ': '';
		echo'>
			  <div class="slider round">&nbsp;</div>
			</div>';
		echo'	
			<h3>Personal Information</h3>
			<table class="form-table">
				
				<tr>
					<td>
						<label for="user_login">User Name</label>
					</td>
					<td>
						<input disabled type="text" id="user_login" name="user_login"  class="regular-text" value="'.$student->data->user_login.'" >
					</td>	
					<td>
						<label for="display_name">Display Name</label>
					</td>
					<td>
						<input type="text" id="display_name" name="display_name"  class="regular-text" value="'.$student->data->display_name.'" >
					</td>
					
				</tr>
				
				<tr>
					<td>
						<label for="first_name">First Name</label>
					</td>
					<td>
						<input type="text" id="first_name" name="first_name"  class="regular-text" value="'.$student->first_name.'" >
					</td>
					<td>
						<label for="last_name">Last Name</label>
					</td>
					<td>
						<input type="text" id="last_name" name="last_name"  class="regular-text" value="'.$student->last_name.'" >
					</td>
					
				</tr>
				
				<tr>
					<td>
						<label for="student_address">Address</label>
					</td>
					<td>
						<input type="text" id="student_address" name="student_address"  class="regular-text" value="'.$student->student_address.'" >
					</td>
					<td>
						<label for="student_address2">Address, Second Line</label
					</td>
					<td>
						<input type="text" id="student_address2" name="student_address2"  class="regular-text" value="'.$student->student_address2.'" >
					</td>
				</tr>
				<tr>
					<td>
						<label for="student_city">City</label>
					</td>
					<td>
						<input type="text" id="student_city" name="student_city"  value="'.$student->student_city.'" >
					</td>
			
					<td>
						<label for="student_state">State</label>
					</td>
					<td>
						<input type="text" id="student_state" name="student_state"  value="'.$student->student_state.'" >
					</td>
				</tr>
				<tr>
					<td>
						<label for="student_postalcode">Postal Code</label>
					</td>
					<td>
						<input type="text" id="student_postalcode" name="student_postalcode"  value="'.$student->student_postalcode.'" >
					</td>
					<td>
						<label for="student_country">Country</label>
					</td>
					<td>
						<input type="text" id="student_country" name="student_country"  class="regular-text" value="'.$student->student_country.'" >
					</td>
				</tr>
			</table>
			
			<h3>Contact Information</h3>
			<table class="form-table">
				
				<tr>
					<td>
						<label for="student_phone">Phone</label>
					
					</td>
					<td>
						
						<input type="text" id="student_phone" name="student_phone"  class="regular-text" value="'.$student->student_phone.'" >
					</td>
					<td>
						  <label for="phone_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="phone_visible" class="switch">
						  <input name="phone_visible" type="checkbox"';
		echo ($student->phone_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
			
				<tr>			
					<td>
						<label for="user_email">Email</label>
					</td>
					<td>
						<input type="email" id="user_email" name="user_email"  class="regular-text" value="'.$student->data->user_email.'" >
					</td>
					<td>
					
						  <label for="email_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="email_visible" class="switch">
						  <input name="email_visible" type="checkbox"';
		echo ($student->email_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
				<tr>
					<td>
						<label for="facebook">Facebook</label>
					
					</td>
					<td>
						
						<input type="text" id="facebook" name="facebook"  class="regular-text" value="'.$student->facebook.'" >
					</td>
					<td>
						  <label for="facebook_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="facebook_visible" class="switch">
						  <input name="facebook_visible" type="checkbox"';
		echo ($student->facebook_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
				
				<tr>
					<td>
						<label for="twitter">Twitter</label>
					
					</td>
					<td>
						
						<input type="text" id="twitter" name="twitter"  class="regular-text" value="'.$student->twitter.'" >
					</td>
					<td>
						  <label for="twitter_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="twitter_visible" class="switch">
						  <input name="twitter_visible" type="checkbox"';
		echo ($student->twitter_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
				<tr>
					<td>
						<label for="instagram">Instagram</label>
					
					</td>
					<td>
						
						<input type="text" id="instagram" name="instagram"  class="regular-text" value="'.$student->instagram.'" >
					</td>
					<td>
						  <label for="instagram_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="instagram_visible" class="switch">
						  <input name="instagram_visible" type="checkbox"';
		echo ($student->instagram_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
				
				<tr>
					<td>
						<label for="pinterest">Pinterest</label>
					
					</td>
					<td>
						
						<input type="text" id="pinterest" name="pinterest"  class="regular-text" value="'.$student->pinterest.'" >
					</td>
					<td>
						  <label for="pinterest_visible">Publicly Visible:</label>
					</td>
					<td>
						<div id="pinterest_visible" class="switch">
						  <input name="pinterest_visible" type="checkbox"';
		echo ($student->pinterest_visible == 'on')? ' checked ': '';
		echo'>
						  <div class="slider round">&nbsp;</div>
						</div>					
					</td>
				</tr>
				
				
			</table>
			
			<h3>Payment Information</h3>
			<table class="form-table">
				<tr>
					<td>
						<label for="student_paypal">Paypal Email</label>
					</td>
					<td>
						<input type="email" id="student_paypal" name="student_paypal"  class="regular-text" value="'.$student->student_paypal.'" >
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<label for="user_registered">Registration Date</label>
					</td>
					<td>
						<input type="text" id="user_registered" name="user_registered"  class="regular-text" value="'.$student->data->user_registered.'" >
					</td>
					<td>
						<label for="last_payment_received">Last Payment Received</label>
					</td>
					<td>
						<input type="text" id="last_payment_received" name="last_payment_received"  class="regular-text" value="'.$student->last_payment_received.'" >
					</td>
					
				</tr>
				<tr>
					<td>
						<label for="student_status">Status</label>
						
					</td>
					<td>';
			
				$student_current = (isset($student->allcaps['student_current']))? $student->allcaps['student_current']: null;
				
				$studCurArr = array(
					0 => 'Inactive',
					1 => 'Current'
				);	
				
				$this->select_forms( $studCurArr, 'student_status', $student_current);
						
						
					echo '</td>
					<td>
						<label for="payments_received">Payments Received</label>
					</td>
					<td>';
			
				$pymt_rcvd = isset($student->payments_received)? $student->payments_received: null;

				$pymt_rcvdArr = array(
					'n/a' => 'n/a',
					'1/1' => 'Paid in Full (1/1)',
					'1/12' => '1 of 12',
					'2/12' => '2 of 12',
					'3/12' => '3 of 12',
					'4/12' => '4 of 12',
					'5/12' => '5 of 12',
					'6/12' => '6 of 12',
					'7/12' => '7 of 12',
					'8/12' => '8 of 12',
					'9/12' => '9 of 12',
					'10/12' => '10 of 12',
					'11/12' => '11 of 12',
					'12/12' => 'Complete (12/12)'
				);	
				
				$this->select_forms( $pymt_rcvdArr, 'payments_received', $pymt_rcvd);
						
						
					echo '</td>
				</tr>
				<tr>
					<td>
						<label for="billing_type">Billing Type</label>
					</td>
					<td>';
			
				$billing_type = isset($student->billing_type)? $student->billing_type: null;

				$billTypeArr = array(
					'' => '---',
					'paypal_recurring' => 'Paypal (recurring)',
					'paypal_manual' => 'Paypal (manual)',
					'paypal_onetime' => 'Paypal (one-time)',
					'check' => 'Check',
					'other' => 'Other'
				);	
				
				$this->select_forms( $billTypeArr, 'billing_type', $billing_type);
						
						
					echo '</td>
								<td>
									<label for="program_rate">Program Rate</label>
					</td>
					<td>';
			
				$program_rate = isset($student->program_rate)? $student->program_rate: null;

				$prgrmRtArr = array(
					''	 	=> '---',
					'9p' 	=> '$9/partial',
					'15p'	=> '$15/partial',
					'18p' 	=> '$18/partial',
					'25p' 	=> '$25/partial',
					'20p' 	=> '$20/partial',
					'34p' 	=> '$34/partial',
					'36p' 	=> '$36/partial',
					'25f' 	=> '$25/ongoing',
					'100f' 	=> '$100/full',
					'150f' 	=> '$150/full',
					'180f' 	=> '$180/full',
					'200f' 	=> '$200/full',
					'380f' 	=> '$380/full',
					'400f' 	=> '$400/full'
				);	
				
				$this->select_forms( $prgrmRtArr, 'program_rate', $program_rate);
						
						
					echo '</td>
							
					<td> 
						<!-- empty -->
					</td>
				</tr>
			</table>
			<h3>Additional Student Information</h3>
			
			<table class="form-table">
				<tr>
					<td>
					<label for="course_access">Course Access</label>
					</td>
					<td>';
					
				$course_access = isset($student->course_access)? $student->course_access: null;

				$courseAccArr = array(
					0	=> '(not set)',
					1 	=> 'main course only',
					2	=> 'main & childbirth',
					3 	=> 'all course materials'
				);	
				
				$this->select_forms( $courseAccArr, 'course_access', $course_access);
				
				echo '</td>
				
					
					<td><label for="course_extensions">Course Extensions</label>
					</td>
					<td>
						<input type="text" id="course_extensions" name="course_extensions"  class="regular-text" value="'.$student->course_extensions.'" ></td>
					<td><label for="certification_date">Certification Date</label>
					</td>
					<td>
						<input type="text" id="certification_date" name="certification_date"  class="regular-text" value="'.$student->certification_date.'" ></td>
					
				</tr>
				<tr>
					<td>&nbsp;</td>	
					<td>&nbsp;</td>	
					<td><label for="certificaiton_last_update">Certification Last Updated</label>
					</td>
					<td>
						<input type="text" id="certificaiton_last_update" name="certificaiton_last_update"  class="regular-text" value="'.$student->certificaiton_last_update.'" ></td>			
					<td><label for="num_recertification">Number of Times Recertified</label>
					</td>
					<td>
						<input type="text" id="num_recertification" name="num_recertification"  class="regular-text" value="'.$student->num_recertification.'" ></td>
				</tr>
				</tr>
			</table>
			
			<h3>Administrator Notes</h3>
			<table class="form-table">
				<tr>
					<td>
						<textarea name="admin_notes" id="admin_notes" rows="5" cols="100">'.$student->admin_notes.'</textarea>
					</td>
				</tr>
			</table>';
			
			submit_button('Update Student');
		echo '</form>'; 
		
		echo '<p><small>Location: <em>classes\editor.class.php 253::load_student_form</em></small><p>';
		$this->admin_footer();
	}
	

	
	
	/*
	 * LOAD TRANSACTIONS OVERVIEW
	 *
	 * @since 1.0
	 **/		


	public function load_transactions_overview(){
		$this->admin_header('Transactions Overview', 'add_transaction'); 

		//Use the WP_List_Table Class? Maybe. 
		
		
		$this->admin_footer();
	}


	/*
	 * LOAD NEW TRANSACTION
	 *
	 * @since 1.0
	 **/


	
	public function load_new_transaction(){
		
		$this->load_transaction_form('New Transaction');

	}


	/*
	 * LOAD TRANSACTION EDITOR
	 *
	 * @since 1.0
	 *
	 * 
	 *
	 * This function is called when an existing transaction needs to be displayed for both review and editing at the same time. 
	 *
	 *
	 **/

	public function load_transaction_editor(){
		global $wpdb;
		
		$transaction_id = null;
		$message = null;
		$transArr = array();

		//This is an updated transaction. 
		if( isset($_GET['trans_id']) ){
			//load the transaction details from the database
			$transaction_id = $_GET['trans_id'];
			if ( !empty($_POST) && check_admin_referer('edit_transaction','transaction-check') ){
				//This is an update to an existing transaction. Proceed accordingly. 
				
				$transTime = ( isset($_POST['trans_time']) )? $_POST['trans_time']: date("Y-m-d H:i:s");
				
				$transData = array(
					'student_id'=>$_POST['student_id'],
					'trans_time' => $transTime,
					'trans_amount'=>$_POST['trans_amount'],
					'trans_label'=>$_POST['trans_label'],
					'trans_detail'=>$_POST['trans_detail'],
					'trans_method'=>$_POST['trans_method'],
					'trans_type'=>$_POST['trans_type'],
					'pp_txn_id'=>$_POST['pp_txn_id']
				);
				
				$transFormat = array( '%f', '%s', '%d', '%s', '%s', '%s', '%s', '%s' );	
				
				$transWhere = array( 'transaction_id'=>$transaction_id );
				
				$db_updated = $wpdb->update( 'transactions', $transData, $transWhere, $transFormat );
					
				
				if($db_updated != false) $message = 'This transaction has been successfully updated.';
			}
		// This is a new transaction insert. 	
		} elseif ( !empty($_POST) && check_admin_referer('edit_transaction','transaction-check') ){ //Not quite the right check???
			//
			$timestamp =  ( isset($_POST['trans_time']) )? $_POST['trans_time']: date("Y-m-d H:i:s");
			
			$transData = array(
				'student_id'=>$_POST['student_id'],
				'trans_amount'=>$_POST['trans_amount'],
				'trans_time'=> $timestamp,
				'trans_label'=>$_POST['trans_label'],
				'trans_detail'=>$_POST['trans_detail'],
				'trans_method'=>$_POST['trans_method'],
				'trans_type'=>$_POST['trans_type'],
				'pp_txn_id'=>$_POST['pp_txn_id']
			);
			
			$transFormat = array( '%f', '%d', '%s', '%s', '%s', '%s', '%s', '%s' );
			
			$wpdb->insert( 'transactions', $transData, $transFormat );
			
			$transaction_id = $wpdb->insert_id;
			
			if($transaction_id != null) $message = 'This transaction has been added.';
			
		}
				
		if( $transaction_id != null){
				$transArr = $wpdb->get_results( 'SELECT * FROM transactions WHERE transaction_id='.intval($transaction_id).' LIMIT 1', ARRAY_A );
				$transArr = $transArr[0];
				foreach($transArr as $tKey => $tVal){
					$transArr[$tKey] = stripslashes($tVal);
				}
		}

				
				
		$this->load_transaction_form('Edit Transaction', 'add_transaction', $transArr, $transaction_id, $message); 
		
	}



	/*
	 * LOAD TRANSACTION FORM
	 *
	 * @since 1.0
	 *
	 *
	 *
	 *
	 *
	 *
	 * Called by $this->load_transaction_editor()
	 *
	 **/	
	
	
	
	public function load_transaction_form( $transTitle, $newAction = null, $transArr = array(), $trans_id = null, $message = null ){
		
		//Do we have a student ID to associate with the transaction? 
		$sid = (isset($_REQUEST['student_id']))?$_REQUEST['student_id']:null;
		//If student ID is available lets assign it to the transaction array so we can display it. 
		if($sid != null)
			$transArr['student_id'] = $sid;
		
		
		//Start to build the page. 
		$this->admin_header($transTitle, $newAction); 
		
		if($message != null)
			echo '<div class="updated" id="message"><p>'.$message.'</p></div>';
		
		echo'<form method="post" action="admin.php?page=edit_transaction';
		if($trans_id != null)
			echo '&trans_id='.intval($trans_id);
		echo'">';
		wp_nonce_field('edit_transaction','transaction-check', true);

		echo'	
			<h3>Transaction Details</h3>
			<table class="form-table">
				<tr>
					<td>
						<label for="trans_label">Transaction Label</label>
						<input type="text" id="trans_label" name="trans_label"  value="'. $transArr['trans_label'] .'" >
					
					</td>
					<td>
						<label for="pp_txn_id">PayPal Transaction ID</label>
						<input type="text" id="pp_txn_id" name="pp_txn_id"  value="'. $transArr['pp_txn_id'] .'" >
					
					</td>
					<td>
						<label for="student_id">Student ID</label>
						<input type="text" id="student_id" name="student_id" value="'. $transArr['student_id'] .'" >
					
					</td>
					
				</tr>
				<tr>
					<td>
						<table>
							<tr>
							
								<td>
									<label for="trans_amount">Amount(0.00)</label>
									<input type="text" id="trans_amount" name="trans_amount" value="'. $transArr['trans_amount'] .'" >
								</td>
							</tr>
							<tr>
								<td>
									<label for="trans_time">Date &amp; Time</label>
									<input type="text" id="trans_time" name="trans_time"  value="'. $transArr['trans_time'] .'" >
								</td>	
							</tr>
						</table>
					</td>
					<td colspan="2">
						<label for="trans_detail">Transaction Details</label>
						<textarea id="trans_detail" name="trans_detail" cols="80" rows="10" >'. $transArr['trans_detail'] .' </textarea>
					</td>
				</tr>
				<tr>
					<td>
						<label for="trans_method">Method of Payment</label>';
					
		
		$trans_method_post = isset($transArr['trans_method'])? $transArr['trans_method']: null;
		

		$methodsArr = array(
			'paypal_manual' => 'Paypal (manual)',
			'paypal_onetime' => 'Paypal (one-time)',
			'paypal_recurring' => 'Paypal (recurring)',
			'credit_card' => 'Credit Card',
			'check' => 'Check',
			'other' => 'Other'
		);	
		
		$this->select_forms( $methodsArr,'trans_method', $trans_method_post);
						
		echo '		</td>
					<td>
						<label for="trans_type">Type of Transaction</label>';
						
		$trans_type_post = isset($transArr['trans_type'])? $transArr['trans_type']: null;
		$typesArr = array(
			'payment' => 'Payment',
			'invoice' => 'Invoice',
			'purchase' => 'Purchase',
			'refund' => 'Refund',
			'credit' => 'Credit',
			'other' => 'Other'
		);
		$this->select_forms( $typesArr, 'trans_type', $trans_type_post);
						

		echo '		</td>
				</tr>
			</table>
			';
		if( $newAction != null ){
			submit_button('Update Transaction');	
		}else{
			submit_button('Add Transaction');	
		}
		
		if(isset($transArr['student_id']) && $transArr['student_id'] != 0){
			echo '<a class="secondary" href="admin.php?page=edit_student&student_id='.$transArr['student_id'].'"><- go back to Student</a>';
		}
		
		echo '</form>'; 	
		
		
		
		$this->admin_footer();
	}

	/*
	 * LOAD GRADE EDITOR
	 *
	 * Descrip: Prep Data to display 
	 *
	 * @since 1.0
	 **/

	public function load_grades_editor(){
		
		$student_id = null;
		$message = null;
		$gradesData = array();
		$studData = array();
		
		
		//Start by processing current grades that have been added. 
		if( isset($_GET['student_id']) ){
			$student_id = $_GET['student_id'];
			
			
			if ( !empty($_POST) && check_admin_referer('edit_grades','grades-check') ){

				$asmt = new Assignment( $student_id );
				
				unset( $_POST[ 'grades-check' ], $_POST[ '_wp_http_referer' ], $_POST[ 'submit' ] );
				$asmts_updated = $asmt->update_all_asmts( $_POST );			
				
				$message = ( $asmts_updated !== false )? 'Grades have been successfully updated.' : 'Failed to update grades in the database.';
					
				
			}
		} 		
		$this->load_grades_form( 'Edit Grades', $message );  
		
	}



	/*
	 * LOAD GRADE FORM
	 *
	 * @since 1.0
	 **/	
	
	
	
	public function load_grades_form( $pageTitle, $message = null, $newAction = null ){
		$sid = ( isset( $_GET[ 'student_id' ] ) )? $_GET[ 'student_id' ] : null ;		
		
		$asmt_map = new Assignment_Map();
		$asmt = new Assignment( $sid );
		
		$asmt->set_status_to_num();
		
		//print_pre( $asmt );
		
		if( isset( $sid ) ){
			$student = get_userdata( $sid );
			$student_name = $student->display_name;
			$pageTitle .= ": <em>$student_name</em>";
		}
		
		$this->admin_header($pageTitle, $newAction); 
		
		if($message != null)
			echo '<div class="updated" id="message"><p>'.$message.'</p></div>';
		
		echo'<form method="post" action="admin.php?page=edit_grades';
		echo ($sid != null)? '&student_id='.intval($sid) : '' ;
		echo'">';
		
		wp_nonce_field('edit_grades','grades-check', true);
		
		echo '<table class="wp-list-table widefat fixed striped">';
		
		 foreach( $asmt_map->asmt_map->certs as $cert_key => $cert ){
			foreach( $cert->courses as $course_key => $course ){
				foreach( $course->units as $unit_key => $unit ){		

					echo "<thead><tr>
						<th colspan='2'>
							<h4>{$unit->title}</h4>
							
						</th>
					</tr>
					<tr class='meta-info'>
						<th><em>assignment name</em></th>
						<th><em>status</em></th>
					</tr></thead><tbody>
					
					";

				
					foreach($unit->assignments as $asmt_key => $asmt_obj){
					
						$studOpt = $asmt->grades[$cert_key][$asmt_key]['status'];
						
						$asmt_admin_url = $this->get_asmt_admin_url( $asmt_key );
						echo "<tr>
							<td>
								<label for='$asmt_key'>"; 
						echo ( $asmt_admin_url == FALSE )? $asmt_obj->title : "<a href='{$asmt_admin_url}' target='_blank'>{$asmt_obj->title}</a>";
						echo "</label>
							</td><td>
							<select ";
						echo ( $asmt_admin_url == FALSE )?"":"disabled ";	
						echo"name='$asmt_key'>";
						foreach($asmt->status_arr as $oKey => $oVal){
							echo "<option value='$oKey' ";
							echo ( $studOpt == $oKey )? "selected='selected'" : "" ;
							echo ">$oVal</option>";
							
						}
								
						echo "</select>";
						echo ( $asmt_admin_url == FALSE )? "":" <a href='{$asmt_admin_url}' target='_blank' title='Edit status in assignment.'>&#x25A3;</a>";	
						echo"
							</td>
							
						</tr>";
					}
					
					echo "<tr><td colspan='2' style='text-align: right' >";
					submit_button('Update Progress');
					echo "</td></tr>";
					echo "</tbody>";
				}
			}
		}

		echo '</table>';	
		
		echo '</form>'; 	
		
		if( !empty( $sid ) ){
			echo '<a class="secondary" href="admin.php?page=edit_student&student_id='.$sid.'"><- go back to student editor</a>';
		} 
		
		
		$this->admin_footer();
	}

	/**
	 * get_asmt_admin_url
	 *
	 * @since 2.1
	 *
	 * Description: generates an admin url for requested assignment. 
	 *		
	 * Called in Editor::load_grades_form()
	 *
	 **/	
	
	public function get_asmt_admin_url( $course_id ){
		
		$sid = ( isset( $_GET[ 'student_id' ] ) )? $_GET[ 'student_id' ] : null ;
		
		
		if( empty( $sid ) )
			return NULL; 
		
		$asmt_args = array( 
			'post_type' => 'assignment',
			'post_status' => 'submitted, resubmitted, incomplete, completed',
			'author' => $sid,
			'post_parent' => $course_id 
		);
		
		$asmt_post = get_posts( $asmt_args );
		
		$admin_url = ( !empty( $asmt_post ) )? admin_url( '/post.php?action=edit&post=' ).$asmt_post[0]->ID : FALSE ; 
		
		return $admin_url;
	}
	
	
	/**
	 * Load Email Student Editor
	 *
	 * @since 2.1
	 *
	 * Description: A page for automating Email responses. 
	 *		
	 * Brent Thought: THis should be dependent up on a messenger class. 
	 *
	 *
	 * Called in Editor::add_admin_pages()
	 *
	 **/	
	 
	public function load_email_student_editor(){
		
		$page_title = "Auto Emailer Tool";
		
		$sid = (isset($_GET['student_id']))?$_GET['student_id']:null;
		
		if( !empty( $sid ) ){
			$student = get_userdata($sid);
			$student_name = $student->display_name;
			$first_name = $student->first_name;
			$page_title .= ": <em>$student_name</em>";
		} 

		$this->admin_header( $page_title ); 
		
		if( empty( $sid ) ){
			echo"<h3>Whoops! No student has been selected to receive emails. <a href='/wp-admin/admin.php?page=students'>Please pick one first!</a></h3>";
			return; 
		}
		
		
		if ( isset( $_POST ) && !empty( $_POST['humming-birds-and-bees'] ) ) {
			 check_admin_referer( 'email_build', 'humming-birds-and-bees' );
			
			//Take Assembled information and build email. 
			$mail_check = true;
			
			$sPostValue = ( isset( $_POST['email_type']) )? $_POST['email_type'] : NULL ;
			$primaryEmailTo = ( isset( $_POST['email_to']) && ( $_POST['email_to'] == 'primary_email_to' ) )? $student->user_email : NULL ;
			$primaryEmailCc = ( isset( $_POST['email_cc']) && ( $_POST['email_cc'] == 'primary_email_cc' ) )? $student->user_email : NULL ;
			$paypalEmailTo = ( isset( $_POST['email_to']) && ( $_POST['email_to'] == 'paypal_email_to' ) )? $student->student_paypal : NULL ;
			$paypalEmailCc = ( isset( $_POST['email_cc']) && ( $_POST['email_cc'] == 'paypal_email_cc' ))? $student->student_paypal : NULL ;
			
			//Specific Values for JQuery Added Fields. 
			
			//To Do Add these valuses. 
			$admin_actions = array();
			
			switch( $sPostValue ){
				case 'acct_inactive':
					$subject = 'Account Inactive Notice';
					$body = <<<EOT
Greetings $first_name,

Just a brief note to let you know that we have not received payment on your account for the past two months.  Your account has been placed on inactive status. If you would like to continue with the course, we will need to make arrangements to receive payment before continuing. If you are no longer interested in taking the course, no further action is required.

EOT;
					$admin_notes = 'Two months without payment on account, account moved to inactive status, notice sent.';
					
					break;
					
				case 'pymt_skipped':
					$subject = 'Payment Skipped';
					$body = <<<EOT
Greetings $first_name,

We have received notice that your monthly payment with PayPal did not successfully process for the current month. This is usually caused by one of two reasons: an expired credit card number or insufficient funds in your bank account.

If you're wanting to cancel your contract with New Beginnings please let us know so that we may make note of it on our end and suspend your billing agreement. Otherwise, please advise on how you would like to make payments. Paypal will automatically try to collect payment again on _____date_____, if funds are available in the account.
					
EOT;
					$admin_notes = 'Skipped payment notice received from PayPal. Notice sent.';
					break;
					
				case 'pp_acct_suspend':
					$subject = 'PayPal Subscription Suspended';
					$body = <<<EOT
Greetings $first_name,

We received notice from PayPal that your subscription agreement has been suspended and no further payments will be collected or credited to your student account. This is usually caused by one of two reasons: an expired credit card number or insufficient funds in your bank account.

If you would like to continue with a recurring monthly subscription plan, I can send a new link for the remaining months on your payment agreement.Otherwise, please advise on how you would like to make payments.

If you're wanting to cancel your contract with New Beginnings, no further action is required and your student account will be deactivated. 				
EOT;
					$admin_notes = 'PayPal subscription has been suspended. Student account moved to inactive status. Notice Sent. ';
					$admin_actions = array(
						'role' =>'student_partial_inactive'
					);
					break;
					
				case 'new_stu_follow':
					$subject = 'New Student Follow Up';
					$body = <<<EOT
Just a little background on our end, my wife Rachel and I started into this business together at the beginning of 2013. Before that time, Rachel had been running and developing the course since about October 2011. Rachel and I were married while going through college and we both graduated with bachelor's degrees about five years after we got married, with 3 children at the time of graduation. I share that only because we know a little of the struggles that it takes to get through schooling, in whatever form that may take.

We realize that our program is extremely attractive because of the pricing, but we've also designed it to be very thorough in preparing our students to be well informed and prepared as they go out to work as doulas. The activities and assignments will give you the experience that you need to be able to complete the program with confidence, despite being remotely located from us.

To this end, I'd be interested to know a little bit more about where you are coming from as you enter into the program. What is your prior experiences with childbirth. Is there anything more that I or Rachel can do to help you move forward with your studies? Are you having second guess about the program or becoming a doula in general? Where can we help? We actually really enjoy getting to know our students and it helps us to feel more connected to you.

Rachel also says that she would encourage you to make sure that you are signed up as a part of the student group on Facebook for our training. To join, go to: https://www.facebook.com/groups/233314480125622/   The group was actually started by one of our students who was being proactive in helping to unify the students and it has become a tremendous resource in connecting our students with each other.

Hope to hear back from you soon, and we wish you the best in your studies with us. 					
EOT;
					$admin_notes = 'New Student Follow Up Sent.';
					break;
					
				case 'acct_pd_full':
					$subject = 'Accoun Paid in Full';
					$body = <<<EOT
Greetings $first_name,

Just a brief note to let you know that your account is now paid in full for your doula training. You now have about 13 more month to complete your doula training and certification.

Please let us know if there is anything more that we can do to help you out with your training or get you moving forward. If you haven't heard, we are now offering student incentives to everyone who is current on their payment and who submit at least one assignment per month. You can read more about that here:

https://www.trainingdoulas.com/students/doula-student-incentives-course-progress-payments/					
EOT;
					$admin_notes = 'Account has been paid in full. Note sent.';
					break;
					
				case 'almst_there':
					$subject = 'You\'re Almost There!';
					$body = <<<EOT
Greetings $first_name,

Just a little note to let you know that we're rooting for your success as you approach the finish line for your certification. Consider this your cheering section wishing you the very best. You've come this far, and we know you're going to make it! 

Keep up the good work!					
EOT;
					$admin_notes = "$first_name's almost done with course materials. Note of encouragement sent.";
					break;
			
				case 'exp_notice':
					$subject = 'Doula Student Account Expires Soon';
					$body = <<<EOT
(NO MESSAGE YET)
EOT;
					$admin_notes = 'Student account/2 year mark is almost up! Notice being sent to explain options.';
					break;
					
				case 'expd_account1':
					$subject = 'Doula Student Account Has Expired';
					$body = <<<EOT
Greetings $first_name,

I'm going through and cleaning up old student accounts today for New Beginnings Doula Training. Your account has expired. Please contact me if you would be interested in extending your training to complete your certification. 	
				
EOT;
					$admin_notes = 'Student account has passed 2 year mark. Expiration notice being sent, no assignments have been submitted.';
					break;		
					
				case 'expd_account2':
					$subject = 'Doula Student Account Has Expired';
					$body = <<<EOT
Greeting $first_name,

I'm going through and cleaning up old student accounts today for New Beginnings Doula Training. Your account has expired. With a handful of assignments completed, please contact me if you would be interested in extending your course to get your certification.

We are now also offering one-on-one student coaching to help mentor our students through the training process. We've seen very positive results with this additional service.

Please let me know if you'd be interested in learning more!

EOT;

					$admin_notes = 'Student account has passed 2 year mark. Expiration notice being sent, some assignments were submitted.';
					break;					
				case 'expd_account3':
					$subject = 'Doula Student Account Has Expired';
					$body = <<<EOT
Greetings  $first_name,

I am going through old accounts today for New Beginnings Doula Training and came across your account. It looks like you've completed a good part of the required course work. However, your training has expired and your account has been moved to inactive status.

Is there anything that we could do on our end to help you complete your training? You'll need to secure an extension, but we can discuss that after we figure out what we can do to help you cross the finish line. 	

Please let me know how we can help!
	
EOT;
					$admin_notes = 'Student account has passed 2 year mark. Expiration notice being sent, many assignments were submitted.';
					break;
					
				case 'almn_renewal':
					$subject = 'Doula Certification Renewal';
					$body = <<<EOT
Greetings  $first_name,

First off, on behalf of all of us here at New Beginnings, let me just take a moment to say "Thank you!" in a very big way. Without your commitment to our training program, we would not be here today, still building and improving birth outcomes for mothers and babies. This past year has been one of our most exciting years yet. In case you missed it:

*	Rachel was featured last year in the Washington Post to discuss the role of doulas in C-Sections.
*	About the same time, we also collaborated with a representative of WHO to provide educational materials for a breastfeeding initiative in Sierra Leone.
*	Perhaps the most exciting development is that we have finally crossed that hard-earned distinction of certifying 100 doulas. You are one of those. Thank you!

Starting in February 2018, we began issuing digital certificates. Each certifying doula now has their own webpage linked directly from our alumni directory. This allows our students to provide proof of certification online to anyone, anywhere. The directory can be viewed from our home page, main menu -> directory. 

Your alumni status with New Beginnings has past its two year mark and as such is currently inactive. We would be honored to continue to recognize your certification. To renew,  please reply to this email to request renewal instructions. You can also log in to the student portal and click on the "Other Resourcs" menu -> "Re-certificaiton" link. 			
EOT;
					$admin_notes = 'Alumnus account has passed 2 year mark. Expiration notice being sent with invitiation to renew certification.';
					break;
					
				/* //HOLD FOR FUTURE EMAILS.
				case '':
					$subject = '';
					$body = <<<EOT
					
EOT;
					$admin_notes = '';
					break; */
					
				
					
				default:
					break;
			
			}
	
			$admin_results = $this->update_admin_notes($admin_notes, $sid, $admin_actions );			
		
		}
		

		
		
		
		echo "<h3>What kind of email would you like to send to {$student_name}?</h3>";
		
		$selectID = 'email_type';
		
		$selectArr = array(
			'ISSUES' => array(
				'acct_inactive'=>'Account Inactive Notice (manual)',
				'pymt_skipped'=>'Payment Skipped (auto)',
				'pp_acct_suspend'=>'PayPal Subscription Suspended (auto)'
			),
			'MOTIVATION' => array(
				'new_stu_follow'=>'New Student Follow Up',
				'acct_pd_full'=>'Accoun Paid in Full',
				'almst_there'=>'You\'re Almost There!'
			),

			'RETENTION' => array(
				'almn_renewal'=>'Alumni Renewal Notice (2019)',
				'exp_notice'=>'Account Expires Soon',
				'expd_account1'=>'Account Has Expired - No Assignments',
				'expd_account2'=>'Account Has Expired - Some Assignments',
				'expd_account3'=>'Account Has Expired - Many Assignments'
			)
			
		
		);
		
		
		
		echo '<form method="post" action="">';
		wp_nonce_field( 'email_build', 'humming-birds-and-bees' ); 
		
		echo '<select  id="email_type" name="email_type" >';
		
		foreach($selectArr as $selOpt => $selArr){
			echo "<optgroup label='{$selOpt}'>";
			foreach($selArr as $selKey => $selVal){
				echo '<option value="'.$selKey.'" ';
				
				if( !empty($sPostValue) && ($sPostValue == $selKey) ) echo 'selected ';
				
				echo '>'.$selVal.'</option>';	
			}
			echo "</optgroup>";
		}	

		echo'</select>';
		
		echo '<h3>Which email address do you want to use?</h3>';
		
		echo "<p>To: Cc: <br>";
		echo "<input type='checkbox' name='email_to' value='primary_email_to' checked='checked' />";
		echo "<input type='checkbox' name='email_cc' value='primary_email_cc' />";
		echo "Primary Email: <strong>".$student->user_email."</strong>";
		echo "<br><input type='checkbox' name='email_to' value='paypal_email_to' />";
		echo "<input type='checkbox' name='email_cc' value='paypal_email_cc' />";
		echo "PayPal Email: <strong>".$student->student_paypal."</strong></p>";
		
		/* $email_subject = url_encode("Test Email Subject");
		$email_body = url_encode("This is a test message. I need to figure out the multiple line thing still.");
		 */
		 
		 
		echo '<p class="submit">
				<input type="submit" class="button-primary" value="Build Email" />
			</p>';

		echo "</form>";
		
		if( !empty( $mail_check ) ){
			
			$email_subject = rawurlencode($subject.' - New Beginnings Doula Training');
			$email_body = rawurlencode($body);
			
			if( !empty( $primaryEmailTo ) ){
				$email_student = $primaryEmailTo;
			} elseif( !empty( $paypalEmailTo ) ){
				$email_student = $paypalEmailTo;
			} else {
				$email_student = null;
			}
			
			
			if( empty($email_student) ){
				echo "Whoops! Looks like you forgot to specify a email address to send this to. Go ahead and pick one and then try again!";
			} else {
			
				echo "<p><a href='mailto:".$email_student."?subject={$email_subject}&body={$email_body}";
				
				//Check to see if we're adding a second email address
				$cc_email = '';
				$cc_email .= ( !empty( $primaryEmailCc ) )? "&cc=$primaryEmailCc": null;
				$cc_email .= ( !empty( $paypalEmailCc ) )? "&cc=$paypalEmailCc": null;
				if( !empty( $cc_email ) ) echo $cc_email;
				
				
				echo "'>Email Is Ready!</a></p>";
				
				?>
				<h3>Here's what this email looks like:</h3>
				<p><em>TO:</em> <?php echo "$student_name &lt;$email_student&gt;"; ?> </p>
				<p><em>CC:</em> <?php echo "&lt;incomplete&gt;"; ?> </p>
				<p><em>SUBJECT:</em> <?php echo $subject;?></p>
				<p><em>BODY:</em><br>
				<?php echo $body; ?>
				</p>
				
				<?php
			
			}
			
			//Display Message Details. 
			
			echo "<hr>";
			
			//Send Record of Action To Database. 
			
			if( $admin_results == TRUE ){
				echo "<h3>Student Account Noted</h3>
				<p>The follow note has been added to the admin notes for <a href='/wp-admin/admin.php?page=edit_student&student_id=$sid' target='_blank'>$student_name</a>:<br> <em>$admin_notes</em></p>
				";
			} 
			
			/* $note_date = date( 'j M Y' );
			$admin_note = $note_date." - ".$admin_notes;
			
			?>
			<h3>Now, let's add a note to the student's account that we've sent them an email.</h3>
			<form method="post" action="">
			<?php wp_nonce_field( 'admin_notes', 'humming-birds-and-bees' ); ?>
			<h4>Admin Notes:</h4>
			<textarea name='admin_notes' rows='3' cols='90'><?php echo $admin_note; ?></textarea>
			<p class="submit">
				<input type="submit" class="button-primary" value="Note Account" />
			</p>
			</form>
			<?php */
		} 
		
		
		//print_pre($student);
			
			
	
	
	
		$this->admin_footer();
	}
	
	/*
	 * LOAD ADMIN MESSAGES MANAGER
	 *
	 * @since 1.0
	 *
	 **/
	
	public function load_admin_messages_manager(){
		//Prep
		global $wpdb; 	
		
		
		$message_active = NULL;
		
		if( isset( $_REQUEST[ 'active' ] ) ){
			$message_active = $_REQUEST[ 'active' ];
		}
		
		//print_pre( $_SERVER );
		$actv_msgs = $wpdb->get_results( "SELECT SQL_CALC_FOUND_ROWS * FROM messages WHERE message_recipient=1 AND message_active = 'y' " );
		$actv_msg_count = $wpdb->get_var( "SELECT FOUND_ROWS()" );
		
		$inact_msgs = $wpdb->get_results( "SELECT SQL_CALC_FOUND_ROWS * FROM messages WHERE message_recipient=1 AND message_active = 'n' " );
		$inact_msg_count = $wpdb->get_var( "SELECT FOUND_ROWS()" );
		
		$all_msg_count = intval( $actv_msg_count ) + intval($inact_msg_count);
		
		$msgs_url = admin_url('admin.php?page=admin_messages');
		
		$admin_url = admin_url('/admin-post.php?action=message_dismiss&message_id=');
		
		//Display
		$this->admin_header( 'Messages Manager' ); 

		echo "<p><a href='{$msgs_url}'>All ({$all_msg_count})</a> | <a href='{$msgs_url}&active=active'>Active ({$actv_msg_count})</a> | <a href='{$msgs_url}&active=inactive'>Inactive ({$inact_msg_count})</a></p>";
		
		echo '<table class="wp-list-table widefat striped">';
		// Display whatever it is you want to show.
		echo "<thead>
			<tr>
				<th>ID</th>
				<th style='width:15%'>Date</th>
				<th>Message</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>";
		
		if( strcmp( $message_active, NULL ) == 0 || strcmp( $message_active, 'active' ) == 0 ){
			foreach( $actv_msgs as $actv_msg  ){
				echo"<tr>
					<td>{$actv_msg->message_id}</td>
					<td>{$actv_msg->message_date}</td>
					<td>{$actv_msg->message_content}</td>
					<td><a href='{$admin_url}{$actv_msg->message_id}'>Dismiss</a></td>
				</tr>";
			}				
		}
		
		if( strcmp( $message_active, NULL ) == 0 || strcmp( $message_active, 'inactive' ) == 0 ){
			foreach( $inact_msgs as $actv_msg  ){
				echo"<tr>
					<td>{$actv_msg->message_id}</td>
					<td>{$actv_msg->message_date}</td>
					<td>{$actv_msg->message_content}</td>
					<td><em>inactive</em></td>
				</tr>";
			}		
		}
	
		echo "</ul>";
		
		echo '</tbody></table>';
		
		$this->admin_footer();
	}
	
		
	/*
	 * LOAD IMPORT STUDENT EDITOR
	 *
	 * @since 1.0
	 *
	 * 
	 *
	 *
	 *
	 *
	 *
	 **/

	public function load_import_transaction_editor( ){
	//	echo "This is the student import editor!";
	
		if ( ! current_user_can( 'create_users' ) )
			wp_die('You do not have sufficient permissions to access this page.' );			
			
		//print_pre($_REQUEST);	
		
		$this->admin_header( "Batch Import Student Transactions" );
		
		//First check to see if we have something to display for review.
 		if ( isset( $_POST['humming-birds-and-bees'] ) ) {
			check_admin_referer( 'import_prep', 'humming-birds-and-bees' );
			$results = $this->prepare_import_csv();	
			
			$home_url = home_url();
			$stud_url = $home_url .'/wp-admin/admin.php?page=edit_student&student_id=';
			$trans_url = $home_url .'/wp-admin/admin.php?page=edit_transaction&trans_id=';
			//Perform Checks first	
			//Check for errors
			if( !empty( $results['errors'] ) ){
				
				//Display errors
				echo '<div class="error"><p><strong>' .  $results['errors'] . '</strong></p></div>';
				$cur_path=$_SERVER['REQUEST_URI'];
				echo "<p><a href='".blog_info('url')."/".$cur_path."'>Please try again!</a></p>";
			} else {
				
				//take the results of the prepared import_csv and display them for consideration. 
				//Set a new form with new nonce for processing import
				
				
				echo '<form method="post" action="" enctype="multipart/form-data">';
				wp_nonce_field( 'import_transaction', 'busy-bees-and-birds' ); 
					
				echo "<table class='wp-list-table widefat fixed'>
						<thead><tr>
							<th>Name/ID</th>
							<th>Import Message</th>
							<th>Additional Messages</th>
							<th class='check-column'><em>Skip</em></th>
						</tr></thead>
						<tbody>";
				
					
				//Displaying results for those transactions that are being proposed for update	
				$i = 1;
				foreach($results as $result){
					
					
					
						echo "<tr>";
					if( empty( $result['transArr'] ) ){
						echo "<td class='skip-row' colspan='4'>";
						echo  "{$result['notice']}";
						
						if( !empty( $result['sid'] ) )
							echo " <a href='{$trans_url}{$result['trans_id']}' target='_blank'>Trx ID: {$result['trans_id']} </a>, student: <a href='{$stud_url}{$result['sid']}' target='_blank'>{$result['name']} / id#:{$result['sid']}</a>";			 
						
						echo "</td>";		
						
					} else {
						$cereal_trans = serialize( $result );
						$cereal_id = 'cereal_trans_'.$i;
						
						
						echo "<input type='hidden' name='$cereal_id' value='$cereal_trans'>";
						//Name/ID
						echo "<td>";
						echo "<a href='".$stud_url . $result['sid']."' target='_blank'>".$result['transArr']['Name']." / id#:". $result['sid']."</a>";			 
						echo "</td>";
						
						//Import Summary
						echo "<td>";
						echo $result['import_message'];			 
						echo "</td>";					
					
						//Additional messages
						echo "<td>";
							
						foreach($result['add_mess'] as $mess ){
							echo "- $mess<br>";
						} 
						echo "</td>";		
											
						//Skip check box
						$check_id = 'trans_check_'.$i;
						$i++;
						
						echo "<th class='check-column'>";
						echo "<input type='checkbox' name='$check_id'>";			 
						echo "</th>";
											
					}	
						echo "</tr>";
				}
					
				echo '</tbody></table>
				
				<p><input type="submit" class="button-primary" value="Process" /></p>
				</form>';
			}
			
			
		//This actually processes the transactions and updates the database. Results of this are displayed here. 
		} elseif( isset( $_POST['busy-bees-and-birds'] ) ) {
			
			check_admin_referer( 'import_transaction', 'busy-bees-and-birds' );
						
			$prepArr = array();
			
			foreach($_POST as $pKey => $pVal){
				if( strpos( $pKey , 'cereal_trans_' )  !==  FALSE ){
					$cur_id = intval( str_replace( 'cereal_trans_', '', $pKey ) );
					//check if we should skip this transaction. 
					$skipped = 'trans_check_'.$cur_id;					
					if( array_key_exists( $skipped, $_POST ) !== TRUE ){
						
						$prepArr[] = unserialize( stripslashes($pVal) );
						
					}
				}
			}
			
			//print_pre($prepArr);
			
			//Call the PROCESS IMPORT CSV, $process_results is an array of results. 
			$process_results = $this->process_import_csv($prepArr);
			
			//Display Results from 
			
			//Make available a timestamped batch record for bookkeeping purposes
			
			if( !empty( $process_results['errors'] ) ){
				echo "Editor::load_student_import_editor, line 1182, there was an error in the process: <br>";
			
			} elseif( !empty( $process_results ) ){
				
				//echo "Editor::load_student_import_editor, line 1186, process results is not empty:  <br>";
			
			} else {
				echo "Editor::load_student_import_editor, line 1189, something else is wrong:  <br>";
			
			}
			
			
			//print_pre( $process_results );

			
			//Dates should be at the beginning of the array. 
			$batchDates = array_shift($process_results);
			
			//print_pre($batchDates);
			$start_date = $batchDates['start_date'];
			$end_date = $batchDates['end_date'];
			
			$displayBatchReport = '';
			
			$batchDate = date('m/d/Y');
			$displayBatchReport = "New Beginnings Doula Training
Transaction Import Batch Report, processed on $batchDate. 
Start Date: $start_date, End Date: $end_date 
================================================
\n";
			
			$displayBatchReport .= $this->batchReport($process_results);
						
			$batchReport = strip_tags( $displayBatchReport );
			
			$dateStamp = $start_date.'_'.$end_date;
			$dateStamp = str_replace('/', '', $dateStamp);
			$form_fields = array ('save'); // this is a list of the form field contents I want passed along between page views
			$method = 'ftp'; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
			 
			// check to see if we are trying to save a file
	
			$url = wp_nonce_url('admin.php?page=import_transaction','import_transaction', $_POST['busy-bees-and-birds']);
			$creds = request_filesystem_credentials($url);
			WP_Filesystem($creds);
			$upload_dir = wp_upload_dir();
			
			//print_pre($upload_dir);
			$fileuri = trailingslashit($upload_dir['path']).'batchReport_'.$dateStamp.'.txt';
			$fileurl = trailingslashit($upload_dir['url']).'batchReport_'.$dateStamp.'.txt';
			
			$a = 1; 
			$fileuri = $this->check_batch_file_exists( $fileuri, $a ); 
			$fileurl = $this->check_batch_file_exists( $fileurl, $a ); 

			// by this point, the $wp_filesystem global should be working, so let's use it to create a file
			global $wp_filesystem;
						
			if ( ! $wp_filesystem->put_contents( $fileuri, $batchReport, FS_CHMOD_FILE) ) {
				echo '<div class="error"><p>Error saving file!</p></div>';
			} else {
				echo '<div class="updated"><p><a href="'.$fileurl.' " target="_blank">Open Batch Report!</a> (saved in .txt format)</p></div>';
			}
			
		
			
			
			$displayBatchReport = nl2br( $displayBatchReport );
			echo '<hr>';
			echo $displayBatchReport;
			echo '<hr>';
			$import_admin_url = admin_url( 'admin.php?page=import_transaction' );
			echo "<a href='$inport_admin_url'>Add Another Batch!</a>";
		} else {
		
		// Show the form.  
		?>
		<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'import_prep', 'humming-birds-and-bees' ); ?>
			
			<div class="updated"><p>This importer is designed to take a batch of transactions from Paypal and process them. Existing users will have their accounts updated with latest transaction information. Transactions that don't have a user account associated with them will be ignored.</p></div>
			<table class="form-table">
				<tr valign="top">
					<td><label for="users_csv">CSV file</label><br>
						<input type="file" id="users_csv" name="users_csv" value="" class="all-options" /><br>
						<span class="description"><?php echo sprintf( 'You may want to see <a href="%s" target="_blank">the example of the CSV file</a>.', '/migration/import-sample.csv'); ?></span>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="Import" />
			</p>
		</form>
		
		<hr>
		<h3>Other Management Tools</h3>
		<p>Jump to -> 
		<a href="http://dev.trainingdoulas.com/tools/ledger_prep.php" target="_blank">Batch Ledger Tool</a> (opens new window)</p>
		
		
		<?php
		
		}
		
		
		
		$this->admin_footer();
	} 

	/**
	 * PREPARE IMPORT CSV
	 *
	 * @since 2.0
	 *
	 * -This is a new function. 
	 * -It prepares contents from CSV file to be considered on the import screen before processing the import
	 *			
	 *
	 * Called in $this->load_import_transaction_editor, approx line 1026
	 *
	 *
	 **/ 
	public function prepare_import_csv() {
		
		$results = array();
		// GET FILE NAME 
		if ( isset( $_FILES['users_csv']['tmp_name'] ) ) {
			// Setup settings variables
			
			$filename = $_FILES['users_csv']['tmp_name'];
			
			if(!file_exists($filename)){
				echo "Error reading file!";
				exit;
			} else {
				$file = fopen($filename, "r");
				
				if(!$file){
					echo "Trouble reading file. sorry!";
					exit;
				} else {
					//echo "File is opened and we're ready to proceed!";
				}
			}


			$first = true;
			
			while(($line = fgetcsv($file)) !== false){
				
				// If not empty and the first line
				if( !empty( $line ) && $first ){
					for($i = 0; $i < sizeof($line); $i++)
					$line[$i] = str_replace( ' ', '_', trim( $line[$i] ) );
					$headers = $line;
					$first = false;
					continue;
				
				// If the first line is empty, abort
				}elseif( empty( $line ) && $first ){// If we are on the first line, the columns are the headers
					break;
				}
				
				//If the line is not empty combine it with the header. 
				if( !empty( $line[0] ) ){
					$line = array_combine($headers, $line);
					
					
					//Find the registered student that this transaction applies to. 
					//Will return false if the transaction is not associated with a student.
					$stud = new Student();
					$student = $stud->get_student_from_paypal($line); 
					
					//If the student does not return with the WP_USER object then we assume that the transaction is not associated with a user. 
					//This is a much needed filter on this particular function to prevent random user insertions into the database. 
					if( !is_a( $student, 'WP_User' ) ){
										
						$results[] = array(
						'notice' => "There is no account associated with this transaction. It will not be inserted. For reference, Name is: {$line['Name']}" );
							
					} else {
					
						$sid = $student->ID;
						$trans = new Transaction($sid);
						
						$results[] = $trans->prep_import( $line );
						
					}
				} else {
					//Line[0] has an empty value. Caused by extra lines inserted at the bottom of the CSV File. Just move on. 
					continue;
				}
			}			
		} else {
			
			$results['error'] = "Error: No file was selected.";
		}
		
		//We need to generate some Response to the import...		
		return $results;
	}



	 
	
	
	/**
	 * PROCESS IMPORT CSV
	 *
	 * @since 1.0
	 *
	 * -Step one: Prepare content of CSV File for import. 
	 *		-Collect CSV information, then prep it for how it will be imported into the student accounts
	 * -Step two: Display information to be imported for consideration before importing. 
	 *		-Is this a new student/entry? 
	 *      -List all actions that will be preformed on the student's account
	 *  	-Option to ignore or delete a specific line item from prepared list
	 * -Step three: process proposed imports. 
	 *      -Take propsed tasks and process them, one by one. 
	 *		-Prepare batch output file to save to local computer. 
	 *		-Also display results on the screen. 
	 *		-Address Errors, provide quick links to student pages in new window for review of conflicts. 
	 *
	 * @ $filename = string to the filename of the CSV file
	 * @ $args = additional parameters to be considered for what? I'm not sure yet. 
	 * @
	 *
	 *
	 *
	 */
   	public static function process_import_csv( $prepArr ) {
	//Function has been gutted and completely overhauled.
		//echo" Editor::process_import_csv(), function as been called.<br>";
	
		$tResults = array(); //Note the additional 's' on the end of the line.
		
		//Add a date range for the batch report
		
		$len = sizeof($prepArr);
		$last = intval($len - 1);
		//Assuming that the last array entry will always be the start date and visa versa. 
		$start_date = $prepArr[$last]['transArr']['Date'];
		$end_date = $prepArr[0]['transArr']['Date'];
		
		$tResults['dates'] = array(
			'start_date' => $start_date,
			'end_date' => $end_date
		); 
		
		
		
		foreach($prepArr as $pKey => $pArr){
			$sid = $pArr['sid'];
			
			if( !empty( $sid ) ){ //We are only going to insert transactions when student IDs are set. 
				
				//echo" Editor::process_import_csv(), line 1406 SID is not empty.<br>";	
				//print_pre( $pArr['transArr'] );
				$trans = new Transaction($sid);
				$trans->paypal_import_prep( $pArr['transArr'] );	
				$tResult = $trans->process_transaction(); // not new user, not override, don't display results, so no optional parameters are being passed.
				
				if( !empty( $tResult ) )
					$tResults[] = $tResult;
				
			}
		}
		/* echo "<br> editor::process_import_csv (line 1395) This should be sent back to the editor screen to process the results. The value of the tResults array is: ";
		print_pre($tResults); */
		
		return( !empty( $tResults ) )? $tResults : false ;
		
	}

	
	
	
	
	/**
	 * Log errors to a file
	 *
	 * @since 0.2
	 *
	 * This function needs to be tested. 
	 **/
 	private function log_errors( $errors ) {
		if ( empty( $errors ) )
			return;

		$log = @fopen( $this->$log_dir_path . 'doula_csv_import_errors.log', 'a' );
		@fwrite( $log, sprintf(  'BEGIN %s', date( 'Y-m-d H:i:s', time() ) ) . "\n" );

		foreach ( $errors as $key => $error ) {
			$line = $key + 1;
			$message = $error->get_error_message();
			@fwrite( $log, sprintf( '[Line %1$s] %2$s', $line, $message ) . "\n" );
		}

		@fclose( $log );
	} 

	
	/*
	 * NB ADMIN HEADER
	 *
	 * @since 1.0
	 **/

	private function admin_header( $pTitle , $addNewLink = NULL ){
		
		echo '<div class="wrap">';
		screen_icon(); 
		echo '<h2>'.$pTitle;
		
		if( $addNewLink != null )
				echo'<a class="add-new-h2" href="admin.php?page='. $addNewLink .'">Add New</a>';
		echo '</h2>';	
		
	}


	
			
		
	/*
	 * NB ADMIN FOOTER
	 *
	 * @since 1.0
	 **/

	private function admin_footer(){

		echo '</div><!-- .wrap -->';

	}


	
			
		
	/*
	 * NB STUDENT OVERVIEW HEADER
	 *
	 * @since 1.0
	 **/

	private function student_overview_header(){ //$user_query

		$this->admin_header('Students Overview', 'add_student'); 
		
		/* echo '
		<ul class="subsubsub">
			<li class="all"><a '.$this->cur_page('all').' href="admin.php?page=students">All<!-- <span class="count">(83)</span> --></a> |</li>
			<li class="current"><a '.$this->cur_page('current').'  href="admin.php?page=students&amp;student_type=current">Current<!-- <span class="count">(83)</span> --></a> |</li>
			<li class="inactive"><a '.$this->cur_page('inactive').'  href="admin.php?page=students&amp;student_type=inactive">Inactive<!-- <span class="count">(83)</span> --></a> |</li>
			<li class="alumni"><a '.$this->cur_page('alumni').'  href="admin.php?page=students&amp;student_type=alumni">Alumni<!-- <span class="count">(83)</span> --></a></li>
		</ul>'; */
		
	}

	
			
		
	/*
	 * NB CUR PAGE
	 *
	 * @since 1.0
	 **/

	private function cur_page($cur_page){
		global $student_type;
		if($cur_page == $student_type){
			return ' class="current" ';
		}
	}


	
			
		
	/*
	 * NB SELECT FORMS
	 *
	 * @since 1.0
	 **/

	private function select_forms( array $selectArr, $selectID, $sPostValue = null ){

		echo '<select  id="'.$selectID.'" name="'.$selectID.'" >';
		
		foreach($selectArr as $selKey => $selVal){
			echo '<option value="'.$selKey.'" ';
			
			if( ($sPostValue != null) && ($sPostValue == $selKey) ) echo 'selected ';
			
			echo '>'.$selVal.'</option>';
		}	

		echo'</select>';
	}
	

	
	
	/*
	 * ADD Grade Key Value translator
	 *
	 * @since 1.1
	 **/
	 
	public function gradeKeyVal($gradeKey){
		
		$gk = substr($gradeKey, 0, 2);
		
		$uNum = ( strlen($gradeKey) == 4 )? substr($gradeKey, 3, 1) : NULL ;
		
		switch($gk){
			case 'mc':
				return 'Main Course, Unit '.$uNum;
			case 'cb':
				return 'Childbirth Course, Unit '.$uNum;
			case 'da':
				return 'Doula Actions';
			case 'bp':
				return 'Birth Packet';
			default:
				return NULL;
		
		}
	}
	

	
	/**
	*
	*  LOCATION SEARCH 
	*
	*  
	*
	**/
	
	public function load_location_search(){
		
		//add a query string that makes it so that you can search for other students by the location of the current student. 
		//Instruction on what to do with Search Results.
		
		//if query string not set, just display empty search bar with instructions. 
		//Add toggle for active verse inactive students/alumni. 
		
		//Search results should include: Student ID: Student name (link to student details),email address (maybe to prepopulated email), city/state/country (link to a google map), registration date (newest to oldest), status (student/alumni/active), number of assignments completed (ratio). 
		
		if ( ! current_user_can( 'create_users' ) )
			wp_die('You do not have sufficient permissions to access this page.' );			
			
		$this->admin_header( "Student/Alumni Location Search" );
		
		//First check to see if we have something to display for review.
		if ( isset( $_REQUEST[ 'location_search' ] ) && !empty( $_REQUEST[ 'location_search' ] ) ){
			
			$location = $_REQUEST[ 'location_search' ];
			
			
			
			
		}elseif( isset( $_REQUEST[ 'student_id' ] ) && !empty( $_REQUEST[ 'student_id' ] ) ){
			$sid = $_REQUEST[ 'student_id' ];
			
			$student = get_user_by( 'id', $sid );
			
			$location = $student->student_state;

			$student_place =  $student->student_city.", ". $student->student_state.", ".$student->student_country ;
				
		}
		
		
		// Show the form.  
		?>
		<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'location_search', 'humming-birds-and-bees' ); ?>
			
			
			<?php 
				$updated = '';
				
				if( isset( $student ) ){
					$updated .= "<b>User:</b> {$student->display_name}<br />";
					
					
				}
				if( isset( $student_place ) ){
					$updated .= "<b>Location:</b> ". $student_place;
					
				}elseif( isset( $location ) ){
					$updated .= "<b>Location:</b> ". $location;	
					
				}
				
				if( !empty( $updated ) ){
					echo '<div class="updated"><p>';
					echo $updated;
					echo '</p></div>';
					
				}
			?>
			
			
			
			<label for="location_search">Location Search</label><br>
			<input type="input" id="location_search" name="location_search" value="<?php echo $location; ?>" class="" /><input type="submit" class="button-primary" value="Locate" />
			
			<br /><small>(Search for all students within a given state or province by entering a two letter geo-code. Ex. AZ for Arizona).</small>
					
			
		</form>
		
		<hr>
		
		<?php 
		 
		 //Show students from newest to oldest for $location.
		 
		 //
		 if( !empty( $location ) ){
			
			$location_args = array(
				'order' => 'ASC',
				'orderby' => 'ID',
				'meta_key' => 'student_state',
				'meta_value' => $location
				
			 );
			 
			 $user_query = new WP_User_Query( $location_args );
			 
			 $locals = array_reverse( $user_query->get_results() );			
			 
		 } else {
			
			$locals = null;
			 
		 }
		 
		 
		 print('
		 
		 <table>
			<thead>
				<tr>
					<td>ID</td>
					<td>Name</td>
					<td>Email</td>
					<td>Location</td>
					<td>Reg. Date</td>
					<td>Status</td>
					<td>Asmts. Completed</td>
					
				</tr>
			</thead>
			<tbody>
		 ');
		 
		 if( !empty( $locals ) ){
			foreach( $locals as $local ){
				
				$local_place =  $local->student_city.", ". $local->student_state.", ".$local->student_country ;
				 
				 //Student ID: Student name (link to student details),email address (maybe to prepopulated email), city/state/country (link to a google map), registration date (newest to oldest), status (student/alumni/active), number of assignments completed (ratio). 
				 $goog_local_place = str_replace(' ', '+', $local_place );
				
				//Get local status
				$local_status = implode(', ', $local->roles);
				
				switch( $local_status ){
					case "alumnus_active":
					case "student_full_active":
					case "student_partial_active":
						$row_color = "Green";
						break;
					
					default:
						$row_color = "LightGray";
						break;
				}
				
				
				//Get assignments completed per student. 
				$asmt = new Assignment( $local->ID);
				$prg_arr = $asmt->get_progress_report();
				 
				$percentComplete = ( !empty( $prg_arr['percentComplete'] ) )? $prg_arr['percentComplete'] : 0 ;
				$completedAsmts = ( !empty( $prg_arr['completedAsmt']) )? $prg_arr['completedAsmt'] : 0 ;
				$totalAsmt = ( !empty( $prg_arr['totalAsmt']) )? $prg_arr['totalAsmt'] : 0 ; 
				 
				$asmts_completed = "<span class='progress-asmt-ratio'>(".$completedAsmts."/".$totalAsmt.")</span>";
				 
				if( ( $completedAsmts <= 5 ) && ( $row_color == "Green" ) ){
					
					$row_color = "Orange";	
				
				}
				 
				print( "<tr style='color:{$row_color};'>" ); 
				
				 
				$local_user_link = "<a href='/wp-admin/admin.php?page=edit_student&student_id={$local->ID}' target='_blank'>{$local->display_name}</a>";  
				
				if( isset(  $student  ) ){
					//If I want directions from one place to another. 
					//http://stackoverflow.com/questions/3217068/is-there-a-way-to-pass-arguments-to-google-maps-get-directions-functionality
					
					
					$student_place =  $student->student_city.", ". $student->student_state.", ".$student->student_country ;
					
					$goog_student_place = str_replace(' ', '+', $student_place );
					
					//http://maps.google.com/maps?saddr=new+york&daddr=baltimore
					
					$goog_map_url = "<a href='https://maps.google.com/maps?saddr=". $goog_student_place ."&daddr=".$goog_local_place."' title='View Google Map' target='_blank'>". $local_place ."</a>";
					
					
				}else{
					
					$goog_map_url = "<a href='https://www.google.com/maps/place/". $goog_local_place ."' title='View Google Map' target='_blank'>". $local_place ."</a>";					
				}
				

				
				
				$table_array = array(
				
					'ID'=> $local->ID,
					'name'=> $local_user_link,
					'email'=> $local->user_email,
					'location'=> $goog_map_url,
					'reg-date'=> $local->user_registered,
					'status'=> implode(', ', $local->roles),
					'asmts-submit'=> $asmts_completed	
					 
				);
				 
				foreach( $table_array as $td){

					print( "<td>{$td}</td>" );
					 
				}
				 				 
				 //print( "<p> Student ID:".  . " Student Name: ". $local->display_name. " email address: ". $local->user_email." city/state/country: ".$local_place."<br />
				 //<a href='https://www.google.com/maps/place/". $goog_local_place ."' target='_blank'>View Google Map for ".$local_place."</a>
				 //</p>");
				print( "</tr>" ); 
			 } 
			 
				 
		 } else {
			
			print( '<tr><td colspan="7"> 
				<em><small>(No results for requested search query. Please try again.) </small></em>
			</td></tr>' );
			 
		 }
		 
		 print('</tbody></table>');
		 
		 //print_pre( $locals );
		
		//STOPPED HERE add $this->location_search_result_line( $sid ); for each result. 
		
		$this->admin_footer();
		
		
	}
	
	
	/**
	 * MAINTENANCE EDITOR
	 *
	 * @since 2.0
	 *
	 * Description: Used for running test cases on specific
	 *		functions.
	 *
	 *
	 *
	 * Called in Editor::add_admin_pages()
	 *
	 **/	
	public function load_maintenance_editor(){
		global $wpdb;
		$maintain_path = ABSPATH.'../../maintenance/';
		
	 	echo "<h1>Test Window</h1>";
		//START TESTING
		include_once( $maintain_path."test-window.php" );
		
	}
	
	

	public function batchReport($resultsArr, $sub = false ){
		$batchReport = '';
		foreach($resultsArr as $rKey => $rVal){
			if( is_array( $rVal ) ){
				if ( $rKey === 'add_mess') $sub = true;
				$batchReport .= $this->batchReport( $rVal, $sub );
				continue;
			}	else {
				if( empty($rVal) ) continue;
				if( $sub ) $batchReport .= ' - ';
				
				$batchReport .= $rVal."\n";	
			} 
		}
		$sub = false;
		$batchReport .= "\n";
		
		return $batchReport;
	}  
	
	
	public function check_batch_file_exists( $file, $i ){
		
		 if( is_file( $file ) || file_exists( $file ) || $this->is_url_exist( $file ) ){
			
			if( strcmp( '-', substr( $file, -6, 1 ) ) === 0 ){
				$file = substr_replace( $file, '-'.$i.'.txt', -6 );
			} else {
				$file = str_replace('.txt', '-'.$i.'.txt', $file );
			}
			
			$i++;
			$file = $this->check_batch_file_exists( $file, $i );
		} 
		
		return $file;
	}
	
	//Credit: http://stackoverflow.com/questions/7684771/how-check-if-file-exists-from-web-address-url-in-php
	public function is_url_exist($url){
		$ch = curl_init($url);    
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($code == 200){
		   $status = true;
		}else{
		  $status = false;
		}
		curl_close($ch);
	   return $status;
	}

	private static function update_admin_notes($notes = NULL, $sid = 0 , $admin_actions = NULL ){
		
		if( !empty( $admin_actions ) ){
			//Process Admin Requests in array. 
			/* $student_proc = new Student();
			$student_proc->sid = $sid;
			
			$student_proc->process_student($admin_actions); */
		}
		
		if( !empty( $notes ) && !empty( $sid ) ){
			
			//Get current date
			$note_date = date( 'j M Y' );
			$admin_note = "\r\n".$note_date." - ".$notes;
			
			$current_admin_notes = get_user_meta($sid, 'admin_notes', TRUE);
			
			$updated_admin_notes = $current_admin_notes . $admin_note;
			
				
			$updated_id = update_user_meta($sid, 'admin_notes', $updated_admin_notes, $current_admin_notes);
			
			return $updated_id;
		}
	}
	
	
	
	
	/**
	 * TEST WINDOW EDITOR
	 *
	 * @since 2.0
	 *
	 * Description: Used for running test cases on specific
	 *		functions.
	 *
	 *
	 *
	 * Called in Editor::add_admin_pages()
	 *
	 **/	
	public function load_test_window_editor(){
		global $wpdb;
		//$maintain_path = ABSPATH.'../../maintenance/';
		
	 	echo "<h1>Test Window</h1>
		<p> Sending Emails today: </p>
		
		
		";
		/* 
		$emlSubject = "Test Email being sent from Dev";
		$emlMsg = "
Thank you for your payment to New Beginnings Doula
Training. A copy of your payment receipt is included
below for your records. \r\n
-----------------------------------------------------  ";
		
		$nbEmailConfirm = new message();
		$nbEmailConfirm->subject = $emlSubject;
		$nbEmailConfirm->content = $emlMsg;
		$nbEmailConfirm->recipient = 163; //Brent's Admin user Id on CRSDEV. 
			
		$mlRslts = $nbEmailConfirm->send_email(); */
		
		$emlWho = 163; 
		
		$trans = new transaction($emlWho);
	
		$emlWhat = array(
			'nbti'			=> '1234',
			'payment_date'	=> '2017-09-08 00:12:25',
			'first_name' 	=> 'Test',
			'last_name'		=> 'Userwithlongname',
			'payer_email' 	=> 'test@trainingdoulas.com',
			'mc_gross' 		=> '50.00',
			'item_name' 	=> 'Monthly Registration',
			'txn_type' 		=> 'recurring_payment',
			'txn_id' 		=> '0123456789123',
			'payment_status' => 'Complete'
		); //paypal_ipn_array
		$emlWhy = 'new_student';
		$emailed = $trans->email_receipt($emlWho, $emlWhat, $emlWhy);
		
			
			
		//$msg = new message();
		
		//set user, subject, message contents. 
		
		//reference: Comment Object Schema
		/* 
			author 	The id of the user object, if author was a user.
			author_email 	Email address for the object author.
			author_ip 	IP address for the object author.
			author_name 	Display name for the object author.
			author_url 	URL for the object author.
			content 	The content for the object.
			date 	The date the object was published.
			date_gmt 	The date the object was published as GMT.
			karma 	Karma for the object.
			parent 	The id for the parent of the object.
			post 	The id of the associated post object.
			status 	State of the object.
			type 	Type of Comment for the object.
			meta 	Meta fields.
		*/
		
		
		
		echo ( $emailed )?'Email was sent successfully!':'Email failed to send! :(';
	
		
	}
	
	
	
}
?>