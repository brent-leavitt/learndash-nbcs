<?php

namespace Doula_Course\App\Clss\Admin_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The concrete class for Admin Top-Level Admin Pages
 */
Class Menu_Page extends Admin_Page implements Menu_Page_Interface
{


	
	/* @var string $icon */
	private $icon = '';
	
	/* @var int $posiion */
	private $position = 50;
	 	 
	 
	 
	 /**
     * Initialize Class with Parameters. 
	 *
     * Five Properties: Title, Slug, Icon, Position, Capability (optional => defaults to 'edit_users'
	 *
     * @return void
     */
	 	
    public function init( string $title, string $slug, string $icon = '', int $position = NULL , string $cap = ''  ){
		
		$this->set_menu_title( $slug );
		
		$this->set_page_title( $title );
		
		$this->set_slug( $slug );
		
		if( !empty( $icon ) )
			$this->icon = $icon;
		
		if( !empty( $position ) )
			$this->position = $position;
		
		if( !empty( $cap ) )
			$this->cap = $cap;		
	}

	 
	 
	 
	 
	 /**
     * Get the URL of the icon used by the admin page.
     *
     * You can also return:
     * - A base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'.
     * - The name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'.
     * - The value 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
     *
     * @return string
     */
    public function get_icon_url(){
		
		return "dashicons-". $this->icon;
	}

    /**
     * Get the position of the admin page in the menu order of the WordPress admin sidebar.
     *
     * @return int
     */
    public function get_position(){
		
		return $this->position;
	}
}

?>