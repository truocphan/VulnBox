<?php

/**
 * IOauth2Proxy class file.
 *
 * This source file is subject to the New BSD License
 * that is bundled with this package in the file license.txt.
 * 
 * @author Andrey Geonya <a.geonya@gmail.com>
 * @link https://github.com/AndreyGeonya/vkPhpSdk
 * @copyright Copyright &copy; 2011-2012 Andrey Geonya
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'interfaces' . DIRECTORY_SEPARATOR . 'IOauth2Proxy.php';

/**
 * Oauth2Proxy is the OAuth 2.0 proxy class.
 * Redirects requests to the external web resource by OAuth 2.0 protocol.
 *
 * @see http://oauth.net/2/
 * @author Andrey Geonya
 */
if( !class_exists( 'Oauth2Proxy' ) ) {
	
	class Oauth2Proxy implements IOauth2Proxy
	{
		public $_clientId;
		public $_clientSecret;
		public $_dialogUrl;
		public $_redirectUri;
		public $_scope;
		public $_responseType;
		public $_accessTokenUrl;
		public $_accessParams;
		public $_authJson;
	
		/**
		 * Constructor.
		 * 
		 * @param string $clientId id of the client application
		 * @param string $clientSecret application secret key
	 	 * @param string $accessTokenUrl access token url
		 * @param string $dialogUrl dialog url
		 * @param string $responseType response type (for example: code)
		 * @param string $redirectUri redirect uri
		 * @param string $scope access scope (for example: friends,video,offline)
		 */
		public function __construct($clientId, $clientSecret, $accessTokenUrl, $dialogUrl, $responseType, $redirectUri = null, $scope = null)
		{
			$this->_clientId = $clientId;
			$this->_clientSecret = $clientSecret;
			$this->_accessTokenUrl = $accessTokenUrl;		
			$this->_dialogUrl = $dialogUrl;
			$this->_responseType = $responseType;
			$this->_redirectUri = $redirectUri;
			$this->_scope = $scope;
		}
	
		/**
		 * Authorize client.
		 */
		public function authorize( $customurl = '' )
		{
			$result = false;
			$vkPhpSdk_user_catch = \WSL\PersistentStorage\WOOSLGPersistent::get('vkPhpSdk_user_catch'); // CSRF protection

			$vkPhpSdkstate = \WSL\PersistentStorage\WOOSLGPersistent::get('vkPhpSdkstate'); // CSRF protection

			if(!empty( $vkPhpSdk_user_catch ) )
			{
				$this->_authJson = $vkPhpSdk_user_catch;
				$result = true;
			}
			else
			{
				if(!(isset($_REQUEST['code']) && $_REQUEST['code']))
				{
					
					
					$vkPhpSdkstate = md5(rand(10,100));
					\WSL\PersistentStorage\WOOSLGPersistent::set('vkPhpSdkstate', $vkPhpSdkstate ); // CSRF protection

					$this->_dialogUrl .= '?client_id=' . $this->_clientId;
					$this->_dialogUrl .= '&redirect_uri=' . $this->_redirectUri;
					$this->_dialogUrl .= '&scope=' . $this->_scope;
					$this->_dialogUrl .= '&response_type=' . $this->_responseType;
					//$this->_dialogUrl .= '&state=' . $vkPhpSdkstate;
					if(!empty($customurl)){
						$this->_dialogUrl .= '&state=' . $customurl;
					}else{
						$this->_dialogUrl .= '&state=' . $vkPhpSdkstate;
					}
					
					return $this->_dialogUrl;	
				}
				
				elseif( isset( $_REQUEST['state'] ) && $_REQUEST['state'] === $vkPhpSdkstate)
				{
					$this->_authJson = file_get_contents($this->_accessTokenUrl
					    .'?client_id='.$this->_clientId
					    .'&client_secret='.$this->_clientSecret
					    .'&code='.$_REQUEST['code']
					    .'&redirect_uri='.$this->_redirectUri
					);
					$auth_json = json_decode($this->_authJson);
					
					$auth_json = $this->object_to_array( $auth_json );
				
					if( !empty( $auth_json ) ) {
						
						
						\WSL\PersistentStorage\WOOSLGPersistent::set('vkPhpSdk_user_catch', $auth_json );

						$result = true;
					}
					else
						$result = false;
				}
			}
			
			return $result;
		}
		
		function object_to_array($result){
			
		    $array = array();
		    if( !empty( $result ) ){
		    	
			    foreach ($result as $key=>$value){	
			    	
			        if (is_object($value))
			        {
			            $array[$key]=$this->object_to_array($value);
			        } else {
			        	$array[$key]=$value;
			        }
			    }
		    }
		    return $array;
		}
		
		/**
		 * Get access token.
		 * 
		 * @return string
		 */
		public function getAccessToken()
		{		
			if ($this->_accessParams === null)
				$this->_accessParams = $this->getAuthJson();
			return $this->_accessParams['access_token'];
		}
	
		/**
		 * Get expires time.
		 * 
		 * @return string
		 */
		public function getExpiresIn()
		{
			if ($this->_accessParams === null)
				$this->_accessParams = $this->getAuthJson();
			return $this->_accessParams['expires_in'];
		}
		
		/**
		 * Get user id.
		 * 
		 * @return string
		 */
		public function getUserId()
		{
			if ($this->_accessParams === null)
				$this->_accessParams = $this->getAuthJson();
			return $this->_accessParams['user_id'];		
		}
		
		/**
		 * Get authorization JSON string.
		 * 
		 * @return string
		 */
		protected function getAuthJson()
		{
			return $this->_authJson;
		}
	}
}