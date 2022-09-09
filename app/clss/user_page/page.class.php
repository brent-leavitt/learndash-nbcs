<?php

namespace Doula_Course\App\Clss\User_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base abstract class for for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
class Page
{
   
	/* @var arr $sections */
	public $sections = [];
	
	 /**
     * Renders the elements of the page.
	 *
	 * returns string
     */
    public function render( $echo = true ): string
	{
		
		$output = '';
		
		foreach( $this->sections as $section ){
			$output .= $section;
		}		
		
		if( $echo )	echo $output;
				
		return ( !$echo )? $output : '';
	}

	
}


?>
