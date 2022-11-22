<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base field building class in the Doula Course Plugin
 * This builds HTML fields.  
 
 //Test JSON 
 //[{"uid":1,"date":"2022-09-14 00:00:00","note":"The first note."},{"uid":2,"date":"2022-10-07 00:00:00","note":"This account has been suspended."},{"uid":1,"date":"2022-11-01 00:00:00","note":"Certificate is being issued today."},{"uid":0,"date":"2022-11-01 00:00:00","note":"System generated comment about the student."}]
 
//Test PHP Serialized
a:4:{i:0;a:3:{s:3:"uid";i:1;s:4:"date";s:19:"2022-09-14 00:00:00";s:4:"note";s:15:"The first note.";}i:1;a:3:{s:3:"uid";i:2;s:4:"date";s:19:"2022-10-07 00:00:00";s:4:"note";s:32:"This account has been suspended.";}i:2;a:3:{s:3:"uid";i:1;s:4:"date";s:19:"2022-11-01 00:00:00";s:4:"note";s:34:"Certificate is being issued today.";}i:3;a:3:{s:3:"uid";i:0;s:4:"date";s:19:"2022-11-01 00:00:00";s:4:"note";s:43:"System generated comment about the student.";}}
 
 
 */
 
class Special_Field
{
	
	/**
	 *
	 * @since 2.0
	 * @var string
	 */
	private $output = NULL;	 
	
	
	
	/**
	 *
	 * @since 2.0
	 * @var string
	 */
	private $name = NULL;	 
	
	
	/**
	 *
	 * @since 2.0
	 * @var MIXED 
	 */
	private $val = NULL;	 
	
	
 
	
	
    /**
     * Builds 
     *
     * @return void
     */	

	public function build( string $name, $val = '' )
	{
		//Temporary Holding Pattern;
		//$val = 'a:4:{i:0;a:3:{s:3:"uid";i:1;s:4:"date";s:19:"2022-09-14 00:00:00";s:4:"note";s:15:"The first note.";}i:1;a:3:{s:3:"uid";i:2;s:4:"date";s:19:"2022-10-07 00:00:00";s:4:"note";s:32:"This account has been suspended.";}i:2;a:3:{s:3:"uid";i:1;s:4:"date";s:19:"2022-11-01 00:00:00";s:4:"note";s:34:"Certificate is being issued today.";}i:3;a:3:{s:3:"uid";i:0;s:4:"date";s:19:"2022-11-01 00:00:00";s:4:"note";s:43:"System generated comment about the student.";}}';
		
		//Another temp value (non serialized)
		$val ="Trainer: Cherylyn

Christy is part of Amanda's group.

12 May 2022 - Coaching call. No answer. - Cherylyn
13 May 2022 - Coaching call. Christy is a mother of 6 and she wants to have one more child. She wants to give back to people, something that she never had. She was fortunate not to have any c-sections, but she feels it's important to provide support to others so they can have the positive birth experiences she wished she could have had. If she can have another baby, she'd love to do it naturally and in nature. In her last pregnancy she didn't go to the doctor much because she felt confident in her body and her ability to give birth. She felt a lot of pressure from the doctor to do things their way. She would prefer a home setting. She was induced with all of her pregnancies, and she wishes she could have waited and let her body experience labor naturally. She's ordered her books and they got lost in the mail. She's waiting for the replacements to arrive. - Cherylyn
20 Jul 2022 - Payment subscription has been suspended. Student account moved to inactive status. Notice Sent. 
22 Jul 2022 - (Brent) Billing for Michelle to be resumed by It Takes a Village/Amanda Rhodes. I've sent Amanda Rhodes an invoice to start payments. 
22 Jul 2022 - (Brent) Invoice Paid and Account Reactivated. 
26 Sep 2022 - Christy's payments have been paused because she only has the birth packets left to turn in. - Cherylyn"; 
		
		
		$this->name = $name;
		$this->val =  $val ?? '';
		
		$rows = $this->sort_values(); 
		
		
		$user = wp_get_current_user(); 
		
		$form = "<tr>
					<td class='name' >". $user->first_name ."</td>
					<td class='date' >". current_time( 'mysql' ) ."</td>
					<td class='note' ><textarea id='admin_notes_row' class='admin_notes_row' placeholder='Enter new admin note here.' > </textarea></td>
					<td class='actions' ><a href='#' class='mini_btn add_note' >&#10095;</a></td>
				</tr>";
		
		//Structure: 
		$structure = "
			<div class='admin_notes'>
				<table col='4' >
					<thead>
						<tr>
							<th class='name' >Source</th>
							<th class='date' >Date</th>
							<th class='note' >Note</th>
							<th class='actions' >Actions</th>
						</tr>
					</thead>
					<tbody>
						$rows $form
					</tbody>
				</table>
			</div>
		";
		
		$hidden = '<input type="hidden" id="admin_notes" name="admin_notes" value="'. $val. '" >'; //Stores the incoming data as the default value.
			
		$this->output = $structure . $hidden; 
		
	}

	
	
	
    /**
     * Processes Incoming Value
     *
     * @return string
     */
	 
    private function sort_values(): string 
	{
		
		$rows = '';
		
		if( !is_serialized( $this->val ) ){
			
			$rows = $this->build_row( __('(old admin notes)', NBCS_TD ), __('(not set)', NBCS_TD ), $this->val, 'convert' ); 
			
		}else{
			
			$this->val = unserialize( $this->val ); 
			
			 
			foreach( $this->val as $row ){
				extract( $row );
				
				$user = get_user_by( 'id', $uid ); 			
				$first_name = ( is_object( $user ) )? $user->first_name ?? $user->nickname  : '('. __( 'system', NBCS_TD). ')'; 
				
				$rows .= $this->build_row( $first_name, $date, $note ); 
				
			} 	
				
		}
		
		return $rows;
 
	}
	
	
	
	
    /**
     * Builds a single row in the admin notes section. 
     *
     * @return string
     */
	 
    private function build_row( string $first_name, string $date, string $note, string $action = 'default' ): string 
	{
		
		switch( $action ){
			case 'convert': 
				$actions = "<a href='#' class='mini_btn note_convert' >Convert</a>"; 
				break;
			
			case 'default':
			default: 
				$actions = "<a href='#' class='mini_btn note_remove' >x</a> 
					<a href='#' class='mini_btn note_move_up' >&mapstoup;</a>
					<a href='#' class='mini_btn note_move_down' >&mapstodown;</a>";
				break; 
			
		}
		
		
		$row = "<tr>
				<td class='name' >$first_name</td>
				<td class='date' >$date</td>
				<td class='note' >$note</td>
				<td class='actions'>
					$actions
				</td>
			</tr>";	
		
		
		
		return $row;
 
	}
		
	
	
    /**
     * 
     *
     * @return string
     */
    public function get( ): string 
	{
		
		return $this->output;
 
	}
	
	
}


?>