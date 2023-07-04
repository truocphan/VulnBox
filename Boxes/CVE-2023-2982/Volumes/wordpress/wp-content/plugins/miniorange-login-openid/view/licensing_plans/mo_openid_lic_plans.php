<?php

function mo_openid_licensing_plans() {
	wp_enqueue_style( 'mo_openid_plugins_page_style', esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/css/mo_openid_licensing_plan.css?version=' . MO_OPENID_SOCIAL_LOGIN_VERSION ) );
	?>
	<input type="hidden" id="mo_license_plan_selected" value="" />
	<div class="mo-openid-tab-content">
		<div class="active">
			<br>
			<div class="mo-openid-cd-pricing-container mo-openid-cd-has-margins"><br>
				<div class="mo-open-id-cd-pricing-switcher">
					<p id="pricing" class="mo-open-id-fieldset" style="background-color: #e97d68;">
						<input type="radio" name="sitetype" value="singlesite" id="singlesite" checked>
						<label for="singlesite">Plugins</label>
						<input type="radio" name="sitetype" value="multisite" id="multisite">
						<label for="multisite">Multisite</label>
						<span id="mo_switcher_1" class="mo-open-id-cd-switch"></span>
						<input type="radio" name="sitetype" value="mo_add-on" id="mo_add-on">
						<label for="mo_add-on">Add-On</label>
						<span id="mo_switcher_2" class="x"></span>
					</p>
				</div>
<!--                <div style="line-height: initial; background: #F2F5FB;border-radius:5px;font-size: large;margin-top:10px;padding:10px;border-style: solid;border-color: #2f6062">-->
<!--                    <span class="dashicons dashicons-info" style="vertical-align: bottom;"></span>-->
<!--                    Upgrading to any plan is a <b style="color: black">One-Time Payment</b> which includes 1 year of updates. You can continue using all the available features in that plan for lifetime. Contact us at <a style="color:blue-->
<!--    " href="mailto:socialloginsupport@xecurify.com">socialloginsupport@xecurify.com</a> for bulk discounts.-->
<!--                </div>-->


				

				<ul style="margin-left:25px" id="list-type" class="mo-openid-cd-pricing-list cd-bounce-invert" >

					

					<li style="width:400px" class="mo-openid-cd-popular">
						<ul class="mo-openid-cd-pricing-wrapper" id="col2">
							<li data-type="singlesite" class="mosslp is-visible" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">Premium</h2>
									<label for="mo_openid_pre">No. of Instances : </label>
									<label name="mo_openid_pre" id="mo_openid_pre">
										1
</label>

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_pre1" class="mo-openid-cd-value">89</span> &nbsp;&nbsp;
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_premium_plan')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li class="mo-openid-lic-bold-cl" style="color: red">X</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">13 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<br></span></div></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">41 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Discord, Twitch, Line, Hubspot, Paypal, Github, Dropbox, Wechat, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo,<span id="mo_openid_dots6">...</span><span id="mo_openid_more6" style="display:none" > WordPress, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots6','mo_openid_more6','mo_openid_myBtn6')" id="mo_openid_myBtn6">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">7 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Google, Vkontakte, LinkedIn,  Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li>Facebook Share Count</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li class="mo-openid-lic-bold-cl" style="padding: 7.2%;">X</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Option</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li class="mo-openid-lic-bold-cl";>X</li>

										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>

										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size: 2em; width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>

										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<li data-type="multisite" class="momslp is-hidden" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">Premium</h2>
									<label for="mo_openid_m_prem">No. of Instances : </label>
									<label id="mo_openid_m_ent" onchange="update_val_ent('mo_openid_m_ent','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										1
									</label><br/><br/>
									<label for="mo_openid_m_ent_sub">No. of Sub-Site : </label>
									<label name="mo_openid_m_ent_sub" id="mo_openid_m_ent_sub" onchange="update_val_ent('mo_openid_m_ent','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										3
									</label>
									
									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_m_pre1" class="mo-openid-cd-value">149</span> &nbsp;&nbsp;

									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_premium_multisite_plan')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li class="mo-openid-lic-bold-cl" style="color: red">X</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">13 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<br></span></div></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">41 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Discord, Twitch, Line, Hubspot, Paypal, Github, Dropbox, Wechat, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo,<span id="mo_openid_dots6">...</span><span id="mo_openid_more6" style="display:none" > WordPress, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots6','mo_openid_more6','mo_openid_myBtn6')" id="mo_openid_myBtn6">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">7 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Google, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li>Facebook Share Count</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li class="mo-openid-lic-bold-cl" style="padding: 7.2%;">X</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Option</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li class="mo-openid-lic-bold-cl";>X</li>

										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>

										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size: 2em; width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>

										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<li data-type="mo_add-on" class="moaslp is-hidden">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">MailChimp Integration Add-on</h2>
									<label for="mo_openid_mca">No. of Instances : </label>
									<label name="mo_openid_mca" id="mo_openid_mca">
										1
									</label>
									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_mca1" class="mo-openid-cd-value">111</span> &nbsp;&nbsp;
										
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_mailchimp_addon')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('mailchimp_adon')"style="cursor:pointer">Click Here to see features<i class="far fa-hand-pointer" style="color: orangered;margin-left: .5%" aria-hidden="true"></i></li>
										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<br>
							<li data-type="mo_add-on" class="moaslp is-hidden">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">Basic Discord Integration Add-on</h2>
									<label for="mo_openid_dis">No. of Instances : </label>
									<label name="mo_openid_dis" id="mo_openid_dis">
										1
									</label>
									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_dis1" class="mo-openid-cd-value">199</span>

									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_discord_addon')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('discord_adon1')"style="cursor:pointer">Click Here to see features<i class="far fa-hand-pointer" style="color: orangered;margin-left: .5%" aria-hidden="true"></i></li>
										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
						</ul> <!-- .mo-openid-cd-pricing-wrapper -->
					</li>

					<li style="width:400px">
						<ul class="mo-openid-cd-pricing-wrapper" id="col3">
							<li data-type="singlesite" class="mosslp is-visible" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">All-Inclusive</h2>
									<label for="mo_openid_ai">No. of Instances : </label>
									<label name="mo_openid_ai" id="mo_openid_ai">
										1
