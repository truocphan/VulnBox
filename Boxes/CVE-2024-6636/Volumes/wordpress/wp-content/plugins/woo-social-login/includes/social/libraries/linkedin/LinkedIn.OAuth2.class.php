<?php
/**
 * LinkedInOAuth2 Class
 * Class LinkedInOAuth2 reference url taken from https://github.com/valpatsk/LinkedIn-OAuth2.0-PHP
 * 
 * @link https://github.com/valpatsk/LinkedIn-OAuth2.0-PHP
 * @package LinkedInOAuth2 Class
 */

//Include OAuth2 class
require_once( 'OAuth2.class.php' );

class LinkedInOAuth2 extends OAuth2 {
	
	public function __construct( $access_token = '' ) {
		
		$this->access_token_url		= 'https://www.linkedin.com/oauth/v2/accessToken';
		$this->authorize_url		= 'https://www.linkedin.com/oauth/v2/authorization';
		
		parent::__construct( $access_token );
		
		$this->access_token_name	= 'oauth2_access_token';
	}
	
	public function getAuthorizeUrl( $client_id, $redirect_url, $scope='', $state='' ) {
		
		$additional_args	= array();
		
		if( $scope != '' ) {
			
			if( is_array( $scope ) ) {
				
				$additional_args['scope']	= implode( ' ', $scope );
				$additional_args['scope']	= $additional_args['scope'];
			} else {
				
				$additional_args['scope'] = $scope;
			}
		}
		
		if(!empty($state)){
			$additional_args['state'] = $state;
		}else{
			$additional_args['state']	= md5(time());	
		}
		
		return parent::getAuthorizeUrl($client_id,$redirect_url,$additional_args);
	}
	
	public function getAccessToken( $client_id = '', $secret = '', $redirect_url = '', $code = '' ) {
		
		$result	= parent::getAccessToken( $client_id, $secret, $redirect_url, $code );
		$result	= json_decode( $result, true ); 
		
		if( isset( $result['error'] ) ) {
			
			$this->error	= $result['error'].' '.$result['error_description'];
			return false;
		} else {
			$this->access_token	= $result['access_token'];
			return $result;
		}
	}
	
