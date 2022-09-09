<?php 

namespace Doula_Course\App\Clss;

if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * A class to generate custom post types. 
	 * 
	 *
	 *
	 */


if( !class_exists( 'Post_Types' ) ){
	class Post_Types{
		
		
		/**
		 *  $args
		 *
		 *	array 
		 */
		
		private	$args = [
		
			'post_type' => '', 			//
			'post_name' => '', 			//
			'post_name_single' => '', 	//
			'post_item' => '', 			//
			'post_items' => '', 		//
			'cap_posts' => '',			//
			'cap_post' => '',			//
			'description' => '', 		//
			'hierarchical' => false,		//
			'exclude_from_search' => true, 	//
			'show_in_menu' => 'utilities',	//
			'menu_pos' => 51,			//
			'menu_icon' => 'awards',	//
			'supports' => array( 'title', 'editor', 'page-attributes', 'revisions', 'comments' ),		//
			'has_archive' => false,		//
			'rewrite' => ''				//
			
		];

		
		/**
		 *  $name
		 *
		 *  Name of the Post Type, used in the registration call. 
		 *
		 *	str
		 */
		
		private $name; 
		
		
				
		/**
		 *  __construct
		 *
		 *	array 
		 */
		
		public function __construct( $args ){
			
			//declare the custom post types here. 
			
			$this->set_args( $args );
			
			$this->name = $this->build();
			
			//add_action( 'admin_init', array( $this, 'set_admin_caps' ) ); 
			
		
		}

		/**
		 *  __construct
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
			
			//Then for any empty fields, fill in based on post_type value. 
			if( !empty( $a[ 'post_type' ] ) ){
				$p_name = $a[ 'post_type' ];
				
				//Set Post Name if Empty, Uppercase first letter and add s. 
				if( empty( $a[ 'post_name' ] ) )
					$a[ 'post_name' ] = ucfirst( $p_name ).'s';
					
				//Set Post Name Single, Uppercase first letter
				if( empty( $a[ 'post_name_single' ] ) )
					$a[ 'post_name_single' ] = ucfirst( $p_name );
				
				//Set Post Item, Uppercase first letter
				if( empty( $a[ 'post_item' ] ) )
					$a[ 'post_item' ] = ucfirst( $p_name );
				
				//Set Post Items, Uppercase first letter, make plural
				if( empty( $a[ 'post_items' ] ) )
					$a[ 'post_items' ] = ucfirst( $p_name ).'s';
				
				//Set Post Description
				if( empty( $a[ 'description' ] ) )
					$a[ 'description' ] =  ' This is the '.$p_name.' post type.';
				
				//Set show_in_menu
				if( empty( $a[ 'show_in_menu' ] ) )
					$a[ 'show_in_menu' ] =  true;
				
				//Set rewrite
				if( empty( $a[ 'rewrite' ] ) )
					$a[ 'rewrite' ] =  $p_name.'s';
				
				//Set Capabilities Posts
				if( empty( $a[ 'cap_posts' ] ) )
					$a[ 'cap_posts' ] = $p_name.'s';
				
				//Set Capabilities Post
				if( empty( $a[ 'cap_post' ] ) )
					$a[ 'cap_post' ] = $p_name;
				
			}
					
			$this->args = $a; 
			
		}
		

		/**
		 *  build
		 *
		 *	Sets up all the paramaters for the post type and then returns the name of the type. 
		 *
		 *	return string
		 */
		 
		 
		private function build( ): string
		{
			$post_type = $this->args[ 'post_type' ];
			
			$this->args = $this->build_params();
			
			return $post_type;	
			
		}
		
		

		/**
		 *  build_labels
		 *
		 * Params $args = array( 
		 *		'post_type' => (str)'', 		//Cardinal Name for the Post Type
		 *		'post_name' => (str)'', 		//Display Name of the Post Type
		 *		'post_name_single' => (str)'', 	//Singular Version of the Display Name
		 *		'post_item' => (str)'', 		//Display Name of an individual Item of the Post Type
		 *		'post_items' => (str)'', 		//Display Name of Items (plural) of the Post Type
		 *	)
		 *
		 *	return array
		 */
		 
		private function build_labels():array
		{
			
			$a = $this->args;
			
			//build labels arguments array. 
			
			$labels = array(
				'name' => _x( $a[ 'post_name' ], 'post type general name', NBCS_TD),
				'singular_name' => _x( $a[ 'post_name_single' ], 'post type singular name', NBCS_TD),
				'add_new' => _x('Add New', $a[ 'post_type' ], NBCS_TD),
				'add_new_item' => __('Add New '.$a[ 'post_item' ], NBCS_TD),
				'edit_item' => __('Edit '.$a[ 'post_item' ], NBCS_TD),
				'new_item' => __('New '.$a[ 'post_item' ], NBCS_TD),
				'all_items' => __( $a[ 'post_items' ], NBCS_TD),
				'view_item' => __('View '.$a[ 'post_item' ], NBCS_TD),
				'search_items' => __('Search '.$a[ 'post_items' ], NBCS_TD),
				'not_found' =>  __('No '.$a[ 'post_items' ].' found', NBCS_TD),
				'not_found_in_trash' => __('No '.$a[ 'post_items' ].' found in Trash', NBCS_TD), 
				'parent_item_colon' => '',
				'menu_name' => __( $a[ 'post_name' ], NBCS_TD)
			);
			
			return $labels;
		}
		
		
				

		/**
		 *  build_params
		 *
		 * Params: $args = array( 
		 *		'post_type' => (str)'', 		//Cardinal Name for the Post Type
		 *		'description' => (str)'', 		//
		 *		'menu_pos' => (int)'', 			//Menu Position
		 *		'menu_icon' => (str)'', 		//Sub-string from dashicons set. 'dashicons-' is already included
		 *		'supports' => (arr)'', 			//Editor items included with this post type
		 *		'rewrite' => (str)'', 			//Rewrite Slug
		 *		'labels' => (arr)'', 			//an array of label variables
		 *		
		 *	)
		 *
		 *	return array
		 */
		
		
		private function build_params( ): array
		{
			
			$a = $this->args;

			$labels = $this->build_labels();

			$caps =  $this->build_caps(
				[ 
					'cap_posts' => $a[ 'cap_posts' ], 
					'cap_post' => $a[ 'cap_post' ] 
				]
			);


			$params = array(
				'labels' => $labels,
				'capabilities' => $caps, 
				'description' => $a[ 'description' ],
				'public' => true ,
				'hierarchical' => $a[ 'hierarchical' ],
				'exclude_from_search' => $a[ 'exclude_from_search' ],
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => $a[ 'show_in_menu' ], //Toggle here to hide from main menu. 
				'menu_position' => $a[ 'menu_pos' ],
				'menu_icon' => 'dashicons-'. $a[ 'menu_icon' ],
				'capability_type'=>'post',
				'map_meta_cap'=> true, 
				'supports' => $a[ 'supports' ],  
				'has_archive' => $a[ 'has_archive' ], 
				'rewrite' => array( 'slug' => $a[ 'rewrite' ] )
			);

			return $params;
			
		}
		
		/**
		 *
		 *
		 * 	Params: $a(
		 *		'cap_post' => '(value)', 
		 *		'cap_posts' => '(value)', 
		 *	)
		 *	
		 *
		 */	
		
		private function build_caps( $a ): array
		{
			
			$caps = array(
			
					'read_post' => 'read_'.$a[ 'cap_post' ],
					'edit_post' => 'edit_'.$a[ 'cap_post' ],
					'delete_post' => 'delete_'.$a[ 'cap_post' ],
			
					'edit_posts' => 'edit_'.$a[ 'cap_posts' ],
					'edit_others_posts' => 'edit_others_'.$a[ 'cap_posts' ],
					'publish_posts' => 'publish_'.$a[ 'cap_posts' ],
					'read_private_posts' => 'read_private_'.$a[ 'cap_posts' ],
					
					'read' => 'read_'.$a[ 'cap_posts' ],
					'delete_posts' => 'delete_'.$a[ 'cap_posts' ],
					'delete_private_posts' => 'delete_private_'.$a[ 'cap_posts' ],
					'delete_published_posts' => 'delete_published_'.$a[ 'cap_posts' ],
					'delete_others_posts' => 'delete_others_'.$a[ 'cap_posts' ],
					'edit_private_posts' => 'edit_private_'.$a[ 'cap_posts' ],
					'edit_published_posts' => 'edit_published_'.$a[ 'cap_posts' ],
					'create_posts' => 'edit_'.$a[ 'cap_posts' ]
				);
				
			return $caps;
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