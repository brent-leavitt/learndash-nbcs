<?php

namespace Doula_Course\App\Tmpl\Pages;

use Doula_Course\App\Clss\Grades\Grades;

// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;	

//START SETUP
$asmt_url = home_url( '/?p=' ); 
	
$student_id = get_current_user_id();	
$student = get_user_by( 'id', $student_id );

if( class_exists( 'Doula_Course\App\Clss\Grades\Grades' ) ){

	$grades = new Grades( );
	$grades->build( $student_id );
	$asmt_map = $grades->get_map();
	$tracks = $grades->get_tracks();
}
//END SETUP 	

	
$report_op = "<p>Below you will find a list of assignments required to complete your doula training and your current status for each assignment according to our records. If you find that our records appear to be incomplete or inaccurate, please contact us so that we may update your account accordingly. </p>
<p>Student Name: <em>{$student->first_name} {$student->last_name}</em></p>";


// First Start with the Tracks	
foreach( $tracks as $track_id => $courses ){
	$report_op .= "<h2>". get_the_title( $track_id ) ."</h2>";	//Track Name
	
	$report_op .= "<table class='form-table nb-student-reports nb-table' >";
	
	foreach( $courses as $course_id ){
		$course = $asmt_map->get_course( $course_id ); //The object of a given course
		
		if( !is_null( $course ) ){
			$course_title = $course->get_title();
			
			foreach( $course->get_children() as $section ){
				$report_op .="<tr>
					<th colspan='2'>
						<h4>". $course_title.", ".$section->get_title()  ."</h4>
					</th>
				</tr>
				<tr class='meta-info'>
					<td><em>assignment name</em></td>
					<td><em>status</em></td>
				</tr>";

				//List Assignments and Status
				foreach($section->get_children() as $asmt ){
					
					$asmt_id = $asmt->get_id();
					$grade = $grades->get_grade_by_id( $asmt_id );
					$status_class = ( !empty( $grade ) )? $grade->get_status(): 'empty' ;
					$status_text = ( !empty( $grade ) )? $grade->get_status(): '(no status)' ;
					
					$report_op .="<tr>
						<td><a href='{$asmt_url}{$asmt_id}' target='_blank'>{$asmt->get_title()}</a>	
						</td><td><span class='{$status_class}'> {$status_text}</span></td>
					</tr>";
				}
			}
		}
	}
	$report_op .='</table>';
}
echo $report_op;
?>