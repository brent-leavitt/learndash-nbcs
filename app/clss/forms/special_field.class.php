<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base field building class in the Doula Course Plugin
 * This builds HTML fields.  
 */
 
class Special_Field
{
	
	/**
	 *
	 * @since 2.0
	 * @var string
	 */
	public $output = NULL;	 
	
	
	
	/**
	 *
	 * @since 2.0
	 * @var string
	 */
	private $name = NULL;	 
	
	
	/**
	 *
	 * @since 2.0
	 * @var MIXED 
	 */
	private $val = NULL;	 
	
	
 
	
	
    /**
     * Builds 
     *
     * @return void
     */	

	public function build( string $name, string $val = '' )
	{
		$this->name = $name;
		$this->val = $val;
		
		//$build = new 
		
		print_pre( $this, "Working on it:"  ); 
		
		$this->output = 'Working in Progress:'. __LINE__ . __METHOD__; 
		
	}

	
	
	
    /**
     * 
     *
     * @return string
     */
    public function get( ): string 
	{
		
		return $this->output;
 
	}
	
	
}


?>