</label>

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_ai1" class="mo-openid-cd-value">199</span> 
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_all_inclusive')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features ">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;"  title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li onclick="mo_all_features_clk('custom_registration_adon')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>Custom Registration Form+</b></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">44 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<span id="mo_openid_dots3">...</span><span id="mo_openid_more3" style="display:none" ><br>Buffer, Amazon_wishlist, Telegram, Line, Yahoo, Instapaper, Mewe, Livejournal, Mix, AOI Mail, Qzone, Gmail, Typepad_post, Fark, Bookmark, Fintel, Mendeley, Slashdot, Wanelo, Classroom, Yummly, Hacker_news, Kakao, Plurk, Trello, wykop, Weibo, Renren, Xing, WordPress, Front it, Skype, Kindle It, Bloggerpost, Mail.ru, Papaly, Blogmarks, Twiddla.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots3','mo_openid_more3','mo_openid_myBtn3')" id="mo_openid_myBtn3">Read more</button>
											</div>
										</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">42 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Dropbox, Discord, Twitch, Line, Hubspot, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo.<span id="mo_openid_dots0">...</span><span id="mo_openid_more0" style="display:none" >Paypal, WordPress, Github, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Wechat, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots0','mo_openid_more0','mo_openid_myBtn0')" id="mo_openid_myBtn0">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">8 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Facebook, Google, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li>Discord Auto Post</li>
										<li>Hover Icons & Floating Icons</li>
										<li>Sharing Icons for BBPress</li>
										<li>WooCommerce Product Sharing</li>
										<li>E-mail subscriber</li>
										<li>Facebook Share Count
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li>Facebook Like & Recommended</li>
										<li>Pinterest Pin it Button</li>
										<li>Twitter Follow Button</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li>Vkontakte, Stumble Upon, Buffer, Pinterest and Reddit Share Count</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Options</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li>Report (Basic Data Analytics)</li>
										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size:2em;width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>
										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<li data-type="multisite" class="momslp is-hidden" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">All-Inclusive</h2>
									<label for="mo_openid_m_ai">No. of Instances : </label>
									<label name="mo_openid_m_ai" id="mo_openid_m_ai" onchange="update_val_ent('mo_openid_m_','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										1
</label><br/><br/>
									<label for="mo_openid_m_ent_sub">No. of Sub-Site : </label>
									<label name="mo_openid_m_ent_sub" id="mo_openid_m_ent_sub" onchange="update_val_ent('mo_openid_m_ent','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										3
</label>
									

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_m_ai1" class="mo-openid-cd-value">259</span> 
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_all_inclusive_multisite_plan')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features ">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;"  title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li onclick="mo_all_features_clk('custom_registration_adon')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>Custom Registration Form+</b></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">44 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<span id="mo_openid_dots3">...</span><span id="mo_openid_more3" style="display:none" ><br>Buffer, Amazon_wishlist, Telegram, Line, Yahoo, Instapaper, Mewe, Livejournal, Mix, AOI Mail, Qzone, Gmail, Typepad_post, Fark, Bookmark, Fintel, Mendeley, Slashdot, Wanelo, Classroom, Yummly, Hacker_news, Kakao, Plurk, Trello, wykop, Weibo, Renren, Xing, WordPress, Front it, Skype, Kindle It, Bloggerpost, Mail.ru, Papaly, Blogmarks, Twiddla.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots3','mo_openid_more3','mo_openid_myBtn3')" id="mo_openid_myBtn3">Read more</button>
											</div>
										</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">42 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Dropbox, Discord, Twitch, Line, Hubspot, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo.<span id="mo_openid_dots0">...</span><span id="mo_openid_more0" style="display:none" >Paypal, WordPress, Github, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Wechat, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots0','mo_openid_more0','mo_openid_myBtn0')" id="mo_openid_myBtn0">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">8 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Facebook, Google, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li class="mo-openid-lic-bold-cl">X</li>
										<li>Discord Auto Post</li>
										<li>Hover Icons & Floating Icons</li>
										<li>Sharing Icons for BBPress</li>
										<li>WooCommerce Product Sharing</li>
										<li>E-mail subscriber</li>
										<li>Facebook Share Count
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li class="mo-openid-lic-bold-cl";>X</li>
										<li>Facebook Like & Recommended</li>
										<li>Pinterest Pin it Button</li>
										<li>Twitter Follow Button</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li>Vkontakte, Stumble Upon, Buffer, Pinterest and Reddit Share Count</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Options</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li>Report (Basic Data Analytics)</li>
										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size:2em;width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>
										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
						   
							
							<li data-type="mo_add-on" class="moaslp is-hidden" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">HubSpot Integration Add-on</h2>
									<label for="mo_openid_hub">No. of Instances : </label>
									<label name="mo_openid_hub" id="mo_openid_hub">
										1
