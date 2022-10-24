<?php
//Widgets for NB Doula Courses

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Student_Progress extends \WP_Widget {


	
	/**
	 *  Title:
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns VOID
	 */	 
	 
	public function __construct() {
		
		$widget_ops = array( 
			'classname' => 'Student_Progress',
			'description' => 'Provides an overview of individual student progress.',
		);
		parent::__construct( 'student_progress', ' Student Progress', $widget_ops );
		
	}

	
	/**
	 *  Title:
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns VOID
	 */	 
	 
	public function widget( $args, $instance ) {
		global $current_user, $post;
		extract( $args );
		$user_id = $current_user->ID ?? 0;					 
		
		
		//an extracted arg:	
		echo $before_widget;
		
		// Display the widget
		echo '<div class="widget-text student_progress_box">';
		
		//two more extracted args:	
		echo $before_title . "Student Progress" . $after_title;

		//Bulk of Widget Goes Here
		if( empty( $user_id ) ):
			
			echo "<p>Please login to continue:</p>";
			echo "<div class='check-in-btn'><a class='button' href='/check-in/'>Log In &raquo;</a></div>";
			
		else:
		
		?>
		<div class="textwidget" id="status_string"><?php //echo $this->student_status_message(); ?>
		<?php
		/* if( is_object( $post ) )
			$this->bookmark_widget( $post, $user_id ); */
		?>
		
		</div>
		
		<div class="textwidget" id="progress_bar">
			<h4>Progress Summary</h4>
			
		<?php 
			// $this->progress_widget( $user_id );

		?> 
			<br><a class="button" href="/progress-report/">Progress Report &raquo;</a>
		</div>
	
		<div class="textwidget" id="billing_status">
			<h4>Billing Information</h4>
			
		<?php 	
			echo "<p>Our billing systems are in transition at this point in time. Click on the \"Billing Overview\" button for more details on your account.</p>";
			echo "<div class='billing-details-btn'><a class='button' href='/account/'>Billing Details &raquo;</a></div>";
		
		
		endif;
		echo '</div>
			  <div class="textwidget last">
			    <p><strong>Having website troubles?</strong>
				<br><a href="/send-feedback/">Send Feedback</a></p>
			  </div>';
		echo $after_widget; 
	}
	
	
		
	/**
	 *  Title: student_status_message
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns STRING
	 */	 
	private function student_status_message( ):  STRING
	{
		// Determine Account Active Status 
		$active = current_user_can( 'student_current' );
			
		return ( !$active )? "Your account is currently marked as <strong>inactive</strong>. <a href='/inactive-student-notice/'>Get details.</a>" : 'Your account is active!' ;
		
	}	
	
		
	/**
	 *  Title: bookmark_widget
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns VOID
	 */	 
	private function bookmark_widget( object $post, int $user_id )
	{
		//Display only on non-course pages. 
		if( isset( $post ) && $post->post_type !== 'sw' ){
			$bkmrk_id = get_user_meta( $user_id, 'course_bookmarks', true );
			
			if( !empty( $bkmrk_id ) ){
				$bkmrk_resume = get_post( $bkmrk_id );
				echo "<hr><h4>Course Bookmark</h4><p><small>You last visited <strong>{$bkmrk_resume->post_title}</strong></small></p><a class='button' href='?p={$bkmrk_resume->ID}/'>RESUME &raquo;</a>"; 
			}			
		}
		
	}	
	
		
	/**
	 *  Title:
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns VOID
	 */	 
	private function progress_widget( int $user_id )	
	{
	
		$asmt = new Assignment( $user_id );
		$prg_arr = $asmt->get_progress_report();
	
		$percentComplete = ( !empty( $prg_arr['percentComplete'] ) )? $prg_arr['percentComplete'] : 0 ;
		$completedAsmts = ( !empty( $prg_arr['completedAsmt']) )? $prg_arr['completedAsmt'] : 0 ;
		$totalAsmt = ( !empty( $prg_arr['totalAsmt']) )? $prg_arr['totalAsmt'] : 0 ;			
		
		
		?>
		<span>Doula Certification</span>
		<div class='progress-mtr-wrap'>
			<div class="progress-mtr">
				<span class="progress-mtr-bar" style="width:<?php echo $percentComplete; ?>%;">&nbsp;</span>
				<span class="progress-mtr-text"><?php echo $percentComplete."% Complete"; ?></span>
			</div>
		</div>
		
		<?php 
		
		echo "<span class='progress-asmt-ratio'>(".$completedAsmts."/".$totalAsmt." Assignments Completed)</span>";
				
	}	
	
		
	/**
	 *  Title:
	 *	
	 *	Description: 
	 * 	
	 *
	 *	Returns VOID
	 */	 
	private function _()
	
	{
		
		
	}
}


?>