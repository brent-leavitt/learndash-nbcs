<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Subscription Overview Widget Shortcodes Class
 *
 * 	
 *
 * 
 */

class Subscription_Widget{
	 
	 public static function load_callback($atts) {
        // Check if the user is logged in
        if( !is_user_logged_in() ) 
            return ''; // Return empty if user is not logged in
    
	
		if( nb_role_is( 'trainer' ) || nb_role_is( 'administrator' ) )
			return ''; // Return empty if user is trainer or admin. 

		
		 // Get the current user's memberships// Get the current user's memberships
        $customer = rcp_get_customer_by_user_id(); 
        $memberships = rcp_get_customer_memberships( $customer->get_id() );

        if (!$memberships) {
            return ''; // Return empty if no active memberships found
        }

        // Prepare the subscription widget HTML
        $output = '<div class="subscription-widget">';
        $output .= '<h3>Active Memberships</h3>';

        foreach ($memberships as $membership) {
            $subscription_name = $membership->get_membership_level_name();
            $auto_renewal = $membership->is_recurring();

            $output .= '<div class="membership">';
            $output .= '<p class="membership-name">' . $subscription_name . '</p>';
            $output .= '<p class="auto-renewal">Auto Renewal: ' . ($auto_renewal ? 'Enabled' : 'Disabled') . '</p>';
            $output .= '<p class="cta-link"><a href="/account/billing/modify/">Edit</a></p>';
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
	}	

}

?>