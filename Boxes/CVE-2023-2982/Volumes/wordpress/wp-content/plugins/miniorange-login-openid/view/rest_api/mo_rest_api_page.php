<?php
function mo_openid_rest_api_page() {
	?>
		<br><br>
	<h1 style="font-style: italic; font-size: 4.5em;margin-left: 6%">Looking for <span style="color: orangered">Rest API / Mobile SSO </span> solution?</h1>
	<h3 style="padding: 3% 3% 0 3%; font-size: 1.5em;line-height: 1.5em" >mini<span style="color: orangered">O</span>range provides Social login Rest API / Mobile SSO. By using API / Mobile SSO, website visitors(User) can login/register via social media platforms to your application and also they will be linked to your WordPress site. API / Mobile SSO allow uses JWT token to send and receive information. </h3>
	<br>
	<center>
	<img src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/rest_api8.jpg" style="width: 60%;">
	</center>
	<br>
	<h1 style="font-style: italic; font-size: 4.5em;margin-left: 4%">How mini<span style="color: orangered">O</span>range <span style="color: orangered">Rest API / Mobile SSO</span> works?</h1>
	<ol style="font-size: 1.5em; margin-left: 20%;line-height: 25px">
		<li>User click on social login button.</li>
		<li>A request has been sent to the miniOrange API / mobile SSO plugin via API.</li>
		<li>mini<span style="color: orangered;font-weight: bold">O</span>range API / Mobile SSO plugin authenticates the user using OAuth/OPenID protocol.</li>
		<li>Using JWT authentication, the plugin sends back the JWT token to the application through webview.</li>
		<li> Reading the JWT token you can retrieve the user information and get the user loggedin.<i class="far fa-smile-beam fa-2x " style="  transform: translate(0%, 20%);color: orange" aria-hidden="true"></i> </li>
	</ol>
	<br>
	<center>
		<img style="width: 70%" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/rest_api5.gif">
	</center>
<br>
	<h1 style="font-style: italic; font-size: 4.5em;margin-left: 3%">How to configure <span style="color: orangered">Rest API / Mobile SSO</span> Plugin?</h1>
	<ol style="font-size: 1.5em; margin-left: 20%;line-height: 25px;padding-right: 8%">
		<li>Configure the facebook and google in the paid plugin which you have installed.That will be for your website.</li>
		<li>Copy the client ID and client secret and paste in the Rest API / mobile plugin.</li>
		<li>For mobile you need to hit the api "http://domain_name?appname=[Your Application name]&application=mobile.</li>
		<li>Check the update and create option in the Rest API / mobile plugin to automatically create the user in WordPress database.</li>
		 </ol>
	<center>
		<img style="width: 40%" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/rest_api_image.png">
	</center>
	<br>
<hr>
	<div style="margin: 1% 5% 1% 9%; border: 2px solid lightgray; height: 100px; width: 85%;background-color: white;padding: 1% 1% 0 1%">
		<h1 style="float: left;font-size: 3.5em;font-style: italic;">WANT TO PURCHASE <span style="color: orangered;">Rest API / MOBILE SSO</span></h1> <a href="https://login.xecurify.com/moas/login?redirectUrl=https://login.xecurify.com/moas/initializepayment&requestOrigin=wp_social_login_rest_api_plan" target="_blank"> <button  class="mo_mobile_sso_click_here">CLICK HERE</button></a>
	</div>
	<?php
}
?>