</label>
									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_hub1" class="mo-openid-cd-value">149</span> &nbsp;&nbsp;
									</div>
								</header>
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_hubspot_addon')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('hubspot_adon')"style="cursor:pointer">Click Here to see features<i class="far fa-hand-pointer" style="color: orangered;margin-left: .5%" aria-hidden="true"></i></li>


										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div>
							</li>
							<br>
						</ul> <!-- .mo-openid-cd-pricing-wrapper -->
					</li>

					<li style="width:400px" class="mo-openid-cd-popular">
						<ul class="mo-openid-cd-pricing-wrapper" id="col4">
							<li data-type="singlesite" class="mosslp is-visible" style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">Enterprise Plan</h2>
									<label for="mo_openid_ai">No. of Instances : </label>
									<label name="mo_openid_ent" id="mo_openid_ent">
									   1 
									</label>

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_ent1" class="mo-openid-cd-value">299</span> &nbsp;&nbsp;
										
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_enterprise_plan')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features ">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;"  title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li onclick="mo_all_features_clk('custom_registration_adon')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>Custom Registration Form+</b></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">44 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<span id="mo_openid_dots3">...</span><span id="mo_openid_more3" style="display:none" ><br>Buffer, Amazon_wishlist, Telegram, Line, Yahoo, Instapaper, Mewe, Livejournal, Mix, AOI Mail, Qzone, Gmail, Typepad_post, Fark, Bookmark, Fintel, Mendeley, Slashdot, Wanelo, Classroom, Yummly, Hacker_news, Kakao, Plurk, Trello, wykop, Weibo, Renren, Xing, WordPress, Front it, Skype, Kindle It, Bloggerpost, Mail.ru, Papaly, Blogmarks, Twiddla.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots3','mo_openid_more3','mo_openid_myBtn3')" id="mo_openid_myBtn3">Read more</button>
											</div>
										</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">42 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Dropbox, Discord, Twitch, Line, Hubspot, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo.<span id="mo_openid_dots0">...</span><span id="mo_openid_more0" style="display:none" >Paypal, WordPress, Github, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Wechat, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots0','mo_openid_more0','mo_openid_myBtn0')" id="mo_openid_myBtn0">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">8 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Facebook, Google, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li onclick="mo_all_features_clk('support_chat')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fa fa-user"></i> &nbsp; &nbsp;</span>Support Chat</li>
										<li onclick="mo_all_features_clk('gravity_form')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fab fa-wpforms"></i> &nbsp; &nbsp;</span>Gravity Form Integration</li>
										<li onclick="mo_all_features_clk('discord_integration')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fab fa-discord"></i> &nbsp; &nbsp;</span>Discord Integration</li>
										<li>Discord Auto Post</li>
										<li>Hover Icons & Floating Icons</li>
										<li>Sharing Icons for BBPress</li>
										<li>WooCommerce Product Sharing</li>
										<li>E-mail subscriber</li>
										<li>Facebook Share Count</li>
										<li onclick="mo_all_features_clk('github_integration')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fab fa-github"></i> &nbsp; &nbsp;</span>Github Integration</li>
										<li onclick="mo_all_features_clk('mailerlite_integration')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fa fa-mail-bulk"></i> &nbsp; &nbsp;</span>Mailerlite Integration</li>
										<li onclick="mo_all_features_clk('active_campaign_integration')" class="mo_openid-on-hover-free-fetr"  style="cursor:pointer;"><span><i class="fa fa-campground"></i> &nbsp; &nbsp;</span>Active Campaign Integration</li>
										<li onclick="mo_all_features_clk('hubspot_integration')" class="mo_openid-on-hover-free-fetr" style="cursor:pointer;"><span><i class="fab fa-hubspot"></i> &nbsp; &nbsp;</span>Hubspot Integration</li>
										<li>Facebook Like & Recommended</li>
										<li>Pinterest Pin it Button</li>
										<li>Twitter Follow Button</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li>Vkontakte, Stumble Upon, Buffer, Pinterest and Reddit Share Count</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Options</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li>Report (Basic Data Analytics)</li>
										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size:2em;width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>
										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<li data-type="multisite" class="momslp is-hidden style="">
								<header class="mo-openid-cd-pricing-header">
									<h2 style="margin-bottom: 10%;">Enterprise Plan</h2>
									<label for="mo_openid_m_ent">No. of Instances : </label>
									<label name="mo_openid_m_ent" id="mo_openid_m_ent" onchange="update_val_ent('mo_openid_m_ent','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										1
</label><br/><br/>
									<label for="mo_openid_m_ent_sub">No. of Sub-Site : </label>
									<label name="mo_openid_m_ent_sub" id="mo_openid_m_ent_sub" onchange="update_val_ent('mo_openid_m_ent','mo_openid_m_ent_sub','mo_openid_m_ent1','mo_openid_m_ent2')">
										3
</label>

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_m_ent1" class="mo-openid-cd-value">359</span> 
									</div>
								</header> <!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" onclick="mosocial_addonform('wp_social_login_enterprise_multisite_plan')" >Upgrade Now</a>
								</footer>
								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features ">
										<li onclick="mo_all_features_clk('free_feature')" style="cursor:pointer;"  title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>All Free Features +</b></li>
										<li onclick="mo_all_features_clk('custom_registration_adon')" style="cursor:pointer;" title="Click here for Feature list" class="mo_openid-on-hover-free-fetr"><b>Custom Registration Form+</b></li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">44 Social Sharing Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;"> Facebook, Twitter, Vkontakte, WhatsApp, Tunmblr, StumbleUpon, LinkedIn, Reddit, Pinterest, Pocket, Digg, Email, Print.<span id="mo_openid_dots3">...</span><span id="mo_openid_more3" style="display:none" ><br>Buffer, Amazon_wishlist, Telegram, Line, Yahoo, Instapaper, Mewe, Livejournal, Mix, AOI Mail, Qzone, Gmail, Typepad_post, Fark, Bookmark, Fintel, Mendeley, Slashdot, Wanelo, Classroom, Yummly, Hacker_news, Kakao, Plurk, Trello, wykop, Weibo, Renren, Xing, WordPress, Front it, Skype, Kindle It, Bloggerpost, Mail.ru, Papaly, Blogmarks, Twiddla.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots3','mo_openid_more3','mo_openid_myBtn3')" id="mo_openid_myBtn3">Read more</button>
											</div>
										</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">42 Social Login Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Apple, Dropbox, Discord, Twitch, Line, Hubspot, Google, Facebook, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo.<span id="mo_openid_dots0">...</span><span id="mo_openid_more0" style="display:none" >Paypal, WordPress, Github, Mail.RU, Disqus, Pinterest, Yandex, Spotify, Reddit, Tumblr, Vimeo, Kakao, Dribbble, Flickr, MeetUp, Stackexchange, Livejournal, Snapchat, Foursquare, Teamsnap, Naver, Odnoklassniki, Wiebo, Wechat, Baidu, Renren, QQ.</span><button style="border:transparent;background-color: transparent;color: tomato;" onclick="myFunction('mo_openid_dots0','mo_openid_more0','mo_openid_myBtn0')" id="mo_openid_myBtn0">Read more</button>
											</div>
										</li>
										<li>Apple, Twitch, & Discord Login</li>
										<li>
											<div class="mo_openid_tooltip" style="padding-left: 40px;">8 Pre-Configured Apps <i class="far fa-comment-dots " style="font-size:18px; color:#85929E"></i><span class="mo_openid_tooltiptext" style="width:100%;">Facebook, Google, Vkontakte, LinkedIn, Amazon, Salesforce, Yahoo, WordPress</span></div></li>
										<li>Sharing Display Option</li>
										<li>Support Chat</li>
										<li>Gravity Form Integration</li>
										<li>Discord Integration</li>
										<li>Discord Auto Post</li>
										<li>Hover Icons & Floating Icons</li>
										<li>Sharing Icons for BBPress</li>
										<li>WooCommerce Product Sharing</li>
										<li>E-mail subscriber</li>
										<li>Facebook Share Count</li>
										<li>Github Integration</li>
										<li>Mailerlite Integration</li>
										<li>Active Campaign Integration</li>
										<li>Hubspot Integration</li>
										<li>Facebook Like & Recommended</li>
										<li>Pinterest Pin it Button</li>
										<li>Twitter Follow Button</li>
										<li>Vertical Icons & Horizontal Icons</li>
										<li>Vkontakte, Stumble Upon, Buffer, Pinterest and Reddit Share Count</li>
										<li>White Backgound for Social Login Icons</li>
										<li>General Data Protection Regulation (GDPR)</li>
										<li>Google recaptcha</li>
										<li>BuddyPress Display Option</li>
										<li>Woocommerce Display Options</li>
										<li>Ultimate Member Display Option</li>
										<li>MemberPress Display Options</li>
										<li>Advance Account Linking</li>
										<li>Redirect After Login & Logout Option</li>
										<li>Role Mapping</li>
										<li>Restrict registration from specific pages</li>
										<li>Extended User Attribute</li>
										<li>User Moderation</li>
										<li>Report (Basic Data Analytics)</li>
										<li>Account Linking & Unlinking for Users</li>
										<li>Email notification to multiple admins</li>
										<li>Welcome Email to end users</li>
										<li>Customizable Email Notification template</li>
										<li>Customizable welcome Email template</li>
										<li>Custom CSS for Social Login buttons</li>
										<li>Social Login Opens in a New Window</li>
										<li>Domain restriction</li>
										<li>Force Admin To Login Using Password</li>
										<li>Send Username and Password Reset link</li>
										<li>Redirect Login To a New Window</li>
										<li>Disable Admin Bar</li>
										<li><span class="mo_openid_tooltip">MemberPress Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by MemberPress to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ); ?>" alt="wc" style="width:35px;height:20px;"> Woocommerce Integration  <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;"> First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page. Social Login icons are displayed on the Woocommerce checkout page.</span></li>
										<li><span class="mo_openid_tooltip"><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/paidmember.png' ); ?>" alt="pmpro" style="width:35px;height:20px;">  Paid Membership pro Integration <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Assign default levels or let users choose to set their levels provided by Paid Membership Pro to the users login using Social Login</span></li>
										<li><div class="mo_openid_tooltip" ><img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ); ?>" alt="bp" style="width:35px;height:20px;"> BuddyPress Integration<i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> Extended attributes returned from social app are mapped to Custom BuddyPress fields. Profile picture from social media is mapped to Buddypress avatar.</span></li>
										<li><div class="mo_openid_tooltip" ><i class="fab fa-mailchimp" style="font-size:2em;width:35px;height:20px;"></i> MailChimp Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>
										<li><div class="mo_openid_tooltip" >miniOrange OTP Integration<span style="color: red">*</span> <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;">Verify your users via OTP on registration.</span></li>
										<li><span class="mo_openid_tooltip">Custom attribute mapping <i class="far fa-comment-dots" style="font-size:18px;color:#85929E"></i> <span class="mo_openid_tooltiptext" style="width:100%;">Extended attributes returned from social app are mapped to Custom attributes created by admin. These Attributes get stored in user_meta.</span></li>
										<li><div class="mo_openid_tooltip" >Custom Integration <i class="far fa-comment-dots " style="font-size:18px;color:#85929E"> </i><span class="mo_openid_tooltiptext" style="width:100%;"> If you have a specific custom requirement in the Social Login Plugin, we can implement and integrate it in the Plugin fo you. This includes all those custom features that come under the scope of Social Login/ Sharing/ Comments and impart additional value to the plugin. These features are chargeable. Please send us a query through the support forum to get in touch with us about your custom requirements.</span></div></li>
										<li>Shortcodes to display social icons on<br/>any homepage page, post, popup and php pages.</li>

										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<li data-type="mo_add-on" class="moaslp is-hidden" style="">
								<header class="mo-openid-cd-pricing-header">

									<h2 style="margin-bottom: 10%;" >Custom Registration Form Add-On </h2>

									<label for="mo_openid_cra">No. of Instances : </label>
									<label name="mo_openid_cra" id="mo_openid_cra">
										1
