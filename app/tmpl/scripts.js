//Delete validation functions on the student assignment editor pages. 

jQuery( document ).ready( function ( $ ) {

	var $delete_form = $( "#delete_assignment" );

	$delete_form.on( 'submit', function( e ){
		e.preventDefault();
		var $delete_submit_btn = $delete_form.children( '#delete_assignment_submit' );
		$delete_submit_btn.remove();

		if( $( '.verify_delete' ).length === 0 ){
			var $confirm_delete_form = $( '<div class="verify_delete" ><p>Are you sure? Type <b>"DELETE"</b> to delete your current work, all attachments, and all comments connected to this assignment. (THIS CANNOT BE UNDONE!)</p> <input type="text" id="verify_delete_input" value=""><input id="confirm-delete" type="submit" class="button" name="delete_assignment" value="YES, I WANT A FRESH START"><input type="button" id="reject-delete" class="button button-secondary" value="NO, DO NOT DELETE"></div>' );

			$delete_form.append( $confirm_delete_form ); 
		
		
			$( 'input#reject-delete' ).on( 'click', function( e1 ){
				$confirm_delete_form.remove();
				$delete_form.append( $delete_submit_btn ); 
				

			}); //end input#reject-delete' on click function
			
			$( 'input#confirm-delete' ).on( 'click', function( e1 ){
				var $verify_delete_input =  $( 'input#verify_delete_input' );
				var $confirm_delete_value = $verify_delete_input.val();
				if( $confirm_delete_value.localeCompare( 'DELETE' ) === 0 ){
					
					$delete_form.unbind( 'submit' ).submit();
					
				} else {
					$verify_delete_input.addClass( 'error' ); 	
				}

			}); //end input#confirm-delete' on click function
		}
	});// end $delete_form.on submit function  

	
	$( ".delete-attachment" ).each( function( index, element ){
				
		$( element ).parent().on( 'submit', function( e2 ){
			e2.preventDefault();
			$( element ).removeClass( 'button-secondary' ); 
			$( element ).val( 'Delete?' );
			if( $( this ).children( '.reject_attach_delete' ).length === 0 ){
				$( this ).append( '<input type="button" class="reject_attach_delete button-secondary" title="Do not remove." value="x" />' ); 
				
				$( element ).on( 'click', function( e3 ){
					$( element ).parent().unbind( 'submit' ).submit();
				}); 
				
				
				$( this ).children( 'input.reject_attach_delete' ).on( 'click', function( e1 ){
					$( this ).remove();
					$( element ).addClass( 'button-secondary' ); 
					$( element ).val( 'x' );

				}); //end input#reject_attach_delete' on click function
			}
		
		});//end element parent on submit function

	});//end .delete-attachment each function 
}); 