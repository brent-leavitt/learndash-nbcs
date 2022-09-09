<?php 

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to generate custom post types. 
	 * 
	 *
	 *
	 */


if( !class_exists( 'Post_Stati' ) ){
	class Post_Stati{
		
		
		/**
		 *  $args
		 *
		 *	array 
		 */
		
		private	$args = [
		
			'label'                     => false,
			'label_count'               => false,
			'exclude_from_search'       => true,
			'public'                    => true,
			'internal'                  => null,
			'protected'                 => null,
			'private'                   => null,
			'publicly_queryable'        => null,
			'show_in_admin_status_list' => true,
			'show_in_admin_all_list'    => true,
			'date_floating'             => true,
			
		];

			
		/**
		 *  $name
		 *
		 *  Status Name. 
		 *
		 *	string 
		 */
		
		private $name; 
		
		
				
		/**
		 *  __construct
		 *
		 *	array 
		 */
		
		public function __construct( string $name, $args = [] ){
			
			
			$this->name = $name;
			
			//declare the custom post types here. 
			
			if( !empty( $args ) )
				$this->set_args( $args );
			
			$this->build( );
		
		}

		/**
		 *  set_args
		 *
		 *	array 
		 */		
		
		private function set_args( $args ): void
		{
			
			$a = $this->args; 
			
			//Get avaiable parameters sent at time of initialization. 
			foreach( $args as $key => $arg ){
				if( key_exists( $key, $a ) ){
					$a[ $key ] = $arg;
				}
			}
			
		/* 	//Then for any empty fields, fill in based on post_type value. 
			if( !empty( $a[ 'post_type' ] ) ){
				$p_name = $a[ 'post_type' ];
				
				//Set Post Name if Empty, Uppercase first letter and add s. 
				if( empty( $a[ 'post_name' ] ) )
					$a[ 'post_name' ] = ucfirst( $p_name ).'s';
					
				
				
			} */
					
			$this->args = $a; 
			
		}
		


		/**
		 *  build
		 *
		 *	builds the label parameters for the status. 
		 *
		 *	return void
		 */		
		
		public function build( ): void
		{
			
			$status = ucfirst( $this->name );
				
			if( !$this->args[ 'label' ] )
				$this->args[ 'label' ] = _x( $status, 'post' );
						
		
			if( !$this->args[ 'label_count' ] )
				$this->args[ 'label_count' ] = _n_noop( $status.' <span class="count">(%s)</span>', $status.' <span class="count">(%s)</span>' );
			
		}
		
		/**
		 *  get_name
		 *
		 *
		 *	return string
		 */
				
		
		public function get_name(): string
		{
			
			return $this->name;
			
		}		
		/**
		 *  get_args
		 *
		 *
		 *	return array
		 */
				
		
		public function get_args(): array
		{
			
			return $this->args;
			
		}		
	
			

		/**
		 *  
		 *
		 *
		 *	return 
		 */
				
		
		public function __(){
			
			
		}	
	}
}