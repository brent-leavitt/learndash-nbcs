<?php 

namespace Doula_Course\App\Clss\Tables;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
 *  New Beginnings Table_Students class
 *  Created on 04 Feb 2021
 */

 
class Table_Students extends List_Table{

	private $trainer = 0;


	//Override default class constructor
	public function __construct(){
		
		parent::__construct( array(
		'singular'=>'student',
		'plural'=>'students',
		'ajax'=> true,
		'screen'=> 'all_students'
		
		));		
		
		$this->set_trainer(); 
	}

	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	 
	public function extra_tablenav( $which ) {
		if ( $which == "top" ){
			//The code that goes before the table is here	
			echo ' <form method="post">
				<input type="hidden" name="page" value="my_list_test" />';
				$this->search_box('search', 'search_id'); 
			echo '</form>';
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
			//echo"Hi, I'm after the table";
		}
	}
	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	public function get_columns() {
		$columns = array(
			'cb'      				=>	'<input type="checkbox" />', 
			'student_fullname'	=>	__( 'Display Name ( Full Name )' ),
			'student_email'		=>	__( 'Email' ),
			'student_date'		=>	__( 'Start Date' ),
			'student_status'		=>	__( 'Roles' ),
			'student_membership'	=>	__( 'Subscription' ),
			'student_grades'		=>	__( 'Grades' )
		);
		
		return $columns;
	}
	
	/**
	* Decide which columns to activate the sorting functionality on
	* @return array $sortable, the array of columns that can be sorted by the user
	*/
	
	//This needs some work.
 	public function get_sortable_columns() {
		return $sortable = array(
            'student_fullname'   => 'display_name',
			'student_email'    	=> 'user_email',
			'student_date'    	=> 'user_registered',
		);
	} 
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	public function prepare_items( ) {
		global $usersearch;
		
		$usersearch = isset( $_REQUEST['s'] ) ? trim( $_REQUEST['s'] ) : '';
		$screen = $_REQUEST['screen'] ?? '';
		
		switch( $screen ){
			case 'all_users': 
				$role = 'all'; 
				break;
			case 'all_students': 
				$role = 'student'; 
				break;
			case 'alumni': 
				$role = 'alumnus'; 
				break;
			case 'inactive':
				$role = 'inactive';
				break; 
			case 'my_students':
			default: 
				$role = '';
				break;		
		} 
		
		
		$trainer = $_REQUEST['trainer'] ?? $this->trainer;
		
		$users_per_page = $this->get_items_per_page( 'users_per_page' );

		$paged = $this->get_pagenum();		
				
		$args = array(
			'number' => $users_per_page,
			'offset' => ( $paged-1 ) * $users_per_page,
			'search' => $usersearch,
			'fields' => 'all_with_meta'
		);
		
		if ( '' !== $args['search'] )
				$args['search'] = '*' . $args['search'] . '*';

		$args['orderby'] = $_REQUEST['orderby'] ?? 'user_registered';			
		$args['order'] = $_REQUEST['order'] ?? 'desc';
		
		if( empty( $role ) ){
			$args[ 'meta_key' ] = 'student_trainer';
			$args[ 'meta_value' ] = $this->trainer;
		}
		
		//Final arguments to consider: STUDENT TYPE
		$args[ 'role' ] = $role ?? 'student';
		
		if( $role == 'all' ){
			$args[ 'role__in' ] = [ 'student', 'alumni', 'inactive' ];
			unset( $args[ 'role' ] ); 
		}
		
		// Query the user IDs for this page
		$wp_user_search = new \WP_User_Query( $args );
		$this->items = $wp_user_search->get_results();
				
		$this->set_pagination_args( array(
			'total_items' => $wp_user_search->get_total(),
			'per_page' => $users_per_page,
		) );	

	}
	
