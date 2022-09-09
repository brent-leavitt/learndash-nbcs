<?php
/**
* Plugin Name: LearnDash for New Beginnings Childbirth Services
* Plugin URI: http://learn.trainingdoulas.com/
* Description: Customization of LearnDash to Work with NBCS WorkFlows 
* Version: 2.0
* Author: Brent Leavitt
* Author URI: https://www.trainingdoulas.com/ 
* License: Open Source
*/

namespace Doula_Course;

use Doula_Course\App\Clss\Course as Course;

if ( ! defined( 'ABSPATH' ) ) { exit; }

if( !defined( 'DOULA_COURSE_PATH' ) )
	define( 'DOULA_COURSE_PATH', plugin_dir_path( __FILE__ ) );

if( !defined( 'DOULA_COURSE_VERSION' ) )
	define( 'DOULA_COURSE_VERSION', '2.0' );

if( !defined( 'NBCS_PREFIX' ) )
	define( 'NBCS_PREFIX', 'nbcs_' );

if( !defined( 'NBCS_TD' ) )
	define( 'NBCS_TD', 'doula_course' );

/** 
 * Activation/Setups
 **/
 
function doula_course_activation (){
	
	do_action( 'doula_course_activate' );
	
	wp_schedule_event( time(), 'hourly', 'nb_test_cron_hook' );
}

register_activation_hook( __FILE__, '\Doula_Course\doula_course_activation' );

 
/** 
 * Dectivation/Cleanups
 **/
 
function doula_course_deactivation (){
	
		
	do_action( 'doula_course_deactivate' );
	
	wp_clear_scheduled_hook('nb_test_cron_hook');
	
}

register_deactivation_hook( __FILE__, '\Doula_Course\doula_course_deactivation' );


 

require_once( 'app/clss/course.php' );
$course = new Course();

$course->go(); 

?>