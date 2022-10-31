

jQuery( document ).ready( function ( $ ) {

	var $delete_form = $( "#delete_assignment" );

  $delete_form.on( 'submit', function( e ){
		e.preventDefault();
		$delete_form.children( '#delete_assignment_submit' ).remove(); 
		$delete_form.append( '<div class="verify_delete" ><p>Are you sure? Type <b>"DELETE"</b> to delete your current work, all attachments, and all comments connected to this assignment. (This cannot be UNDONE!)</p> <input type="text" id="verify_delete_input" value=""><input type="button" class="button" value="YES, I WANT A FRESH START"></div>' ); 
	} );  

}); 