<?php

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Pages;

if ( !defined( 'ABSPATH' ) ) { exit; }

$pages = new Pages(
	[
		'complete_registration',
		'payment_complete',
		'progress_report',
		'profile_editor',
		'billing_overview',
		'billing_details',
		'course_extension',
		'account_payoff',
		'cancel_recurring',
		'cancel_manual',
		'cancel_account',
		'reactivate_account',
		'renew_certification',
		'pdf_manuals',
	]
);

$pages->build();


//Styles
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'doula-course-styles', plugins_url( 'doula-course/templates/styles.css' ), false );
} );

/*-----------------*/


/*-----------------*/
//A function to get all the meta data for the student to display and manipulate. 
function get_student_meta(){
	global $current_user;

	return ( $current_user->ID != null)? get_userdata( $current_user->ID ):NULL; 
	/* if( $current_user->ID != null){
		$sid = $current_user->ID;
		$student = get_userdata($sid); 
	}
	return $student; */
}


/*-----------------*/
function gradeKeyVal($gradeKey){
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


?>