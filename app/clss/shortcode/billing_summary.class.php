<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Billing Summary Shortcodes Class
 *
 * 	
 *
 * 
 */

class Billing_Summary{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
    public static function load_callback( $atts ) {
        // Prepare the HTML for the billing summary
        ob_start();

       // Start the HTML output
	   echo '<div class="billing-summary-wrapper">';
	   echo '<h3>Billing Summary</h3>';
	   echo '<ul>';

	   // Display the last three transactions
	   $transactions = self::get_last_three_transactions();
	   foreach ( $transactions as $transaction ) {
		   echo '<li>';
		   echo '<a href="' . esc_url( $transaction['link'] ) . '">' . esc_html( $transaction['date'] ) . '</a>';
		   echo ' - ';
		   echo '<span class="amount">' . esc_html( $transaction['amount'] ) . '</span>';
		   echo '</li>';
	   }

	   // Close the HTML output
	   echo '</ul>';
	   echo '<a href="/account/billing_overview/">View More</a>';
	   echo '</div>';


        // Return the prepared HTML
        return ob_get_clean();
    }

    public static function get_last_three_transactions() {
        // Get the current user's ID
        $user_id = get_current_user_id();
    
        // Retrieve the last three payments for the user using RCP_Payments::get_payments()
        $payments = RCP_Payments::get_payments( array(
            'number'  => 3,
            'order'   => 'DESC',
            'orderby' => 'date',
            'user_id' => $user_id,
        ) );
    
        // Prepare the array to store transaction details
        $transactions = array();
    
        // Loop through the payments and retrieve transaction details
        foreach ( $payments as $payment ) {
            $transaction = array(
                'link'   => 'code to generate link is pending', //get_permalink( $payment->membership_id ),
                'date'   => date( 'F j, Y', strtotime( $payment->date ) ),
                'amount' => rcp_currency_filter( rcp_format_amount( $payment->amount ) ),
            );
    
            $transactions[] = $transaction;
        }
    
        // Return the last three transactions
        return $transactions;
    }
}
?>