<?php

require_once dirname( __FILE__ ) . '/mo_openid_sso_encryption.php';

ob_start();

require 'view/config_apps/mo_openid_config_apps.php';
require 'view/customise_social_icons/mo_openid_cust_icons.php';
require 'view/disp_options/mo_openid_dispopt.php';
require 'view/doc_tab/mo_openid_doc_tab.php';
require 'view/email_settings/mo_openid_set_email.php';
require 'view/integration/mo_openid_integrate.php';
require 'view/licensing_plans/mo_openid_lic_plans.php';
require 'view/link_social_account/mo_openid_Acclink.php';
require 'view/premium_features/mo_openid_prem_feat.php';
require 'view/profile_completion/mo_openid_prof_comp.php';
require 'view/recaptcha/mo_openid_recap.php';
require 'view/redirect_options/mo_openid_redirect_op.php';
require 'view/registration/mo_openid_registration.php';
require 'view/reports/mo_openid_reports.php';
require 'view/shrtco/mo_openid_shrtco.php';
require 'CustomerOpenID.php';
require 'view/soc_sha/soc_apps/mo_openid_sharing.php';
require 'view/soc_sha/share_cnt/mo_openid_shrcnt.php';
require 'view/soc_sha/cust_text/mo_openid_cust_shricon.php';
require 'view/soc_sha/disp_shropt/mo_openid_disp_shropt.php';
require 'view/soc_sha/shrt_co/mo_openid_shrtco.php';
require 'view/soc_com/com_Cust/mo_openid_comm_cust.php';
require 'view/soc_com/com_display_options/mo_openid_comm_disp_opt.php';
require 'view/soc_com/com_select_app/mo_openid_comm_select_app.php';
require 'view/soc_com/com_Enable/mo_openid_comm_enable.php';
require 'view/soc_com/com_shrtco/comm_shrtco.php';
require 'view/add_on/custom_registration_form.php';
require 'view/add_on/mo_woocommerce_add_on.php';
require 'view/add_on/mo_mailchimp_add_on.php';
require 'view/add_on/mo_buddypress_add_on.php';
require 'view/add_on/mo_hubspot_add_on.php';
require 'view/add_on/mo_discord_add_on.php';
require 'view/soc_sha/soc_med_cust/mo_openid_social_media_cust.php';
require 'view/soc_sha/twitter_btn/mo_twitter_btn.php';
require 'view/soc_sha/soc_med_ser/mo_openid_social_media_services.php';

