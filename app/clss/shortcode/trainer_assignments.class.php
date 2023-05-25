<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Trainer Assignments Front-End Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Trainer_Assignments {
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		if( nb_role_is( 'trainer' ) )
		{
			?>

			<h3>Assignments Pending Review</h3>
			<p>Below is a list of assignments that are ready for your review: </p>
			<ul>
				<li><a href="#">Assignment Name, Status, Submitted on 12/23/2023</a></li>
				<li><a href="#">Assignment Name, Status, Submitted on 12/23/2023</a></li>
				<li><a href="#">Assignment Name, Status, Submitted on 12/23/2023</a></li>
				<li><a href="#">Assignment Name, Status, Submitted on 12/23/2023</a></li>
				<li><a href="#">Assignment Name, Status, Submitted on 12/23/2023</a></li>
			</ul>

			<p><a href="/wp-admin/edit.php?post_type=assignment&trainer=20&view=all_my_pending">Grade Assignments &raquo;</a></p> 
			<?php
		}
		
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>