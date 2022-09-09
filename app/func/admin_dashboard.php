<?php 

namespace Doula_Course\App\Func;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * These functions load and build the admin dashboard widgets.  
 * A check is in place to call to see if admin pages are being loaded before this file is loaded. 
 */
 

/**
 *  add_admin_dashboard_widgets
 *	
 *	The action hook that builds the array of admin dahsboard widgets used in the doula course. 
 *	
 * 	
 *	Returns VOID
 */	

function add_admin_dashboard_widgets() {
	
	$widgets = [
		'assignment'	=>	'Assignments Manager',
		'message'		=> 	'Recent Messages',
		'billing' 		=> 	'Billing Manager'		
	];
	
	foreach( $widgets as $slug => $title )
		admin_dashboard_widget_builder( $slug, $title );

}

add_action( 'wp_dashboard_setup', 'Doula_Course\App\Func\add_admin_dashboard_widgets' );


/**
 *  admin_dashboard_widget_builder
 *	
 *	Builds the call to the wp_add_dashboard_widget function. 
 *	
 * 	
 *	Returns VOID
 */	

function admin_dashboard_widget_builder( $slug, $title ){
	
	wp_add_dashboard_widget(
		"{$slug}_widget",         
		$title,        
		"Doula_Course\App\Func\\{$slug}_admin_widget" // Display function.
	);	
}


/**
 *  assignment_admin_widget
 *	
 *	Gives an overview of assignments needing action from trainers. 
 * 	
 *
 *	Returns 
 */	
 
function assignment_admin_widget() {

	//Count Assignments Needing Action. 
	$asmts = wp_count_posts('assignment');
	
	$submitted =  ( isset( $asmts->submitted ) )? intval( $asmts->submitted ): 0 ;
	$resubmitted = ( isset( $asmts->resubmitted ) )? intval( $asmts->resubmitted ) : 0 ;
	$asmt_url = admin_url('edit.php?post_type=assignment');
	$total_asmts = $submitted + $resubmitted;
	
	$string = 'There ';
	
	if( $total_asmts == 0 ){
	
		$string .= "are <em>NO ASSIGNMENTS</em> to be graded at this time!";
		
	} else {
	
		$string .= ( $total_asmts > 1 )? 'are ':'is ';
		$s_end = ( $submitted > 1 )? 's':'';
		$string .= ( $submitted > 0 )? "<em>$submitted new assignment$s_end</em> ":"";
		$string .= ( ( $submitted > 0 ) && ( $resubmitted > 0  ) )?'and ': '';
		$s_end = ( $resubmitted > 1 )?'s':'';
		$string .= ( $resubmitted > 0 )?"<em>$resubmitted resubmitted assignment$s_end</em> ":"";
		$string .= 'that ';
		$string .= ( $total_asmts > 1 )? 'are ':'is ';
		$string .= "ready to be graded!"; /**/
		
	}
	
	echo "<h2>$string</h2>
		<p><a href='$asmt_url'>Go To Assignments &rarr; </a></p>";
}


/**
 *  message_admin_widget
 *	
 *	A quick view of student messages for trainer review.
 * 	
 *
 *	Returns VOID
 */	

function message_admin_widget() {
	global $wpdb; 
	$admin_url = admin_url('/admin-post.php?action=message_dismiss&message_id=');
	$msgs_url = admin_url( 'admin.php?page=admin_messages' );
	$rcnt_msgs = $wpdb->get_results( "SELECT SQL_CALC_FOUND_ROWS * FROM nb_messages WHERE message_recipient=1 AND message_active = 'y' LIMIT 10" );
	$actv_msg_count = $wpdb->get_var( "SELECT FOUND_ROWS()" );
	
	// Display whatever it is you want to show.
	echo "<ul>";
	foreach( $rcnt_msgs as $actv_msg  ){
		echo "<li><small><em>{$actv_msg->message_date}</em></small><br>
		{$actv_msg->message_content}<span> | <a href='{$admin_url}{$actv_msg->message_id}'>dismiss x</a></span></li>";
		 //<span><a href='#'>View &rsaquo;</a></span> |
	}
	echo "</ul>
	<h3>Total Active Messages: {$actv_msg_count}</h3>
	<hr>
	<p><a href='$msgs_url'>View All Messages &rarr; </a></p>";
}


/**
 *  billing_admin_widget
 *	
 * 	Displays an overview of recent billing activities. 
 *	
 * 	
 *	Returns VOID
 */	

function billing_admin_widget() {

	// Display whatever it is you want to show.
	echo "<h3>Accounts Requiring Action</h3>";
	echo "<p>Holding queue.</p>";
	echo "<h3>Recent Billing Activities</h3>";
	echo "<p>Holding bay</p>";
}


/**
 *  dismiss_message
 *	
 *	Dismisses active messages and marks them as inactive in the database. 
 *	
 * 	
 *
 *	Returns VOID
 */	

function dismiss_message() {
	 global $wpdb;

	 if( isset( $_REQUEST[ 'message_id' ] ) ){
		$return_url = $_SERVER[ 'HTTP_REFERER' ];
		$msg_id = $_REQUEST[ 'message_id' ];
			
		$updated = $wpdb->update( 'nb_messages', array( 'message_active' => 'n' ),  array( 'message_id' => $msg_id ), array( '%s' ), array( '%d' ) );	
		
		if( empty( $updated ) ){
			
			$subject = 'NB Dismiss Message Function Error';
			$message = 'This is an WEB ADMIN NOTICE. \r\n The NB dismiss message function failed to update to the database. This message is sent from the admin.php file in the doula-training plugin, on line 137.';
			$msgr = new Message();
			$msgr->admin_notice( $subject, $message );
		}
		
		wp_redirect( $return_url );
		exit;
	} 
}

add_action( 'admin_post_message_dismiss', 'Doula_Course\App\Func\dismiss_message' );

?>
