<?php

namespace Doula_Course\App\Clss\Shortcode;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  In-Page Menu Shortcodes Class
 *
 * 	
 *
 * 
 */

class Page_Menu{
	

	/**
	 *  
	 *
	 * Pay attention to the static callback. 
	 *
	 *
	 */
	 
	public static function load_callback( $attr, $content = NULL ){
		
		ob_start();
		
		echo "<ul id='in-page-menu' class='row cat-list'>"; 
		
		$i = 1;
		foreach( $attr as $id => $name )
		{
			 
			echo "<li id='{$id}_link' class='page-menu-item menu-item-{$i}'>
				<a href='#{$id}'>
					<span class='menu-id'>#{$id}</span>
					<span class='menu-name'>{$name}</span>
				</a>
			</li>";
			$i++;
		}
		echo "</ul>"; 
		
		return ob_get_clean();

	}	
	

}
?>