<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The interface for preparing form values 
 * This sets up the values to be passed into forms.
 */
 
interface Form_Values_Interface
{
    public function __construct( );
	
	public function build_values();
	
	public function get_values();
	
	

}


?>
