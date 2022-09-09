<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The abstract form builder class
 * This sets up the basics of forms. This is actually the director in the director/builder pair. 
 */
 
abstract class Abstract_Field_Director
{
    abstract function __construct( Abstract_Field_Builder $field_builder );
	
	

}


?>