	public function getProfile(){
		
		$params	= array();
		$fields	= array(
						'current-status',
						'id',
						'picture-url',
						'first-name',
						'last-name',      
						'public-profile-url',
						'num-connections',
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v2/me";
		$params['method']			= 'get';
		$params['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true ); 
	}

	/* Get Profile data by Profile Open Id */
	public function getIDProfile(){
		
		$params	= array();
		$fields	= array(
						'current-status',
						'id',
						'sub',
						'picture-url',
						'first-name',
						'last-name',      
						'public-profile-url',
						'num-connections',
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v2/userinfo";
		$params['method']			= 'get';
		$params['format']			= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true ); 
	}

	public function getProfileEmail( $access_token ){
		
		$params	= array();
		$params['headers']['Authorization'] = 'Bearer '.$access_token;
		$params['headers']['X-RestLi-Protocol-Version'] = '2.0.0';

		$params['url']				= "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))";
		$params['method']			= 'get';
		$params['format']	= 'json';
		$result						= $this->makeRequest( $params, 'action');
		
		return json_decode( $result, true ); 
	}

	public function getProfileImage( $access_token ){
		
		$params	= array();
		$params['headers']['Authorization'] = 'Bearer '.$access_token;
		$params['headers']['X-RestLi-Protocol-Version'] = '2.0.0';

		$params['url']				= "https://api.linkedin.com/v2/me?projection=(profilePicture(displayImage~:playableStreams))";
		$params['method']			= 'get';
		$params['format']	= 'json';
		$result						= $this->makeRequest( $params, 'action');
		
		return json_decode( $result, true ); 
	}

	
	
	public function getUserProfile($user_id){
		
		$params	= array();
		
		$fields	= array(
						'id',
						'firstName',
						'lastName',      
						'profilePicture',
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v2/people/".$user_id.":({$request})";
		$params['method']			= 'get';
		$params['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}

	public function getConnections() {
		
		$params						= array();
		$params['url']				= "https://api.linkedin.com/v1/people/~/connections";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getGroups( $person_id ) {
		
		$fields	= array(
						'group:(id,name)',
						'membership-state',
						'show-group-logo-in-profile',
						'allow-messages-from-members',
						'email-digest-frequency',      
						'email-announcements-from-managers',
						'email-for-every-new-post'
					);
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v2/groupMemberships/?q=member&membershipStatuses=OWNER&member=".$person_id;
		$params['method']			= 'get';
		$params['format']	= 'json';
		$params['count']	= 200;
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getGroup( $group_id = '' ) {
		
		if( !$group_id ) return false;
		
		$fields = array(
						'id',
						'small-logo-url',
						'large-logo-url',
						'name',
						'short-description',
						'description',
						'site-group-url',
						'num-members'
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v2/groupDefinitions/".$group_id;
		$params['method']			= 'get';
		$params['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getCompanies() {
		
		$fields						= array( 'id', 'name' );
		$request					= join( ',', $fields );
		$params['url']				= "https://api.linkedin.com/v1/people/~:(first-name,positions:(company:({$request})))";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$params['args']['count']	= 100;
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getCompany( $company_id = "" ) {
		
		if( !$company_id ) return false;
		
		$fields = array(
						'id',
						'name',
						'website-url',
						'square-logo-url',
						'logo-url',
						'blog-rss-url',
						'description',
						'num-followers'
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v1/companies/".$company_id.":({$request})";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getAdminCompanies() {
		
		$fields		= array( 'id', 'name' );
		$request	= join( ',', $fields );
		
		$params['url']						= "https://api.linkedin.com/v1/companies";
		$params['method']					= 'get';
		$params['format']			= 'json';
		$params['count']			= 100;
		$params['is-company-admin']	= 'true';
		
		$result								= $this->makeRequest($params);
		
		return json_decode( $result, true );
	}
	
	public function getFollowedCompanies(){
		
		$fields						= array( 'id', 'name' );
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v1/people/~/following/companies:({$request})";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$params['args']['count']	= 200;
		$result						= $this->makeRequest($params);
		
		return json_decode( $result, true );
	}
	
	public function getStatuses( $self = false, $start=0, $count = 20 ) {
		
		$params['url']				= "https://api.linkedin.com/v1/people/~/network/updates";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		
		if( $start != 0 ) $params['args']['start']	= $start;
		if( $count != 0 ) $params['args']['count']	= $count;
		if( $self ) {
			$params['args']['scope']	= 'self';
		}
		$params['args']['type']		= 'SHAR';
		$params['args']['order']	= 'recency';
		
		$result	= $this->makeRequest( $params );
		return json_decode( $result, true );
	}

	public function getUserStatuses( $user_id, $self = true, $start=0, $count = 20 ) {
		
		$params['url']				= "https://api.linkedin.com/v1/people/id=".$user_id."/network/updates";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		
		if( $start != 0 ) $params['args']['start']	= $start;
		if( $count != 0 ) $params['args']['count']	= $count;
		if( $self ) {
			$params['args']['scope']	= 'self';
		}
		
		$params['args']['type']		= 'SHAR';
		$params['args']['order']	= 'recency';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getGroupPosts($group_id, $start=0,$count = 20, $order="", $category="",$role=""){
		$fields	= array(
						'id',
						'creator:(id,first-name,last-name,picture-url,headline)',
						'title',
						'summary',
						'likes',
						'comments',
						'site-group-post-url',
						'creation-timestamp',
						'attachment:(image-url,content-domain,content-url,title,summary)',
						'relation-to-viewer'
					);
		
		$request	= join( ',', $fields );
		
		if( $role != "" ) {
			$params['url']	= "https://api.linkedin.com/v1/people/~/group-memberships/".$group_id."/posts:({$request})";
		} else {
			$params['url']	= "https://api.linkedin.com/v1/groups/".$group_id."/posts:({$request})";
		}
		
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		
		if( $start != 0 ) $params['args']['start']	= $start;
		if( $count != 0 ) $params['args']['count']	= $count;
		if( $order != '' ) $params['args']['order']	= $order;
		if( $category != '' ) $params['args']['category']	= $category;
		if( $role != '' ) $params['args']['role']	= $role;
		
		$params['args']['ts']	= time();
		$result					= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getCompanyUpdates( $company_id, $start=0, $count = 20 ) {
		
		if( !$company_id ) return false;
		
		$params['url']				= "https://api.linkedin.com/v1/companies/".$company_id."/updates";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		
		if( $start != 0 ) $params['args']['start']	= $start;
		if( $count != 0 ) $params['args']['count']	= $count;
		
		$params['args']['order']		= 'recency';
		$params['args']['ts']			= time();
		$params['args']['event-type']	= 'status-update';
		$params['args']['twitter-post']	= 'false';
		$result							= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	//returns as ARRAY, if chaning to object change in getGroupPostResponses
	protected function getPostMeta( $post_id ) {
		$fields	= array(
						'id',
						'site-group-post-url',
						'creation-timestamp'
					);
		
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v1/posts/".$post_id.":({$request})";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$result						= $this->makeRequest( $params );
		
		return json_decode( $result, true );
	}
	
	public function getGroupPostResponses($post_id, $start = 0){
		 
		$fields = array(
						'id',
						'text',
						'creator:(id,first-name,last-name,picture-url)',
						'creation-timestamp'
					);
		
		$post_info					= $this->getPostMeta( $post_id );
		$request					= join(',',$fields);
		$params['url']				= "https://api.linkedin.com/v1/posts/".$post_id."/comments:({$request})";
		$params['method']			= 'get';
		$params['args']['format']	= 'json';
		$params['args']['count']	= 500;
		
		if( $start != 0 ) $params['args']['start'] = $start;
		
		$params['args']['order']	= 'recency';
		$content_return				= $this->makeRequest($params);
		$content_return				= json_decode( $content_return, true );
		
		$content_return['siteGroupPostUrl']	= isset( $post_info['siteGroupPostUrl'] ) ? $post_info['siteGroupPostUrl'] : '';
		return $content_return;
	}
	
	public function getNetworkPostResponses($update_key){
		$params['url'] = "https://api.linkedin.com/v1/people/~/network/updates/key=".$update_key."/update-comments";
		$params['method']='get';
		$params['args']['format']='json';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	
	public function getCompanyUpdateResponses($company_id,$update_id){
		$params['url'] = "https://api.linkedin.com/v1/companies/".$company_id."/updates/key=".$update_id."/update-comments";
		$params['method']='get';
		$params['args']['format']='json';
		$params['args']['event-type']='status-update';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	
	public function shareStatus($args=array(), $profile_id, $access_token ){
		
		$params['url'] = 'https://api.linkedin.com/v2/shares';
		$params['method']='post';
		$params['headers']['Authorization'] = 'Bearer '.$access_token;
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$params['headers']['X-RestLi-Protocol-Version'] = '2.0.0';
		

		$json=array();
		// $json['comment']=$args['comment'];
		if(isset($args['title']) || isset($args['submitted-url']) || isset($args['submitted-image-url']) || isset($args['description']) ){

			$json = array();

			if(isset($args['title'])){
				$json['content']['title'] = $args['title'];
			}

			if(isset($args['submitted-url']) && empty( $args['submitted-image-url'] ) ){
				$json['content']['contentEntities'] = array( array( 'entityLocation' => $args['submitted-url'] ) );
			}

			if(isset($args['submitted-url']) && !empty( $args['submitted-image-url'] ) ) {

				$json['content']['contentEntities'] = array( array( 'entityLocation' => $args['submitted-url'], 'thumbnails' => array( array('resolvedUrl' => $args['submitted-image-url'] ) ) ) );
			} 

			if(isset($args['description'])){
				$json['text'] = array( 'text' => $args['description'] );
			}

			if( !empty( $profile_id ) ){
				$json['owner'] = $profile_id;
			}
		}

		$json['distribution'] = array( 'linkedInDistributionTarget' => array( 'visibleToGuest' => true ) );

		$params['args'] = json_encode($json);

		$result =  $this->makeRequest($params, 'posting');
		return json_decode($result,true);
		// return: array('updateKey'=>'...','updateUrl'=>'...')
	}
	
	public function postToGroup( $group_id, $title, $message, $content = array() ) {
		
		$params['url']						= 'https://api.linkedin.com/v1/groups/'.$group_id.'/posts';
		$params['method']					= 'post';
		$params['headers']['Content-Type']	= 'application/json';
		$params['headers']['x-li-format']	= 'json';
		
		$json = array( 'title' => $title, 'summary' => $message );
		
		if( is_array( $content ) AND count( $content ) > 0 ) {
			
			// If the content of the post is specified (e.g., a link to a website), add it here
			$json['content'] = array(); 
			if( isset($content['title'] ) ) {
				$json['content']['title'] = $content['title'];
			}
			if( isset($content['submitted-url'] ) ) {
				$json['content']['submitted-url'] = $content['submitted-url'];
			}
			if( isset($content['submitted-image-url'] ) ) {
				$json['content']['submitted-image-url'] = $content['submitted-image-url'];
			}
			if( isset( $content['description'] ) ) {
				$json['content']['description'] = $content['description'];
			}
		}
		
		$params['args']	= json_encode( $json );
		$result			= $this->makeRequest( $params );
		return json_decode( $result, true );
	}
	
	public function postToCompany($company_id,$message,$content=array()){
		$params['url'] = 'https://api.linkedin.com/v1/companies/'.$company_id.'/shares';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('comment'=>$message , 'visibility'=> array('code'=>'anyone'));

		if(is_array($content) AND count($content)>0) {
			// If the content of the post is specified (e.g., a link to a website), add it here
			$json['content'] = array(); 
			if(isset($content['title'])){
				$json['content']['title'] = $content['title'];
			}
			if(isset($content['submitted-url'])){
				$json['content']['submitted-url'] = $content['submitted-url'];
			}
			if(isset($content['submitted-image-url'])){
				$json['content']['submitted-image-url'] = $content['submitted-image-url'];
			}
			if(isset($content['description'])){
				$json['content']['description'] = $content['description'];
			}
		}
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	
	public function deleteFromGroup($post_id){
		$params['url'] = 'https://api.linkedin.com/v1/posts/'.$post_id;
		$params['method']='delete';
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	
	public function commentToGroupPost($post_id,$response_text){
		$params['url'] = 'https://api.linkedin.com/v1/posts/'.$post_id.'/comments';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('text'=>$response_text);
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
	
	public function commentToNetworkPost($post_id,$response_text){
		$params['url'] = 'https://api.linkedin.com/v1/people/~/network/updates/key='.$post_id.'/update-comments';
		$params['method']='post';
		$params['headers']['Content-Type']='application/json';
		$params['headers']['x-li-format']='json';
		$json = array('comment'=>$response_text);
		$params['args']=json_encode($json);
		$result =  $this->makeRequest($params);
		return json_decode($result,true);
	}
}