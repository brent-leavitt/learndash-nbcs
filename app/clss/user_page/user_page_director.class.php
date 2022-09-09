<?php

namespace Doula_Course\App\Clss\User_Page;

use Doula_Course\App\Clss\Forms\Student_Values;
use Doula_Course\App\Clss\Forms\Empty_Values;
use Doula_Course\App\Clss\Processors\Forms_Processing_Director;
use Doula_Course\App\Clss\Interfaces\Page_Builder;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base abstract class for for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
class User_Page_Director
{
   
	/* @var string $slug */
	private $slug = '';
	
	/* @var Page_Builder_Interface $builder */
	private $builder;
	
	/**
	 * student_id
	 *
	 * @since 2.0
	 * @var int
	 */
	private $student_id = 0;
	
		
	/**
	 * values
	 *
	 * @since 2.0
	 * @var array
	 */
	 private $values = [];
	
	
	/**
	 * notices
	 *
	 * @since 2.0
	 * @var array
	 */
	private $notices = [];
	
	 

	/**
     * Get the capability required to view the admin page.
     *
     * @return string
     */
    public function __construct( string $slug ){
		
		$this->slug = strtolower( str_replace( '-', '_', $slug ) );	
		$this->set_student_id();	
		
		$this->values = new Empty_Values();
		
		
		//Process Form Updates. 
		$this->process_data();
		
	}
	 
	
	 
	 /**
     * Set the Page Builder
     *
     * @return void
     */
    public function set_builder( Page_Builder $builder ){
		
		$this->builder = $builder;
		
	} 	 
	 
	 
		
	/**
     * Set the student id if available. 
     *
     * @return void
     */
    public function set_student_id(): void
    {
        $this->student_id = $_REQUEST['student_id'] ?? 0;
		//You may need to call a separate class if not available in the _GET string
		
    }
	 
	 
	 /**
     * Build the Page
     *
     * @return void
     */
    public function build(): void {
		
		if( empty( $this->slug )){
			$this->build_default();
			return;
		}
		
		$build =  'build_'. $this->slug;
				
		if( !method_exists( $this, $build ) ){
			echo "No template is available for this page yet! Sorry!";
			//$this->build_default();
			return;
		} 
		
		$this->$build();
		
		
	} 	 
	 
	 /**
     * Build the students_overview_page
     *
     * @return void
     */
    private function build_login( ): void
	{
				
		$this->build_simple_form( '' );	
		
	}	
	
 	 
	 
	 /**
     * Build the students_overview_page
     *
     * @return void
     */
    private function build_register_lite( ): void
	{
		$this->build_simple_form( '' );		
		
	}	
	
 	 
	 
	 /**
     * Build the students_overview_page
     *
     * @return void
     */
    private function build_simple_form( string $title = '' ): void
	{
		if( !empty( $title ) )
			$this->builder->build_form_top( $title );
		
		$this->builder->build_notices( $this->notices );		
		$this->builder->build_form( $this->slug, $this->values );
		//$this->builder->build_form_btm();
		
	}	
	


	/**
     * Build the _page
     *
     * @return void
     */
    private function build_tables_page(){
		
		$this->builder->build_title();
		$this->builder->build_top( $this->slug );
		$this->builder->build_table( $this->slug );
		$this->builder->build_bottom();
		
	}	

	/**
     * Process Data
     *
     * @return void
     */
    private function process_data( ){
		
		if( !isset( $_POST ) || empty( $_POST ) )
			return;
		
		$processor = new Forms_Processing_Director( $this->slug, $this->student_id );
		if( $processor->is_valid() !== false )
			$processor->process();
		$response = $processor->get_notices( );
		
		$this->notices = array_merge_recursive( $this->notices, $response );
		
	}	
	

	
	
	/**
     * Build the _page
     *
     * @return void
     */
/*      public function set_hiddens( ){
		
		//....
		if( !empty( $_REQUEST[ 'enrollment' ] ) && !empty( $_REQUEST[ 'service' ] ) )
			$this->values['hidden_data'] = htmlspecialchars('e='. $_REQUEST[ 'enrollment' ] .'&s='.$_REQUEST[ 'service' ] ) ;
		
	}	 */
	
	/**
     * Build the _page
     *
     * @return void
     */
    private function build_( ){
		
		//....
		
		
		
	}	
	
}


?>
