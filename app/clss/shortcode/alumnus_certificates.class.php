<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Alumnus Certificates Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Alumnus_Certificates{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		//if there are no certificates to display, then user will not have alumnus status, hence: 
		if( nb_role_is( 'alumnus' ) ){

			//CHAT GPT CODE

			// Get the current user's ID
			$user_id = get_current_user_id();

			// Query all certificates associated with the user
			$certificates_query = new WP_Query( array(
				'post_type'      => 'certificates',
				'posts_per_page' => -1,
				'author'         => $user_id,
			) );

			// Prepare the HTML for the certificates list
			ob_start();

			// Start the HTML output
			echo '<div class="alumnus-certs-widget">
				<h3>Certificates</h3>
				<ul>';

			// Display each certificate in the list
			if ( $certificates_query->have_posts() ) {
				while ( $certificates_query->have_posts() ) {
					$certificates_query->the_post();
					$certificate_title = get_the_title();
					$expiration_date = get_post_meta( get_the_ID(), 'cert_expire_date', true );

					// Format the expiration date
					$formatted_expiration_date = date( 'F j, Y', strtotime( $expiration_date ) );

					// Check if the certificate has expired
					if ( strtotime( $expiration_date ) < strtotime( 'today' ) ) {
						$expiration_text = '(expired on ' . $formatted_expiration_date . '. <a href="#">Renew</a>)';
					} else {
						$expiration_text = '(expires on ' . $formatted_expiration_date . ')';
					}

					echo '<li>';
					echo esc_html( $certificate_title ) . ' ' . $expiration_text;
					echo '</li>';
				}
			} else {
				echo '<li>No certificates found.</li>';
			}

			// Reset the post data
			wp_reset_postdata();

			// Close the HTML output
			echo '</ul></div><!-- end Alumnus_Certs_Widget -->';

			// Return the prepared HTML
			$results = ob_get_clean();
		}

		return ( isset( $results ) )? $results : NULL; 

	
	}	
	

}
?>