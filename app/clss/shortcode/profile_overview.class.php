<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Profile Overview Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Profile_Overview{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		$user_info = get_current_user(); 

		//User Name and Address on left side
		?>
			<div id="nb_profile_overview_wrap" class"col-med-1">
				<div id="nb_profile_name_address" class"col-med-2">
					<p><strong>Registered Name:</strong> Jamie Janes</p>
					<p>
						<strong>Address</strong>
					<br>
						123 N. 456 W.
					<br>
						Anytown, AZ 85643
					<br>
						United States
					</p>
					

				</div>
				<div id="nb_profile_email_phone" class"col-med-2">

					<p><strong>Display Name:</strong> Jamie</p>
					<p><strong>Email:</strong> jamie@trainingdoulas.com</p>
					<p><strong>Phone:</strong> 555-555-5555</p>

				</div>
			</div>

		<?php

		//Display Name, Email, and Phone number on right side. 
		?>



		<?php

		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>