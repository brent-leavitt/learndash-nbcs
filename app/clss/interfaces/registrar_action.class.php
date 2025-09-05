<?php

namespace Doula_Course\App\Clss\Interfaces;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base interface for Registar Action Sub Classes in the Doula Course Plugin
 * This creates a common interface that the registar class can use to toggle between
 * the different actions available in the registar/cashier process.  
 */
 
interface Registrar_Action
{
    /**
     * 
     *
     * @return void
     */
    public function set_registar( object $registar );

    /**
     * 
     *
     * @return void
     */
    public function do_action(  );

	

   
}


?>
