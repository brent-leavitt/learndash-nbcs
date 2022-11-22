//Admin Notes functionality for the student editor admin pages. 

jQuery( document ).ready( function ( $ ) {

	var $admin_notes = $( 'div.admin_notes' );
	var $converter = $( 'a.note_convert' ); 

	$converter.click( function( e ){
		e.preventDefault();

		var $old_notes = $converter.parent().prev('td.note');
		var $old_note_texts = $old_notes.text();
		var $converted = $old_note_texts.split(/\r?\n/);
		
		
		var $new_notes = '';  

		$.each( $converted, function( index, line ){
			$new_notes += build_row( 'hold', 'hold', line );
		});

		$converter.parent().parent( 'tr' ).remove();
		$admin_notes.find( 'tbody' ). prepend( $new_notes ); 

		//$old_notes.empty().text( $converted ); 
		//$converter.parent().prev('td.note').text( 'This is a test again!' ); 
		console.log( $converted ); 

	}) ; 

	 
}); 

function build_row( $first_name, $date, $note ){

	if( !$note ){
		return; 
	}
		
	let $actions = "<a href='#' class='mini_btn note_remove' >x</a><a href='#' class='mini_btn note_move_up' >&mapstoup;</a><a href='#' class='mini_btn note_move_down' >&mapstodown;</a>";

	return "<tr><td class='name' >" 
	+ $first_name +
	"</td><td class='date' >"
	+ $date +
	"</td><td class='note' >"
	+ $note +
	"</td><td class='actions'>"
	+ $actions +
	"</td></tr>";

}