<?php

namespace Doula_Course\App\Clss\User_Page;

use Doula_Course\App\Clss\Student;
use Doula_Course\App\Clss\Forms\Form_Builder;
use Doula_Course\App\Clss\Forms\Form_Values_Interface;
use Doula_Course\App\Clss\Forms\Student_Values;
use Doula_Course\App\Tmpl\HTML_Template;
use Doula_Course\App\Clss\Interfaces\Page_Builder;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The Admin Menu Builder class
 */
Class User_Page_Builder implements Page_Builder
{
	
	/* @var string $title */
	private $title = '';

	/* @var obj $page */
	private $page;


	/**
     * 
     *
     * @return void
     */
    public function __construct( string $title )
    {
		
				
        $this->reset();
		$this->title = $title;

    }
	
	
	/**
     * Reset the Page Element for a fresh build
     *
     * @return void
     */
    public function reset(): void
    {
        $this->page = new Page();
    }
	
	
	/**
     * 
     *
     * @return void
     */
    public function build_title( $add = '' ): void
	{
		$html = new HTML_Template( 'page_title', [ $this->title, $add ] );
		
		$this->page->sections[] = $html->generate();  
		
	}
	
	
	
	/**
     * 
     *
     * @return void
     */
    public function build_form_top(): void
	{
		$html = new HTML_Template( 'form_top', [ $this->title ] );
		
		$this->page->sections[] = $html->generate();  
		
	}
	
	/**
     * 
     *
     * @return void
     */
    public function build_form_btm(): void
	{
		$html = new HTML_Template( 'form_btm', [ $this->title ] );
		
		$this->page->sections[] = $html->generate();  
		
	}
	
	
	/**
     * Add To Title 
     *
     * For Instances when we want the title to be more descriptive. 
     *
     * @return void
     */
    public function add_to_title(string $add_text = NULL ): void
	{
		
		if( !empty( $add_text ) )
			$this->title = $this->title.': <i>'.$add_text.'</i>';
		
	}

    /**
     * Build Top
     *
     * temporary functionality. 
     *
     * @return void
     */
    public function build_top( string $slug ): void
	{	
		
		$this->page->sections[] = '<section>Top of the page information goes here.</section>';
		
	}
	
    /**
     * 
     *
     * @return void
     */
    public function build_table( string $slug ): void
	{
		if (!current_user_can('edit_users'))
			wp_die(__('You do not have sufficient permissions to access this page.'));
			
		$role = $_GET[ 'role' ]	?? NULL ;
		
		//Checks is type of WP_Table class. 
		$table_class ="Doula_Course\App\Clss\Tables\Table_". ucfirst( $slug );
			
		if( !class_exists( $table_class ) ) //This should be available because the Tables class is loaded before the editor class...
			wp_die(__( 'The requested TABLE-type class is not available.' ));
		
		$sid = $this->student->id ?? NULL; 
			
		$table = new $table_class( ); //$sid WAIT TO ASSIGN
		$table->prepare_items( $role ); 
		
		ob_start();
		$table->display();
		$this->page->sections[] = ob_get_clean();
	}
	
    /**
     * 
     *
     * @return void
     */
    public function build_main(): void
	{
		$this->page->sections[] = '<div><p>Main Body of the Page goes here</p></div>';
		
	}
		
    /**
     * Build Notices
     *
     * @return void
     */
    public function build_notices( array $notices ): void
	{
		
		$notice_str = var_export( $notices, true );
		
		$this->page->sections[] = "<p>The NOTICES are: <br>".$notice_str ."</p>";
		
	}
	
    /**
     *  Employ a nested director/builder set of classes. 
     *
     * @return void
     */
    public function build_form( string $slug, Form_Values_Interface $form_values ): void
	{
	
		$form_builder = new Form_Builder( ); //The "sub" director class
		$form_builder->set_form_values( $form_values );
		$form_builder->set_form( $slug );
		$form_builder->build_form();
		$this->page->sections[] = $form_builder->get_form();
		
	}	
	
	
    /**
     *  May not be needed. 
     *
     * @return void
     */
    public function build_( string $slug, object $args ): void
	{
		
		//echo "The BUILD_$slug function has been called!";
		
		
	}

    /**
     * 
     *
     * @return void
     */
    public function build_bottom(): void
	{
		$html = new HTML_Template( 'admin_page_footer' );
		
		$this->page->sections[] = $html->generate();  
		
	}
 
    
	/**
     * Return the page object for use after it has been built. 
	 *
     * @return 
     */
	 
 
    public function get_page(){
		
		$page =  $this->page;
		$this->reset();
		
		return $page;
		
	} 

	
	
}

?>

