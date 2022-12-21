<?php 

namespace Doula_Course\App\Tmpl;

use Doula_Course\App\Clss\Grades\Assignments;
use Doula_Course\App\Clss\Grades\Grades;
use Doula_Course\App\Clss\Grades\Create_Assignments_Map as Creator;
use Doula_Course\App\Clss\Grades\Assignments_Map as Map;



if ( ! defined( 'ABSPATH' ) ) { exit; }

echo "The Sandbox has been loaded! <br>

   
 
	
	
	//  Functions to display a list of all the shortcodes
function diwp_get_list_of_shortcodes(){
 
    // Get the array of all the shortcodes
    global $shortcode_tags;
     
    $shortcodes = $shortcode_tags;
     
    // sort the shortcodes with alphabetical order
    ksort($shortcodes);
     
    $shortcode_output = "<ul>";
     
    foreach ($shortcodes as $shortcode => $value) {
        $shortcode_output = $shortcode_output.'<li>['.$shortcode.']</li>';
    }
     
    $shortcode_output = $shortcode_output. "</ul>";
     
    return $shortcode_output;
 
}
	
//print diwp_get_list_of_shortcodes();
	
	/* 
	$trainers = get_users( [
        'role'    => 'trainer',
        'orderby' => 'ID',
        'order'   => 'ASC'
    ] );
	
	print_pre( $trainers[0]->ID, "Trainers"  ); 
	 */
	
	/* $asmt = new Assignments();
	
	//$asmt_map = array( ... );
	
	$asmt->build( $asmt_map );
	
	print_pre( $asmt, 'The Assignments Class!' ); */
	
	// ------------ //

	
	/* 
	$student_id = 7; //Doula One's user ID. 
	
	$grades = new Grades(); 
	
	$grades->build( $student_id );

	print_pre( $grades, 'The Grades Class:' );  */
	
	
	
	/* $creator = new Creator(); 
	$creator->build();
	$created_map = $creator->get_map();
	
	print_pre( $created_map, 'The return of the Create_Assignments_Map::get_map() method:' ); 
	
	// ------------ //
	
	$asmt_map = new Map();
	$asmt_map->build( );
	
	print_pre ( $asmt_map, 'The Assignment Map Class:' . __FILE__ . __LINE__ );*/
	
	
	/* $grades = new Grades();
	$grades->build( 15 );
	
	print_pre ( $grades, 'The Grades Object:' . __FILE__ . __LINE__ ); */
	
	
?>	
