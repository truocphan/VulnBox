<?php
/**

* Plugin Name: Social Login Lite For WooCommerce

* Plugin URI: http://www.phoeniixx.com/

* Description: Social Login plugin allows you to let customers associate their social (Facebook and Google+) profiles with your ecommerce site.

* Version: 1.6.0
 
* Author: phoeniixx

* Text Domain: phoen_social_login

* Domain Path: /languages

* Author URI: http://www.phoeniixx.com/

* License: GPLv2 or later

* License URI: http://www.gnu.org/licenses/gpl-2.0.html

* WC requires at least: 2.6.0

* WC tested up to: 3.8.0

**/ 

ob_start();

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	session_start();
	// Put your plugin code here 
	require_once('social_login_settings.php');

	require_once('my_account_login_hook.php');

	require_once('my_account_register_hook.php');

	require_once('login_page_hook.php');

	include_once('checkout_page_hook.php');
	
	add_action('admin_enqueue_scripts', 'updimg_scripts');
		
	function updimg_scripts() 
	{
		
		if (isset($_GET['page']) && !empty($_GET['page'])) {
			
			if(sanitize_text_field($_GET['page'] == 'psl-social-login')){
		
				wp_enqueue_media();
			
			}
		
		}
	}
	register_activation_hook(__FILE__, 'psl_plugin_activation');
		
	function psl_plugin_activation() {
		
		update_option( 'psl_enable_plugin', 1 );
		
		$social_setting=array(
										
										'facebook_details'=>array(
																				'enable_facebook'=> '' ,'facebook_id'=> '','fb_icon_url'=> ''
																				),
										'google_plus_details'=>array(
																				'enable_google_plus'=> '','google_id'=> '','google_api'=> '','google_icon_url'=> ''
																				),
										'change_text_details'=>array(
																				'sign_in'=> '','sign_up'=> ''),'change_text_details'=>array('login_label'=> '','checkout_label'=> ''
																				)
										
										);

		update_option('psl_social_plugin', $social_setting);

	}
	
	register_deactivation_hook(__FILE__, 'psl_plugin_deactivation');
		
	function psl_plugin_deactivation() {
		
		update_option( 'psl_enable_plugin', 0 );

	}
	
	add_action('wp_head', 'social_login_lite_head');
	
	add_action('admin_head', 'social_login_lite_head');
	
	function social_login_lite_head()
	{
			?>
				<style>
					 
					.login-txt {
						margin: 4px 0 0 4px;
					}

					#logo-link {
								margin: 8px 3px;
							   }
							   
					.form-row.form-row-first.login-checkout {
						float: none;
						text-align: center;
						width: 100%;
					}	

					.phoenixx_promt_msg{
						border-left-color: #46b450;
						background: #fff none repeat scroll 0 0;
						border-left: 4px solid #46b450;
						box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
						margin: 5px 0px 2px;
						padding: 1px 12px;
					}
						
				</style>
				
				<div id="fb-root"></div>
				
	<script>

	<?php

		$setting_data=get_option('psl_social_plugin');
		
		extract($setting_data['facebook_details']);

	?>
