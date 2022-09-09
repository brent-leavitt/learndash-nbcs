<?php
/**
 * Admin Page Title
 *
 * Available variables:
 *
 *  string $title
 */
 
/*  if( !isset( $args[ 1 ] ) )
	$args[] = NULL; */
 
 list( $title ) = $args;
?>

<div class="wrap">
	<h2>
		<?php print( $title ); ?>
	</h2>	