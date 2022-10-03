<?php 

namespace Doula_Course\App\Func;

//To enable namespacing, add_actions need attention.

use Doula_Course\App\Clss\Admin_Page\Admin_Menu_Builder as Admin_Menu_Builder;
use Doula_Course\App\Clss\Admin_Page\Admin_Page_Director as Admin_Page_Director;
use Doula_Course\App\Clss\Admin_Page\Admin_Page_Builder as Admin_Page_Builder;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * These functions are only loaded on the admin pages. A check is in place to call to see if admin pages are being loaded. 
 */


/**
 * Not Admin
 * 
 * This filters out users that are not admins and sends them out of the admin area. 
 * 
 * return false
 */
 
function not_admin()
{
	if( is_user_logged_in() && !current_user_can( 'edit_posts' ) )
	{
			$site_url = site_url();
			wp_redirect($site_url); 
			exit; 
	}
	return false;
}

add_action('init', 'Doula_Course\App\Func\not_admin'); 


/**
 * Build Admin Pages
 *
 * each subset features 4 or 5 variables as follows: 
 * string $title, string $slug, string $icon, int $position, string $cap = 'edit_users' , [submenu]
 *
 * return void
 */

function build_admin_menus(){
	
	
	$admin_menus = [
	
	
		[ 'Students Overview', 'students', 'heart', 34, 	// Student Overview Table 
			[
				['Add Student', 'new_student'],				// Add New Students
				['Location Search', 'student_location']		// Student Search By Location
			]
		],
		
		[ NULL, 											// Subpages that don't show up in main admin menu.
			[
				['Email Student', ''],						// Email an individual student
				['Edit Student', ''],						// Edit an individual student profile
				['Add New Transaction', 'add_transaction'],	// Add a trannsaction to a student profile
				['Edit Transaction', ''],					// Edit an existing transaction
				['Edit Grades', '']							// Grades Editor for an individual student. 
			]
		], 
		
		[ 'Code Sandbox', 'sandbox', 'hammer', 95, 	// Coding Sandbox Page. 
			[]												// Space for subpages if needed. 
		]
		/*, 
		[ '', '', '', '', 						//
			['', ''],							//
			['', ''],							//
		], */
		
	];
	
	$builder = new Admin_Menu_Builder();
	
	$builder->build( $admin_menus );

}
 
 
add_action( 'admin_menu', 'Doula_Course\App\Func\build_admin_menus' );

 
/**
 * Render Admin Page
 * 
 * Builds the objects for admin page menu setup. 
 * 
 * return void
 */
 
function render_admin_page( string $slug, string $title ){
	
	$director = new Admin_Page_Director( $slug );
	$builder = new Admin_Page_Builder( $title);
	$director->set_builder( $builder );

	$director->build();	
	$builder->get_page()->render();
	
}

 
/**
 * Add Admin Menu Separator
 * 
 * This sets the code that will insert a new menu spacer in the admin menu. 
 * 
 * return void
 */

function add_admin_menu_separator( int $position ): VOID
{

	global $menu;
	$index = 0;

	foreach($menu as $offset => $section) {
		if ( substr( $section[ 2 ], 0, 9 ) == 'separator' )
			$index++;
		if ( $offset >= $position ) {
			$menu[ $position ] = array( '', 'read' ,"separator{$index}", '', 'wp-menu-separator' );
			break;
		}
	}

	ksort( $menu );
}



 
/**
 * Add Menu Spacers
 * 
 * This allows for the setting of multiple spacers in the admin menu. 
 *
 * NOTE: if the separator position is the same as the as an existing menu item, 
 * the existing menu item will be overridden. 
 * 
 * return void
 */

function admin_menu_spacers(): VOID
{

	add_admin_menu_separator( 29 );
	add_admin_menu_separator( 31 );
	add_admin_menu_separator( 41 );
	
}

add_action( 'admin_menu', 'Doula_Course\App\Func\admin_menu_spacers' );



?>
