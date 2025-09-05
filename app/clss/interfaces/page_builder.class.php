<?php

namespace Doula_Course\App\Clss\Interfaces;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base interface for Template Building Classes in the Doula Course Plugin
 * This sets up the list of standard template methods commont to all builders. 
 */
 
interface Page_Builder
{
    /**
     * 
     *
     * @return void
     */
    public function build_title( string $add_link );

    /**
     * 
     *
     * @return void
     */
    public function build_top( string $slug );

    /**
     * 
     *
     * @return void
     */
    public function build_main();

    /**
     * 
     *
     * @return void
     */
    public function build_bottom();

   
}


?>
