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
 * //DISABLED FOR BUG - 16 Feb 2023
 * Not Admin  - THIS CREATES A BUG or CONFLICT with RCP plugin registration page. Maybe I don't need this now?!
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

//add_action('init', 'Doula_Course\App\Func\not_admin'); 


/**
 * Build Admin Pages
 *
 * each subset features 4 or 5 variables as follows: 
 * string $title, string $slug, string $icon, int $position, string $cap = 'edit_users' , [submenu]
 *
 * return void
 */

function build_admin_menus(){
	global $menu, $submenu;
	
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
	
	/*--- NEXT SECTION ---*/
	
	//print_pre( $submenu, 'The Admin Sub Menu Before' );
	//If is trainer role: 
	$roles = nb_get_current_user_roles(); 
	if( in_array( 'trainer', $roles ) ){
		
		$trainer_id = get_current_user_id(); 
		
		$submenu[ 'edit.php?post_type=assignment' ][ 5 ][ 0 ] = 'All Assignments'; 
		
		$asmt_base_url = 'edit.php?post_type=assignment';
		
		//second, intentionally out of order for use in the array_unshift foreach loop action. 
		$trainer_sub_menu[ 3 ] = [
			0 => 'My Graded Asmts',
			1 => 'edit_assignments',
			2 => 'edit.php?post_type=assignment&view=all_my_graded&trainer='.$trainer_id	,
		]; 
		
		//first
		$trainer_sub_menu[ 2 ] = [
			0 => 'My Assignments',
			1 => 'edit_assignments',
			2 => 'edit.php?post_type=assignment&view=all_my_pending&trainer='.$trainer_id,
		]; 
		
		//note refenced sub_array. 
		$asmts_arr = &$submenu[ $asmt_base_url ];
		
		foreach( $trainer_sub_menu as $tsm )
			array_unshift( $asmts_arr, $tsm ); 
		
		
		//unset menues for trainers
		
		$unset_menues = [ 'learndash-lms', 'edit.php', 'edit.php?post_type=portfolio' ]; 
		foreach( $unset_menues as $unset_string ){
			foreach( $menu as $menu_key => $menu_item ){
				if( array_search( $unset_string, $menu_item ) ) //Hide dashboard and Learndash from trainers?
					unset( $menu[ $menu_key ] );
			}
		}		
		
		//A little more clean up.
		//unset( $menu[ 4 ] ); //spacer
		unset( $menu[ 25 ] ); //comments
		
	}
}
 
 
add_action( 'admin_menu', 'Doula_Course\App\Func\build_admin_menus', 90 );

 
/**
 * filter_selected_asmt_submenu
 * 
 * This filters the submenus for the assignment CPT to highlight (class=current) for "my assignment" views. 
 * 
 * return $parent_file
 */


function filter_selected_asmt_submenu( $parent_file ){
    global $submenu_file;
	
    $roles = nb_get_current_user_roles(); 
	
	if( in_array( 'trainer', $roles ) ){
		if (isset($_GET['trainer']) && isset($_GET['view'])) 
			$submenu_file = 'edit.php?post_type=assignment&view=' . $_GET['view'] . '&trainer=' . $_GET['trainer'];
	}
	
    return $parent_file;
}

add_filter('parent_file', 'Doula_Course\App\Func\filter_selected_asmt_submenu');
 
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

/**
 * Add_Admin_Styles
 * 
 * This allows for the admin CSS to be added. 
 *
 * 
 * 
 * return void
 */


function nb_add_admin_style() {
    global $pagenow;     
		
	wp_register_style( 'nb_admin', plugin_dir_url( __DIR__ ) . 'tmpl/admin-styles.css', false, '1.0.0' );
	wp_enqueue_style( 'nb_admin' );

	if( strcmp( $pagenow, 'admin.php' ) === 0 
		&& isset( $_GET[ 'page' ] )  
		&& strcmp( $_REQUEST[ 'page' ], 'edit_student' ) === 0 )
		{
				
			wp_register_script( 'nb_admin_notes_script', plugin_dir_url( __DIR__ ) . 'tmpl/nb_admin_notes_script.js', array('jquery'), 1.0, true );
			wp_enqueue_script( 'nb_admin_notes_script' ); 
			
		}
		
}
add_action( 'admin_enqueue_scripts', 'Doula_Course\App\Func\nb_add_admin_style' );



?>
