<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * 	Template Name:  Billing Overview Page
 *
 * @file           page-billing-overview.php
 */
 
	global $wpdb;
	
	$bill_url = home_url( 'billing/' );
	$bill_detail_url = $bill_url.'billing-details/';
	
	
	ob_start();
	
	$student = get_student_meta(); 
	//Present an overview of billing information
	$student_cap = $student->roles[0];
	$alumnus = ( ( strcmp( 'alumnus_active', $student_cap ) == 0 ) || ( strcmp( 'alumnus_inactive', $student_cap ) == 0 ) )? true : false ;
	$base_url = home_url();
	
	//Summary Statement at the Top of the Page
	$stud_date_obj = new DateTime( $student->data->user_registered );
	$stud_date_init = clone $stud_date_obj;
	
	$today = new DateTime('now');
	//
	$bll_actn = array(); //A holding bay for relevenat billing actions to display for user. 
	
	//Variables
	$opt_pymnts = false; //Does student have the option to cancel or payoff course, only on payment plans.
	$opt_reactv = false; //Can the student reactivate their account? If inactive and monthly payment plan. 
	$opt_payment_type = 0; //0 = full course, 1 = autopay, 2 = manual. for Cancelling payments. 
	$opt_extend = false; //Does student have the option to extend their training?
	
	$renew_arr = array('student_partial_active', 'student_full_active', 'student_full_inactive' ); //Current and Active Student roles array
			
	//Setting up variables based on user info. 
	if( strcmp( $student->payments_received, '12/12' ) !== 0 ){
		$opt_pymnts = ( strcmp( 'student_partial_active', $student_cap  ) == 0 )? true : false; 
		$opt_reactv = ( strcmp( 'student_partial_inactive', $student_cap  ) == 0 )? true : false;
		
		switch( $stud_bill_type ){
			case "paypal_recurring":
				$opt_payment_type = 1;
				break;
			case "paypal_manual":
				$opt_payment_type = 2;
				break;
			default: 
				$opt_payment_type = 0;
				break;
		}
	}
	
	
	
	echo "<h2>Account Summary</h2>";
	
	
	if( !$alumnus ) { //If not alumni, is student
	
		$stud_reg_date = $stud_date_obj->format( "l, F j, Y" );
		$stud_date_obj->modify('+2 years');
		
		$stud_end_date = clone $stud_date_obj;
		$stud_end_date_string = $stud_date_obj->format( "l, F j, Y" );
		
		$stud_date_obj->modify('-4 month'); //This should already be set to the expiration date for registration, allow 3 more months. 
		$stud_exp_soon_date = clone $stud_date_obj;
		

		echo "<p>Registration Date: <em>$stud_reg_date</em></p><p>Our billing systems are in transition. Some functionality is still available. For additional in questions regarding your account, please contact Brent directly at brent@trianingdoulas.com. Thank you!</p>";
		/* if( $today < $stud_end_date ) {
			echo "You have until <strong>$stud_end_date_string</strong> to complete your training.";
		} else {
			echo "Your student account expired on <strong>$stud_end_date_string</strong>. To resume your training for BIRTH DOULA certification, please select from the options available below.";
		}
		
		
		$pymt_rcvdArr = array(
			'1/1' => 'Paid in Full (1/1)',
			'1/12' => '1 of 12',
			'2/12' => '2 of 12',
			'3/12' => '3 of 12',
			'4/12' => '4 of 12',
			'5/12' => '5 of 12',
			'6/12' => '6 of 12',
			'7/12' => '7 of 12',
			'8/12' => '8 of 12',
			'9/12' => '9 of 12',
			'10/12' => '10 of 12',
			'11/12' => '11 of 12',
			'12/12' => 'Complete (12/12)'
		);	
		
		$stud_pay_rcvd = $student->payments_received;
		
		$billTypeArr = array(
			'paypal_recurring' => 'by recurring subscription using Paypal',
			'paypal_manual' => 'by manual invoice using Paypal',
			'check' => 'via check by mail'
		);	
		
		$stud_bill_type = $student->billing_type;
		
		$progRateArr = array(
			'18p' => 18,
			'20p' => 20,
		);
		
		$stud_prog_rate = $student->program_rate;
		
		//Add more Checks here. 
		if( ( $stud_pay_rcvd == '1/1' ) || ( $stud_pay_rcvd == '12/12' ) ){
			echo " Your account is paid for in full. <br>";
		} else {
			echo " You have made {$pymt_rcvdArr[$stud_pay_rcvd]} payments.<br>";
			
			if( $opt_reactv ){
				echo "Your account is currently marked as <em>inactive</em>. To continue with your training, please reactivate your account.";
			} else {
				echo "Your billing plan is \${$progRateArr[$stud_prog_rate]} per month, making payments {$billTypeArr[$stud_bill_type]}.";
			}
		}	
		
		echo "</p><hr>"; */

		
		//Additional Billing Actions - make visible only if relevant to account
		
		//REQUEST COURSE EXTENSION

		 //This is a partial student whose account is currently inactive, but has still made full payments... they can renew their accout. 
		if( ( strcmp('student_partial_inactive', $student_cap) == 0 )  && ( strcmp( $stud_pay_rcvd, '12/12' )== 0 ) )
			$renew_arr[] = 'student_partial_inactive';
		  
		 
		if( ( in_array( $student_cap, $renew_arr) ) && ( $today > $stud_exp_soon_date ) )
			$bll_actn[] = 'course_extension'; 
			
		
		//REQUEST ACCOUNT PAYOFF
		
		if( ( !empty( $opt_pymnts ) || !empty( $opt_reactv ) ) && ( $today < $stud_end_date  ) ){
			$bll_actn[] = 'account_payoff'; 
		}
		
		//CANCEL PAYMENTS AND ACCOUNTS
		
		if( $opt_pymnts !== false ){
			if( strcmp($stud_bill_type, 'paypal_recurring' ) == 0 )
				$bll_actn[] = 'cancel_recurring';
			elseif( strcmp($stud_bill_type, 'paypal_manual' ) == 0 )
				$bll_actn[] = 'cancel_manual';
			elseif( strcmp($stud_bill_type, 'check' ) == 0 )
				$bll_actn[] = 'cancel_account';
		}
		
		
		// REACTIVATE ACCOUNT
		if( ( strcmp( 'student_partial_inactive', $student_cap ) == 0 ) && (  strcmp( $student->payments_received, '12/12' ) !== 0  ) )
			$bll_actn[] = 'reactivate_account';
		
	} else {
	
		echo "Your training certification for BIRTH DOULA was issued on DATE and is good through NEW DATE.";
		
		// RENEW CERTIFICATION
		echo "<hr>";
		
		if( ( strcmp( 'alumnus_inactive', $student_cap ) == 0 ) || ( strcmp('alumnus_active', $student_cap ) == 0 ) )
			$bll_actn[] = 'renew_certification';
		
	}
	
	$actn_details = array(
		'course_extension' => array(
			'title' => 'Course Extension',
			'url' => 'course-extension',
			'icon' => 'share',
			'detail' => 'Add six(6) months to your course.'
		),
		'account_payoff' => array(
			'title' => 'Account Payoff',
			'url' => 'account-payoff',
			'icon' => 'flag-checkered',
			'detail' => 'Payoff the balance of your training and save.'
		),
		'cancel_recurring' => array(
			'title' => 'Cancel Account',
			'url' => 'cancel-recurring',
			'icon' => 'trash',
			'detail' => 'Deactivate your account and suspend your current payment agreement.'
		),
		'cancel_manual' => array(
			'title' => 'Cancel Account',
			'url' => 'cancel-manual',
			'icon' => 'trash',
			'detail' => 'Deactivate your account and end future invoicing.'
		),
		'cancel_account' => array(
			'title' => 'Cancel Account',
			'url' => 'cancel-account',
			'icon' => 'trash',
			'detail' => 'Deactivate your account.'
		),
		'reactivate_account' => array(
			'title' => 'Reactivate Account',
			'url' => 'reactivate-account',
			'icon' => 'share-alt',
			'detail' => 'Resume payments and continue working on your certification.'
		),
		'renew_certification' => array(
			'title' => 'Renew Certification',
			'url' => 'renew-certification',
			'icon' => 'refresh',
			'detail' => 'Renew your certification for the next two years.'
		)
		
		/*,
		'' => array(
			'title' => '',
			'url' => '',
			'icon' => '',
			'detail' => ''
		),*/
	
	);
	
	foreach($bll_actn as $actn){
		
		$details = $actn_details[$actn];
	
		echo "<div class='tcol-lg-4 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12 home-iconmenu homeitemcount1'>                                      
			<a class='home-icon-item' title='{$details['title']}' target='_self' href='{$bill_url}{$details['url']}/' >
				<i class='icon-{$details['icon']}'></i><h4>{$details['title']}</h4><p>{$details['detail']} </p> 
			</a>
		</div>";
	
	}	
	
		
		
	//Transactions Table
	
	echo '<hr style="clear: both;">
		<h3>Student Transaction Records</h3>';
					
		
		$student_id = $student->ID;
		
		$txn_results = $wpdb->get_results('SELECT * FROM `transactions` WHERE student_id='.$student_id);
	
	//Now Add Tables. 
		
	//	print_pre( $bll_actn );
	//	print_pre( $txn_results );
		
		echo "<table id='student_transactions' class='invoice-list nb-table'>";
		echo "<thead>
			<tr>
				<th>Txn ID</th>
				<th>Date</th>
				<th>Amount(USD)</th>
				<th>Description</th>
				<th>Type</th>
			</tr>	
		</thead>
		<tbody>
		";
		
		
		
		foreach( $txn_results as $txn ){
			$tx_url = $bill_detail_url."?tx_id=".$txn->transaction_id;
			echo "<tr>
				<td><a href='{$tx_url}'>{$txn->transaction_id}</a></td>
				<td>{$txn->trans_time}</td>
				<td>{$txn->trans_amount}</td>
				<td>{$txn->trans_label}</td>
				<td>{$txn->trans_type}</td>
			</tr>";
		}
		
		echo "</tbody></table>";
	
		?>
			
		<h2>Unsubscribe</h2>	
			
		<p>Need to pause or stop your training? Use the "Unsubscribe" button below to do so. </p>
		
		<p>After logging in to PayPal. Select a past payment to view the details. Within the body of the payment receipt, there should be a link to "Manage New Beginnings Childbirth Service payments". After clicking on that link, you should be brought to a screen that allows you to manage your subscription, including a large "cancel" button near the top of the page.</p>
		
		<p>This will terminate your billing agreement with New Beginnings Childbirth Services. You will not billed for subsequent months unless you re-subscribe for training.</p>
		
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=L72PBSRBFF3C6">
		<img src="https://www.paypalobjects.com/en_US/i/btn/btn_unsubscribe_LG.gif" border="0">
		</a>
		<?php
	
	return ob_get_clean();
?>