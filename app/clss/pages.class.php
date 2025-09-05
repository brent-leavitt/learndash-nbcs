<?php

namespace Doula_Course\App\Clss;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Pages Class
 *
 * 	
 *
 * 
 */

class Pages{
	
	/**
	 *
	 */
	private $pages = [];
		
	/**
	 *
	 */
	private $plugin_dir_path;
	
	/**
	 * 
	 */	
	 
	public function __construct( array $page ){
		
		$this->pages = $page;
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );
		
	}
	
	/**
	 * Runs through the list of pages to be built
	 */
	 
	public function build(){
				
		foreach( $this->pages as $page )
			$this->add( $page );
	}	
	
	/**
	 * Runs the wordpress Add_ShortCode method
	 *
	 *	This is not quite right: Proper documentation is here: https://developer.wordpress.org/reference/functions/add_shortcode/
	 *
	 * @var $page string
	 */
	 
	private function add( $page ){
	
		add_shortcode( 'nb_'.$page  , function() use ( $page ){
			
			include_once( $this->get_template_path( $page ) );
			
		}  );

	}
	
	/**
	 * Assembles the template path to be called. 
	 * @var $template string 
	 */
	 
	private function get_template_path( $template ){
	
		return $this->plugin_dir_path. "../tmpl/page-" .str_replace( '_', '-', $template ).".php";

	}

}
?>