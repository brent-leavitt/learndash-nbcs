<?php
$fields = array(
			//	[ 'section', 'Section Title', array $fields ],
				//[ string $type, string $name, string $label, array $extras = [] , array $options = [] ], //because every field can have extras, but not every field has options. 
			
			[ 'section', 'Register', 1, [
				[ 'hidden', 'enrollment', '' ],
				[ 'hidden', 'service', '' ],
				[ 'text', 'user_name', 'Username' , [ 'required' ] ],
				[ 'email', 'user_email', 'Email Address', [ 'required' ] ],
				[ 'password', 'password', 'Password', [ 'required' ] ],
				[ 'password', 'password_2', 'Confirm Password', [ 'required' ] ]
			]],
			[ 'submit', '', 'Register' ],
			

			
			/*[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ], */

);
?>