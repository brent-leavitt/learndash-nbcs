<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The abstract form builder class
 * This sets up the basics of forms. This is actually the director in the director/builder pair. 
 */
 
abstract class Abstract_Form_Builder
{
    abstract function __construct();
	
	abstract function set_form( string $slug );
	
	abstract function get_form();
	
	abstract function build_form();

}


?>
