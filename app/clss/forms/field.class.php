<?php

namespace Doula_Course\App\Clss\Forms;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base field building class in the Doula Course Plugin
 * This builds HTML fields.  
 */
 
class Field
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
	private $type = NULL;	 
	
	
	/**
	 *
	 * @since 2.0
	 * @var string
	 */
	private $label = NULL;	 
	
	
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
	 *
	 * @since 2.0
	 * @var array
	 */
	private $options = [];	 

	
	/**
	 *
	 * @since 2.0
	 * @var array
	 */
	private $extras = [];	 
	
	/**
	 *
	 * @since 2.0
	 * @var bool
	 */
	private $hidden = false;	   
	 
	
    /**
     * 
     *
     * @return void
     */
    public function set_type( string $type ){
		$this->type = $type;	
	}		 

	
    /**
     * 
     *
     * @return void
     */
    public function set_label( string $label ){
		$this->label = $label;	
	}	  
	 
	
    /**
     * 
     *
     * @return void
     */
    public function set_name( string $name ){
		$this->name = $name;	
	}

	
    /**
     * 
     * 
     * $val MIXED
     *
     * @return void
     */
    public function set_val( $val ){
		
		$this->val = $val;
	}

	
    /**
     * 
     *
     * @return void
     */
    public function set_options( array $options ){
		$this->options = $options ?: [] ;
	}

	
    /**
     * 
     *
     * @return void
     */
    public function set_extras( array $extras ){
		$this->extras = $extras ?: [] ;
	}

	
    /**
     * 
     *
     * @return void
     */
    public function format( ):VOID
	{
		
		$format = 'format_'.$this->type;
		$this->$format();
 
	}

    /**
     * Format a text field
     *
     * @return void
     */	

	private function format_text():VOID
	{
		$this->output = $this->build_text_input();
	}

    /**
     * Format an Email Field
     *
     * @return void
     */	

	private function format_email():VOID
	{
		
		$this->output = $this->build_text_input();		
		
	}
	
    /**
     * Format a Password Field
     *
     * @return void
     */	

	private function format_password():VOID
	{
		
		$this->output = $this->build_text_input();
	}
	
	
    /**
     * Format a set of Checkboxs
     *
     * @return void
     */	

	private function format_checkbox():VOID
	{
		
		$this->output = $this->build_multi_input();
		
	}
	
    /**
     * Format a set of Radio Buttons
     *
     * @return void
     */	

	private function format_radio(): VOID
	{
		
		$this->output = $this->build_multi_input();
		
	}
	
    /**
     * Format a submit button
     *
     * @return void
     */	

	private function format_submit(): VOID
	{
		
		$this->output = $this->build_button_input();		
		
	}
	
    /**
     * Format a button
     *
     * @return void
     */	

	private function format_button(): VOID
	{
		
		$this->output = $this->build_button_input();
		
	}
	
    /**
     * Format a hidden field
     *
     * @return void
     */	

	private function format_hidden(): VOID
	{
		$this->hidden = true;
		$label = false; 
		$this->output = $this->build_text_input( $label );
	}
	
    /**
     * Format a textarea
     *
     * @return void
     */	

	private function format_textarea(): VOID
	{
		
		$this->output = $this->build_text_area();
		
	}
	
	
    /**
     * Format a select drop-down
     *
     * @return void
     */	

	private function format_select(){
		
		$this->output = $this->build_select();
		
	}
	

    /**
     * 
     *
     * @return void
     */	

	private function format_empty(){
		
		$this->output = '<!-- Empty Placeholder -->';
	}
	
    /**
     * 
     *
     * @return void
     */	

	private function format_special(){
		
		$this->output = $this->build_special();
	}
	
    /**
     * 
     *
     * @return void
     */	

	private function format_(){
		
		return '<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->val.'" />';
	}
	
	
    /**
     * Add additional attributes to field 
	 * such as required, readonly, or diabled. 
     *
     * @return string
     */	

	private function add_extras():string 
	{
		
		$extra_arr = [ 'disabled', 'readonly', 'required', 'multiple' ];
		
		foreach( $this->extras as $extra ){
			if( in_array( $extra, $extra_arr ))
				 return $extra.' ';
		}
		return '';
	}
	
    /**
     * Builds l for fields
     *
     * @return void
     */	

	private function build_label( $id = NULL, $label = NULL ): STRING
	{
		$id = $id ?? $this->name;
		$label = $label ?? $this->label;
		
		return '<label for="'.$id.'" >'.$label.'</label>';
	}

	
    /**
     * 
     *
     * @return string
     */	

	private function build_text_input( bool $label = true ): STRING
	{
		$output = '';
		
		if( $label )
			$output .= $this->build_label();
		
		$output .= '<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->val.'" ';
	
		if( !empty( $this->extras ) )
			$output .= $this->add_extras();
		
		$output .='/>';
		
		return $output;
	}

	
    /**
     *  Build Button Input
     *
     * @return string
     */	

	private function build_button_input(): STRING
	{
		
		$output = '<input type="'.$this->type.'" ';
		$output .= ( isset( $this->name ) )? 'id="'.$this->name.'" ': '';
		$output .= 'value="'.$this->label.'" ';
	
		if( !empty( $this->extras ) )
			$output .= $this->add_extras();
		
		$output .='class="button" />';
		
		return $output;
	}
	
	
    /**
     * 
     *
     * @return string
     */	

	private function build_multi_input(): STRING
	{
		$output = '';
		
		foreach( $this->options as $key => $label ){
			
			$id = $this->name.'_'.$key;
			
			$output .= '<input type="'.$this->type.'" name="'. $this->name;
			
			$output .= ( strcmp( $this->type, 'checkbox' ) === 0 )? '[]': '' ;
			
			$output .= '" id="'.$id .'" value="'.$key.'" ';
			
			$output .= $this->is_selected( $key ) ? 'checked ': '' ;
	
			$output .='/> ';

			$output .= $this->build_label( $id, $label );
			
		}
		
		return $output;
	}

	
    /**
     * assess if field should be checked
     *
     * @return bool
     */
    public function is_selected( $key ): bool 
	{
		if( $this->val == NULL )
			return false;
		
		return ( strcmp( $this->type, 'checkbox' ) == 0 ) ?
			in_array( $key, $this->val ):
			( strcmp( $key, $this->val ) == 0 );
	}

	
    /**
     * 
     *
     * @return string
     */	

	private function build_select(): STRING
	{
		$output = $this->build_label();
		
		$output .= '<select id="'. $this->name .'" name="'. $this->name .'"';
		
		if( !empty( $this->extras ) )
			$output .= $this->add_extras();

		$output .= ' >';
		
		foreach( $this->options as $value => $label ){
			
			$output .= '<option value="'.$value.'" ';
			
			$output .= $this->is_selected( $value ) ? 'selected ': '' ;
	
			$output .='>'. $label .'</option>';
			
		}
		
		$output .= "</select>";
		return $output;
	}

		
	
    /**
     * 
     *
     * @return string
     */	

	private function build_text_area(): STRING
	{
		$output = $this->build_label();
		
		$output .= '<textarea name="'.$this->name.'" id="'.$this->name.'" ';
	
		if( !empty( $this->extras ) )
			$output .= $this->add_textarea_attrs();
		
		if( !empty( $this->extras ) )
			$output .= $this->add_extras();
		
		$output .='>';
		
		$output .= $this->val;
		
		$output .= '</textarea>';
		
		return $output;
	}

	
	
	
    /**
     * 
     *
     * @return string
     */
	private function add_textarea_attrs(): STRING
	{
		$attrs = [
			'rows'=> '4',
			'cols'=> '40'
		];
		
		$output = '';
		
		foreach( $attrs as $attr => $default )
			$output .= $this->add_attrib( $attr, $default );
		
		return $output;
 
	}
		
	
	
    /**
     * 
     *
     * @return string
     */
	private function add_attrib( string $attr, $default ): STRING
	{
		$output = $attr.'="';
		$output .= array_key_exists( $attr, $this->extras ) ? $this->extras[ $attr ] : $default ;
		$output .= '" ';
		
		return $output;
	}
		
		
	
    /**
     * 
     *
     * @return string
     */	

	private function build_special(): STRING
	{
		//$output = $this->build_label();
		
		$special = new Special_Field(); 
		
		$special->build( $this->name, $this->val ); 
		
		return $special->get(); 
		
		
		
		$output .= '<p id="'.$this->name.'" />'.$this->val.'</p>';
		
		$output .= '<input type="text" name="'.$this->name.'" id="'.$this->name.'" value="" placeholder="Add New Admin Note Here!"/>';
		
		return $output;
		
	}		

	
	
    /**
     * is_hidden
     *
     * @return bool
     */
    public function is_hidden( ): bool
	{
		
		return $this->hidden;
 
	}
	
	
    /**
     * 
     *
     * @return string
     */
    public function get_output( ): string 
	{
		
		return $this->output;
 
	}
	
	
}


?>