<?php

namespace Doula_Course\App\Tmpl;

use Doula_Course\App\Clss\Grades\Grades; 

if ( ! defined( 'ABSPATH' ) ) { exit; }

/*

//	Custom Template: Course Asssignments
 
// 	This is not a standard Wordpress template, but called by the material_loop() in the /func/post_types.php file in the doula_course plugin. 
	
*/

global $wpdb, $current_user;
$course_permalink = get_permalink(); //Not sure what this is for?


?>  

			<div class="course-nav top">
				<?php course_nav_bar();?>
			</div><!-- end .course-nav top -->
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 

				<div class="post-side-col">
					<h5>Unit Contents</h5>
					<ul>
						<?php wp_list_pages( $list_args ); ?>
					</ul>
				</div>
                <div class="post-entry">
                    <h2><?php the_title(); ?></h2>
					<hr>
                    
					<?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    
				<?php 
				 $grades = new Grades( );
				 $grades->build( $current_user->ID  ); 
				 
				//This is the logic that allows for a grade to be submitted for a specific assignment, but not have "assignment" CPT attached to the grades, so that a trainer may override a required assignment submission. 
				if( !empty( $grades->get_grade_by_id( $post->ID ) ) && empty( $grades->assignment_exists( $post->ID ) ) ):
					$asmt_status_string = $grades->get_grade_status(  $post->ID  );
			
			
					echo "<hr>
					<div class='asmt_submitted'> 
						<h3>Assignment Submitted</h3>
						<p><em>This assignment is already marked as <strong>{$asmt_status_string}</strong>, but was submitted some other way, probably via email.</em></p>
					</div>";
				else: ?>
				
					<p><a class="button" href="#asmt-editor">Jump to Assignment Editor &darr;</a></p>
					<hr style="clear: both;">
					<h2 id="asmt-editor">Assignment Editor</h2>
					<?php // We may want to insert comments on the assignment here? Toggle Visibility. ?>
			
					<?php  include_once( plugin_dir_path( __FILE__ ).'/assignment-editor.php' );
				
					// Restore original Post Data //
					 wp_reset_postdata();
				 
				 endif;
				 
				?>
				
				</div><!-- end of .post-entry -->	               
				   
			</div><!-- end of #post-<?php the_ID(); ?> -->       

  
			<div class="course-nav btm">
				<?php course_nav_bar();?>
			</div><!-- end .course-nav btm -->
           