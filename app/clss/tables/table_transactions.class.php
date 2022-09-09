<?php 

namespace Doula_Course\App\Clss\Tables;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
 *  New Beginnings Table_Transactions class
 *  Created on 04 Feb 2021
 */


class Table_Transactions extends List_Table{

	
	/**
	* student_id
	*
	* @since 2.0
	* @var int
	*/
	private $student_id = 0; 


	//Override default class constructor
	public function __construct(){
		
		//$screen = ( isset( $GLOBALS['hook_suffix'] ) )? get_current_screen(): null;
	
		
		
		parent::__construct( array(
		'singular'=>'transaction',
		'plural'=>'transactions',
		'ajax'=> true,
		'screen'=> 'student_transactions'
		
		));		
		
		$this->set_student_id();
		
	}

	
		/**
		* set_student_id
		*
		* @return void
		*/
		public function set_student_id():void
		{
			
			$this->student_id = $_REQUEST['student_id'] ?? NULL;
		}
	
	
		/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	 
	public function extra_tablenav( $which ) {
		
	
		if ( $which == "top" ){
			//The code that goes before the table is here	
			
			if( $this->student_id !== 0 )
				echo '<div class="new_trans"><a class="secondary" href="admin.php?page=add_transaction&amp;student_id='.$this->student_id.'" >Add New Transaction</a></div>';
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
		
		//$test_query = $wpdb->get_results('SELECT * FROM nb_transactions WHERE student_id='.$student_id);
		
		$this->items = $wpdb->get_results('SELECT * FROM nb_transactions WHERE student_id='.$this->student_id.' LIMIT 50');
	
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

?>