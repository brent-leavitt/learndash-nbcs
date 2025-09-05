<?php
$fields = array(
			//	[ 'section', 'Section Title', array $fields ],
				//[ string $type, string $name, string $label, array $extras = [] , array $options = [] ], //because every field can have extras, but not every field has options. 
			
			[ 'section', 'Test Form', 2, [
				[ 'text', 'user_login', 'User Name', [ 'disabled' ] ],
				[ 'password', 'user_pass', 'Password'  ],
				
			]],
			[ 'submit', '', 'Update Student' ]
			
			/*[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ],
			[ 'text', '', '', [ '' ] ], */

);
?>