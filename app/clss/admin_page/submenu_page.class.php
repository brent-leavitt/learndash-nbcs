<?php


namespace Doula_Course\App\Clss\Admin_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * A WordPress submenu page.
 */
class Submenu_Page extends Admin_Page implements Submenu_Page_Interface
{


	/* @var string $parent */
	private $parent_slug = '';
	 	 

		
	 
	 /**
     * Initialize Class with Parameters. 
	 *
     * Five Properties: Title, Slug, Icon, Position, Capability (optional => defaults to 'edit_users'
	 *
     * @return void
     */
	 	
    public function init( ?string $parent, string $title, ?string $slug = NULL, ?string $cap = NULL ){
		
		$this->set_page_title( $title );
		
		$slug = ( !empty( $slug ) )? $slug : $title;
		
		$this->set_slug( $slug );
		
		$this->set_menu_title();
		
		$this->parent_slug = ( !empty( $parent ) )? $parent : NULL;	
		
		if( !empty( $cap ) )
			$this->cap = $cap;		
	}

	 
	
	/**
     * Get the parent slug of the admin page.
     *
     * @return string
     */
    public function get_parent_slug(){
		
		return $this->parent_slug;
		
	}

}


?>
