<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The Form Builder (director in a Builder pattern design) 
 * This sets up the basics of forms. 
 */
 
class Field_Director extends Abstract_Field_Director
{
	
	/**
	 * The current set of form fields.
	 *
	 * @since 2.0
	 * @var array of classes
	 */
	private $builder = NULL;
	 
	 
	/**
	 * The current set of form fields.
	 *
	 * @since 2.0
	 * @var array of classes
	 */
	private $fields;
	
	

	 
	 	
    /**
     * 
     *
     * @return void
     */
    public function __construct(Abstract_Field_Builder $field_builder) {
        
		$this->builder = $field_builder;
		 
		 //Add a $_REQUEST call to see if the form has been submitted. 
		 //Load $_REQUEST data if available. 
		 
    }
	
	/**
     * 
     *
     * @return void
     */
	
    public function build( string $type, string $label, string $name, array $extras = [], array $options = [], $val = NULL ){
	  
	  $this->builder->set_type( $type );
      
	  $this->builder->set_label( $label );
      
	  $this->builder->set_name( $name );
	  
	  if( !empty( $extras  ) )
		$this->builder->set_extras( $extras );
	
	  if( !empty( $options ) )
		$this->builder->set_options( $options );
      
	  if( !empty( $val ) )
		$this->builder->set_val( $val );
		
		//$this->set_field();
		return $this->get_field();
    }
	
	
	/**
     * 
     *
     * @return void
     */
 
    public function set_field() {

		$this->fields[] = $this->builder->get_field();
		
		$this->builder->_reset();

    }	
	
	
	/**
     * MAY NOT BE NEEDED
     *
     * @return void
     */
 
    public function format() {
		
      $this->builder->format();
	  
    }

	
		
	/**
     * 
     *
     * @return void
     */
 
    public function get_field() {
		
		$field = $this->builder->get_field();
	  
		$this->builder->_reset();

		return $field; 
		
    }	
		
		
	/**
     * 
     *
     * @return void
     */
 
    public function get_fields() {
		
      return $this->fields;
    }	
	
}


?>
