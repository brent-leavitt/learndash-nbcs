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
    public static function load_callback( $atts ) {
        
      $output = '<div class="subscription-summary">';
      $output .= '<h3>Subscription Summary</h3>';
      // Check if the user is logged in

      if (is_user_logged_in()) {
          // Get the current user's memberships
          $customer = rcp_get_customer_by_user_id(); 
          $memberships = rcp_get_customer_memberships( $customer->get_id() );

          if ($memberships) {
              $membership = current($memberships);
              $membership_level = rcp_get_membership_level( $membership->get_object_id() );
              
              $subscription_name = $membership->get_membership_level_name();
              $start_date = $membership->get_created_date();
              $expiration_date = $membership->get_expiration_date();
              $duration = $membership_level->get_duration()." ".$membership_level->get_duration_unit();
              $auto_renewal = $membership->is_recurring();

                // Prepare the subscription summary HTML
              
              $output .= '<div class="column">';
              $output .= '<p><strong>Subscription:</strong> ' . $subscription_name . '</p>';
              $output .= '<p><strong>Start Date:</strong> ' . $start_date . '</p>';
              $output .= '<p><strong>Expiration Date:</strong> ' . $expiration_date . '</p>';
              $output .= '</div>';
              $output .= '<div class="column">';
              $output .= '<p><strong>Duration:</strong> ' . $duration . '</p>';
              $output .= '<p><strong>Auto-Renewal:</strong> ' . ($auto_renewal ? 'Enabled' : 'Disabled') . '</p>';
              $output .= '</div>';
              
          } else {
            $output .= '<p>No active subscription found.</p>';
          }

          $output .= '<div class="cta-link">';
          $output .= '<p class="has-text-align-right"><a href="/account/billing/">Billing Overview</a></p>';
          $output .= '</div>';

        } else {
          $output .= '<p>Please log in to view your subscription summary.</p>';
        }
        
        $output .= '</div>';

        return $output;
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