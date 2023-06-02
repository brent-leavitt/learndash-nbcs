<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Account Widget for an Overview of All Certificates Earned Shortcodes Class
 *
 * 	
 *
 * 
 */

class Certificates_Overview{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL, $handler = NULL ){
		
		ob_start();
		
		//get available certificates for user. 
		if( nb_role_is( 'alumnus' ) )
		{
			echo "";
			echo"
				<div id='nb_certificates_overview_wrap' class='nb_account_section_wrap'>
				<h3 id='certificates'>Certificates Granted</h3>
					<div class='nb_account_section'>";
						/* <div class='nb_flex_item'>
							
							

						</div>
						<div class='nb_flex_item'>

						
						</div> */
				echo "<p class='nb_account_notice'>
						If you are seeing this section, our records indicate that you have completed a certifiation program in the past, and that you have been granted alumnus status. 
						We have not yet fully automated the certificate review and renewal process. Click on the 'Review/Renew Certificates' link for more information about renewals.
					</p>"; 
				echo "</div><!-- end nb_account_section -->
					<div class='edit-link' >
						<p class='has-text-align-right'><a href='/account/certificates/'>Review/Renew Certificates</a></p>
					</div>
				</div><!-- end nb_account_section_wrap -->
			";
		
		}
		return ob_get_clean();
				 
		
		//return "This is the Payment class method: load_callback! <br>";
	}	
	

}
?>