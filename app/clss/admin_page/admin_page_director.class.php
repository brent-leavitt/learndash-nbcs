<?php

namespace Doula_Course\App\Clss\Admin_Page;

use Doula_Course\App\Clss\Forms\Student_Values;
use Doula_Course\App\Clss\Forms\Empty_Values;
use Doula_Course\App\Clss\Forms\Admin_Emailer_Values;
use Doula_Course\App\Clss\Processors\Forms_Processing_Director;
use Doula_Course\App\Clss\Interfaces\Page_Builder;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The base abstract class for for Admin Pages in the Doula Course Plugin
 * This sets up the common requirements for the //add_menu_page() and //add_submenu_page()
 */
 
class Admin_Page_Director
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
    private function build_students( ){
		
		$this->builder->build_title( 'new_student' );
		//$this->builder->build_top( $this->slug );
		$this->builder->build_table( $this->slug );
		$this->builder->build_bottom();
		
	}	
	
	 /**
     * Build the new student page
     *
     * @return void
     */
    private function build_new_student():void
	{
		if( empty( $this->student_id ) ){
			$values = new Empty_Values( );
		} else {
			$values = new Student_Values(  );
			$values->set_student( $this->student_id );
		}
		
		$this->builder->build_title();
		$this->builder->build_notices( $this->notices );
		$this->builder->build_top( $this->slug );
		$this->builder->build_form( $this->slug, $values );
		$this->builder->build_bottom();
		
	}	

	
	 /**
     * Build the new student page
     *
     * @return void
     */
    private function build_edit_student(){
		
		$values = new Student_Values(  );
		$values->set_student( $this->student_id );
		
		$this->builder->add_to_title( $values->get_student_full_name() );
		$this->builder->build_title();
		$this->builder->build_notices( $this->notices );
		$this->builder->build_top( $this->slug );
		$this->builder->build_table( 'transactions' );
		$this->builder->build_form( $this->slug, $values );
		$this->builder->build_bottom();
		
	}	
	
	
	/**
     * Build the email_student page
     *
     * @return void
     */
    private function build_email_student( ){
		
		$values = new Admin_Emailer_Values(  );
		$values->set_student( $this->student_id );
		
		$this->builder->add_to_title( $values->get_student_full_name() );
		$this->builder->build_title();
		$this->builder->build_notices( $this->notices );
		//$this->builder->build_top( $this->slug );
		$this->builder->build_form( $this->slug, $values );
		$this->builder->build_bottom();
		
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
    private function build_( $slug ){
		
		$this->builder->build_title();
		$this->builder->build_external( $slug ); 
		
	}	

	
	 /**
     * Build the Sandbox Page
     *
     * @return void
     */
    private function build_sandbox( ){
		$this->builder->build_title();
		$this->builder->build_external( 'sandbox' ); 
	}	 
	
	
	
	 /**
     * Build the test_page
     *
     * @return void
     */
    private function build_test( ){
		
		//....
		
		$this->builder->build_title();
		
	}	 
	 
	 /**
     * Build the default_page
     *
     * @return void
     */
    private function build_default( ){
		
		$this->builder->build_title();
		//$this->builder->build_top();
		$this->builder->build_main();
		$this->builder->build_bottom();
		
		
		
	} 
	 


	
}


?>
