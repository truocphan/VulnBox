<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * LinkedIn OAuth2 Class
 * 
 * Class OAuth2 reference url taken from https://github.com/valpatsk/OAuth2.0-class
 * @link https://github.com/valpatsk/OAuth2.0-class
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class OAuth2 {
	
	protected $access_token;
	protected $access_token_url;
	protected $authorize_url;
	protected $access_token_name;
	public $error;
	
	function __construct( $access_token = '' ) {
		
		$this->access_token			= $access_token;
		$this->error 				= "";
		$this->access_token_name	='access_token';
	}
	
	/**
	 * LinekedIn Get Autorize Url
	 * 
	* @package WooCommerce - Social Login
 	* @since 1.0.0
	 */
	public function getAuthorizeUrl( $client_id, $redirect_url, $additional_args = array() ) {
		
		$auth_link = $this->authorize_url.
							"?response_type=code".
							"&client_id=".$client_id.
							"&redirect_uri=".urlencode($redirect_url);
		
		foreach( $additional_args as $k => $v ) {
			
			$auth_link .= '&' . $k . '=' . urlencode( $v );
		}
		
		return $auth_link;
	}
	
	/**
	 * LinekedIn Get Access Tocken
	 * 
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */
	public function getAccessToken( $client_id = "", $secret = "", $redirect_url = "", $code = "" ) {
		
		if( $code == '' ) {
			$code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : '';
		}
		
		$params				= array();
		$params['url']		= $this->access_token_url;
		$params['method']	= 'post';
		$params['args']		= array(
									'code'			=> $code, 
									'client_id'		=> $client_id, 
									'redirect_uri'	=> $redirect_url, 
									'client_secret'	=> $secret, 
									'grant_type'	=> 'authorization_code'
								);
		$result	= $this->makeRequest( $params );
		return $result;
	}
	
	/**
	 * LinekedIn Create Request
	 * 
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */
    protected function makeRequest( $params = array(), $action = '' ) {
        
    	$this->error	= '';
        $method			= isset( $params['method'] ) ? $params['method'] : 'get';
        $headers		= isset( $params['headers'] ) ? $params['headers'] : array();
        $args			= isset( $params['args'] ) ? $params['args'] : '';
        $url			= $params['url'];
        
        if( empty( $action ) ){
        	$url			.= '?';
		}   
		     
        if( $this->access_token && empty( $action ) ) {
            $url	.= $this->access_token_name.'='.$this->access_token;
        }
		
        if( $method == 'get' && !empty( $args ) ) {
            $url	.= '&' . $this->preparePostFields( $args );
        }

        $ch	= curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url ); 
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        
        if( $method == 'post' ) {
            curl_setopt( $ch, CURLOPT_POST, TRUE );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->preparePostFields( $args ) );
        } elseif( $method == 'delete' ) {
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
        } elseif( $method == 'put' ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "PUT" );
        }
        
        //curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        if( is_array( $headers ) && !empty( $headers ) ) {
            
        	$headers_arr = array();
            foreach( $headers as $k => $v ){
                $headers_arr[] = $k.': '.$v;
            }
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers_arr );
        }
        
        $result = curl_exec( $ch );
        
        curl_close( $ch );
        return $result;
    }
	
    /**
	 * LinekedIn Prepare CURL Post Fields
	 * 
	 * @package WooCommerce - Social Login
     * @since 1.0.0
	 */
	protected function preparePostFields( $array ) {
		
		if( is_array( $array ) ) {
			
			$params	= array();
			foreach( $array as $key => $value ) {
				$params[] = $key . '=' . urlencode( $value );
			}
			
			return implode('&', $params);
		} else {
			return $array;
		}
	}
}