<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base interface for Form Classes in the Doula Course Plugin
 * This sets up the basics of forms. 
 */
 
class Field_Builder extends Abstract_Field_Builder
{
	
	
	/**
	 * Field Object
	 *
	 * @since 2.0
	 * @var Field
	 */
	private $field;
	
	

	
	  
	 public function __construct(  ){
		 
		 $this->_reset();
	 }
	 
	 
	
    /**
     * Reset
     *
     * @return void
     */ 
	 
	 public function _reset(  ){
		 $this->field = new Field();
		 
	 }
	 
	 
	
    /**
     * 
     *
     * @return void
     */
    public function set_type( string $type ){
		$this->field->set_type( $type );	
	}
	
    /**
     * 
     *
     * @return void
     */
    public function set_name( string $name ){
		$this->field->set_name( $name );	
	}
	
    /**
     * 
     *
     * @return void
     */
    public function set_label( string $label ){
		$this->field->set_label( $label );
	}
	
    /**
     * 
     *
     * @return void
     */
    public function set_val( $val ){
		$this->field->set_val( $val );
	}

    /**
     * 
     *
     * @return void
     */
    public function set_options( array $options ){
		$this->field->set_options( $options );
	}
	
    /**
     * 
     *
     * @return void
     */
    public function set_extras( array $extras ){
		$this->field->set_extras( $extras );
	}

	
    /**
     * 
     *
     * @return string
     */
	public function format( ){ 
		
		$this->field->format();
		
	}

	
    /**
     * 
     *
     * @return string
     */
	 
    public function get_field( ){ 
		
		return $this->field;
		
		
	}

}


?>
