<?php 

namespace Doula_Course\App\Clss;


/*
 *  New Beginnings Message PHP Class
 *	Recreated on 2 Jun 2022
 *
 *	Old Version is moved to __old.message.class
 *
 *	A class to handle system messaging to the user or other parties. 
 *
 */


if ( ! defined( 'ABSPATH' ) ) { exit; } 
 
//check if WP_user is set.  
 
if( !class_exists( 'Message' ) ){ 
	class Message { //Not sure we need the WP_User class, but it could be helpful.
		
		/**
		 * messages
		 *
		 * A holding bay for message to be stored within the class. Messages are being stored by user (user-type) and type (message-type)., 
		 * For example: user - admin, student, etc.  type - error, warning, noticee, confirmation, etc. 
		 *
		 *
		 *
		 * array 
		 *
		 * @since 0.1
		 **/
		 
		private $messages = [];
		
		
		//Then Methods

		/**
		 * _construct
		 *
		 * do nothing.
		 *
		 **/
		 
		public function __construct(){
		
			
		}		
		
	
		/**
		 * 	init
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		 
		public function init() {

			
		
		}	

		
		
		/**
		 * 	set
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
		private function set( $user, $type, $message ): VOID
		{
		
			$this->messages[ $user ][ $type ][] = $message; 
			
		}
		
		
		
		/**
		 * 	get_by
		 *
		 *	gets messages for a given set of user/type paramaters. If paramater is left empty, return all. 
		 *
		 *	returns ARRAY
		 **/
		 
		
		private function get_by($user= '', $type = '' ): ARRAY
		{
			//user not set and type not set, return all.  
			if( !$user && !$type )
				return $this->messages; 
			
			//user is set, but type is not, return all messages for specific user. 
			if( $user && !$type )
				return $this->messages[ $user ];
			
			//user is not set, but type is set. 
			if( !$user && $type ){
				return $this->get_all_by_type( $type ); 
			}

			return $this->messages[ $user ][ $type ] ?: NULL ; 
		}
		
		
		
		/**
		 * 	get_all_by_type
		 *
		 *	get all messages by a specific type, regardless of user. 
		 *
		 *	returns VOID
		 **/
		 
			 
		private function get_all_by_type( $type )
		{
	
			$result = []; 
			
			foreach( $this->messages as $msg_arr )
				$result = $msg_arr[ $type ]; 
				
			return $result; 
				
		
		}	

		
		
		/**
		 * 	add
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function add( STRING $user, STRING $type, STRING $message )
		{

			$this->set( $user, $type, $message ); 
		
		}
		
		
		/**
		 * 	remove
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function remove(  STRING $user = '', STRING $type = '', INT $pos = -1 )
		{
			//remove all messages. 
			if( !$user && !$type && $pos < 0 )
				unset( $this->messages ); 
			
			//remove all message from a specific user. 
			if( $user && !$type && $pos < 0  ) 
				unset( $this->messages[ $user ] ); 
			
			//remove specific message type from a specific user. 
			if( $user && $type && $pos < 0  ) 
				unset( $this->messages[ $user ][ $type ] ); 
			
			//remove specific type from all users. 
			if( !$user && $type && $pos < 0  ) 
				// IS THIS NEEDED? 
			
			if( $user && $type && $pos >= 0 )
				unset( $this->messages[ $user ][ $type ][ $pos ] ); 
			
		}
		
		
		/**
		 * 	get
		 *
		 *	(descrip)
		 *
	*	returns ARRAY
		 **/
		 
			 
		public function get( STRING $user = '', STRING $type = '' ): ARRAY
		{
			
			return $this->get_by( $user, $type ); 
		
		}
		
	

		
			
		/**
		 * 	send
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		public function send()
		{
			
			
		}

		
		/**
		 * 	(title)
		 *
		 *	(descrip)
		 *
		 *	returns VOID
		 **/
		 
			 
		private function _()
		{

			
		
		}


		
	}	
}
?>