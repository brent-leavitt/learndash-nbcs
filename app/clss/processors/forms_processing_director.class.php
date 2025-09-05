<?php

namespace Doula_Course\App\Clss\Processors;


if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * The director class for processing submitted form data. Depending on what form is being processed, this directs to a sub-processor. 
 * 
 */
 
class Forms_Processing_Director
{
  
	/**
	 * valid
	 *
	 * A simple validation switch, false until proven true.
	 *
	 * @since 2.0
	 * @var string
	 */
	private $valid = false;
	
	/**
	 * action
	 *
	 * The action to be processed. 
	 *
	 * @since 2.0
	 * @var string
	 */
	private $action = '';
		
	/**
	 * ID
	 * 
	 * User ID to be processed
	 *
	 * @since 2.0
	 * @var string
	 */
	private $ID = 0;
	
	/**
	 * processor
	 *
	 * The specific processing object
	 *
	 * @since 2.0
	 * @var Processor
	 */
	private $processor;
	
	
	/**
	 * notices
	 * 
	 * Notices sent back to the admin page
	 *
	 * @since 2.0
	 * @var string
	 */
	private $notices = [];

	
	
	//Tasks
	/*
		- Check permissions
		- check nonce
		- filter results
		- 
		- Send to sub-processor
		- Retreive Responses from sub-processors
		- 
	
	*/
	
	
	/**
	* Constuctor
	*
	* @return void
	*/ 
	public function __construct( string $action, int $uid = 0 ){
		
		$this->setup( $action, $uid );
		
		$this->check_permissions( ); 
		
		if( $this->valid == false )
			return;
		
		$this->set_processor( );
			
	}

	
	/**
	* setup
	* 
	* Build the new student page
	*
	* @return void
	*/	
	private function setup(  string $action, int $uid = 0 ){

		$this->action = $action; 
		$this->ID = $uid ?? 0;
		
	}
	

	
	/**
	* check_permissions
	* 
	* Check Permissions for processing the data before it is sent to be processed. 
	*
	* @return void
	*/	
	private function check_permissions( ): void
	{

		if( empty( $_POST ) )
			return;
		
		if( empty( $_POST[ $this->action.'_nonce'] ) )
			return;
		
		if( !wp_verify_nonce( $_POST[ $this->action.'_nonce'], $this->action ) ){
			$this->notices[ 'warnings' ][] = 'Sorry Charlie, permission denied!'; 
			return;
		}
		
		
/* 		if( $_POST['_wp_http_referer'] !== '/wp-admin/admin.php?page='. $this->action .'&student_id='.$this->ID  ){
			$this->notices[ 'warnings' ][] = 'Are ya\' lost, stranger?'; 
			return;
		} */
		
		unset( $_POST[ $this->action.'_nonce'] );
		unset( $_POST['_wp_http_referer'] );
		$this->valid = true;
	}
	
	/**
	* Set Processor
	*
	* @return void
	*/	
	private function set_processor(  ){
		
		$sub_processor_name = 'Doula_Course\App\Clss\Processors\\'. ucwords( $this->action , '_' ).'_Processor';
		if( class_exists( $sub_processor_name ) )
			$this->processor = new $sub_processor_name( $this->ID );
		else{
			$this->valid = false;
			$this->notices[ 'error' ][] = "The Sub-Processor <i>{$sub_processor_name}</i> is not available!"; 
		}
	}

	
	/**
	* Process
	*
	* @return array 
	*/	
	public function process( ): void
	{
		$response = $this->processor->process();
		
		$this->notices = array_merge_recursive( $this->notices, $response );
			
	}

	
	/**
	* is_valid
	*
	* @return bool
	*/	
	public function is_valid( ): bool 
	{
		
		return $this->valid;
			
	}
	
	/**
	* get_notices
	*
	* @return array
	*/	
	public function get_notices( ): array 
	{
		
		return $this->notices;
			
	}



	
	
}


?>
