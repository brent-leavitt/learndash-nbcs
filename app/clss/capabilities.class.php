<?php 

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to generate capabilities. 
	 * 
	 */


if( !class_exists( 'Capabilities' ) ){
	class Capabilities{
		
		
		/**
		 *  $types
		 *
		 *	array 
		 */
		
		private	$types = [];

		/**
		 *  $caps
		 *
		 *	array 
		 */
		
		private	$caps = [];

			
		/**
		 *  $tmpl
		 *
		 *  This is the basic list of capabilities to be added for a given post type. true for plural, false for singular. 
		 *
		 *	array
		 */
		
		private $tmpl = [
			'read_' => false,
			'edit_' => false,
			'delete_' => false,
			
			'edit_' => true,
			'edit_others_' => true,
			'publish_' => true,
			'read_private_' => true,
			
			'read_' => true,
			'delete_' => true,
			'delete_private_' => true,
			'delete_published_' => true,
			'delete_others_' => true,
			'edit_private_' => true,
			'edit_published_' => true,
		]; 
		
		
				
		/**
		 *  __construct
		 *
		 *	array 
		 */
		
		public function __construct( $types ){
			
			//types that we will be creating capabilties for. 
			$this->types = $types;
			$this->build_caps();
		
		}

		/**
		 *  get_caps
		 *
		 *	array 
		 */		
		
		public function get_caps(): array
		{
			
			return $this->caps;
						
		}
		

			

		/**
		 *  build_caps
		 *
		 *  This takes the incoming post types and buids out the available capabilities for each. 
		 *
		 *	return void
		 */
		 
		private function build_caps(): void
		{
			
			$caps = [];
			
			foreach( $this->types as $type ){
				foreach( $this->tmpl as $name => $plural ){
					$a = $name.$type;
					$a .= ($plural)? 's':'';
					$caps[] = $a; 
				}
			}
			
			
			$this->caps = $caps; 
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