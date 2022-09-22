<?php 
namespace Doula_Course\App\Tmpl;

use Doula_Course\App\Clss\Grades\Grades;
use Doula_Course\App\Func\get_asmt_admin_url;

$student_id = 0;
$program_id = 0; 
$message = null;
$gradesData = array();
$studData = array();


//Start by processing current grades that have been added. 
if( isset($_GET['student_id']) ){
	$student_id = $_GET['student_id'];
	
	$grades = new Grades();
	$grades->build( $student_id );
	
	if( isset($_GET['program_id']) )
		$program_id = $_GET['program_id'];
	else
		$program_id = key( $grades->get_tracks() );
	
	if ( !empty($_POST) && check_admin_referer('edit_grades','grades-check') ){

		$asmt = new Assignment( $student_id );
		
		unset( $_POST[ 'grades-check' ], $_POST[ '_wp_http_referer' ], $_POST[ 'submit' ] );
		$asmts_updated = $asmt->update_all_asmts( $_POST );			
		
		$message = ( $asmts_updated !== false )? 'Grades have been successfully updated.' : 'Failed to update grades in the database.';
			
		
	}
} 		

if( !empty( $program_id ) )
	load_grades_form( 'Edit Grades', $program_id, $message );  
else
	echo "<p>This student is not currently enrolled in any training programs.</p>"; 


if( !empty( $student_id ) )
		echo '<p><a class="secondary" href="admin.php?page=edit_student&student_id='.$student_id.'"><- go back to student editor</a></p>';
		
		
		
		
		
function load_grades_form( $pageTitle, $program_id, $message = null, $newAction = null ){
	
	$stati = [ 
		"draft" => "No Status", 
		"submitted" => "Submitted", 
		"resubmitted" => "Resubmitted", 
		"incomplete" => "Incomplete", 
		"completed" => "Completed"
	]; 

	
	$sid = ( isset( $_GET[ 'student_id' ] ) )? $_GET[ 'student_id' ] : null ;		
	
	$grades = new Grades();
	$grades->build( $sid );
	
	
	if( isset( $sid ) ){
		$student = get_userdata( $sid );
		$student_name = $student->display_name;
		$pageTitle .= ": <em>$student_name</em>";
	}
		
	$program = NULL; 	
	$map = $grades->get_map()->get_assignments_map();
	
	foreach( $map  as $program_obj ){
		if( strcmp( $program_id, $program_obj->get_id() ) == 0)
			$program = $program_obj;
	}
	if( !empty( $program ) )		
		echo "<h2>{$program->get_title()}</h2>"; 	
		
	if($message != null)
		echo '<div class="updated" id="message"><p>'.$message.'</p></div>';
	
	
	//Small form to select correct program. Defaults to first available program. 
		
	echo'<form method="get" action="admin.php" >
	<input type="hidden" name="page" value="edit_grades" />
	<input type="hidden" name="student_id" value="';
	echo ($sid != null)? intval($sid) : '' ;
	echo'">
		<select name="program_id">';
	foreach( $grades->get_tracks() as $track_id => $track ){
		echo '<option ';
		if( strcmp( $program_id, $track_id ) == 0 )
			echo 'selected="selected" ';
		echo " value='{$track_id}'>".get_the_title( $track_id )."</option> ";  
	} 
	
	echo '</select>'; 
	
	wp_nonce_field('edit_grades','course-check', true);
	
	echo '<input type="submit" value="Switch">
	</form>';
	
	//Load Grades Editor Form
	
	echo'<form method="post" action="admin.php?page=edit_grades&student_id='.intval($sid). '&program_id='. intval( $program_id ) .'"  >';
	
	wp_nonce_field('edit_grades','grades-check', true);
	
	echo '<table class="wp-list-table widefat fixed striped">';
	if( !empty( $program ) )
		foreach( $program->get_children() as $unit ){
				

				echo "<thead><tr>
					<th colspan='2'>
						<h4>{$unit->get_title()}</h4>
						
					</th>
				</tr>
				<tr class='meta-info'>
					<th><em>assignment name</em></th>
					<th><em>status</em></th>
				</tr></thead><tbody>
				
				";

			
				foreach( $unit->get_children() as $asmt ){
					//print_pre( $asmt, "<br>This is the ASMT: LINE ".__LINE__  ); 
					$asmt_id = $asmt->get_id(); 
					$asmt_title = $asmt->get_title(); 
					$grade = $grades->get_grade_by_id( $asmt_id );
					
					$status = ( !empty( $grade ) ) ? $grade->get_status() : NULL;
					
					$asmt_admin_url = get_asmt_admin_url( $asmt_id ); 
					
					echo "<tr>
						<td>
							<label for='$asmt_id'>"; 
					echo ( $asmt_admin_url == FALSE )? $asmt_title : "<a href='{$asmt_admin_url}' target='_blank'>{$asmt_title}</a>";
					echo "</label>
						</td><td>
						<select ";
					echo ( $asmt_admin_url == FALSE )?"":"disabled ";	
					echo"name='$asmt_id'>";
					foreach( $stati as $oKey => $oVal ){
						echo "<option value='$oKey' ";
						echo ( strcmp( $status, $oKey ) == 0 )? "selected='selected'" : "" ;
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

	echo '</table>';	
	
	echo '</form>'; 	
	
	
}



/**
 * get_asmt_admin_url
 *
 * @since 2.1
 *
 * Description: generates an admin url for requested assignment. 
 *		
 * Called in tmpl/edit_grades.php::load_grades_form()
 *
 **/	

function get_asmt_admin_url( $course_id ){
	
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
		
?>