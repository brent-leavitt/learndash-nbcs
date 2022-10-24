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
	$asmts = $grades->get_map()->get_assignments_map();
	$my_grades = $grades->get_grades();
}
//END SETUP 	

	
$report_op = "<p>Below you will find a list of assignments required to complete your doula training and your current status for each assignment according to our records. If you find that our records appear to be incomplete or inaccurate, please contact us so that we may update your account accordingly. </p>
<p>Student Name: <em>{$student->first_name} {$student->last_name}</em></p>";


// First Start with the Tracks	
foreach( $asmts as $program ){
	$report_op .= "<h2>". $program->get_title() ."</h2>";	//Program Name
	
	$report_op .= "<table class='form-table nb-student-reports nb-table' >";
	
	
	
	foreach( $program->get_children() as $course ){
		//$course = $asmt_map->get_course( $course_id ); //The object of a given course
		
		if( !is_null( $course ) ){
			$course_title = $course->get_title();
			$report_op .="<tr>
					<th colspan='2'>
						<h4>". $course_title. "</h4>
					</th>
				</tr>
				<tr class='meta-info'>
					<td><em>assignment name</em></td>
					<td><em>status</em></td>
				</tr>";
			foreach( $course->get_children() as $assignment ){
			
					$asmt_id = $assignment->get_id();
					$grade = $grades->get_grade_by_id( $asmt_id );
					$status_class = ( !empty( $grade ) )? $grade->get_status(): 'empty' ;
					$status_text = ( !empty( $grade ) )? $grade->get_status(): '(no status)' ;
					
					$report_op .="<tr>
						<td><a href='{$asmt_url}{$asmt_id}' target='_blank'>{$assignment->get_title()}</a>	
						</td><td><span class='{$status_class}'> {$status_text}</span></td>
					</tr>";
			}
		}
	}
	$report_op .='</table>';
}
print( $report_op );
?>