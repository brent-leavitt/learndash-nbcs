<?php
/**
 * Admin Page Top Menu
 *
 * Available variables:
 *
 *  string $slug
 */
 
 
if ( ! defined( 'ABSPATH' ) ) { exit; }
 
if( !isset( $args[ 0 ] ) )
	$args[] = NULL;

list( $slug ) = $args;
$student_id = absint( $_GET[ 'student_id' ] );

switch( $slug ):
	case 'edit_student': ?>

<ul class="subsubsub">
	<li class="assignments"><a href="edit.php?post_type=assignment&student_id=<?php echo $student_id; ?>" target="_blank">Assignments</a> |</li>
	<li class="grades"><a href="admin.php?page=edit_grades&student_id=<?php echo $student_id; ?>" target="_blank">Grades</a> |</li>
	<li class="payments"><a href="admin.php?page=rcp-members&edit_member=<?php echo $student_id; ?>" target="_blank">Payments &amp; Subscriptions</a> </li>
</ul>

<br>

<?php break;	
endswitch;

?>