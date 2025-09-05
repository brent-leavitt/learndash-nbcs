<?php

namespace Doula_Course\App\Tmpl;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base abstract class for for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
class HTML_Template
{
   
	/* @var string $slug */
	private $slug = '';
	
   
	/* @var arr $args */
	private $args = [];
	
	 /**
     * Setup the class
	 *
	 * returns void
     */
	public function __construct( string $slug, array $args = [] )
	{
			$this->slug = $slug;
			$this->args = $args;
		
	}
	 /**
     * Generates the templated html
	 *
	 * returns void
     */
    public function generate( ): string
	{
		
		$args = $this->args;
		
		ob_start();
		
		include __DIR__. '/parts/' .$this->slug. '.tmpl.php';
		
		return ob_get_clean();	
		
	}

	
}


?>
