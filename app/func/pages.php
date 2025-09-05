<?php

namespace Doula_Course\App\Func;

use Doula_Course\App\Clss\Pages;

if ( !defined( 'ABSPATH' ) ) { exit; }

$pages = new Pages(
	[
		'pdf_manuals',
		'progress_report',
		/* 'complete_registration',
		'payment_complete', */
		/* 'profile_editor',
		'billing_overview',
		'billing_details',
		'course_extension',
		'account_payoff',
		'cancel_recurring',
		'cancel_manual',
		'cancel_account',
		'reactivate_account',
		'renew_certification', */
	]
);

$pages->build();


//Styles
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style( 'doula-course-styles', plugins_url( 'learndash-nbcs/app/tmpl/styles.css' ), false );
	wp_enqueue_script( 'learndash-nbcs-js', plugins_url( 'learndash-nbcs/app/tmpl/scripts.js' ), 'jquery', false, true );
} );




?>