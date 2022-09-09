<?php

namespace Doula_Course\App\Clss\Admin_Page;

use function Doula_Course\App\Func\render_admin_page as render_admin_page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base abstract class for for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
abstract class Admin_Page implements Admin_Page_Interface
{
   
	/* @var string $menu_title */
	protected $menu_title = '';
	
	/* @var string $page_title */
	protected $page_title = '';
	
	/* @var string $slug */
	protected $slug = '';

	/* @var string $cap */
	protected $cap;


	/**
     * Get the capability required to view the admin page.
     *
     * @return string
     */
    public function __construct( string $cap = 'edit_users' ){
		
		$this->cap = $cap;		
	}
	 
	
	 
	 /**
     * Set the Menu Title
     *
     * @return void
     */
    protected function set_menu_title( ?string $slug = NULL ){
		
		$slug = ( isset( $slug ) )? $slug : $this->slug;
		$this->menu_title = ucwords( str_replace( [ '-', '_' ], ' ', $slug) );
		
	} 	 
	 
	 /**
     * Set the Page Title
     *
     * @return void
     */
    protected function set_page_title( string $title ){
		
		$this->page_title =  ucwords( str_replace( '_', ' ', $title) );
		
	} 	 
	 
	 /**
     * Set the slug
     *
     * @return void
     */
    protected function set_slug( string $slug ){
		
		$this->slug = strtolower( str_replace( ' ', '_', $slug) );
		
	} 
	 
	 /**
     * Get the capability required to view the admin page.
     *
     * @return string
     */
    public function get_capability(){
		return $this->cap;
	}

    /**
     * Get the title of the admin page in the WordPress admin menu.
     * Converts $slug property to the title for the visual Menu texts.
	 *
     * @return string
     */
    public function get_menu_title(){
		
		return $this->menu_title;
		
	}

    /**
     * Get the title of the admin page.
     *
     * @return string
     */
    public function get_page_title(){
		
		return $this->page_title;
	}

    /**
     * Get the slug used by the admin page.
     *
     * @return string
     */
    public function get_slug(){
		
		return $this->slug;
	}	
	
	
	 /**
     * Renders the admin page.
     */
    public function render_page( array $args ) //: Doula_Course\App\Clss\Admin_Page\Admin\Render_Template
	{

		 echo render_admin_page( ...$args );
		
	}

	
}


?>
