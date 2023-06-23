<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Reader Upgrade Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

 class Reader_Upgrade {

    public static function load_callback($atts) {

        // Check if the current user has the "reader" role
        if( nb_role_is( 'reader' ) ){

            // Prepare the promotional box HTML
            $output = '<div class="upgrade-promo">';
            $output .= '<h3>Upgrade to Student Doula</h3>';
            $output .= '<p>Unlock the full potential of your doula journey. Become a student doula and gain full access to training, a personal trainer, and community support.</p>';
            $output .= '<a href="/register/upgrade/" class="cta-button">Upgrade Now</a>';
            $output .= '</div>';

            return $output;
        }

        return ''; // Return empty string if the user doesn't have the "reader" role
    }
}

?>