<?php

namespace Doula_Course\App\Clss\Admin_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base interface clas for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
interface Admin_Page_Interface
{
    /**
     * Get the capability required to view the admin page.
     *
     * @return string
     */
    public function get_capability();

    /**
     * Get the title of the admin page in the WordPress admin menu.
     *
     * @return string
     */
    public function get_menu_title();

    /**
     * Get the title of the admin page.
     *
     * @return string
     */
    public function get_page_title();

    /**
     * Get the slug used by the admin page.
     *
     * @return string
     */
    public function get_slug();

    
    /**
     * Renders the admin page.
     */
    public function render_page( array $args );
	
}


?>
