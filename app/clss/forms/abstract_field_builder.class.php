<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The abstract field builder class. 
 * Very basic setup. 
 */
 
abstract class Abstract_Field_Builder
{

    /**
     * @return obj
     */
    abstract function get_field();

}


?>