var app_id = '<?php echo $facebook_id;?>';
var login_nonce = '<?php echo wp_create_nonce( 'login-nonce' );?>';
	window.fbAsyncInit = function() {

		FB.init({

		  appId      : app_id, // Set YOUR APP ID

		  status     : true, // check login status

		  cookie     : true, // enable cookies to allow the server to access the session

		  xfbml      : true  // parse XFBML

		});
		
		FB.getLoginStatus( function(response) {
			
			//console.log(response);
			
		}, true);

	};


	function facebook_login(){
		
		FB.login(function(response) {
		  if (response.status === 'connected') {
			
			getUserInfo(response.authResponse.accessToken,app_id);
			 
		  } else {
				console.log('User cancelled login or did not fully authorize.');
		  }
		}, { auth_type: 'reauthenticate', scope: 'email',auth_nonce: login_nonce })
		
	}

	function getUserInfo(token,app_id) {

	  FB.api('/me/?fields=email,first_name,last_name,name', function(response) {
		
		response.email=jQuery.trim(response.email);

			if(response.email!='')
			{
				
				var str=response.name;

				str +="*"+response.email;

				str += "*" + "face";
				
				str += "*" + token;
				
				str += "*" + app_id;

				var data = {

					'action': 'psl_data_retrive',
					'security': login_nonce,
					'data': btoa(str)  // We pass php values differently!

				};
			}
			else
			{
				
				alert("NO Email is provided");
				
			}

			// We can also pass the url value separately from ajaxurl for front end AJAX implementations

			jQuery.post('<?php echo site_url();?>/wp-admin/admin-ajax.php', data, function(response,status) {

				if(response=='success')
				{
					
					fb_login=<?php echo get_option('psl_fb_login');?>
					
					<?php
					
					if( is_checkout() ){
						
						?>
						
							window.location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
							
						<?php
						
					}
					else
					{
						?>
						
							window.location.href = "<?php echo site_url();?>/my-account";
						
						<?php
					}
					
					?>
					
					
				}

			});

		});

	}

	function getPhoto(){

		FB.api('/me/picture?type=normal', function(response) {

		  var str="<br/><b>Pic</b> : <img src='"+response.data.url+"'/>";

		  document.getElementById("status").innerHTML+=str;

		});

	}

	  // Load the SDK asynchronously

	(function(d){

	 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];

	 if (d.getElementById(id)) {return;}

	 js = d.createElement('script'); js.id = id; js.async = true;

	 js.src = "//connect.facebook.net/en_US/all.js";

	 ref.parentNode.insertBefore(js, ref);

	}(document));

	</script>

	<div id="profile"></div>

	<script src="https://apis.google.com/js/client:plusone.js" type="text/javascript"></script>

	<script src="https://apis.google.com/js/platform.js?onload=onLoadCallback" async defer></script>

	<script type="text/javascript">

	 <?php 

	$setting_data=get_option('psl_social_plugin');

	extract($setting_data['google_plus_details']);

	 ?>
	function google_login() 
	{
		
		var myParams = {
		
			'clientid' : '<?php echo $google_id;?>',
		
			'cookiepolicy' : 'single_host_origin',
		
			'callback' : 'loginCallback',
		
			'approvalprompt':'force',
		
			'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
		
		  };
		
		  gapi.auth.signIn(myParams);
		
	}
		
	function loginCallback(result)
	{

		if(result['status']['signed_in'])
		{

			var request = gapi.client.plus.people.get(
			{

				'userId': 'me'

			});

			request.execute(function (resp)
			{

					if(resp['emails'])
					{

						for(i = 0; i < resp['emails'].length; i++)
						{

							if(resp['emails'][i]['type'] == 'account')
							{

							   var  email = resp['emails'][i]['value'];
								email=jQuery.trim(email);

							}

						}

					}

				var user_name=resp['displayName'];
				
				if(email!='' && user_name!=''){
					
					var str =  resp['displayName'];

					str += "*" + email;
					
					str += "*" + "gplus";

					//document.getElementById("profile").innerHTML = str;

					var data = {

						'action': 'psl_data_retrive',

						'security': login_nonce,

						'data': btoa(str) // We pass php values differently!

					};
					
				}else{
					alert("NO Email is provided");
				}

					// We can also pass the url value separately from ajaxurl for front end AJAX implementations

				 jQuery.post('<?php echo site_url();?>/wp-admin/admin-ajax.php', data, function(response) {

					if(response=='success'){

						<?php
					
					if( is_checkout() ){	
						
						?>
						
							window.location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
							
						<?php
						
					}
					else
					{
						?>
						
							window.location.href = "<?php echo site_url();?>/my-account";
						
						<?php
					}
					
					?>

					}

				}); 

			});

		}

	}
		
	function onLoadCallback()
	{

		gapi.client.setApiKey('<?php echo $google_api;?>');

		gapi.client.load('plus', 'v1',function(){});

	}
		
	(function() {

		   var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;

		   po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';

		   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);

	})();
		
	</script>
			<?php
		
	}

	$setting_data=get_option('psl_social_plugin');

	add_action('admin_menu', 'pctm_social_login');

	function pctm_social_login(){
		
		$plugin_dir_url =  plugin_dir_url( __FILE__ );
		
		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, $plugin_dir_url.'/images/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', 'Social Login', 'Social Login', 'manage_options', 'psl-social-login', 'social_login_settings' );
		
	}

	add_action( 'wp_ajax_psl_data_retrive', 'psl_data_retrive' );

	add_action( 'wp_ajax_nopriv_psl_data_retrive', 'psl_data_retrive' );
	
	function psl_data_retrive()
	{
		
		extract($_POST);

		check_ajax_referer( 'login-nonce', 'security');

		$data = sanitize_text_field(base64_decode($data));
		
		$user_details=explode("*",$data);

		$profile_name =sanitize_user($user_details[0]);
		
		$profile_email=sanitize_email($user_details[1]);
		
		$social_type = $user_details[2];
		
		if( !email_exists( $profile_email )&& username_exists( $profile_name )){
			
			$profile_id=rand(1000, 9999);
			
			$profile_name=$profile_name.$profile_id;
			
		}

		$user_id = username_exists( $profile_name );
		
		if ( !$user_id and email_exists($profile_email) == false ) {
			
			$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			
			$user_idd = wp_create_user( $profile_name, $random_password, $profile_email );
			
		}
	
		if(isset($user_idd)) 
		{

			$user1 = get_user_by('id',$user_idd);
			
			wp_clear_auth_cookie();
			
			wp_set_current_user( $user1->ID, $user1->user_login );

			wp_set_auth_cookie( $user1->ID );
			
			$user_info = get_userdata($user1->ID);
		
			do_action( 'wp_login', $user1->user_login,$user_info );

			$to=$profile_email;

			$sub = get_bloginfo( 'name' )." Details";
			
			$msg='<div style="background-color:#f5f5f5;width:100%;margin:0;padding:70px 0 70px 0">
					<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td valign="top" align="center">
									<table width="600" cellspacing="0" cellpadding="0" border="0" style="border-radius:6px!important;background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:6px!important">
										<tbody>
											<tr>
												<td valign="top" align="center">
													<table width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#557da1" style="background-color:#557da1;color:#ffffff;border-top-left-radius:6px!important;border-top-right-radius:6px!important;border-bottom:0;font-family:Arial;font-weight:bold;line-height:100%;vertical-align:middle">
														<tbody>
															<tr>
																<td>
																   <h1 style="color:#ffffff;margin:0;padding:28px 24px;display:block;font-family:Arial;font-size:30px;font-weight:bold;text-align:left;line-height:150%">'.get_bloginfo( 'name' ).' Login Details</h1>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td valign="top" align="center">
													<table width="600" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td valign="top" style="background-color:#fdfdfd;border-radius:6px!important">
																	<table width="100%" cellspacing="0" cellpadding="20" border="0">
																		<tbody>
																			<tr>
																				<td valign="top">
																					<div style="color:#737373;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
																						<p>Your '. get_bloginfo( 'name' ).' Login Details Are:</p>
																						<p>Username: '.$profile_name.'</p>
																						<p>Password: '.$random_password.'<br/></p>
																					 </div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr>
												<td valign="top" align="center">
													<table width="600" cellspacing="0" cellpadding="10" border="0" style="border-top:0">
														<tbody>
															<tr>
																<td valign="top">
																	<table width="100%" cellspacing="0" cellpadding="10" border="0">
																		<tbody>
																			<tr>
																				<td valign="middle" style="border:0;color:#99b1c7;font-family:Arial;font-size:12px;line-height:125%;text-align:center" colspan="2">
																					<p>'.get_bloginfo( 'name' ).'</p>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>' ;  
		   
			$header = "MIME-Version: 1.0\r\n";
			
			$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			wp_mail($to,$sub,$msg,$header);
			
			if($social_type=='gplus'){
				
				$gp_count=get_option('psl_gp_count');
				
				if($gp_count==''){
					
					$gp_count=0;
					
				}
				
				$gp_count=$gp_count+1;
				
				update_option('psl_gp_count',$gp_count);
				update_option('psl_gp_count_'.$user1->ID,'Google');
				
			}
			
			if($social_type=='face'){
				
				$fb_count=get_option('psl_fb_count');
				
				if($fb_count==''){
					
					$fb_count=0;
					
				}
				
				$fb_count=$fb_count+1;
				
				update_option('psl_fb_count',$fb_count);
				update_option('psl_fb_count_'.$user1->ID,'Facebook');
				
			}
			
			echo "success";

			exit;
				
		}
		else
		{ 						

			$user1 = get_user_by( 'email', $profile_email);
			
			if (! in_array( 'administrator', (array) $user1->roles ) ) {
				
				wp_set_current_user( $user1->ID, $user1->user_login );

			wp_set_auth_cookie( $user1->ID );
			
			$user_info = get_userdata($user1->ID);
		
			do_action( 'wp_login', $user1->user_login,$user_info );
			
			if($social_type=='gplus'){
				
				$gp_count=get_option('psl_gp_count');
				
				if($gp_count=='')
				{
					
					$gp_count=0;
					
				}
				
					$gp_count=$gp_count+1;
					
					update_option('psl_gp_count',$gp_count);
					update_option('psl_gp_count_'.$user1->ID,'Google');
					
			}
			
			if($social_type=='face'){
				
				$fb_count=get_option('psl_fb_count');
				
				if($fb_count==''){
					
					$fb_count=0;
					
				}
				
				$fb_count=$fb_count+1;
				
				update_option('psl_fb_count',$fb_count);
				update_option('psl_fb_count_'.$user1->ID,'Facebook');
				
			}

			echo "success";

			exit;	
				
			}else{
				
				echo "Only Customer can login with this.";

				exit;	
					
					
			}	
														 

		}
	}

}
else
{

	add_action('admin_notices', 'phoen_social_login_admin_notices');

		function phoen_social_login_admin_notices() {

			if (!is_plugin_active('woocommerce/woocommerce.php')) {

				echo "<div class='error'><p>Please active WooCommerce First To Use WooCommerce Social Login</p></div>";

			}

		}

}

ob_clean();

?>