</label>

									<div class="cd-price" style="margin-top: 9%;">
										<span class="mo-openid-cd-currency">$</span>
										<span id="mo_openid_cra1" class="mo-openid-cd-value">111</span> &nbsp;&nbsp;

									</div>
								</header>
								<!-- .mo-openid-cd-pricing-header -->
								<footer class="mo-openid-cd-pricing-footer">
									<a href="#" class="mo-openid-cd-select" id="mosocial_purchase_cust_addon" onclick="mosocial_addonform('wp_social_login_extra_attributes_addon')" >Upgrade Now</a>
								</footer>

								<div class="mo-openid-cd-pricing-body">
									<ul class="mo-openid-cd-pricing-features">
										<li onclick="mo_all_features_clk('custom_registration_adon')"style="cursor:pointer">Click Here to see features<i class="far fa-hand-pointer" style="color: orangered;margin-left: .5%" aria-hidden="true"></i></li>
										<li><a style="cursor: pointer" onclick="mo_openid_support_form('')">Click here to Contact Us</a></li>
									</ul>
								</div> <!-- .mo-openid-cd-pricing-body -->
							</li>
							<br>
						  
							
							<br>



						</ul> <!-- .mo-openid-cd-pricing-wrapper -->
					</li>

				</ul> <!-- .mo-openid-cd-pricing-list -->
			</div>
		</div>

	</div>

	<script>

		//apple plan
		jQuery('#mo_openid_ap').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_ap1').html("25");
				jQuery('#mo_openid_ap2').html("<s>29</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_ap1').html("89");
				jQuery('#mo_openid_ap2').html("<s>145</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_ap1').html("149");
				jQuery('#mo_openid_ap2').html("<s>290</s>");
			}
		});

		//hubspot addon
		jQuery('#mo_openid_hub').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_hub1').html("35");
				jQuery('#mo_openid_hub2').html("<s>70</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_hub1').html("149");
				jQuery('#mo_openid_hub2').html("<s>350</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_hub1').html("249");
				jQuery('#mo_openid_hub2').html("<s>700</s>");
			}
		});

		//custom registration add on
		jQuery('#mo_openid_cra').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_cra1').html("29");
				jQuery('#mo_openid_cra2').html("<s>60</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_cra1').html("119");
				jQuery('#mo_openid_cra2').html("<s>300</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_cra1').html("179");
				jQuery('#mo_openid_cra2').html("<s>600</s>");
			}
		});

		

		//apple plan for multisite
		function update_val_apple(no_of_ins, no_of_sub_sites, value, actual_value){
			no_of_sites=document.getElementById(no_of_ins).value;
			no_of_sub=document.getElementById(no_of_sub_sites).value;
			if(no_of_sites==1)
			{
				jQuery('#' + value).html(25+(20 * no_of_sub ));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+35 * no_of_sub +"</s>");
			}
			else if(no_of_sites==2)
			{
				jQuery('#' + value).html(45+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+70 * no_of_sub+"</s>");
			}
			else if(no_of_sites==3)
			{
				jQuery('#' + value).html(65+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+105 * no_of_sub+"</s>");
			}
			else if(no_of_sites==4)
			{
				jQuery('#' + value).html(85+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+140 * no_of_sub+"</s>");
			}
			else if(no_of_sites==5)
			{
				jQuery('#' + value).html(99+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+175 * no_of_sub+"</s>");
			}
			else if(no_of_sites==6)
			{
				jQuery('#' + value).html(112+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+210 * no_of_sub+"</s>");
			}
			else if(no_of_sites==7)
			{
				jQuery('#' + value).html(125+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+245 * no_of_sub+"</s>");
			}
			else if(no_of_sites==8)
			{
				jQuery('#' + value).html(135+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+280 * no_of_sub+"</s>");
			}
			else if(no_of_sites==9)
			{
				jQuery('#' + value).html(145+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+315 * no_of_sub+"</s>");
			}
			else if(no_of_sites==10)
			{
				jQuery('#' + value).html(150+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+350 * no_of_sub+"</s>");
			}
		}

		//woocommerce addon/plan
		jQuery('#mo_openid_wca_in').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_wca_in1').html("19");
				jQuery('#mo_openid_wca_in2').html("<s>40</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_wca_in1').html("89");
				jQuery('#mo_openid_wca_in2').html("<s>200</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_wca_in1').html("149");
				jQuery('#mo_openid_wca_in2').html("<s>400</s>");
			}
		});

		//premium plugin
		jQuery('#mo_openid_pre').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_pre1').html("49");
				jQuery('#mo_openid_pre2').html("<s>80</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_pre1').html("149");
				jQuery('#mo_openid_pre2').html("<s>400</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_pre1').html("199");
				jQuery('#mo_openid_pre2').html("<s>800</s>");
			}
		});

		//premium for multisite
		function update_val_prem(no_of_ins, no_of_sub_sites, value, actual_value){
			no_of_sites=document.getElementById(no_of_ins).value;
			no_of_sub=document.getElementById(no_of_sub_sites).value;
			if(no_of_sites==1)
			{
				jQuery('#' + value).html(49+(20 * no_of_sub ));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+59 * no_of_sub +"</s>");
			}
			else if(no_of_sites==2)
			{
				jQuery('#' + value).html(98+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+118 * no_of_sub+"</s>");
			}
			else if(no_of_sites==3)
			{
				jQuery('#' + value).html(138+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+177 * no_of_sub+"</s>");
			}
			else if(no_of_sites==4)
			{
				jQuery('#' + value).html(168+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+236 * no_of_sub+"</s>");
			}
			else if(no_of_sites==5)
			{
				jQuery('#' + value).html(199+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+295 * no_of_sub+"</s>");
			}
			else if(no_of_sites==6)
			{
				jQuery('#' + value).html(208+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+354 * no_of_sub+"</s>");
			}
			else if(no_of_sites==7)
			{
				jQuery('#' + value).html(218+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+413 * no_of_sub+"</s>");
			}
			else if(no_of_sites==8)
			{
				jQuery('#' + value).html(228+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+472 * no_of_sub+"</s>");
			}
			else if(no_of_sites==9)
			{
				jQuery('#' + value).html(238+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+531 * no_of_sub+"</s>");
			}
			else if(no_of_sites==10)
			{
				jQuery('#' + value).html(248+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+590 * no_of_sub+"</s>");
			}
		}

		//buddypress addon/plan
		jQuery('#mo_openid_bpa').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_bpa1').html("19");
				jQuery('#mo_openid_bpa2').html("<s>40</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_bpa1').html("89");
				jQuery('#mo_openid_bpa2').html("<s>200</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_bpa1').html("149");
				jQuery('#mo_openid_bpa2').html("<s>400</s>");
			}
		});

		//all-inclusive plan
		jQuery('#mo_openid_ai').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_ai1').html("89");
				jQuery('#mo_openid_ai2').html("<s>140</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_ai1').html("299");
				jQuery('#mo_openid_ai2').html("<s>700</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_ai1').html("449");
				jQuery('#mo_openid_ai2').html("<s>1400</s>");
			}
		});
		//Enterprise plan
		jQuery('#mo_openid_ent').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_ent1').html("199");
				jQuery('#mo_openid_ent2').html("<s>299</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_ent1').html("599");
				jQuery('#mo_openid_ent2').html("<s>1500</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_ent1').html("999");
				jQuery('#mo_openid_ent2').html("<s>2990</s>");
			}
		});

		//all-inclusive plan for multisite
		function update_val_allinc(no_of_ins, no_of_sub_sites, value, actual_value){
			no_of_sites=document.getElementById(no_of_ins).value;
			no_of_sub=document.getElementById(no_of_sub_sites).value;
			if(no_of_sites==1)
			{
				jQuery('#' + value).html(89+(20 * no_of_sub ));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+99 * no_of_sub +"</s>");
			}
			else if(no_of_sites==2)
			{
				jQuery('#' + value).html(159+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+198 * no_of_sub+"</s>");
			}
			else if(no_of_sites==3)
			{
				jQuery('#' + value).html(209+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+297 * no_of_sub+"</s>");
			}
			else if(no_of_sites==4)
			{
				jQuery('#' + value).html(259+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+396 * no_of_sub+"</s>");
			}
			else if(no_of_sites==5)
			{
				jQuery('#' + value).html(299+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+495 * no_of_sub+"</s>");
			}
			else if(no_of_sites==6)
			{
				jQuery('#' + value).html(339+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+594 * no_of_sub+"</s>");
			}
			else if(no_of_sites==7)
			{
				jQuery('#' + value).html(379+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+693 * no_of_sub+"</s>");
			}
			else if(no_of_sites==8)
			{
				jQuery('#' + value).html(409+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+792 * no_of_sub+"</s>");
			}
			else if(no_of_sites==9)
			{
				jQuery('#' + value).html(429+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+891 * no_of_sub+"</s>");
			}
			else if(no_of_sites==10)
			{
				jQuery('#' + value).html(449+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+990 * no_of_sub+"</s>");
			}
		}

 //Enterprise plan for multisite
		function update_val_ent(no_of_ins, no_of_sub_sites, value, actual_value){
			no_of_sites=document.getElementById(no_of_ins).value;
			no_of_sub=document.getElementById(no_of_sub_sites).value;
			if(no_of_sites==1)
			{
				jQuery('#' + value).html(199+(20 * no_of_sub ));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+399 * no_of_sub +"</s>");
			}
			else if(no_of_sites==2)
			{
				jQuery('#' + value).html(349+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+798 * no_of_sub+"</s>");
			}
			else if(no_of_sites==3)
			{
				jQuery('#' + value).html(449+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+1197 * no_of_sub+"</s>");
			}
			else if(no_of_sites==4)
			{
				jQuery('#' + value).html(599+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+1596 * no_of_sub+"</s>");
			}
			else if(no_of_sites==5)
			{
				jQuery('#' + value).html(699+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+1995 * no_of_sub+"</s>");
			}
			else if(no_of_sites==6)
			{
				jQuery('#' + value).html(799+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+2394 * no_of_sub+"</s>");
			}
			else if(no_of_sites==7)
			{
				jQuery('#' + value).html(849+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+2793 * no_of_sub+"</s>");
			}
			else if(no_of_sites==8)
			{
				jQuery('#' + value).html(899+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+3192 * no_of_sub+"</s>");
			}
			else if(no_of_sites==9)
			{
				jQuery('#' + value).html(949+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+3591 * no_of_sub+"</s>");
			}
			else if(no_of_sites==10)
			{
				jQuery('#' + value).html(999+(20 * no_of_sub * no_of_sites));
				no_of_sub=parseInt(no_of_sub) +1;
				jQuery('#' + actual_value).html("<s>"+3990 * no_of_sub+"</s>");
			}
		}

		//mailchimp addon
		jQuery('#mo_openid_mca').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_mca1').html("19");
				jQuery('#mo_openid_mca2').html("<s>40</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_mca1').html("89");
				jQuery('#mo_openid_mca2').html("<s>200</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_mca1').html("149");
				jQuery('#mo_openid_mca2').html("<s>400</s>");
			}
		});

		//discord addon
		jQuery('#mo_openid_dis').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_dis1').html("65");
				jQuery('#mo_openid_dis2').html("<s>130</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_dis1').html("269");
				jQuery('#mo_openid_dis2').html("<s>650</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_dis1').html("449");
				jQuery('#mo_openid_dis2').html("<s>1300</s>");
			}
		});

		//Apple plan
		jQuery('#mo_openid_ap').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_ap1').html("19");
				jQuery('#mo_openid_ap2').html("<s>40</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_ap1').html("99");
				jQuery('#mo_openid_ap2').html("<s>200</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_ap1').html("179");
				jQuery('#mo_openid_ap2').html("<s>400</s>");
			}
		});

		//social sharing for multisite
		jQuery('#mo_openid_ssm').on('change', function () {
			if (this.value === "1") {
				jQuery('#mo_openid_ssm1').html("19");
				jQuery('#mo_openid_ssm2').html("<s>25</s>");
			} else if (this.value === "5") {
				jQuery('#mo_openid_ssm1').html("45");
				jQuery('#mo_openid_ssm2').html("<s>125</s>");
			}
			else if (this.value === "10") {
				jQuery('#mo_openid_ssm1').html("99");
				jQuery('#mo_openid_ssm2').html("<s>250</s>");
			}
		});
		
		var card1 = document.getElementById('col1');
		var card2= document.getElementById('col2');
		var card3= document.getElementById('col3');
		var card4= document.getElementById('col4');

		document.getElementById('multisite').addEventListener('click', function() {
			card1.classList.toggle('flipped');
			card2.classList.toggle('flipped');
			card3.classList.toggle('flipped');
			card4.classList.toggle('flipped');
		}, false);

		document.getElementById('mo_add-on').addEventListener('click', function() {
			card1.classList.toggle('flipped');
			card2.classList.toggle('flipped');
			card3.classList.toggle('flipped');
			card4.classList.toggle('flipped');
		}, false);

		document.getElementById('singlesite').addEventListener('click', function() {
			card1.classList.toggle('flipped');
			card2.classList.toggle('flipped');
			card3.classList.toggle('flipped');
			card4.classList.toggle('flipped');
		}, false);
	</script>
	<script>
		jQuery("input[name=sitetype]:radio").change(function() {
			if (this.value == 'multisite') {
				document.getElementById("list-type").style.width = "100%";
				jQuery('.mosslp').removeClass('is-visible').addClass('is-hidden');
				jQuery('.moaslp').removeClass('is-visible').addClass('is-hidden');
				jQuery('.momslp').addClass('is-visible').removeClass('is-hidden is-selected');
				jQuery('#mo_switcher_2').removeClass('mo-open-id-cd-switch-2');
				jQuery('#mo_switcher_1').addClass('mo-open-id-cd-switch');
				jQuery('#mo_share_plan').hide();
			}
			else if(this.value=='singlesite'){
				document.getElementById("list-type").style.width = "100%";
				jQuery('.mosslp').addClass('is-visible').removeClass('is-hidden');
				jQuery('.momslp').removeClass('is-visible').addClass('is-hidden is-selected');
				jQuery('.moaslp').removeClass('is-visible').addClass('is-hidden is-selected');
				jQuery('#mo_share_plan').show();
			}
			else if(this.value=='mo_add-on'){
				document.getElementById("list-type").style.width = "100%";
				jQuery('.moaslp').addClass('is-visible').removeClass('is-hidden');
				jQuery('.momslp').removeClass('is-visible').addClass('is-hidden is-selected');
				jQuery('.mosslp').removeClass('is-visible').addClass('is-hidden is-selected');
				jQuery('#mo_switcher_1').removeClass('mo-open-id-cd-switch');
				jQuery('#mo_switcher_2').addClass('mo-open-id-cd-switch-2');
				jQuery('#mo_share_plan').hide();
			}
		});

		jQuery(document).ready(function($){
			var x="<?php echo esc_attr( get_option( 'mo_openid_extension_tab' ) ); ?>";
			if(x==1){
				document.getElementById('singlesite').checked= false;
				document.getElementById('mo_add-on').checked= true;
				jQuery('#mo_share_plan').hide();
			}
			if(document.getElementById("mo_add-on").checked == true){
				jQuery('.mosslp').removeClass('is-visible').addClass('is-hidden');
				jQuery('.momslp').removeClass('is-visible').addClass('is-hidden');
				jQuery('.moaslp').addClass('is-visible').removeClass('is-hidden is-selected');
				jQuery('#mo_switcher_1').removeClass('mo-open-id-cd-switch');
				jQuery('#mo_switcher_2').addClass('mo-open-id-cd-switch-2');
			}

			//switch from monthly to annual pricing tables
			bouncy_filter($('.cd-pricing-container'));

			function bouncy_filter(container) {
				container.each(function(){
					var pricing_table = $(this);
					var filter_list_container = pricing_table.children('.cd-pricing-switcher'),
						filter_radios = filter_list_container.find('input[type="radio"]'),
						pricing_table_wrapper = pricing_table.find('.mo-openid-cd-pricing-wrapper');

					//store pricing table items
					var table_elements = {};
					filter_radios.each(function(){
						var filter_type = $(this).val();
						table_elements[filter_type] = pricing_table_wrapper.find('li[data-type="'+filter_type+'"]');
					});

					//detect input change event
					filter_radios.on('change', function(event){
						event.preventDefault();
						//detect which radio input item was checked
						var selected_filter = $(event.target).val();

						//give higher z-index to the pricing table items selected by the radio input
						show_selected_items(table_elements[selected_filter]);

						//rotate each mo-openid-cd-pricing-wrapper
						//at the end of the animation hide the not-selected pricing tables and rotate back the .mo-openid-cd-pricing-wrapper

						if( !Modernizr.cssanimations ) {
							hide_not_selected_items(table_elements, selected_filter);
							pricing_table_wrapper.removeClass('is-switched');
						} else {
							pricing_table_wrapper.addClass('is-switched').eq(0).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
								hide_not_selected_items(table_elements, selected_filter);
								pricing_table_wrapper.removeClass('is-switched');
								//change rotation direction if .mo-openid-cd-pricing-list has the .cd-bounce-invert class
								if(pricing_table.find('.mo-openid-cd-pricing-list').hasClass('cd-bounce-invert')) pricing_table_wrapper.toggleClass('reverse-animation');
							});
						}
					});
				});
			}
			function show_selected_items(selected_elements) {
				selected_elements.addClass('is-selected');
			}

			function hide_not_selected_items(table_containers, filter) {
				$.each(table_containers, function(key, value){
					if ( key != filter ) {
						$(this).removeClass('is-visible is-selected').addClass('is-hidden');

					} else {
						$(this).addClass('is-visible').removeClass('is-hidden is-selected');
					}
				});
			}
		});
	</script>
	<div class="clear">
		<hr>
		<h3>Refund Policy -</h3>
		<p><b>At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you
				purchased is not working as advertised and you've attempted to resolve any issues with our support
				team, which couldn't get resolved then we will refund the whole amount within 10 days of the
				purchase. Please email us at <a href="mailto:info@xecurify.com"><i>info@xecurify.com</i></a> for any
				queries regarding the return policy.</b></p>
		<b>Not applicable for -</b>
		<ol>
			<li>Returns that are because of features that are not advertised.</li>
			<li>Returns beyond 10 days.</li>
		</ol>
	</div>

	<div id="myModal_3" class="mo_openid_popup-modal">

		<div class="mo_openid_modal-content">
			<span class="mo_openid-popup-modal-close">&times;</span>
			<div id="add_list_inst">
			</div>
		</div>
	</div>
	<?php
	$feature_array = array(
		'free_feature'                => '<h2>Social Login Free Feature</h2><li>Social Login with Facebook, Google, Twitter, Vkontakte, LinkedIn, Amazon, Salesforce & Yahoo</li><li>NO SETUP required for default social login apps.</li><li>Setup your own social login application with APP ID and APP Secret for Facebook, Google, Twitter, Vkontakte, LinkedIn, Amazon & Yahoo</li><li>Preview is available for Social Login icons</li><li>Icon Customizations – customize shape, theme, space & size of social login icons.</li><li>Profile completion (username, email) – Prompt user for email & username if social login app doesn’t return it. Email is verified with verification code.</li><li>Assign Roles to users – Assign WordPress roles to users logging in with social login application.</li><li>Add the Social Login Icons on: login page, registration page, comment form or anywhere on your site using our Social Login widget/ shortcode.</li><li>Sync Social Profile Picture with WordPress.</li><li>Enable Email Notification to admin when user registers with social login application.</li><li>Enable/disable user registration.</li><li>Customize Login/Logout Redirect URL.</li><li>Customizable Text For Social Login Icons.</li><li>SHORTCODE available for social login.</li><li>Support using email and in-plugin support form.</li>',
		'mailchimp_adon'              => '<h2><i class="fab fa-mailchimp" style="width:35px;height:30px;color: #0c1f28; font-size: 2em"></i> Mailchimp Integration Add-on Feature</h2><h2>Description</h2><li>A user is added as a subscriber to a mailing list in MailChimp when that user registers using Social Login. First name, last name and email are also captured for that user in the Mailing List. Option is available to download csv file that has list of emails of all users in WordPress.</span></li>',
		'custom_registration_adon'    => '<h2><img src="' . esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/miniorange_logo.png' ) . '" style="width:35px;height:30px;">  Custom Registration Form Add-on Features</h2><li>Create a pre-registration form</li><li>Allow user to select Role while Registration</li> <li>All WordPress Themes Supported</li><li>Map Users Data returned from all Social Apps</li><li>Add Custom Fields in the Registration form</li><li>Edit Profile option using shortcode</li><li>Support input field types: text, date, checkbox or dropdown</li><li>Advanced Form Control</li><li>Sync existing meta field</li>',
		'woocommerce_int_adon'        => '<h2><img src="' . esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/woocommerce.png' ) . '" style="width:35px;height:30px;"> WooCommerce Integration Add-on Features</h2><li>WooCommerce Display Options</li><li>First name, last name and email are pre-filled in billing details of a user and on the Woocommerce checkout page.</li><li>Social Login on WooCommerce Login Page</li><li>Social Login on WooCommerce Registration Page</li><li>Social Login on WooCommerce Checkout Page</li><li>Before WooCommerce Login Form</li><li>Before "Remember Me" of WooCommerce Login Form</li> <li>After WooCommerce Login Form</li><li>Before WooCommerce Registration Form</li><li>Before "Register button" of WooCommerce Registration Form</li> <li>After WooCommerce Registration Form</li><li>Before & After WooCommerce Checkout Form</li>',
		'buddypress_adon'             => ' <h2><img src="' . esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/buddypress.png' ) . '" style="width:35px;height:30px;"> Buddypress Integration Add-on Features</h2><h2>Description</h2><li>Social Login for BuddyPress</li> <li>Extended attributes returned from social app are mapped to Custom BuddyPress fields.</li><li> Profile picture from social media is mapped to Buddypress avatar</li><li>Before BuddyPress Registration Form</li><li>Before BuddyPress Account Details</li><li>After BuddyPress Registration Form</li>',
		'hubspot_adon'                => ' <h2><i class="fab fa-hubspot" style="width:35px;height:30px;color: orangered;font-size: 1.6em;"></i>Hubspot Integration Add-on Feature</h2><h2>Description</h2><li>Add social login user to Hubspot Contact List</li><li>Track the activities of the user on your website.</li>',
		'discord_adon1'               => ' <h2><i class="fab fa-discord" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Discord Integration Add-on Feature</h2><h2>Description</h2><li>Manage your users and restrict the login/registration of user based on whether the user is present in the Discord server or not.</li>',
		'discord_adon2'               => ' <h2><i class="fab fa-discord" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Discord Integration Add-on Feature</h2><h2>Description</h2><li>Manage your users and restrict the login/registration of user based on whether the user is present in the Discord server or not.</li><li>Advance role mapping (Discord to WordPress).</li><li>Add user to discord server.</li>',
		'discord_adon3'               => ' <h2><i class="fab fa-discord" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Discord Integration Add-on Feature</h2><h2>Description</h2><li>Manage your users and restrict the login/registration of user based on whether the user is present in the Discord server or not.</li><li>Advance role mapping (Discord to WordPress).</li><li>Add user to discord server.</li><li>Wordpress to Discord role mapping.</li><li>Manage user subscription on discord server.Supported Plugins PaidMembershipPro WooCommerce Subscriptions.</li>',
		'support_chat'                => ' <h2><i class="fa fa-user" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Support Chat Add-on Feature</h2><h2>Description</h2><li>Manage your users queries and guide your users while chatting with them through this support chat feature.</li>',
		'gravity_form'                => ' <h2><i class="fab fa-wpforms" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Gravity Form Integration Feature</h2><h2>Description</h2><li>It allows you to map the attribute return from the social login provider with the Gravity user registration form.</li>',
		'discord_integration'         => ' <h2><i class="fab fa-discord" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Discord Integration Add-on Feature</h2><h2>Description</h2><li>Manage your users and restrict the login/registration of user based on whether the user is present in the Discord server or not.</li>',
		'github_integration'          => ' <h2><i class="fab fa-github" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Github Integration Add-on Feature</h2><h2>Description</h2><li>Manage your users and restrict the login/registration of user based on whether the user is present in the Github server or not.</li>',
		'mailerlite_integration'      => ' <h2><i class="fa fa-mail-bulk" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Mailerlite Integration </h2><h2>Description</h2><li>A user is added as a subscriber to a mailing list in Mailerlite when that user registers using Social Login. First name and email are also captured for that user in the Mailing List.</li>',
		'active_campaign_integration' => ' <h2><i class="fa fa-campground" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i> Active Campaign Add-on Feature</h2><h2>Description</h2><li>A user is added as a contact in to your active campaign list when user register with social media application. Captured first name, last name and email address.</li>',
		'hubspot_integration'         => ' <h2><i class="fab fa-hubspot" style="width:35px;height:30px;color: #7289da;font-size: 1.6em;"></i>Hubspot Integration Add-on Feature</h2><h2>Description</h2><li>Add social login user to Hubspot Contact List</li><li>Track the activities of the user on your website.</li>',
		'social_share'                => ' <h2><img src="' . esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'includes/images/miniorange_logo.png' ) . '" style="width:35px;height:30px;"> Social Share Plan Feature</h2><li>All Free Features</li><li>45 Social Sharing Apps</li><li>Sharing Display Option</li><li>Hover Icons & Floating Icons</li><li>Discord Auto Post</li><li>Sharing Icons for BBPress</li><li>WooCommerce Product Sharing</li><li>E-mail subscriber</li><li>Facebook, Vkontakte, Stumble Upon, Buffer, Pinterest and Reddit Share Count</li><li>Facebook Like & Recommended</li><li>Pinterest Pin it Button</li><li>Twitter Follow Button</li>',
	)

	?>
	<script> //var modal = ;
		function mo_all_features_clk(x){
			if( x === 'discord_role_mapping'){
				window.open('https://plugins.miniorange.com/discord-wordpress-single-sign-on-integration');
			}
			else{
				var instruction=<?php echo json_encode( $feature_array ); ?>;
				document.getElementById('add_list_inst').innerHTML= instruction[x];
				document.getElementById('myModal_3').style.display="block";
			}

		}

		var span = document.getElementsByClassName("mo_openid-popup-modal-close")[0];
		span.onclick = function() {
			document.getElementById('myModal_3').style.display = "none";
		}
		window.onclick = function(event) {
			if (event.target == document.getElementById('myModal_3')) {
				document.getElementById('myModal_3').style.display = "none";
			}
		}
	</script>
	<script>
		//to set heading name


		function myFunction(dots_id,read_id,btn_id) {

			var dots = document.getElementById(dots_id);
			var moreText = document.getElementById(read_id);
			var btnText = document.getElementById(btn_id);

			if (dots.style.display === "none") {
				dots.style.display = "inline";
				btnText.innerHTML = "Read more";
				moreText.style.display = "none";
			} else {
				dots.style.display = "none";
				btnText.innerHTML = "Close";
				moreText.style.display = "inline";
			}
		}
		function mosocial_addonform(planType) {
			var mo_openid_customer_toggle_update_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-customer-toggle-update-nonce' ) ); ?>';
			jQuery.ajax({
				url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", //the page containing php script
				method: "POST", //request type,
				dataType: 'json',
				data: {
					action: 'mo_register_customer_toggle_update',
					'mo_openid_customer_toggle_update_nonce' : mo_openid_customer_toggle_update_nonce,
				},
				success: function (result) {
					if(result.status){
						jQuery('#requestOrigin').val(planType);
						jQuery('#mosocial_loginform').submit();
					}
					else
					{
						alert("It seems you are not registered with miniOrange. Please login or register with us to upgrade to premium plan.");
						window.location.href="<?php echo esc_url( site_url() ); ?>".concat("/wp-admin/admin.php?page=mo_openid_general_settings&tab=profile");
					}
				}
			});
		}
	</script>

	</td>

	<td>
		<form style="display:none;" id="mosocial_loginform" action="<?php echo esc_url( get_option( 'mo_openid_host_name' ) ) . '/moas/login'; ?>"
			  target="_blank" method="post" >
			<input type="email" name="username" value="<?php echo esc_attr( get_option( 'mo_openid_admin_email' ) ); ?>" />
			<input type="text" name="redirectUrl" value="<?php echo esc_attr( get_option( 'mo_openid_host_name' ) ) . '/moas/initializepayment'; ?>" />
			<input type="text" name="requestOrigin" id="requestOrigin"/>
		</form>
	</td>
	<?php
}

