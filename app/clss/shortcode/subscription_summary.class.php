<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Subscription Summary Shortcodes Class
 *
 * 	
 *
 * 
 */

 class Subscription_Summary {
    public static function load_callback($atts) {
        // Check if the user is logged in
        if (is_user_logged_in()) {
            // Get the current user's memberships
            $user_id = get_current_user_id();
            $memberships = rcp_get_customer_memberships($user_id);

            if ($memberships) {
                $membership = current($memberships);
                $subscription_name = $membership->get_subscription_name();
                $start_date = $membership->get_date('start');
                $expiration_date = $membership->get_date('expiration');
                $duration = $membership->get_duration();
                $auto_renewal = $membership->is_recurring();

                 // Prepare the subscription summary HTML
				 $output = '<div class="subscription-summary">';
				 $output .= '<h3>Subscription Summary</h3>';
				 $output .= '<div class="column">';
				 $output .= '<p><strong>Start Date:</strong> ' . $start_date . '</p>';
				 $output .= '<p><strong>Expiration Date:</strong> ' . $expiration_date . '</p>';
				 $output .= '</div>';
				 $output .= '<div class="column">';
				 $output .= '<p><strong>Duration:</strong> ' . $duration . '</p>';
				 $output .= '<p><strong>Auto-Renewal:</strong> ' . ($auto_renewal ? 'Enabled' : 'Disabled') . '</p>';
				 $output .= '</div>';
				 $output .= '<div class="cta-link">';
				 $output .= '<p><a href="/account/billing/modify/">Edit Subscription</a></p>';
				 $output .= '</div>';
				 $output .= '</div>';
 
                return $output;
            } else {
                return '<p>No active subscription found.</p>';
            }
        } else {
            return '<p>Please log in to view your subscription summary.</p>';
        }
    }
}


/* 
.subscription-summary {
  display: flex;
  flex-wrap: wrap;
}

.subscription-summary h3 {
  width: 100%;
}

.subscription-summary .column {
  flex: 0 0 50%;
}

.subscription-summary .cta-link {
  width: 100%;
}

@media (max-width: 700px) {
  .subscription-summary .column {
    flex: 0 0 100%;
  }
}

*/ 

?>