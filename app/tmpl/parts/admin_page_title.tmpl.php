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
 
?>

<div class="wrap">
	<h2><?php print( $title ); ?>
		
		<?php if( $addNewLink != null ):?>
				<a class="add-new-h2" href="admin.php?page=<?php print( $addNewLink ); ?>">Add New</a>
		<?php endif; ?>
	</h2>	