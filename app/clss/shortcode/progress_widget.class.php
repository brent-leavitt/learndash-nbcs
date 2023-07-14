<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Progress Sidebar Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

 class Progress_Widget {
    public static function load_callback($atts) {
        // Check if the current user has the "student" role
        if( nb_role_is( 'student' ) ){
            // Retrieve the enrolled courses for the current user
            $user_id = get_current_user_id();
            $enrolled_courses = learndash_user_get_enrolled_courses( $user_id );

            // Prepare the progress report HTML
            $output = '<div class="progress-widget">';
            $output .= '<h3>My Progress</h3>';

			if( !empty( $enrolled_courses ) ){
                // Separate completed and in-progress courses
                $completed_courses = array();
                $inprogress_courses = array();

                foreach( $enrolled_courses as $course ){
                    //$course_id = $course;
                    $course_progress = learndash_course_progress(array('user_id' => $user_id, 'course_id' => $course));
                    print_pre( $course_progress, 'Course Progress' ); 
                    $progress_percentage = 0; //$course_progress['completed'];

                    if ($progress_percentage == 100) {
                        $completed_courses[] = $course;
                    } else {
                        $inprogress_courses[] = array( 'course' => $course, 'progress' => $progress_percentage );
                    }
                }

                // Sort the in-progress courses by course ID
                usort($inprogress_courses, function($a, $b) {
                    return $a['course'] - $b['course'];
                });

                // Generate the HTML for in-progress courses
                if( !empty( $inprogress_courses ) ){
                    $output .= '<ul>';

                    foreach ($inprogress_courses as $course_data) {
                        $course = $course_data['course'];
                        $progress_percentage = $course_data['progress'];
                        $course_title = get_the_title( $course );

                        $output .= '<li>';
                        $output .= '<span class="course-title">' . $course_title . '</span>';
                        $output .= '<div class="progress-bar">';
                        $output .= '<div class="progress" style="width: ' . $progress_percentage . '%;"></div>';
                        $output .= '</div>';
                        $output .= '</li>';
                    }

                    $output .= '</ul>';
                }

                // Generate the HTML for completed courses
                if (!empty($completed_courses)) {
                    $output .= '<ul class="completed-courses">';

                    foreach ($completed_courses as $course) {
                        $course_title = get_the_title( $course );
                        $output .= '<li class="completed">' . $course_title . ' (Completed)</li>';
                    }

                    $output .= '</ul>';
                }
            } else {

                $output .= '<p>No enrolled courses found.</p>';
            }

            $output .= '</div>';

            return $output;
        }

        return ''; // Return empty string if the user doesn't have the "student" role
    }
}


/* CSS for Progress Bar 

.progress-widget ul li {
    position: relative;
    margin-bottom: 15px;
}

.progress-bar {
    width: 100%;
    height: 10px;
    background-color: #f2f2f2;
    border-radius: 5px;
    overflow: hidden;
}

.progress {
    height: 100%;
    background-color: #4caf50;
    width: 0;
    transition: width 0.3s ease-in-out;
}

*/

?>

