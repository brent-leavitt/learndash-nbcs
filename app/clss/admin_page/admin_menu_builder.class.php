<?php

namespace Doula_Course\App\Clss\Admin_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The Admin Menu Builder class
 */
Class Admin_Menu_Builder
{

	
	 /**
     * Build the Menus
     *
     * @return 
     */
    public function build( array $admin_menus ){
		
		foreach( $admin_menus as $admin_page ){
			if( is_array( $admin_page ) ){

				//Check if we have any subpages and remove then from the $admin_page array. 
				$subs = ( is_array( $admin_page[ array_key_last( $admin_page ) ] ) )?  array_pop( $admin_page ) : NULL ; 
				
				//Build the admin pages if not NULL. 
				
				$parent = ( !empty( $admin_page[ 1 ] ) )? $admin_page[ 1 ] : NULL; //Second value is slug. 
				
				if( !empty( $parent ) )
					$this->build_pages( $admin_page );	
				
				//Build Sub Pages 
				if( !empty( $subs ) ){
					foreach( $subs as $sub_page )
						$this->build_sub_pages( $sub_page, $parent );	
				}
			}	
		}
	}
	
	 
	 /**
     * Get the main_menu page object
     *
     * @return 
     */
    private function get_main_page_obj( ){
		
		return new Menu_Page();
		
	}
	 
	 /**
     * Get the submenu page object
     *
     * @return 
     */
    private function get_sub_page_obj( ){
		
		return new Submenu_Page();
		
	}
	 
	 
	 /**
     * Build the Main Menu Admin Pages
     *
     * @return 
     */
    private function build_pages( array $admin_page ){
				
		$main_menu = $this->get_main_page_obj();
		
		$main_menu->init( ...$admin_page );
		
		$this->add_main_menu( $main_menu );
		
	}
	 
	 /**
     * Build the Sub Menu Admin Pages
     *
     * @return 
     */
    private function build_sub_pages( array $sub_page, ?String $parent ){

		array_unshift( $sub_page, $parent );
		
		$sub_menu = $this->get_sub_page_obj();
		
		$sub_menu->init( ...$sub_page );
		
		$this->add_sub_menu( $sub_menu );
	}

    /**
     * Add a top level menu item.
	 *
     * @return 
     */
    private function add_main_menu( Menu_Page_Interface $menu_page ){
		
		$args = [ $menu_page->get_slug(), $menu_page->get_page_title() ];
				
		add_menu_page(
			$menu_page->get_page_title(),
			$menu_page->get_menu_title(),
			$menu_page->get_capability(),
			$menu_page->get_slug(),
			function() use( $args, $menu_page ) { $menu_page->render_page( $args ); },
			$menu_page->get_icon_url(),
			$menu_page->get_position()
		);		
	}

    /**
     * Add a sub level menu item
	 *
     * @return 
     */
    private function add_sub_menu( Submenu_Page_Interface $sub_menu ){
		
		$args = [ $sub_menu->get_slug(), $sub_menu->get_page_title() ];
		
		add_submenu_page(
			$sub_menu->get_parent_slug(),
			$sub_menu->get_page_title(),
			$sub_menu->get_menu_title(),
			$sub_menu->get_capability(),
			$sub_menu->get_slug(),
			function() use( $args, $sub_menu ) { $sub_menu->render_page( $args ); }
		);
		
	}
 
    /**
     *
	 *
     * @return 
     */
   /* 
    public function _(){
		
		return $;
		
	} 
	*/
	
	
}

?>