	/**
	 * Display the rows of students in the table
	 * @return string, echo the markup of the rows
	 */
	public function display_rows() {
		
		$views = $this->get_views();
		unset($views['administrator']); //Don't need to show the admin. 
		
		echo "<ul class='subsubsub'>\n";
		foreach ( $views as $class => $view ) {
			
			
			 $views[ $class ] = "\t<li class='$class'>$view";
		}
        echo implode( " |</li>\n", $views ) . "</li>\n";
        echo "</ul>";

		//Get the students registered in the prepare_items method
		$students = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methodsc
		list( $columns, $hidden ) = $this->get_column_info();


		$table_output = '';
		
		//Loop for each student
		if( !empty( $students ) ){
			
			foreach($students as $student){
		
				//Open the line
				$table_output .= '<tr id="student_'.$student->ID.'">';
				foreach ( $columns as $column_name => $column_display_name ) {

					//Style attributes for each col
					$class = "class='$column_name column-$column_name'";
					$style = "";
					if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
					$attributes = $class . $style;

					//edit link
					$editlink  = '/wp-admin/admin.php?page=edit_student&amp;student_id='.(int)$student->ID; //Not sure where this is being called...
					$email_page_link  = '/wp-admin/admin.php?page=email_student&amp;student_id='.(int)$student->ID; //Not sure where this is being called...

					//Display the cell
					switch ( $column_name ) {
						case "cb":	$table_output .=  '<th scope="row" class="check-column"><input type="checkbox" /></th>';	break;
						case "student_id":	$table_output .=  '<td '.$attributes.'>'.stripslashes($student->ID).'</td>';	break;
						case "student_fullname": $table_output .=  '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($student->display_name). ' ( '. $student->first_name .' '.$student->last_name.' )</a></td>'; break;
						case "student_email": $table_output .=  '<td '.$attributes.'><a href="'.$email_page_link.'">'.stripslashes($student->user_email).'</a></td>'; break;
						case "student_date": $table_output .=  '<td '.$attributes.'>'.stripslashes($student->user_registered).'</td>'; break;
						case "student_status": 
							$table_output .=  '<td '.$attributes.'>';
							$table_output .= implode( ', ', $student->roles );
							$table_output .=  '</td>'; 
							break; //fix these.
							
						case "student_membership": 					
							$table_output .=  '<td '.$attributes.'>'. rcp_show_user_columns( '(not set)', 'rcp_subscription', $student->ID ). '</td>'; 
							break; //fix these.
							
						case "student_grades": $table_output .=  '<td '.$attributes.'><a href="/wp-admin/admin.php?page=edit_grades&amp;student_id='.(int)$student->ID.'">grades</a></td>'; break;
						
					}
				}

				//Close the line
				$table_output .= '</tr>';
			}
		}
		
		print( $table_output );
	}
	
	/*
	*	Differnt Available Views:
	*
		- My Students (default) 
			-Trainer ID is either inherent in the current user role, or is set as an URL parameter. 
			-all users with role of student and user_meta = 'student_trainer' that matches trainer_id.
		- All Students (active only)
		- Alumni (active only)
		- Inactives (note whether student or alumni)
		- All (active and inactive)
	*
	*
	*
	* 	
	*/
	
	
	protected function get_views() {
 
		$url = 'admin.php?page=students';

		$students = nb_count_students( $this->trainer ) ; 
	
		$current_view = $_GET[ 'screen' ] ?? 'my_students';
		
        $view_links = array();
		foreach( $students as $view => $num ){
			
            $class = ( $current_view == $view )? ' class="current"' : '';
			$name = ucwords( str_replace( '_', ' ', $view ) ); 
            $name = sprintf( __('%1$s <span class="count">(%2$s)</span>'), $name, number_format_i18n( $num ) );
            $view_links[$view] = "<a href='" . esc_url( add_query_arg( 'screen', $view, $url ) ) . "'$class>$name</a>";
		}
	
 
        return $view_links;
    }
	
	
	private function set_trainer(){
		
		$this->trainer = nb_get_current_trainer_id(); 
		
	}
	
	
} /* END Table_Students class */

?>