function mo_register_openid() {
	if ( isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) !== 'register' ) {  // phpcs:ignore WordPress.Security.NonceVerification -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
		$active_tab = sanitize_text_field( $_GET['tab'] ); // phpcs:ignore WordPress.Security.NonceVerification  -- Ignoring nonce verification because we are fetching data from URL and not on form submission.
	} else {
		$active_tab = 'config_apps';
	}
	if ( mo_openid_restrict_user() ) {
		$disable = 'disabled';
	} else {
		$disable = '';
	}

	if ( $active_tab == 'reports' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<div style="text-align:center; margin-top: 3%;"><h1>Login Reports</h1><h4>This feature is only available in the All-Inclusive Plan</h4></div>

		<?php
	} elseif ( $active_tab == 'doc_tab' || $active_tab == 'rest_api_page' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left;margin-left: 40px;" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<?php
	} elseif ( $active_tab == 'licensing_plans' ) {
		?>
		<div style="text-align:center;"><h1>Social Login Plugin</h1></div>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<div style="text-align:center; color: rgb(233, 125, 104); margin-top: 55px; font-size: 23px"> You are currently on the Free version of the plugin <br> <br><span style="font-size: 20px; ">
					<li style="color: dimgray; margin-top: 0px;list-style-type: none;">
						<div class="mo_openid-quote">
							<p>
								<span onclick="void(0);" class="mo_openid-check-tooltip" style="font-size: 15px">Why should I upgrade?
									<span class="mo_openid-info">
										<span class="mo_openid-pronounce">Why should I upgrade to premium plugin?</span>
										<span class="mo_openid-text">Upgrading lets you access all of our features such as Integration, User account moderation etc.</span>
									</span>
								</span>
							</p>
						</div>
						<br><br>
					</li>
		</div>

		<?php
	} else {
		if ( $active_tab == 'integration-woocommerce' || $active_tab == 'integration-buddypress' || $active_tab == 'integration-mailchimp' || $active_tab == 'integration-customregistration' || $active_tab == 'integration-hubspot' || $active_tab == 'integration-discord' || $active_tab == 'integration-memberpress' || $active_tab == 'integration-paidmemb' ) {
			$active_tab = 'integration';
		}
		?>

		<?php
		include 'view/rate_us/rate_us.php';

		?>
	<div style="width: 100%;margin-top: 13px;" id="mo-main-content-div">
		<div id="mo_openid_menu_height" style="width: 15%; float: left; background-color: #32373C; ">
			<div style="margin-top: 9px;border-bottom: 0px;text-align:center;">
				<div><img style="float:left;margin-left:8px;padding-top: 5px; height: 52px;width: 54px;padding-bottom: 23px;" src="<?php echo esc_url( plugins_url( 'includes/images/logo.png"', __FILE__ ) ); ?>"></div>
				<br>
				<span style="font-size:18px;color:white;float:left; font-weight: 900;"><?php echo esc_attr( mo_sl( 'miniOrange' ) ); ?></span>
			</div>
			<div class="mo_openid_tab" style="width:100%;">
				<a id="config_apps" class="tablinks
				<?php
				if ( $active_tab == 'config_apps' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'config_apps' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Configure Apps' ) ); ?></a>
				<a id="customise_social_icons" class="tablinks
				<?php
				if ( $active_tab == 'customise_social_icons' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'customise_social_icons' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Customise Social Login Icons' ) ); ?></a>
				<a id="disp_opt" class="tablinks
				<?php
				if ( $active_tab == 'disp_opt' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'disp_opt' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Display Options' ) ); ?></a>
				<a id="redirect_opt" class="tablinks
				<?php
				if ( $active_tab == 'redirect_opt' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'redirect_opt' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Redirect Options' ) ); ?></a>
				<a id="registration" class="tablinks
				<?php
				if ( $active_tab == 'registration' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'registration' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Registration' ) ); ?></a>
				<a id="integration" style="color: white;" class="tablinks
				<?php
				if ( $active_tab == 'integration' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Popular Integrations' ) ); ?><span class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>
				<a id="profile_completion" class="tablinks
				<?php
				if ( $active_tab == 'profile_completion' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'profile_completion' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Profile Completion' ) ); ?></a>
				<a id="email_settings" class="tablinks
				<?php
				if ( $active_tab == 'email_settings' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'email_settings' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Email Notification' ) ); ?><span class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>
				<a id="recaptcha" class="tablinks
				<?php
				if ( $active_tab == 'recaptcha' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'recaptcha' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Recaptcha / GDPR' ) ); ?><span class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>
				<a id="premium_features" class="tablinks
				<?php
				if ( $active_tab == 'premium_features' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'premium_features' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Premium Features' ) ); ?><span class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>
				<a id="link_social_acc" class="tablinks
				<?php
				if ( $active_tab == 'link_social_acc' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'link_social_acc' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Link Social Account & Domain Restriction' ) ); ?>
					 <?php
						if ( $disable ) {
							?>
					<span class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span><?php } ?></a>
				<a id="shortcodes" class="tablinks
				<?php
				if ( $active_tab == 'shortcodes' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'shortcodes' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Shortcodes' ) ); ?></a>
				<a id="add_on" class="tablinks
				<?php
				if ( $active_tab == 'add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Add On' ) ); ?></a>
				<a id="profile" class="tablinks
				<?php
				if ( $active_tab == 'profile' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'profile' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'User Profile' ) ); ?></a>
			</div>
		</div>
		<style>
			body {font-family: Arial, Helvetica, sans-serif;}
		</style>
		<div id="mo_openid_settings" style="width: 85%; float: right;">
			<div style="background-color: #FFFFFF;width: 100%;">
					<div id="mo_openid_page_heading" class="mo_openid_highlight" style="color: white;margin: 0;padding-bottom: 32px;padding-top: 12px;">&nbsp
					   <div align="center">
							<table>
								<tr>
									<td><div>
										<a id="addon" class="mo_sl_header_text mo_sl_effect-shine mo_sl_expand" style="color: white; text-decoration: none; " <?php echo $active_tab == 'add_on' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Add On' ) ); ?></a>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="pricing" class="mo_sl_header_text mo_sl_effect-shine mo_sl_expand" style="color: white; text-decoration: none; "<?php echo $active_tab == 'licensing_plans' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" onclick="mo_openid_extension_switch()"><?php echo esc_attr( mo_sl( 'Licensing Plan' ) ); ?></a>
											&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<a id="doc_tab" class="mo_sl_header_text mo_sl_effect-shine mo_sl_expand" style="color: white; text-decoration: none; "<?php echo $active_tab == 'doc_tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'doc_tab' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Documentation' ) ); ?></a>
											&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b><a id="rest_api_page" class="mo_sl_header_text mo_sl_effect-shining mo_sl_expand" target="_blank" style="color: white; text-decoration: none; "<?php echo $active_tab == 'rest_api_page' ? 'nav-tab-active' : ''; ?>" href="https://plugins.miniorange.com/wordpress-rest-api-authentication"><?php echo esc_attr( mo_sl( 'Rest API' ) ); ?></a></b>
											&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<a id="reports" class="mo_sl_header_text mo_sl_effect-shine mo_sl_expand" style="color: white; text-decoration: none; "<?php echo $active_tab == 'reports' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'reports' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Reports' ) ); ?></a>
											&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<a id="mo_openid_rateus_modal" class="mo_sl_header_text mo_sl_effect-shine mo_sl_expand" onclick="asdf(this)" style="color: white; text-decoration: none; " ><?php echo esc_attr( mo_sl( 'Rate us' ) ); ?></a>
											&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<a id="sale" class="mo_sl_header_text mo_button_black " style="color: black; text-decoration: none; " href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" ><?php echo esc_attr( mo_sl( 'Sale' ) ); ?></a>
<!--                                        &nbsp;&nbsp;&nbsp;<a id="mo_openid_restart_gtour" class="header_text" style="color: white; text-decoration: none; " onclick="window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps';restart_tour()" value="Restart Tour">--><?php // echo mo_sl('Restart Tour'); ?><!--</a>-->
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div id="mo_openid_msgs"></div>
				<table style="width:100%;">
					<?php
	}
	?>
						<tr>
							<td style="vertical-align:top;">
								<?php
								switch ( $active_tab ) {
									case 'config_apps':
										mo_openid_show_apps();
										break;
									case 'customise_social_icons':
										mo_openid_customise_social_icons();
										break;
									case 'disp_opt':
										mo_openid_disp_opt();
										break;
									case 'redirect_opt':
										mo_openid_redirect_opt();
										break;
									case 'registration':
										mo_openid_registration();
										break;
									case 'link_social_acc':
										mo_openid_linkSocialAcc();
										break;
									case 'profile_completion':
										mo_openid_profile_completion();
										break;
									case 'email_settings':
										mo_openid_email_notification();
										break;
									case 'recaptcha':
										mo_openid_configure_recaptcha();
										break;
									case 'premium_features':
										mo_openid_premium_features();
										break;
									case 'rest_api_page':
										mo_openid_rest_api_page();
										break;
									case 'integration':
									case 'integration-woocommerce':
									case 'integration-buddypress':
									case 'integration-mailchimp':
									case 'integration-customregistration':
									case 'integration-hubspot':
									case 'integration-discord':
									case 'integration-memberpress':
									case 'integration-paidmemb':
										mo_openid_integrations();
										break;
									case 'shortcodes':
										mo_openid_login_shortcodes();
										break;
									case 'add_on':
										header( 'Location: ' . site_url() . '/wp-admin/admin.php?page=mo_openid_settings_addOn' );
										break;
									case 'profile':
										mo_openid_profile();
										break;
									case 'reports':
										mo_openid_reports();
										break;
									case 'doc_tab':
										mo_openid_doc_tab();
										break;
									case 'licensing_plans':
										mo_openid_licensing_plans();
										break;
								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript" src= "<?php echo esc_url( plugins_url( '/includes/js/mo_openid_phone.js', __FILE__ ) ); ?>"></script>
		<input type="button" id="mymo_btn" style="display: none" class="mo_support-help-button" data-show="false" onclick="mo_openid_support_form('')" value="<?php echo esc_attr( mo_sl( 'NEED HELP' ) ); ?>">
	<div style="position: fixed; bottom: 97px; right: 166px;" >

		<ul id="mo-support-menu">
			<a class="mo-support-menu-button" href="#mo-support-menu" onclick="hide_txt()" title="Need Help ?"> <img style=" height: 72px; width: 72px;" src="<?php echo esc_url( plugins_url( 'includes/images/support.png"', __FILE__ ) ); ?>"> </a>
			<a class="mo-support-menu-button" href="#0" onclick="un_hide_txt()" ><img style=" height: 72px; width: 72px;" src="<?php echo esc_url( plugins_url( 'includes/images/support.png"', __FILE__ ) ); ?>"></a>

				<li class="mo-support-menu-item ">
					<div class="tttooltip" >
					<a href="https://wordpress.org/support/plugin/miniorange-login-openid/" target="_blank">
						<span > <i class="fab fa-wpforms" style="margin-left: 12px;margin-top: 12px;"></i></span>
					</a>
					<span class="tttooltiptext">Forum</span>
					</div>
				</li>
			<li class="mo-support-menu-item mo-setup-video">
				<div class="tttooltip" >
				<a href="https://youtu.be/ln17jan6t1Y" target="_blank">
					<span ><i class="fa fa-cogs" style="margin-left: 10px; margin-top: 12px;"></i></span>
				</a>
					<span class="tttooltiptext">Setup Video</span></div>
			</li>

			<li class="mo-support-menu-item">
				<div class="tttooltip" >
				<a href="#mo-support-menu" onclick="window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps';restart_tour()">
					<span ><i class="fa fa-history" style="margin-left: 12px; margin-top: 12px;"></i></span>
				</a>
				<span class="tttooltiptext">Restart Tour</span></div>
			</li>
			<li class="mo-support-menu-item">
				<div class="tttooltip" >
				<a href="#mo-support-menu" onclick="mo_openid_support_form('')">
					<span ><i class="fa fa-user" style="margin-left: 14px; margin-top: 12px;"></i></span>
				</a>
					<span class="tttooltiptext">Mail Us</span></div>
			</li>
		</ul>
	</div>
	<button type="button" id="need-help-txt" onclick="hide_txt()" class="mo-sl-help-button-text" style="">Hello there!<br>Need Help? Drop us an Email</button>
	<?php include 'view/support_form/miniorange_openid_support_form.php'; ?>
	<script>

		jQuery("#contact_us_phone").intlTelInput();
			function hide_txt(){

				var mod = document.getElementById("need-help-txt");
				mod.style.display = "none";
			}
		function un_hide_txt(){

			var mod = document.getElementById("need-help-txt");
			mod.style.display = "block";
		}
			function mo_openid_support_form(abc) {

				var def = "It seems that you have shown interest. Please elaborate more on your requirements.";
				if (abc == '' || abc == "undefined")
					def = "Write your query here";

				jQuery("#contact_us_phone").intlTelInput();
				var modal = document.getElementById("myModal");
				modal.style.display = "block";
				var mo_btn = document.getElementById("mymo_btn");
				mo_btn.style.display = "none";
				var span = document.getElementsByClassName("mo_support_close")[0];
				var modales = document.getElementById("myModalsss");

				document.getElementById('mo_openid_support_msg').placeholder = def;
				document.getElementById("feature_plan").value= abc;
				span.onclick = function () {
					modal.style.display = "none";
					mo_btn.style.display = "none";
				}
				window.onclick = function (event) {
					if (event.target == modal) {
						modal.style.display = "none";
						modales.style.display = "none";
						mo_btn.style.display = "none";
					}
				}
			}

		function wordpress_support() {
			window.open("https://wordpress.org/support/plugin/miniorange-login-openid","_blank");
		}

		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;

		}
	</script>
	<script>
		function five_star() {
			jQuery("#mo_openid_rateus_myModal").hide();
			jQuery("#mo_openid_rating-5").prop('checked',false);

		}
		function mo_openid_extension_switch(){
			var extension_url = window.location.href;
			var cookie_names = "extension_current_url";
			var d = new Date();
			d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
			var expired = "expires="+d.toUTCString();
			document.cookie = cookie_names + "=" + extension_url + ";" + expired + ";path=/";
			<?php update_option( 'mo_openid_extension_tab', '0' ); ?>
		}
		function form_popup(rating){
			var mo_openid_rating_given_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-rating-given-nonce' ) ); ?>';
			jQuery.ajax({
				url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", //the page containing php script
				method: "POST", //request type,
				dataType: 'json',
				data: {
					action: 'mo_openid_rating_given',
					rating: rating,
					'mo_openid_rating_given_nonce' : mo_openid_rating_given_nonce,
				},
				success: function (result) {
					jQuery("#mo_openid_support_form_feedback").show();
					jQuery("#moOpenIdRateUs").hide();
				}
			});
		}
		// Get the modal
		var modal = document.getElementById("mo_openid_rateus_myModal");

		// Get the button that opens the modal
		var mo_btn = document.getElementById("mo_openid_rateus_modal");

		// Get the <span> element that closes the modal
		var mo_openid_span = document.getElementsByClassName("mo_openid_rateus_close")[0];
		var mo_openid_span1 = document.getElementsByClassName("mo_openid_rateus_feedback_close")[0];


		// When the user clicks the button, open the modal

		mo_btn.onclick = function() {
			jQuery("#mo_openid_support_form_feedback").hide();
			jQuery("#mo_openid_rating-4").prop('checked',false);
			jQuery("#mo_openid_rating-3").prop('checked',false);
			jQuery("#mo_openid_rating-2").prop('checked',false);
			jQuery("#mo_openid_rating-1").prop('checked',false);
			jQuery("#mo_openid_rating-0").prop('checked',false);
			modal.style.display ="block";
			jQuery("#moOpenIdRateUs").show();
		}

		// When the user clicks on <span> (x), close the modal
		mo_openid_span.onclick = function() {
			modal.style.display = "none";
		}
		mo_openid_span1.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event){
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

		var base_url = '<?php echo esc_url( site_url() ); ?>';

		var new_tour1 = new Tour({
			name: "new_tour1",
			steps: [
				{
					element: "#mo_openid_free_avail_apps",
					title: "Available apps",
					content: "Available social login apps.",
					backdrop:'body',
					backdropPadding:'6',
				},
				{
					element: "#google",
					title: "Enable social login apps",
					content: "Configure your own custom apps for social login applications by clicking on any of the application box.",
					backdrop:'body',
					backdropPadding:'6',
					onNext: function() {
						getappsInLine('google');
					}
				},
				{
					element: "#mo_set_pre_config_app",
					title: "Enable pre-configure app",
					content: "If you don't want to set up your own app then enable pre configured app from here.",
					backdrop:'body',
					backdropPadding:'6',
				},
				{
					element: "#mo_openid_cust_app_instructions",
					title: "Configure your app",
					content: "If you want to set up your own app then follow these instrutions.",
					backdrop:'body',
					backdropPadding:'6',
				},

				{
					element: ".mo-openid-app-name",
					title: "Set up custom app",
					content: "Enter your App ID and Secret here.",
					backdrop:'body',
					backdropPadding:'6',
					onNext: function() {
						jQuery('#custom_app_div').hide();
						jQuery(".mo-openid-sort-apps-move").css("background", "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAiCAYAAACuoaIwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA+tpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTExIDc5LjE1ODMyNSwgMjAxNS8wOS8xMC0wMToxMDoyMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDE4LTAxLTE3VDE1OjE4OjM5KzAxOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOC0wMS0xN1QxNjoxMTo0MSswMTowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxOC0wMS0xN1QxNjoxMTo0MSswMTowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QjhCNkM3MkZGQjk4MTFFN0E4RDJBRUZBQTI4OUVBNzIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QjhCNkM3MzBGQjk4MTFFN0E4RDJBRUZBQTI4OUVBNzIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpCOEI2QzcyREZCOTgxMUU3QThEMkFFRkFBMjg5RUE3MiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpCOEI2QzcyRUZCOTgxMUU3QThEMkFFRkFBMjg5RUE3MiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Po8u91EAAABlSURBVHjaYvz//z8DvQATAx3BqGWjlg0ey1iIUKMDxHFQ9iIgvkKkHFk+AxnGD8VxJMhRHIyMZMoRbRkoeD4B8QcgXkiCHKZrRsvG0aQ/mvRHk/5o0h9N+qNJf9SyUcuoCAACDABr5TA7L7qpSQAAAABJRU5ErkJggg==')");
					}
				},
				{
					element: "#mo_openid_move_google",
					title: "Change positions of login icons",
					content: "You can change positions of social login icons by holding and dragging these 4 dots.",
					backdrop:'body',
					backdropPadding:'6',
					onNext: function(){
						window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=disp_opt';
					}
				},
				{
					element: "#mo_openid_disp_opt_tour",
					title: "Display options.",
					content: "Select options where you want to display social login icons.",
					backdrop:'body',
					delay: 300,
					backdropPadding:'6',
					onNext: function() {
						window.location = base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=licensing_plans'
					}
				},{
					element: "#pricing",
					title: "Licensing Plans",
					content: "Check out more features to choose the best plan for you.",
					backdrop:'body',
					delay:300,
					backdropPadding:'6',
				}
			],
			template: function () {
				var return_value;
				if(new_tour1.getCurrentStep()===7)
					return_value="<div style='margin-top:2%;font-size: unset !important' class=\"mo_openid_popover\" role=\"tooltip\"> <div class=\"mo_openid_arrow\" ></div> <h3 class=\"mo_openid_popover-header\" style='margin-top: 0px'></h3> <div class=\"mo_openid_popover-body\"></div> <div class=\"mo_openid_popover-navigation\"> <div class=\"mo_openid_tour_mo_btn-group\"><div style=\"width:47%;margin-top: -7%;\"><h4  style=\"float:left;margin-top:30%;margin-left:30%;\">"+ (new_tour1.getCurrentStep()+1)+"/8</h4></div></div>&nbsp;&nbsp; <button class=\"button button-primary button-large\" data-role=\"end\" onclick=\"end_new_tour1();\">Done</button> </div> </div>";
				else {
					if(new_tour1.getCurrentStep()===6){
						return_value = "" +
							"<div style='width:13% !important;font-size: unset !important' class=\"mo_openid_popover\" role=\"tooltip\"> " +
							"<div class=\"mo_openid_arrow\" ></div> " +
							"<h3 class=\"mo_openid_popover-header\" style=\"font-size: 1rem;\"></h3> " +
							"<div class=\"mo_openid_popover-body\"></div> " +
							"<div class=\"mo_openid_popover-navigation\"> " +
							"<div class=\"mo_openid_tour_mo_btn-group\" style=\"width: 100%;\"> " +
							"<button class=\"mo_openid_tour_mo_btn mo_openid_tour_mo_btn-sm mo_openid_tour_mo_btn-secondary mo_openid_tour_mo_btn_next-success\" style=\"width: 54%;height: 0%;font-size: 1rem;\" data-role=\"next\">Next &raquo;</button>&nbsp;&nbsp;" +
							"<button class=\"mo_openid_tour_mo_btn mo_openid_tour_mo_btn-sm mo_openid_tour_mo_btn-secondary mo_openid_tour_mo_btn_next-success\" style=\"width: 54%;height: 0%;font-size: 1rem;\" data-role=\"end\" onclick=\"end_new_tour1();\">Skip</button>" +
							"<div style=\"width:47%;margin-top: -7%;\">" +
							"<h4  style=\"float:right;margin-left: 53%;\">" + (new_tour1.getCurrentStep() + 1) + "/8</h4>" +
							"</div>" +
							"</div>" +
							"</div>" +
							"</div>";
					}
					else {
						return_value = "" +
							"<div style='font-size: unset !important' class=\"mo_openid_popover\" role=\"tooltip\"> " +
							"<div class=\"mo_openid_arrow\" ></div> " +
							"<h3 class=\"mo_openid_popover-header\" style=\"margin-top:0px;\"></h3> " +
							"<div class=\"mo_openid_popover-body\"></div> " +
							"<div class=\"mo_openid_popover-navigation\"> " +
							"<div class=\"mo_openid_tour_mo_btn-group\" style=\"width: 100%;\"> " +
							"<button class=\"mo_openid_tour_mo_btn mo_openid_tour_mo_btn-sm mo_openid_tour_mo_btn-secondary mo_openid_tour_mo_btn_next-success\" style=\"width: 54%;height: 0%;font-size: 1rem;\" data-role=\"next\">Next &raquo;</button>&nbsp;&nbsp;" +
							"<button class=\"mo_openid_tour_mo_btn mo_openid_tour_mo_btn-sm mo_openid_tour_mo_btn-secondary mo_openid_tour_mo_btn_next-success\" style=\"width: 54%;height: 0%;font-size: 1rem;\" data-role=\"end\" onclick=\"end_new_tour1();\">Skip</button>" +
							"<div style=\"width:47%;margin-top: -7%;\">" +
							"<h4  style=\"float:right;margin-left: 53%;\">" + (new_tour1.getCurrentStep() + 1) + "/8</h4>" +
							"</div>" +
							"</div>" +
							"</div>" +
							"</div>";
					}
				}
				return (return_value);
			}
		});

		var temp = "<?php echo esc_attr( get_option( 'mo_openid_tour_new' ) ); ?>";
		temp =0;
		if(temp=="0") { // Initialize the tour
			new_tour1.init();
			// Start the tour
			new_tour1.start();
		}
		function restart_tour() {
			window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps';
			new_tour1.restart();
		}

		function end_new_tour2(){
			var mo_openid_tour_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-tour-nonce' ) ); ?>';
			var tour_variable = "plugin_tour";
			jQuery.ajax({
				url:base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps', //the page containing php script

				method: "POST", //request type,
				data: {
					update_tour_status: tour_variable,
					'mo_openid_tour_nonce': mo_openid_tour_nonce,
				},
				dataType: 'text',
				success:function(result){
					window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps';
				}
			});
		}
		function end_new_tour1() {
			window.location= base_url+'/wp-admin/admin.php?page=mo_openid_general_settings&tab=config_apps';
		}



		var new_tour2 = new Tour({
			name: "new_tour2",
			steps: [
				{
					element: "#mo_support_form",
					title: "miniOrange Support",
					content: "Feel free to reach out to us in case of any assistance.",
					backdrop:'body',
					//delay:200,
					backdropPadding:'6',
					onshow: function() {
						mo_openid_support_form('');
					}
				},
			],
			template: function () {
				return ("<div class=\"mo_openid_popover\" role=\"tooltip\"> <div class=\"mo_openid_arrow\" ></div> <h3 class=\"mo_openid_popover-header\" style=\"margin-top:0px;\"></h3> <div class=\"mo_openid_popover-body\"></div> <div class=\"mo_openid_popover-navigation\"> <div class=\"mo_openid_tour_mo_btn-group\" style=\"width: 100%;\"><button class=\"button button-primary button-large\" data-role=\"end\"onclick='end_new_tour2();'>OK Got It</button></div></div></div>");
			}
		});
		var temp = "<?php echo esc_attr( get_option( 'mo_openid_tour_new' ) ); ?>";
		if(temp=="0"&&new_tour1.ended()) {
			new_tour2.init();
			if(!new_tour2.ended()) {
				mo_openid_support_form('');
			}
			new_tour2.start();
		}


	</script>
	<?php

}

function mo_register_sharing_openid() {
	if ( isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) !== 'register' ) { // phpcs:ignore WordPress.Security.NonceVerification
		$active_tab = sanitize_text_field( $_GET['tab'] ); // phpcs:ignore WordPress.Security.NonceVerification
	} else {
		$active_tab = 'soc_apps';
	}

	if ( $active_tab == 'reports' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<div style="text-align:center; margin-top: 3%;"><h1>Login Reports</h1><h4>This feature is only available in the All-Inclusive Plan</h4></div>

		<?php
	} elseif ( $active_tab == 'doc_tab' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left;margin-left: 40px;" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<?php
	} elseif ( $active_tab == 'licensing_plans' ) {
		?>
		<div style="text-align:center;"><h1>Social Login Plugin</h1></div>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<div style="text-align:center; color: rgb(233, 125, 104); margin-top: 55px; font-size: 23px"> You are currently on the Free version of the plugin <br> <br><span style="font-size: 20px; ">
						<li style="color: dimgray; margin-top: 0px;list-style-type: none;">
							<div class="mo_openid-quote">
								<p>
									<span onclick="void(0);" class="mo_openid-check-tooltip" style="font-size: 15px">Why should I upgrade?
										<span class="mo_openid-info">
											<span class="mo_openid-pronounce">Why should I upgrade to premium plugin?</span>
											<span class="mo_openid-text">Upgrading lets you access all of our features such as Integration, User account moderation etc.</span>
										</span>
									</span>
								</p>
							</div>
							<br><br>
						</li>


		</div>


		<?php
	} else {

		?>

	<div>
		<table>
			<tr>
				<td><img id="logo" style="margin-top: 25px"
						 src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>includes/images/logo.png"></td>
				<td>&nbsp;<a style="text-decoration:none" href="https://plugins.miniorange.com/"
							 target="_blank"><h1 style="color: #c9302c"><?php echo esc_attr( mo_sl( 'miniOrange Social Login' ) ); ?></h1></a></td>
				<td> <a id="forum" style="margin-top: 23px" class="button" <?php echo $active_tab == 'forum' ? 'nav-tab-active' : ''; ?>" href="https://wordpress.org/support/plugin/miniorange-login-openid/" target="_blank"><?php echo esc_attr( mo_sl( 'Forum' ) ); ?></a></td>
				<td> <a id="addon" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button" <?php echo $active_tab == 'add_on' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Add On' ) ); ?></a></td>
				<td> <a id="doc_tab" style="margin-top: 23px;background: #0867B2;border-color: #0867B2;color: white;" class="button"<?php echo $active_tab == 'doc_tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'doc_tab' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Documentation' ) ); ?></a></td>
				<td> <a id="reports" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'reports' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'reports' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Reports' ) ); ?></a></td>
				<td> <a id="pricing" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'licensing_plans' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" onclick="mo_openid_extension_switch()"><?php echo esc_attr( mo_sl( 'Upgrade Now' ) ); ?></a></td>
				<td>
			</tr>
		</table>
	</div>
	<div style="width: 100%">

		<div style="width: 15%; float: left; background-color: #32373C; border-radius: 15px 0px 0px 15px;">
			<div style="height:54px;margin-top: 9px;border-bottom: 0px;text-align:center;">
				<div><img style="float:left;margin-left:8px;width: 43px;height: 45px;padding-top: 5px;"
						  src="<?php echo esc_url( plugins_url( 'includes/images/logo.png"', __FILE__ ) ); ?>"></div>
				<br>
				<span style="font-size:20px;color:white;float:left;"><?php echo esc_attr( mo_sl( 'miniOrange' ) ); ?></span>
			</div>

			<div class="mo_openid_tab" style="min-height:395px;width:100%; height: 445px;border-radius: 0px 0px 0px 15px;">
				<a id="social_apps" class="tablinks
				<?php
				if ( $active_tab == 'soc_apps' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'soc_apps' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">
									<?php
									echo esc_attr(
										mo_sl(
											'Select
                    Social Apps'
										)
									);
									?>
							</a>
				<a id="customization"
				   class="tablinks
				   <?php
					if ( $active_tab == 'customization' ) {
						echo '_active';}
					?>
					"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'customization' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Customization' ) ); ?>
				</a>
				<a id="share_cnt" class="tablinks
				<?php
				if ( $active_tab == 'share_cnt' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'share_cnt' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">
									<?php
									echo esc_attr(
										mo_sl(
											'Social
                    Share Counts'
										)
									);
									?>
							</a>

				<a id="display_opt" class="tablinks
				<?php
				if ( $active_tab == 'display_opt' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'display_opt' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">
									<?php
									echo esc_attr(
										mo_sl(
											'Display
                    Option'
										)
									);
									?>
							</a>
				<a id="short_code" class="tablinks
				<?php
				if ( $active_tab == 'short_code' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'short_code' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Shortcodes' ) ); ?></a>

				<a id="social_media_services" class="tablinks
				<?php
				if ( $active_tab == 'social_media_services' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'social_media_services' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Social Media Services' ) ); ?><span style="margin-left: 1%" class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>

				<a id="social_icons_customization" class="tablinks
				<?php
				if ( $active_tab == 'social_icons_customization' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'social_icons_customization' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Social Icons Customization' ) ); ?><span style="margin-left: 2%" class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>

				<a id="mo_twitter_mo_btn" class="tablinks
				<?php
				if ( $active_tab == 'mo_twitter_mo_btn' ) {
					echo '_active';}
				?>
				"
				   href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_twitter_mo_btn' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Twitter Follow Button' ) ); ?><span style="margin-left: 1%" class="mo-openid-premium"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></span></a>


			</div>
		</div>

		<div id="mo_openid_settings" style="width: 85%; float: right;">
			<div style="background-color: #FFFFFF;width: 90%;border-radius: 0px 15px 15px 0px;">
				<div class="mo_container">
					<h3 id="mo_openid_page_heading" class="mo_openid_highlight" style="color: white;margin: 0;padding: 23px;border-radius: 0px 15px 0px 0px;">&nbsp</h3>
					<div id="mo_openid_msgs"></div>
					<table style="width:100%;">
						<?php
	}
	?>
						<tr>
							<td style="vertical-align:top;">
								<?php
								switch ( $active_tab ) {
									case 'soc_apps':
										mo_openid_share_apps();
										break;
									case 'share_cnt':
										mo_openid_share_cnt();
										break;
									case 'customization':
										mo_openid_customize_icons();
										break;
									case 'display_opt':
										mo_openid_display_share_opt();
										break;
									case 'short_code':
										mo_openid_short_code();
										break;
									case 'licensing_plans':
										mo_openid_licensing_plans();
										break;
									case 'add_on':
										header( 'Location: ' . site_url() . '/wp-admin/admin.php?page=mo_openid_settings_addOn' );
										break;
									case 'social_media_services':
										mo_openid_social_media_services();
										break;
									case 'social_icons_customization':
										mo_openid_social_icons_customization();
										break;
									case 'mo_twitter_mo_btn':
										mo_twitter_mo_btn();
										break;
									case 'reports':
										mo_openid_reports();
										break;
									case 'doc_tab':
										mo_openid_doc_tab();
										break;
								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="<?php echo esc_url( plugins_url( '/includes/js/mo_openid_phone.js', __FILE__ ) ); ?>"></script>

		<input type="button" id="mymo_btn" style="display: none" class="mo_support-help-button" data-show="false" onclick="mo_openid_support_form('')" value="<?php echo esc_attr( mo_sl( 'NEED HELP' ) ); ?>">

	</div>
	<?php include 'view/support_form/miniorange_openid_support_form.php'; ?>
	<script>
		function mo_openid_extension_switch(){
			var extension_url = window.location.href;
			var cookie_names = "extension_current_url";
			var d = new Date();
			d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
			var expired = "expires="+d.toUTCString();
			document.cookie = cookie_names + "=" + extension_url + ";" + expired + ";path=/";
			<?php update_option( 'mo_openid_extension_tab', '0' ); ?>
		}
		jQuery("#contact_us_phone").intlTelInput();
		function mo_openid_support_form(abc) {
			jQuery("#contact_us_phone").intlTelInput();
			var modal = document.getElementById("myModal");
			modal.style.display = "block";
			var mo_btn=document.getElementById("mymo_btn");
			mo_btn.style.display="none";
			var span = document.getElementsByClassName("mo_support_close")[0];
			span.onclick = function () {
				modal.style.display = "none";
				mo_btn.style.display="block";
			}
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
					mo_btn.style.display="block";
				}
			}
		}
		function wordpress_support() {
			window.open("https://wordpress.org/support/plugin/miniorange-login-openid","_blank");

		}

		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;

		}
	</script>
	<?php
}

function mo_comment_openid() {
	if ( isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) !== 'register' ) { // phpcs:ignore WordPress.Security.NonceVerification
		$active_tab = sanitize_text_field( $_GET['tab'] ); // phpcs:ignore WordPress.Security.NonceVerification
	} else {
		$active_tab = 'select_applications';
	}

	if ( $active_tab == 'reports' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<div style="text-align:center; margin-top: 3%;"><h1>Login Reports</h1><h4>This feature is only available in the All-Inclusive Plan</h4></div>

		<?php
	} elseif ( $active_tab == 'doc_tab' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left;margin-left: 40px;" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<?php
	} elseif ( $active_tab == 'licensing_plans' ) {
		?>
		<div style="text-align:center;"><h1>Social Login Plugin</h1></div>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<div style="text-align:center; color: rgb(233, 125, 104); margin-top: 55px; font-size: 23px"> You are currently on the Free version of the plugin <br> <br><span style="font-size: 20px; ">
						<li style="color: dimgray; margin-top: 0px;list-style-type: none;">
							<div class="mo_openid-quote">
								<p>
									<span onclick="void(0);" class="mo_openid-check-tooltip" style="font-size: 15px">Why should I upgrade?
										<span class="mo_openid-info">
											<span class="mo_openid-pronounce">Why should I upgrade to premium plugin?</span>
											<span class="mo_openid-text">Upgrading lets you access all of our features such as Integration, User account moderation etc.</span>
										</span>
									</span>
								</p>
							</div>
							<br><br>
						</li>
		</div>

		<?php
	} else {
		?>
	<div>

			<table>
				<tr>
					<td><img id="logo" style="margin-top: 25px"
							 src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>includes/images/logo.png"></td>
					<td>&nbsp;<a style="text-decoration:none" href="https://plugins.miniorange.com/"
								 target="_blank"><h1 style="color: #c9302c"><?php echo esc_attr( mo_sl( 'miniOrange Social Login' ) ); ?></h1></a></td>
					<td> <a id="forum" style="margin-top: 23px" class="button" <?php echo $active_tab == 'forum' ? 'nav-tab-active' : ''; ?>" href="https://wordpress.org/support/plugin/miniorange-login-openid/" target="_blank"><?php echo esc_attr( mo_sl( 'Forum' ) ); ?></a></td>
					<td> <a id="addon" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button" <?php echo $active_tab == 'add_on' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Add On' ) ); ?></a></td>
					<td> <a id="doc_tab" style="margin-top: 23px;background: #0867B2;border-color: #0867B2;color: white;" class="button"<?php echo $active_tab == 'doc_tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'doc_tab' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Documentation' ) ); ?></a></td>
					<td> <a id="reports" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'reports' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'reports' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Reports' ) ); ?></a></td>
					<td> <a id="pricing" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'licensing_plans' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" onclick="mo_openid_extension_switch()"><?php echo esc_attr( mo_sl( 'Upgrade Now' ) ); ?></a></td>
					<td>
				</tr>
			</table>

	</div>
	<div style="width: 100%">

		<div style="width: 15%; float: left; background-color: #32373C; border-radius: 15px 0px 0px 15px;">
			<div style="height:54px;margin-top: 9px;border-bottom: 0px;text-align:center;">
				<div><img style="float:left;margin-left:8px;width: 43px;height: 45px;padding-top: 5px;" src="<?php echo esc_url( plugins_url( 'includes/images/logo.png"', __FILE__ ) ); ?>"></div>
				<br>
				<span style="font-size:20px;color:white;float:left;"><?php echo esc_attr( mo_sl( 'miniOrange' ) ); ?></span>
			</div>
			<div class="mo_openid_tab" style="min-height:395px;width:100%; height: 445px;border-radius: 0px 0px 0px 15px;">
				<a id="select_applications" class="tablinks
				<?php
				if ( $active_tab == 'select_applications' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'select_applications' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Select Applications' ) ); ?></a>
				<a id="display_options" class="tablinks
				<?php
				if ( $active_tab == 'display_options' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'display_options' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Display options' ) ); ?></a>
				<a id="customize_text" class="tablinks
				<?php
				if ( $active_tab == 'customize_text' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'customize_text' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Customization' ) ); ?></a>
				<a id="enable_comment" class="tablinks
				<?php
				if ( $active_tab == 'enable_comment' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'enable_comment' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Enable and Add Social Comments' ) ); ?></a>
				<a id="comment_shortcode" class="tablinks
				<?php
				if ( $active_tab == 'comment_shortcode' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'comment_shortcode' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Shortcodes' ) ); ?></a>



			</div>
		</div>

		<div id="mo_openid_settings" style="width: 85%; float: right;">
			<div style="background-color: #FFFFFF;width: 90%;border-radius: 0px 15px 15px 0px;">
				<div class="mo_container">
					<h3 id="mo_openid_page_heading" class="mo_openid_highlight" style="color: white;margin: 0;padding: 23px;border-radius: 0px 15px 0px 0px;">&nbsp</h3>
					<div id="mo_openid_msgs"></div>
					<table style="width:100%;">
						<?php
	}
	?>
						<tr>
							<td style="vertical-align:top;">
								<?php
								switch ( $active_tab ) {
									case 'select_applications':
										select_comment_app();
										break;
									case 'display_options':
										select_comment_disp();
										break;
									case 'customize_text':
										select_comment_customize();
										break;
									case 'enable_comment':
										select_comment_enable();
										break;
									case 'comment_shortcode':
										select_comment_shortcode();
										break;
									case 'licensing_plans':
										mo_openid_licensing_plans();
										break;
									case 'reports':
										mo_openid_reports();
										break;
									case 'add_on':
										header( 'Location: ' . site_url() . '/wp-admin/admin.php?page=mo_openid_settings_addOn' );
										break;
									case 'doc_tab':
										mo_openid_doc_tab();
										break;
								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>


		</div></div>
	<script type="text/javascript" src= "<?php echo esc_url( plugins_url( '/includes/js/mo_openid_phone.js', __FILE__ ) ); ?>"></script>

	<input type="button" id="mymo_btn" style="display: none" class="mo_support-help-button" data-show="false" onclick="mo_openid_support_form('')" value="<?php echo esc_attr( mo_sl( 'NEED HELP' ) ); ?>">

	<?php include 'view/support_form/miniorange_openid_support_form.php'; ?>
	<script>
		function moSharingSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
		}
		function moSharingSpaceValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
		}
		function moLoginSizeValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
		}
		function moLoginSpaceValidate(e){
			var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
		}
		function moLoginWidthValidate(e){
			var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
		}
		function moLoginHeightValidate(e){
			var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
		}
		jQuery(document).ready(function(){
			var sel = jQuery(".mo_support_input_container");
			sel.each(function(){
				if(jQuery(this).find(".mo_support_input").val() !== "")
					jQuery(this).addClass("mo_has_value");
			});
			sel.focusout( function(){
				if(jQuery(this).find(".mo_support_input").val() !== "")
					jQuery(this).addClass("mo_has_value");
				else jQuery(this).removeClass("mo_has_value");
			});
		});
	</script>
	<script>
		function mo_openid_extension_switch(){
			var extension_url = window.location.href;
			var cookie_names = "extension_current_url";
			var d = new Date();
			d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
			var expired = "expires="+d.toUTCString();
			document.cookie = cookie_names + "=" + extension_url + ";" + expired + ";path=/";
			<?php update_option( 'mo_openid_extension_tab', '0' ); ?>
		}
		jQuery(document).ready(function ()
		{
			jQuery("#bkgOverlay").delay(4800).fadeIn(400);
			jQuery("#mo_openid_rateus_myModal").delay(5000).fadeIn(400);

			jQuery("#mo_btnClose").click(function (e)
			{
				HideDialog();
				e.preventDefault();
			});
		});
		//Controls how the modal popup is closed with the close button
		function HideDialog()
		{
			$("#bkgOverlay").fadeOut(400);
			$("#delayedPopup").fadeOut(300);
		}

	</script>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		function mo_openid_support_form(abc) {
			jQuery("#contact_us_phone").intlTelInput();
			var modal = document.getElementById("myModal");
			modal.style.display = "block";
			var mo_btn=document.getElementById("mymo_btn");
			mo_btn.style.display="none";
			var span = document.getElementsByClassName("mo_support_close")[0];
			span.onclick = function () {
				modal.style.display = "none";
				mo_btn.style.display="block";
			}
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
					mo_btn.style.display="block";
				}
			}
		}
		function wordpress_support() {
			window.open("https://wordpress.org/support/plugin/miniorange-login-openid","_blank");

		}

		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;

		}
	</script>

	<?php

}

function mo_openid_addon_desc_page() {

	if ( isset( $_GET['tab'] ) && sanitize_text_field( $_GET['tab'] ) !== 'register' ) { // phpcs:ignore WordPress.Security.NonceVerification
        $active_tab = sanitize_text_field($_GET[ 'tab' ]); // phpcs:ignore
	}     elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_settings_addOn') // phpcs:ignore
		$active_tab = 'custom_registration_form';
    elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_woocommerce_add_on') // phpcs:ignore
		$active_tab = 'mo_woocommerce_add_on';
    elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_mailchimp_add_on') // phpcs:ignore
		$active_tab = 'mo_mailchimp_add_on';
    elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_buddypress_add_on') // phpcs:ignore
		$active_tab = 'mo_buddypress_add_on';
    elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_hubspot_add_on') // phpcs:ignore
		$active_tab = 'mo_hubspot_add_on';
    elseif (isset($_REQUEST) && sanitize_text_field($_REQUEST['page']) == 'mo_openid_discord_add_on') // phpcs:ignore
		$active_tab = 'mo_discord_add_on';
	else {
		$active_tab = 'custom_registration_form';
	}

	if ( $active_tab == 'reports' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<div style="text-align:center; margin-top: 3%;"><h1>Login Reports</h1><h4>This feature is only available in the All-Inclusive Plan</h4></div>

		<?php
	} elseif ( $active_tab == 'doc_tab' ) {
		?>
		<br>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left;margin-left: 40px;" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<br>
		<?php
	} elseif ( $active_tab == 'licensing_plans' ) {
		?>
		<div style="text-align:center;"><h1>Social Login Plugin</h1></div>
		<div><a style="margin-top: 0px;background: #d0d0d0;border-color: #1162b5;color: #151515; float: left" class="button" onclick="window.location='<?php echo esc_url( site_url() ); ?>'+'/wp-admin/admin.php?page=mo_openid_general_settings'"><span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"></span><b style="font-size: 15px;"> &nbsp;&nbsp;Back To Plugin Configuration</b></a></div>
		<div style="text-align:center; color: rgb(233, 125, 104); margin-top: 55px; font-size: 23px"> You are currently on the Free version of the plugin <br> <br><span style="font-size: 20px; ">
							<li style="color: dimgray; margin-top: 0px;list-style-type: none;">
								<div class="mo_openid-quote">
									<p>
										<span onclick="void(0);" class="mo_openid-check-tooltip" style="font-size: 15px">Why should I upgrade?
											<span class="mo_openid-info">
												<span class="mo_openid-pronounce">Why should I upgrade to premium plugin?</span>
												<span class="mo_openid-text">Upgrading lets you access all of our features such as Integration, User account moderation etc.</span>
											</span>
										</span>
									</p>
								</div>
								<br><br>
							</li>
		</div>

		<?php
		mo_openid_licensing_plans();
	} else {

		?>
	 <div>
		<table>
			<tr>
				<td><img id="logo" style="margin-top: 25px" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>includes/images/logo.png"></td>
				<td>&nbsp;<a style="text-decoration:none" href="https://plugins.miniorange.com/" target="_blank"><h1 style="color: #c9302c">miniOrange Social Login &nbsp;</h1></a></td>
				<td> <a id="forum" style="margin-top: 23px" class="button" <?php echo $active_tab == 'forum' ? 'nav-tab-active' : ''; ?>" href="https://wordpress.org/support/plugin/miniorange-login-openid/" target="_blank">Forum</a></td>
				<td> <a id="doc_tab" style="margin-top: 23px;background: #0867B2;border-color: #0867B2;color: white;" class="button"<?php echo $active_tab == 'doc_tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'doc_tab' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Documentation' ) ); ?></a></td>
				<td> <a id="reports" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'reports' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'reports' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Reports' ) ); ?></a></td>
				<td> <a id="pricing" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'licensing_plans' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" onclick="mo_openid_extension_switch()">Licensing Plans</a></td>
				<td> <a id="mo_openid_go_back" style="margin-top: 23px;background: #FFA335;border-color: #FFA335;color: white;" class="button"<?php echo $active_tab == 'mo_openid_go_back' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_openid_go_back' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Social Login</a></td>
			</tr>
		</table>
	</div>
	<div style="width: 100%">

		<div style="width: 15%; float: left; background-color: #32373C; border-radius: 15px 0px 0px 15px;">
			<div style="height:54px;margin-top: 9px;border-bottom: 0px;text-align:center;">
				<div><img style="float:left;margin-left:8px;width: 43px;height: 45px;padding-top: 5px;" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>includes/images/logo.png"></div>
				<br>
				<span style="font-size:20px;color:white;float:left;">miniOrange</span>
			</div>
			<div class="mo_openid_tab" style="min-height:395px;width:100%; height: 445px;border-radius: 0px 0px 0px 15px;">
				<a id="custom_registration_form" class="tablinks
				<?php
				if ( $active_tab == 'custom_registration_form' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'custom_registration_form' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Custom Registration Form</a>
				<a id="mo_woocommerce_add_on" class="tablinks
				<?php
				if ( $active_tab == 'mo_woocommerce_add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_woocommerce_add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'WooCommerce Add on' ) ); ?></a>
				<a id="mo_buddypress_add_on" class="tablinks
				<?php
				if ( $active_tab == 'mo_buddypress_add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_buddypress_add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'BuddyPress Add on' ) ); ?></a>
				<a id="mo_mailchimp_add_on" class="tablinks
				<?php
				if ( $active_tab == 'mo_mailchimp_add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_mailchimp_add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'MailChimp Add on' ) ); ?></a>
				<a id="mo_hubspot_add_on" class="tablinks
				<?php
				if ( $active_tab == 'mo_hubspot_add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_hubspot_add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'HubSpot Add on' ) ); ?></a>
				<a id="mo_discord_add_on" class="tablinks
				<?php
				if ( $active_tab == 'mo_discord_add_on' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_discord_add_on' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Discord Add on' ) ); ?></a>
				<a id="mo_openid_go_back" class="tablinks
				<?php
				if ( $active_tab == 'mo_openid_go_back' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'mo_openid_go_back' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Go to Social Login</a>
				<a id="mo_openid_licensing" class="tablinks
				<?php
				if ( $active_tab == 'mo_openid_licensing_plans' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>" onclick="mo_openid_extension_switch()">Licensing Plans</a>
				<a id="profile_com" style="display: none;"> class="tablinks
				<?php
				if ( $active_tab == 'profile_com' ) {
					echo '_active';}
				?>
				" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'profile_com' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Profile</a>
			</div>
		</div>

		<div id="mo_openid_settings" style="width: 85%; float: right;">
			<div style="background-color: #FFFFFF;width: 90%;border-radius: 0px 15px 15px 0px;">
				<div class="mo_container">
					<h3 id="mo_openid_page_heading" class="mo_openid_highlight" style="color: white;margin: 0;padding: 23px;border-radius: 0px 15px 0px 0px;">&nbsp</h3>
					<div id="mo_openid_msgs"></div>
					<?php

	}
	?>

					<table style="width:100%;">
						<tr>
							<td style="vertical-align:top;">
								<?php
								switch ( $active_tab ) {
									case 'reports':
										mo_openid_reports();
										break;
									case 'doc_tab':
										mo_openid_doc_tab();
										break;
									case 'profile_com':
										mo_openid_profile();
										break;
									case 'custom_registration_form':
										if ( mo_openid_is_customer_registered() && mo_openid_is_customer_addon_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-extra-attributes-addon/miniorange_openid_sso_customization_addon.php' ) ) {
												do_action( 'customization_addon' );
											} else {
												$addon_message_type = 'Custom Registration Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_custom_registration_form();
										}
										break;
									case 'mo_woocommerce_add_on':
										if ( mo_openid_is_customer_registered() && mo_openid_is_wca_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-woocommerce-addon/miniorange_openid_woocommerce_integration_addon.php' ) ) {
												do_action( 'mo_wc_addon' );
											} else {
												$addon_message_type = 'WooCommerce Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_woocommerce_add_on();
										}
										break;
									case 'mo_mailchimp_add_on':
										if ( mo_openid_is_customer_registered() && mo_openid_is_mailc_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-mailchimp-addon/miniorange_openid_mailchimp_addon.php' ) ) {
												do_action( 'mo_mailchimp_addon' );
											} else {
												$addon_message_type = 'MailChimp Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_mailchimp_add_on();
										}
										break;
									case 'mo_buddypress_add_on':
										if ( mo_openid_is_customer_registered() && mo_openid_is_bpp_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-buddypress-addon/mo_openid_buddypress_display_addon.php' ) ) {
												do_action( 'buddypress_integration_addon' );
											} else {
												$addon_message_type = 'BuddyPress Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_buddypress_addon_display();
										}
										break;
									case 'mo_hubspot_add_on':
										if ( mo_openid_is_customer_registered() && mo_openid_is_hub_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-hubspot-addon/mo_openid_hubspot_integration.php' ) ) {
												do_action( 'hubspot_integration_addon' );
											} else {
												$addon_message_type = 'HubSpot Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_hubspot_add_on_display();
										}
										break;
									case 'mo_discord_add_on':
										if ( mo_openid_is_customer_registered() && mo_openid_is_dis_license_key_verified() ) {
											if ( is_plugin_active( 'miniorange-login-openid-discord-addon/mo_openid_discord_integration.php' ) ) {
												do_action( 'discord_integration_addon' );
											} else {
												$addon_message_type = 'Discord Addon';
												mo_openid_show_addon_message_page( $addon_message_type );
											}
										} else {
											mo_openid_discord_add_on_display();
										}
										break;
									case 'setup_video':
										break;
									case 'mo_openid_go_back':
										header( 'Location: ' . site_url() . '/wp-admin/admin.php?page=mo_openid_general_settings' );
										break;

								}
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
 <script type="text/javascript" src= "<?php echo esc_url( plugins_url( '/includes/js/mo_openid_phone.js', __FILE__ ) ); ?>"></script>
		<input type="button" id="mymo_btn" style="display: none" class="mo_support-help-button" data-show="false" onclick="mo_openid_support_form('')" value="<?php echo esc_attr( mo_sl( 'NEED HELP' ) ); ?>">
	</div>
	<?php include 'view/support_form/miniorange_openid_support_form.php'; ?>
	<script>
		jQuery("#contact_us_phone").intlTelInput();
		function mo_openid_support_form(abc) {
			jQuery("#contact_us_phone").intlTelInput();
			var modal = document.getElementById("myModal");
			modal.style.display = "block";
			var mo_btn=document.getElementById("mymo_btn");
			mo_btn.style.display="none";
			var span = document.getElementsByClassName("mo_support_close")[0];
			span.onclick = function () {
				modal.style.display = "none";
				mo_btn.style.display="block";
			}
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
					mo_btn.style.display="block";
				}
			}
		}
		function mo_openid_extension_switch(){
			var extension_url = window.location.href;
				var cookie_names = "extension_current_url";
				var d = new Date();
				d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
				var expired = "expires="+d.toUTCString();
				document.cookie = cookie_names + "=" + extension_url + ";" + expired + ";path=/";

			<?php update_option( 'mo_openid_extension_tab', '1' ); ?>
		}
		function wordpress_support() {
			window.open("https://wordpress.org/support/plugin/miniorange-login-openid","_blank");

		}

		function mo_openid_valid_query(f) {
			!(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(/[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;

		}
	</script>
	<?php
}
