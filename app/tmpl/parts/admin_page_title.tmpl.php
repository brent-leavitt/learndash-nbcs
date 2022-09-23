<?php
/**
 * Admin Page Title
 *
 * Available variables:
 *
 *  string $title
 */
 
if( !isset( $args[ 1 ] ) )
	$args[] = NULL;
 
list( $title, $addNewLink ) = $args;
 
//get user name. 
if( !empty( $_GET[ 'student_id' ] ) )
	 $sid = $_GET[ 'student_id' ];
 
if( !empty( $sid ) ){
	$s_meta = get_user_meta( $sid );
	$s_name = '';
	$s_name .= !empty( $s_meta[ 'first_name' ][0] ) ? $s_meta[ 'first_name' ][0] : ''; 
	$s_name .= !empty( $s_meta[ 'last_name' ][0] ) ? ' '. $s_meta[ 'last_name' ][0] : '';	
}
 
if( !empty( $s_name ) )
	$title .= ": <i>{$s_name}</i>";
 
?>

<div class="wrap">
	<h2><?php print( $title ); ?>
		
		<?php if( $addNewLink != null ):?>
				<a class="add-new-h2" href="admin.php?page=<?php print( $addNewLink ); ?>">Add New</a>
		<?php endif; ?>
		
		
	</h2>	