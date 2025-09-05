<?php

namespace Doula_Course\App\Clss;

use Doula_Course\App\Clss\Registar\Login;
use Doula_Course\App\Clss\Registar\Register;
use Doula_Course\App\Clss\Registar\Checkout;
use Doula_Course\App\Clss\Registar\Process;
use Doula_Course\App\Clss\Registar\Errors;
use Doula_Course\App\Clss\Registar\Receipt;

if ( !defined( 'ABSPATH' ) ) { exit; }

/**
 * 
 * (I think we're following a "State" design pattern here.)
 * 
 * 
 */

class Registrar{
	
	 
	 /**
	 * 
	 * 
	 * Data required to be processed, without this we cannot move forward.
	 *
	 * @since 2.0
	 * @var array
	 */
	 private $args = array(
		'enrollment' => '',
		'service' => '',
		'patron' => 0,
	);
	

	 /**
	 * action 
	 * 
	 * what action to take in the check out process
	 *
	 * @since 2.0
	 * @var object
	 */	
	private $action; 
	
	
	
	 /**
	 * screen
	 * 
	 * which scren to load  in the check out process
	 *
	 * @since 2.0
	 * @var string
	 */
	/* private $screen = 'default'; */

	
	
	 /**
	 * login_required 
	 * 
	 * Does the user need to be logged in?
	 *
	 * @since 2.0
	 * @var bool
	 */
	private $login_required = false;
	
	
	 /**
	 * patron
	 * 
	 * The user object
	 *
	 * @since 2.0
	 * @var object
	 */
	private $patron;
	 	
	 /**
	 * valid
	 * 
	 * Is Valid
	 *
	 * @since 2.0
	 * @var boolean
	 */
	private $valid = false;
	 
	 	
	
	 /**
	 * output
	 * 
	 * Final Output
	 *
	 * @since 2.0
	 * @var string
	 */
	private $output;
	 
	 
	
	/**
	 *  
	 *
	 *
	 */
	 
	public function __construct( ){
		
		if( $this->set_args() && $this->set_action() )
			$this->valid = true;
		
		$this->set_patron();
		
		print_pre($_REQUEST);
		print_pre($this);
	}
	
		
		
	/**
	 * Set Args
	 *  
	 * Setting up initial values
	 *
	 * @since 2.0
	 * @return bool
	 */
	 
	private function set_args( ): bool
	{
			
		if( !empty( $_REQUEST[ 'enrollment' ] ) )
			$this->args[ 'enrollment' ] = sanitize_text_field( $_REQUEST[ 'enrollment' ] ); 
	
		if( !empty( $_REQUEST[ 'service' ] ) )
			$this->args[ 'service' ] = sanitize_text_field( $_REQUEST[ 'service' ] ); 
		
		/* if( !empty( $_REQUEST[ 'patron' ]  ) )
			$this->args[ 'patron' ] = intval( $_REQUEST[ 'patron' ] ); */
		
		$this->login_required = ( 
			!empty( $_REQUEST[ 'skip' ] )  && 
			boolval( $_REQUEST[ 'skip' ] ) == true 
		)? false : true ;	
		
		
		
		return ( !empty( $this->args[ 'enrollment' ] ) &&  !empty( $this->args[ 'service' ] ) );

	}
	
		
	/**
	 * Set Action
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return bool
	 */
	 
	public function set_action( string $action = '' ): bool
	{
		if( empty( $action ) )
			$action = ( !empty( $_REQUEST[ 'action' ] ) )? sanitize_text_field( $_REQUEST[ 'action' ] ) : '' ;
		
		
		if( in_array( $action, [
			'login', //login
			'register', //register
			'checkout', //order summary (last step before making payment)
			'process', //payment received (backend process)
			'errors', //processing errors, unsuccessful payment.
			'receipt' //order receipt, confirmation.
		] ) ){
			
			$this->change_action( $action );
			return true;
		}
			
		return false;
	}
		
		
	/**
	 * Set Patron
	 *  
	 * 
	 *
	 * @since 2.0
	 * @return void
	 */
	 
	private function set_patron( ): void
	{
		if( is_user_logged_in() )
			$patron_id = get_current_user_id();		
		
		if( !empty( $patron_id )){
			$this->patron = get_user_by( 'ID', $patron_id );
			$this->args[ 'patron' ] = intval( $patron_id );
		}
	
	}

		
	/**
	 *  set_output
	 *  
	 *  Overrides all other output. 
	 *
	 */
	 
	private function set_output( string $output ): VOID
	{
		$this->output = $output; 
	
	}
	
	
	/**
	 *  add_output
	 *  
	 *  Adds to existing output. 
	 *
	 */
	 
	private function add_output( string $output ): VOID
	{
		$this->output .= $output; 
	
	}
	
	
	/**
	 *  change_action
	 *  
	 *
	 *
	 */
	 
	private function change_action( string $action ): VOID
	{
		$action = ucfirst( $action );
		$this->action = new $action(); 
	
	}	

	
	
	
	
	
	/**
	 *  go
	 *
	 *  Makes sure that we're allowed to be here and then process action. 
	 *
	 */
	 
	public function go(): void
	{
		if( !$this->valid ){
		/* 	nocache_headers();
			wp_safe_redirect( '/register/' ); exit; */
			
			die( 'NOT VALID. WHY?' );
		}
		
		$this->action->set_registar( $this );
		$this->action->do_action();	
		
	}

	
	

	/**
	 * get_arg
	 *
	 * return mixed;
	 */
	 
	public function get_arg( $arg )
	{
	
		return $this->args[ $arg ];

	}
	
	/**
	 *  
	 *
	 *
	 */
	 
	private function process( ){
	
		$content = __( 'We\'re doing the processing now... : <br>' ); 
		
		//
		
		//
		
		
		$this->output .= $content;

	}
	
	/**
	 *  
	 *
	 *
	 */
	 
	private function errors( ){
	
		$content = __( 'We\'re checking for some errors... : <br>' ); 
		
		
		$this->output .= $content;

	}
		
	/**
	 *  
	 *
	 *
	 */
	 
	private function receipt( ){
	
		$content = __( 'Success! Here is your receipt!: <br>' ); 
		
		
		$this->output .= $content;

	}	
	
	
	/**
	 *  is login required
	 *
	 *	Returns the value of login_required property. 
	 *
	 *	@return bool
	 */
	 
	public function is_login_required( ): bool
	{
	
		return $this->login_required;

	}
	
	
	/**
	 *  display
	 *
	 *	returns string  
	 */
	 
	public function display( ): string
	{
	
		return $this->output;

	}

}
?>