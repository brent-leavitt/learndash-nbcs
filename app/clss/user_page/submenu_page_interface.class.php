<?php

namespace Doula_Course\App\Clss\Admin_Page;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * A WordPress submenu page.
 */
interface Submenu_Page_Interface extends Admin_Page_Interface
{

	/**
     * Get the parent slug of the admin page.
     *
     * @return string
     */
    public function get_parent_slug();

}


?>
