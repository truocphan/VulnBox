<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Xoo_Exception extends Exception{

	public $wpErrorCode = null;

	public function __construct($error, $code = 0, Exception $previous = null){

		if(is_wp_error( $error )){
			$message = $error->get_error_message();
			$this->wpErrorCode = $error->get_error_code();
		}else{
			$message = $error;
		}		

		parent::__construct($message, $code, $previous);	

	}

	public function getWpErrorCode(){
		return $this->wpErrorCode;	
	}

}

