<?php
//Set Trainers_Arr code. 
$trainers_arr = nb_get_trainers();

//Set Admin Notes
$admin_notes_arr = []; //nb_get_admin_notes(); 


$fields = array(
			//	[ 'section', 'Section Title', array $fields ],
				//[ string $type, string $name, string $label, array $extras = [] , array $options = [] ], //because every field can have extras, but not every field has options. 
			
			[ 'section', 'Personal Informatiton', 2, [
				[ 'text', 'user_login', 'User Name', [ 'disabled' ] ],
				[ 'text', 'display_name', 'Display Name' ],
				[ 'text', 'first_name', 'First Name' ],
				[ 'text', 'last_name', 'Last Name' ],
				[ 'text', 'student_address', 'Address' ],
				[ 'text', 'student_address2', 'Address, Second Line' ],
				[ 'text', 'student_city', 'City' ],
				[ 'text', 'student_state', 'State' ],
				[ 'text', 'student_postalcode', 'Postal Code' ],
				[ 'text', 'student_country', 'Country' ]
			]],
			[ 'section', 'Contact Information', 2, [
				[ 'text', 'student_phone', 'Phone' ],
				[ 'email', 'user_email', 'Email' ],
			]],
			/* [ 'section', 'Payment Details', 2, [
				[ 'select', 'student_pay_service', 'Pay Service', [], 	[ 
					'' => '-----', 
					'paypal' => 'PayPal', 
					'stripe' => 'Stripe'
				]],
				[ 'text', 'student_pay_id', 'Pay ID' ],
				[ 'email', 'student_pay_email', 'Pay Email', [ 'multiple' ]],
				[ 'empty', '', '' ],
				[ 'text', 'user_registered', 'Registration Date' ],
				[ 'text', 'last_payment_received', 'Last Payment Received' ],
				[ 'select', 'student_status', 'Status', [], [ 
					'0' => 'Inactive', 
					'1' => 'Current'
				]],
				[ 'select', 'billing_type', 'Billing Type' , [], [ 
					'' => '-----', 
					'paypal_recurring' => 'PayPal (recurring)', 
					'paypal_onetime' => 'PayPal (one-time)', 
					'paypal_manual' => 'PayPal (manual)', 
					'check' => 'Check', 
					'other' => 'Other', 
				] ]
				
			]],
			[ 'section', 'Additional Student Information', 2, [
				[ 'checkbox', 'student_tracks', 'Tracks' , [], $available_tracks 
				],
				[ 'text', 'certificate_id', 'Certificate ID' ],
				[ 'text', 'certification_date', 'Certification Date' ],
				[ 'text', 'certificaiton_last_update', 'Certificaiton Last Updated' ],
			]], */
			[ 'section', 'Additional Student Information', 2, [
				
				[ 'select', 'student_trainer', 'Trainer' , [], $trainers_arr ],
				[ 'text', 'user_registered', 'Start Date' ],
				
			]],
			
			[ 'section', 'Admin Notes', 1, [
				[ 'special', 'admin_notes', 'Admin Notes' ],
			]],
			[ 'submit', '', 'Update Student' ]
			
			/*[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ], */

);
?>