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
			'cb'      			=>	'<input type="checkbox" />', 
			'id'				=>	__( 'ID' ),
			'date'				=>	__( 'Date' ),
			'amount'			=>	__( 'Amount' ),
			'subscription'		=>	__( 'Subscription' ),
			'status'			=>	__( 'Status' ),
			'payment_type'		=>	__( 'Source' ),
			'transaction_type'	=>	__( 'Type' )
		);
		
		return $columns;
	}
	
	
	public function prepare_items(){
		global $wpdb;
		
		//$test_query = $wpdb->get_results('SELECT * FROM nb_transactions WHERE student_id='.$student_id);
		
		$this->items = $wpdb->get_results('SELECT * FROM wp_rcp_payments WHERE user_id='.$this->student_id.' LIMIT 50');
	
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
			$table_output .= '<tr id="transaction_'.intval($transaction->id).'">';
			foreach ( $columns as $column_name => $column_display_name ) {

				//Style attributes for each col
				$class = "class='$column_name column-$column_name'";
				$style = "";
				if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
				$attributes = $class . $style;

				//edit link
				$editlink  = '/wp-admin/admin.php?page=rcp-payments&amp;payment_id='.intval($transaction->id). '&amp;view=edit-payment'; //Not sure where this is being called...

				
				
				/*
					$columns = array(
						'cb'      			=>	'<input type="checkbox" />', 
						'id'				=>	__( 'ID' ),
						'date'				=>	__( 'Date' ),
						'amount'			=>	__( 'Amount' ),
						'subscription'		=>	__( 'Subscription' ),
						'status'			=>	__( 'Status' ),
						'payment_type'		=>	__( 'Payment Type' ),
						'transaction_type'	=>	__( 'Trans Type' )
					);
		
				*/
				
				
				//Display the cell
				switch ( $column_name ) {
					case "cb":	$table_output .=  '<th scope="row" class="check-column"><input type="checkbox" /></th>';	break;
					case "id":	$table_output .=  '<td '.$attributes.'><a href="'.$editlink.'" target="_blank" >ID: '.stripslashes($transaction->id).'</a></td>';	break;
					case "date": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->date).'</td>'; break;
					case "amount": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->amount).'</td>'; break;
					case "subscription": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->subscription).'</td>'; break;
					case "status": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->status).'</td>'; break;
					case "payment_type": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->payment_type).'</td>'; break;
					case "transaction_type": $table_output .=  '<td '.$attributes.'>'.stripslashes($transaction->transaction_type).'</td>'; break;
					
					
					
				}
			}

			//Close the line
			$table_output .= '</tr>';
		}}
		
		print( $table_output );
	}
	
	
//Lifted from RCP and then modified. 

	
	/**
	 * Render the main ID column.
	 *
	 * @param object $payment
	 *
	 * @since 3.1
	 * @return string
	 */
	public function column_id( $payment ) {

		$edit_url              = add_query_arg( array(
			'payment_id' => absint( $payment->id ),
			'view'       => 'edit-payment'
		), $this->get_base_url() );
		
		
		// Link to edit payment.
		$actions = array(
			'edit' => '<a href="' . esc_url( $edit_url ) . '" title="' . esc_attr__( 'Edit payment', 'rcp' ) . '">' . __( 'Edit', 'rcp' ) . '</a>'
		);
	

		/**
		 * Filters the row actions.
		 *
		 * @param array  $actions Default actions.
		 * @param object $payment Payment object.
		 *
		 * @since 3.1
		 */
		$actions = apply_filters( 'rcp_payments_list_table_row_actions', $actions, $payment );

		$final = '<strong><a href="' . esc_url( $edit_url ) . '" title="' . esc_attr__( 'Edit payment', 'rcp' ) . '">' . esc_html( $payment->id ) . '</a></strong>';

		if ( current_user_can( 'rcp_manage_payments' ) ) {
			$final .= $this->row_actions( $actions );
		}

		return $final;

	}

	/**
	 * Message to be displayed when there are no payments.
	 *
	 * @since 3.1
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No payments found.', 'rcp' );
	}
	
}

?>