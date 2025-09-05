<?php

namespace Doula_Course\App\Clss;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 *  Roles Class
 *
 * 	This is one-time-use class to build out the roles that are to be used on the site. 
 *
 * Reference: https://kinsta.com/blog/wordpress-user-roles/#how-to-create-custom-user-roles-in-wordpress
 */

class Roles{
	
	/**
	 *  $args
	 *
	 *	incoming array of data
	 */
	private $args = [];
	
	/**
	 *  format
	 *
	 * 	format of outgoing data
 	 */
	private $format = [
		'name' 		=> '',
		'u_name' 	=> '',
		'caps' 		=> []
	];
	
	/**
	 *  roles
	 *
	 *	finalized data for export
	 */
	private $roles = [];
		
	
	
	/**
	 *  
	 *
	 *
	 */
	 
	public function __construct( array $args ){
		
		$this->args = $args; 
		
	}
	
	/**
	 *  
	 *
	 *
	 */
	 
	public function build(){
		
		
		
		foreach( $this->args as $name => $caps ){
			
			$format = $this->format;
			
			$format['name'] = $name;
			$format['u_name'] = ucwords( str_replace( '_', ' ', $name ) );
			
			$format[ 'caps' ] = $this->build_caps( $caps );
			
			$this->roles[] = $format;
			//wp_mail( 'brent@trainingdoulas.com', 'A role has been built', "We want to build the role: $role !" );
			
		}
	}	
	
	/**
	 *  build_caps
	 *
	 *	returns array
	 */
	 
	public function build_caps( $caps ): array
	{
	
		$arr = [];
		
		foreach( $caps as $cap )
			$arr[ $cap ] = true;
			
		return $arr; 
	}
	

	
	/**
	 *  get_roles
	 *
	 *	returns array  
	 */
	 
	public function get_roles( ): array
	{
	
		return $this->roles;

	}
	
	/**
	 *  
	 *
	 *
	 */
	 
	private function add( ){
	
		//does nothing. 

	}
}
?>