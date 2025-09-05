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
		
		$user_info = wp_get_current_user(); 

		//print_pre( $user_info, 'User Info' ); 
		//User Name and Address on left side
		
		echo"
		<div class='nb_account_section_wrap'>
			<h3 id='profile'>Personal Information</h3>
			<div id='nb_profile_overview_wrap' class='nb_account_section'>
				<div id='nb_profile_name_address' class='nb_flex_item'>
					<p><strong>Registered Name:</strong>{$user_info->first_name} {$user_info->last_name}</p>
					<p>
						<strong>Address:</strong>
					<br>
						{$user_info->student_address}";

		if( !empty( $user_info->student_address1 ) ){
			echo "
				<br>
					{$user_info->student_address1}
			";

		}
					
		echo"		<br>
						{$user_info->student_city}, {$user_info->student_state} 
					<br>
						{$user_info->student_postalcode}
					<br>
						{$user_info->student_country}
					</p>
					

				</div>
				<div id='nb_profile_email_phone' class='nb_flex_item'>

					<p><strong>Display Name:</strong> {$user_info->display_name}</p>
					<p><strong>Email:</strong> {$user_info->user_email}</p>
					<p><strong>Phone:</strong> {$user_info->student_phone}</p>

				</div>
			</div><!-- end nb_account_section -->
			<div class='edit-link' >
				<p class='has-text-align-right'><a href='/account/profile/'>Edit Profile</a></p>
			</div>
		</div><!-- end nb_account_section_wrap -->
		";

		//Display Name, Email, and Phone number on right side. 
		?>



		<?php

		return ob_get_clean();
				 
		
		//return 'This is the Payment class method: load_callback! <br>';
	}	
	

}
?>