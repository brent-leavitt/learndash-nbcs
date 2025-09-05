//Admin Notes functionality for the student editor admin pages. 

jQuery( document ).ready( function ( $ ) {

	let $admin_notes = $( 'div.admin_notes' );
	let $converter = $( 'a.note_convert' ); 

	$converter.click( function( e ){
		e.preventDefault();
	} );

	let $old_notes = $converter.parent().prev('td.note');
	let $old_note_texts = $old_notes.text();
	let $converted = $old_note_texts.split(/\r?\n/);
	let $new_notes = '';  

	$.each( $converted, function( index, line ){
		$new_notes += break_apart( line );
	});

	let $new_texts = "<tr><td class='name' >(old admin notes)</td><td class='date' >(n/a)</td><td class='note' >"
	+ $new_notes +
	"</td><td class='actions'>(code not ready to convert)</td></tr>";

	$converter.parent().parent( 'tr' ).remove();

	if( $converter.length !== 0 )
		$admin_notes.find( 'tbody' ). prepend( $new_texts ); 

/*	//We're going to wait on this. For right now, just reformat incoming texts for display. 
	$converter.click( function( e ){
		e.preventDefault();

		let $old_notes = $converter.parent().prev('td.note');
		let $old_note_texts = $old_notes.text();
		let $converted = $old_note_texts.split(/\r?\n/);
		
		
		let $new_notes = '';  

		$.each( $converted, function( index, line ){
			//Create a list of filters
			//Filter out Date
			//Filter out user
			//Filter out note
			//discard the rest. 

			$new_notes += build_row( 'hold', 'hold', line );
		});

		$converter.parent().parent( 'tr' ).remove();
		$admin_notes.find( 'tbody' ). prepend( $new_notes ); 

		//$old_notes.empty().text( $converted ); 
		//$converter.parent().prev('td.note').text( 'This is a test again!' ); 
		console.log( $converted ); 

	}) ; 

*/


	 
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

function break_apart( $note ){

	if( !$note ){
		return ''; 
	}
		
	return "<p>" + $note + "</p>";

}