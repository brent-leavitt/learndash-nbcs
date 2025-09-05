<?php

namespace Doula_Course\App\Clss\Forms;

//use student ?

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * This allows forms to pass in empty value sets. 
 * Other than this, this class does nothing. 
 *
 */
 
class Empty_Values implements Form_Values_Interface
{
  
  
	/**
	* Hidden
	*
	* @var array
	*/
	private $hidden = [];
	
    
  
	/**
	* Valid Hidden
	*
	*  A list of any possible hidden keys that may pass this way. 
	*
	*
	* @var array
	*/
	private $valid_hidden_keys = [
		'enrollment',
		'patron',
		'service'
	];
	
  
  
	/**
	* Constuctor
	*
	* @return void
	*/
	public function __construct(  ){
		

	}	
	
	
	/**
	* valid_nonce
	*
	* @return bool
	*/	
	private function valid_nonce( ): bool
	{
		foreach( $_REQUEST as $k => $v ){
			if( strpos( $k, '_nonce') !== false){
				$slug = substr( $k , 0, strpos( $k, '_nonce' ) );
				$valid = wp_verify_nonce( $v, $slug );
				return $valid;
			}
		}
		 return false;	
	}	
	
	
	/**
	* valid_hidden
	*
	* @return array
	*/	
	private function valid_hidden( ){
			
		foreach( $_REQUEST as $k => $v ){
			if( in_array( $k, $this->valid_hidden_keys ) )
					$this->hidden[ $k ] = $v; 			
		}
	}		
	
	
	/**
	* dump_all
	*
	* @return void
	*/	
	private function dump_all( ):void
	{
		foreach( $_REQUEST as $key => $val ){
			unset( $_REQUEST[ $key ] );	
			unset( $_GET[ $key ] );	
			unset( $_POST[ $key ] );	
			
		}
			
	}	
	
	
		
	/**
	* Build the new student page
	*
	* @return void
	*/	
	public function build_values(  ){
		
		/* if( !$this->valid_nonce() ){
			$this->dump_all();
			return;
		} */
		
		$this->valid_hidden();
		
		
	}
	
	
	
	/**
	* Get Values
	*
	* @return array
	*/	
	public function get_values( ){
		
		return $this->hidden;
			
	}

		
	

	
	
}


?>
