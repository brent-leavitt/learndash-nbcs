<?php

namespace Doula_Course\App\Clss\Shortcode;

use Doula_Course\App\Clss\Registrar;
use Doula_Course\App\Clss\Cashier_Action_Filter;


if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Cashier Shortcodes Class
 *
 * 	
 *	Call the Registrar class which does most of the heavy lifting for this short code. 
 * 
 */

class Cashier{
	
	
	 /**
	 * action
	 * 
	 * Action to be taken by the cashier
	 *
	 * @since 2.0
	 * @var string
	 */
	
	private $action; 
	
	 
	/**
	 * load_callback
	 * 
	 * 
	 *
	 * @since 2.0
	 * @return string
	 */
	 
	public static function load_callback( $atts )
	{
		
		self::filter_action();
		
		//Get Cashier Class
		$registrar = new Registrar();
		$registrar->go();
		return $registrar->display();

	}	
	
	 
	/**
	 * filter_action
	 * 
	 * 
	 *
	 * @since 2.0
	 * @return void
	 */
	 	
	public static function filter_action(){
		
		$filter = new Cashier_Action_Filter();
		$filter->set_action();
	}
	
}
?>