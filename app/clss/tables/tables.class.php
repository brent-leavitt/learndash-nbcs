<?php 

/*
 *  New Beginnings Tables PHP Classes
 *	Created on 10 May 2013
 *  Updated on 18 July 2013
 */

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}

class Students_Tables extends WP_List_Table{

	//Override default class constructor
	public function __construct(){
		
		//$screen = ( isset( $GLOBALS['hook_suffix'] ) )? get_current_screen(): null;
		
		
		
		parent::__construct( array(
		'singular'=>'student',
		'plural'=>'students',
		'ajax'=> true,
		'screen'=> array( 'id' => 'toplevel_page_students' )
		
		));		
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
			'student_fullname'	=>	__( 'Name' ),
			'student_email'		=>	__( 'Email' ),
			'student_date'		=>	__( 'Start Date' ),
			'student_status'		=>	__( 'Status' ),
			'student_payment'	=>	__( 'Last Payment Received' ),
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
	public function prepare_items($student_type = NULL) {
		global $usersearch;
		
		$usersearch = isset( $_REQUEST['s'] ) ? trim( $_REQUEST['s'] ) : '';
		$role = isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : '';
		$users_per_page = $this->get_items_per_page( 'users_per_page' );

		$paged = $this->get_pagenum();

		/*
		 * Pagination 
		*/
		$per_page = 5;
		$current_page = $this->get_pagenum();
		$total_items = count($this->example_data);
		 
		// only ncessary because we have sample data
		if( !empty( $this->example_data ) ){
			$this->found_data = array_slice($this->example_data,(($current_page-1)*$per_page),$per_page);		
		}
		 
		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;
		
				
		$args = array(
			'number' => $users_per_page,
			'offset' => ( $paged-1 ) * $users_per_page,
			/* 'cap' => 'student',  */
			//'exclude' => 1, //exclude super admin who's userId is 1.
			'search' => $usersearch,
			'fields' => 'all_with_meta'
		);
		
		if ( '' !== $args['search'] )
				$args['search'] = '*' . $args['search'] . '*';

		if ( isset( $_REQUEST['orderby'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
		} else {
			$args['orderby'] = 'user_registered';
		}
				
		if ( isset( $_REQUEST['order'] ) ){
			$args['order'] = $_REQUEST['order'];
		} else {
			$args['order'] = 'desc';
		}
		

		//Final arguments to consider: STUDENT TYPE
		
		if( !empty( $student_type ) ){
			
			$args['role'] = $student_type; 
			
		} else {
		
			$args['cap'] = 'student';
				
		}
		
				
		// Query the user IDs for this page
		$wp_user_search = new WP_User_Query( $args );

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
		if(!empty($students)){foreach($students as $student){
/*  		print('<pre>');
			print_r($student);
			print('</pre>'); */
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
					case "student_fullname": $table_output .=  '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($student->display_name).'</a></td>'; break;
					case "student_email": $table_output .=  '<td '.$attributes.'><a href="'.$email_page_link.'">'.stripslashes($student->user_email).'</a></td>'; break;
					case "student_date": $table_output .=  '<td '.$attributes.'>'.stripslashes($student->user_registered).'</td>'; break;
					case "student_status": $table_output .=  '<td '.$attributes.'>';
						$table_output .=  ($student->allcaps['student_current'] == 1)? 'Current' : 'Inactive';
						$table_output .=  '</td>'; break; //fix these.
					case "student_payment": 
					
						$rcvd = get_user_meta($student->ID, 'payments_received', true);
						$rcvd = ($rcvd === '1/1' )? 'full' : $rcvd;
						$rcvd = ($rcvd === '12/12' )? 'full' : $rcvd;
						
						$lastPymt = get_user_meta($student->ID, 'last_payment_received', true);
						$lastPymtTime = strtotime($lastPymt);
						//30 days = (30 * 24 * 60 * 60)
						$currentTime = time();
						$_30DaysBack = $currentTime - (30 * 24 * 60 * 60); 
						$_60DaysBack = $currentTime - (60 * 24 * 60 * 60); 
						
						$lateNote = '';
						
						if(($lastPymtTime < $_30DaysBack ) && ($rcvd != 'full')){
							$lateNote = ' style="color: orange;"';
						}
						
						if(($lastPymtTime < $_60DaysBack ) && ($rcvd != 'full')){
							$lateNote = ' style="color: red;"';
						}
						
						
						
						//if last payment is more than 30 days ago. 
						
						//if last payment is more than 60 days ago. 
						
						
						$table_output .=  '<td '.$attributes.'><span'.$lateNote.'>'.$lastPymt.'('. $rcvd . ')</span></td>'; 
						break; //fix these.
					case "student_grades": $table_output .=  '<td '.$attributes.'><a href="/wp-admin/admin.php?page=edit_grades&amp;student_id='.(int)$student->ID.'">grades</a></td>'; break;
					
				}
			}

			//Close the line
			$table_output .= '</tr>';
		}}
		
		print( $table_output );
	}
	
	protected function get_views() {
        global $wp_roles, $role;
 
		if( isset( $_GET['role']) ) {
			$role = $_GET['role'];
		}
		$url = 'admin.php?page=students';
		$users_of_blog = count_users();
   
        $total_users = $users_of_blog['total_users']-1;
        $avail_roles =& $users_of_blog['avail_roles'];
        unset($users_of_blog);
 
        $class = empty($role) ? ' class="current"' : '';
        $role_links = array();
        $role_links['all'] = "<a href='$url'$class>" . sprintf( _nx( 'All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_users, 'users' ), number_format_i18n( $total_users ) ) . '</a>';
        foreach ( $wp_roles->get_names() as $this_role => $name ) {
            if ( !isset($avail_roles[$this_role]) )
                continue;
				
            $class = '';
 
            if ( $this_role == $role ) {
                $class = ' class="current"';
            }
 
            $name = translate_user_role( $name );
            /* translators: User role name with count */
            $name = sprintf( __('%1$s <span class="count">(%2$s)</span>'), $name, number_format_i18n( $avail_roles[$this_role] ) );
            $role_links[$this_role] = "<a href='" . esc_url( add_query_arg( 'role', $this_role, $url ) ) . "'$class>$name</a>";
        }
 
        return $role_links;
    }
	
} /* END STUDENT_TABLES class */


class Transaction_Tables extends WP_List_Table{

	//Override default class constructor
	public function __construct(){
		
		//$screen = ( isset( $GLOBALS['hook_suffix'] ) )? get_current_screen(): null;
		
		
		
		parent::__construct( array(
		'singular'=>'transaction',
		'plural'=>'transactions',
		'ajax'=> true,
		'screen'=> array( 'id' => 'toplevel_page_students' )
		
		));		
	}

		/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	 
	public function extra_tablenav( $which ) {
		$sid = (isset($_REQUEST['student_id']))?$_REQUEST['student_id']:null;
	
		if ( $which == "top" ){
			//The code that goes before the table is here	
			
			if($sid != null)
				echo '<div class="new_trans"><a class="secondary" href="admin.php?page=add_transaction&amp;student_id='.$sid.'" >Add New Transaction</a></div>';
			echo ' <form method="post">
				<input type="hidden" name="transaction" value="student_transactions_list" />';
/* 				$this->search_box('search', 'search_id');  */
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
			'trans_id'				=>	__( 'ID' ),
			'trans_date'			=>	__( 'Date' ),
			'trans_amount'			=>	__( 'Amount' ),
			'trans_label'			=>	__( 'Description' ),
			'trans_type'			=>	__( 'Type' )
		);
		
		return $columns;
	}
	
	
	public function prepare_items(){
		global $wpdb;
		
		$student_id = $_REQUEST['student_id'];
		
		$test_query = $wpdb->get_results('SELECT * FROM `transactions` WHERE student_id='.$student_id);
		

		
		$this->items = $wpdb->get_results('SELECT * FROM transactions WHERE student_id='.$student_id.' LIMIT 40');
	
	}
	
	public function display_rows() {

		//Get the students registered in the prepare_items method
		$transactions = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();


		$table_output = '';
		
		//Loop for each student
		if(!empty($transactions)){foreach($transactions as $transaction){
/* 			print('<pre>');
			print_r($transaction);
			print('</pre>'); */
			//Open the line
			$table_output .= '<tr id="transaction_'.intval($transaction->transaction_id).'">';
			foreach ( $columns as $column_name => $column_display_name ) {

				//Style attributes for each col
				$class = "class='$column_name column-$column_name'";
				$style = "";
				if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
				$attributes = $class . $style;

				//edit link
				$editlink  = '/wp-admin/admin.php?page=edit_transaction&amp;trans_id='.intval($transaction->transaction_id); //Not sure where this is being called...

				//Display the cell
				switch ( $column_name ) {
					case "cb":	$table_output .=  '<th scope="row" class="check-column"><input type="checkbox" /></th>';	break;
					case "trans_id":	$table_output .=  '<td '.$attributes.'><a href="'.$editlink.'">'.stripslashes($transaction->transaction_id).'</a></td>';	break;
					case "trans_date": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->trans_time).'</td>'; break;
					case "trans_amount": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->trans_amount).'</td>'; break;
					case "trans_label": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->trans_label).'</td>'; break;
					case "trans_type": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->trans_type).'</td>'; break;
					
					
				}
			}

			//Close the line
			$table_output .= '</tr>';
		}}
		
		print( $table_output );
	}
	
}



/*
			'student_id'=>__('ID'),
			'student_username'=>__('Username'),
			'student_fullname'=>__('Name'),
			'student_email'=>__('Email'),
			'student_status'=>__('Status'),
			'student_payment'=>__('Payments')

*/
?>