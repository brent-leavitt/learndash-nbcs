jQuery(document).ready( function($){

	$( '#track-courses-div select' ).change( function(){
		var select_id = $( this ).attr( 'id' ).substring(14); //returns just the ID number. 
		var option_id = $( this ).find( ':selected' ).val();
		var post_id = $( 'input#post_ID' ).val(); 
		//alert( 'The select box changed! '+ select_id + ':' + option_id );
		
		
		data = {
			action: 'track_courses_action',
			nonce: ajax_object.nonce,
			track_id: post_id,
			course_position: select_id,
			course_id: option_id
		};
		
		$.post( ajax_object.ajax_url, data, function( response ) {
			alert( response );
			//$( '#track-courses-div select' ).find('span.result').text( response ); 
		});
		
	});


});