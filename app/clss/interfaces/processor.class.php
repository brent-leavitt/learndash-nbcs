<?php

namespace Doula_Course\App\Clss\Interfaces;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The interface for preparing form values 
 * This sets up the values to be passed into forms.
 */
 
interface Processor
{
	
    public function __construct( int $uid );
	
	
	public function process(): array;
	
	

}


?>
