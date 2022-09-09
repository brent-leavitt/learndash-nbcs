<?php
$fields = array(
			//	[ 'section', 'Section Title', array $fields ],
				//[ string $type, string $name, string $label, array $extras = [] , array $options = [] ], //because every field can have extras, but not every field has options. 
			
			[ 'section', 'Check In', 1, [
				[ 'text', 'user_name', 'Username' ],
				[ 'password', 'password', 'Password' ]
			]],
			[ 'submit', '', 'Sign In' ],
			

			
			/*[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ], */

);
?>