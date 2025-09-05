<?php

//Load Dynamic Fields before building array. Like for Availble Email Templates. 

$mess_arr = get_posts([
	'numberposts' => 50,
	'post_type' => 'notification',
	'orderby' => 'menu_order',
	
]);

$avail_messages = [ '' => '-----' ];

foreach( $mess_arr as $mess )
	$avail_messages[ $mess->ID ] =  $mess->post_title; 


$fields = array(
			//	[ 'section', 'Section Title', array $fields ],
				//[ string $type, string $name, string $label, array $extras = [] , array $options = [] ], //because every field can have extras, but not every field has options. 
			
			[ 'section', 'Email Student', 2, [
				[ 'select', 'email_template', 'Pre-built Templates', [],  $avail_messages ],
				[ 'text', 'first_name', 'First Name' ],
				[ 'email', 'user_email', 'Primary Email' ],
				[ 'checkbox', 'student_email_visible', 'Use Primary Email' ],
				[ 'email', 'student_pay_email', 'Payment Email' ],
				[ 'checkbox', 'student_pay_email_visible', 'Use Payment Email' ],
			]],
			[ 'submit', '', 'Load Template' ],
			[ 'section', 'Compose Email', 1, [
				[ 'email', 'to_student_email', 'To:' ],
				[ 'email', 'cc_student_email', 'Cc:' ],
				[ 'text', 'email_subject', 'Subject Line' ],
				[ 'textarea', 'email_body', 'Mesage Body' ],
			]],
			[ 'section', 'Message Admin Notes', 1, [
				[ 'textarea', 'message_admin_notes', '' ],
			]],
			[ 'submit', '', 'Send Email' ],

			
			/*[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ], */

);
?>