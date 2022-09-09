<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The Form Builder (director in a Builder pattern design) 
 * This sets up the basics of forms. 
 */
 
class Form_Builder extends Abstract_Form_Builder
{
	
	/**
	 * The current set of form fields.
	 *
	 * @since 2.0
	 * @var array of classes
	 */
	
	 private $field_director = NULL;
	 
	/**
	 * The slug.
	 *
	 * @since 2.0
	 * @var string
	 */
	private $slug = NULL;
	

	/**
	 * The outputted form code.
	 *
	 * @since 2.0
	 * @var string
	 */
	private $output = NULL;
	
	/**
	 * The current set of field values.
	 *
	 * @since 2.0
	 * @var array of key -> value pairs
	 */
	private $values;

	
	/**
	 * nonce
	 *
	 * @since 2.0
	 * @var string
	 */
	private $nonce = '';
	 
	
	/**
	 * action
	 *
	 * @since 2.0
	 * @var string
	 */
	private $action = '';
	

	 
	 	
    /**
     * 
     *
     * @return void
     */
    public function __construct() {
    
		 $this->_request = $this->filter_request( $_REQUEST );
		 //Add a $_REQUEST call to see if the form has been submitted. 
		 //Load $_REQUEST data if available. 
		 $this->set_action();
		
		 
    }
		
	/**
     * set form 
     *
     * @return void
     */
 
    public function set_form( string $slug ) {
		$this->slug = $slug;
		$this->set_nonce();
    }	
	
	/**
     * set form values
     *
     * @return void
     */
 
    public function set_form_values( Form_Values_Interface $form_values ) {
		
		$form_values->build_values();
		
		$this->values = $form_values->get_values(); //Sets an array
		
    }	
		
	/**

     * set action
     *
     * @return void
     */
 
    private function set_action() {
		
		$this->action = $_SERVER[ 'REQUEST_URI' ];
		
    }	
		
		
	/**

     * set nonce
     *
     * @return void
     */
 
    private function set_nonce() {
		
		if( function_exists( 'wp_create_nonce' ) )
			$this->nonce =  wp_create_nonce( $this->slug );
		
    }	
		
		
	/**
     * filter_request
     *
     * @return void
     */
 
    public function filter_request( array $request ) {
      
	  if( function_exists( 'sanitize_text_field' ) ){
		  
		  //do something.
		  
	  }else{
		  
		  //do something else. 
	  }
	  
	  return $request;
	  
    }	
		
		
	/**
     * build form
     *
	 
     * @return void
     */
 
	public function build_form( ) {
		
		$this->build_form_top();
		$this->build_fields();
		$this->build_form_bottom();
	}	
	

	
	/**
     * build fields
     * 
     *
     * @return void
     */
	 
    private function build_fields( ) {
		
		//Move this block of code to a json file. This the only thing that varies from file to file, so it would make sense to sepearate it out. 
		$page = 'fields/'. $this->slug.'.php';
		
		
		//loads the $fields var for the given page.
		include $page;  
		$fields = $fields ?: [];
		
		$builder = new Field_Builder();
		$this->field_director = new Field_Director( $builder );
		
		foreach( $fields as $field ){
			
			if( strcmp( $field[ 0 ], 'section' ) !== 0  ){
			
				$field_obj = $this->build_field( $field );
				
				if( $field_obj !== NULL )
					$this->output .= $this->format_field( $field_obj ); //returns a string. 
				
			}else{
				
				//Yes this is a table
				
				$this->output .= '<h3>'.$field[ 1 ].'</h3><table class="form-table">';
				
				$this->build_rows( $field[ 2 ], $field[ 3 ] );
				
				$this->output .= "</table>";	

			}
		}
			
			
			
	}

		
	/**
     * build rows
     * 
     *
     * @return void
     */
	 
	 
	
	private function build_rows( int $num_rows, array $fields ):void
	{
		$count = 0;
		$group = [];
		
		foreach( $fields as $field ){
			
			$group[] = $field;
			$count++;
			
			if( $count >= $num_rows  ){
				
				$this->build_row( $group );
				$group = [];
				$count = 0; 
			}		
		}
		
		//render final row, if partial
		if( !empty( $group ) ){
			$empties = $num_rows - count( $group );
			for( $i = 0; $i < $empties; $i++ ){
				$group[] = ''; //adding empty columns.
			}
			$this->build_row( $group );
		}
	}
		
	/**
     * build row
     * 
     * 
     *
     * @return void
     */
	 
	 
	
    private function build_row( array $fields ) {
				
		$output = '';
		
		foreach( $fields as $field ){
			
			if( is_array( $field ) ){
								
				$field_obj = $this->build_field( $field );	
				
				if( $field_obj !== NULL )
					$formatted = $this->format_field( $field_obj, 'td' );
				
				//Skips the nesting into a table row. 
				if( $field_obj->is_hidden() ){
					$this->output .= $formatted;
					continue;
				} else {					
					$output .= $formatted;
				}
			}
		}
		
		if( !empty( $output ) )
			$this->output .= "<tr>". $output ."</tr>";
		
	}
	
	
	/**
     * 
     *
     * @return void
     */
	 
	 
	
    private function build_field( array $field ) {
				
		list( $type, $name, $label ) = $field;
		$extras = $field[ 3 ] ?? []; //Because the extra data may not always be available. 
		$options = $field[ 4 ] ?? []; //Because the extra data may not always be available. 
		$val = $this->values[ $name ] ?? ''; 
		
		return $this->field_director->build( $type, $label, $name, $extras, $options, $val );
		
	}

		
	/**
     * build_form_top
     *
     * @return void
     */
	 
    private function build_form_top(  ):VOID
	{
				
		$this->output .= '<form method="post" id="form_'.$this->slug.'" action="'.$this->action.'"  >';
		
		if( !empty( $this->nonce ) )
			$this->output .= '<input type="hidden" id="'.$this->slug.'_nonce" name="'.$this->slug.'_nonce" value="'.$this->nonce.'" > <input type="hidden" name="_wp_http_referer" value="'.$this->action.'">';
		
	}
		
	/**
     * build_form_bottom
     *
     * @return void
     */
	 
    private function build_form_bottom(  ):VOID
	{
				
		$this->output .= '</form>';	
		
	}	
	
	/**
     * format_field
     *
     * @return string
     */
 
    private function format_field( Field $field, string $element = 'div' ): string
	{
		$output = '';
		
		$field->format();
		
		if( !$field->is_hidden() )
			$output .= "<$element>";
		
		$output .= $field->get_output();
		
		if( !$field->is_hidden() )
			$output .= "</$element>";
		
		return $output;
    }

	
	/**
     * 
     *
     * @return void
     */
 
    public function get_form() {
      return $this->output;
    }
	

}


?>