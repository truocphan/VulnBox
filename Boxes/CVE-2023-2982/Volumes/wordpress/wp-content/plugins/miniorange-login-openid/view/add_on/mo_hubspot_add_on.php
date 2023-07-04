<?php

function mo_openid_hubspot_add_on_display() { ?>
	<div id="hub_adv_disp" style="display: block">
		<table>
			<tr>
				<td >
					<h3><?php echo esc_attr( mo_sl( 'Hubspot Add-on' ) ); ?>
						<input type="button" value="<?php echo esc_attr( mo_sl( 'Purchase' ) ); ?>"
							   onclick="mosocial_addonform('wp_social_login_hubspot_addon')"
							   id="mosocial_purchase_hub"
							   class="button button-primary button-large"
							   style="float: right; margin-left: 10px;">
						<input type="button" value="<?php echo esc_attr( mo_sl( 'Verify Key' ) ); ?>"
							   id="mosocial_purchase_hub_verify"
							   class="button button-primary button-large"
							   style="float: right;">
					</h3>
					<br>
					<b><?php echo esc_attr( mo_sl( 'Hubspot Addon provide hubspot integration where user contact will be automatically added to hubspot contact list on successful registration via social login applications. With Hubspot integration, you can monitor a use\'s activities on your website. User behavior involves the sites viewed by the user as well as the time spent on each page.' ) ); ?></b>
				</td>
			</tr>
		</table>
		<table class="mo_openid_display_table table" id="mo_openid_hub_video"></table>

	</div>
	<br>
	<div class="mo_openid_highlight">
		<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'Hubspot Integration' ) ); ?></h3>
	</div>
	<p style="font-weight: bold; font-size: 17px; display: inline-block; margin-left: 1.5%">Enter the Key</p>
	<input class="mo_openid_table_textbox" required type="text" style="margin-left:40px;width:300px;"
		   placeholder="Enter your App key" disabled />
	<br/>
	<p style="font-weight: bold; font-size: 17px; display: inline-block; margin-left: 1.5%">Enter the HubID</p>
	<input class="mo_openid_table_textbox" required type="text" style="margin-left:40px;width:300px;"
		   placeholder="Enter your HubID" disabled />
	<hr>
	<div class="mo_openid_table_layout">
		<table style="width: 100%;">
			<tr id="sel_apps">
				<td><h3 ><?php echo esc_attr( mo_sl( 'Select Social Apps' ) ); ?> </h3>
					<table style="width: 100%">
						<p style="font-size: 17px;"><?php echo esc_attr( mo_sl( 'Select Social Login Application for Hubspot Integration' ) ); ?></p>

						<tr>
							<td style="width:25%">
								<label class="mo_openid_checkbox_container"><b><?php echo esc_attr( mo_sl( 'Click Here To Enable All Apps' ) ); ?></b>
									<input type="checkbox" class="app_enable"/>
									<span class="mo_openid_checkbox_checkmark_disable"></span>
								</label>
							</td>
						</tr>
						<?php
						function check_enable_apps( $app_name ) {
							$all_apps_check = get_option( 'mo_hubspot_apps' );
							$all_apps_check = explode( '#', $all_apps_check );
							foreach ( $all_apps_check as $apps_enable ) {
								if ( $app_name == $apps_enable ) {
									return true;
								}
							}
							return false;
						}
						$all_apps = 'facebook#google#vkontakte#twitter#linkedin#amazon#paypal#salesforce#yahoo#apple#hubspot#wordpress#disqus#pinterest#yandex#spotify#twitch#vimeo#kakao#discord#dribbble#flickr#line#meetup#stackexchange#snapchat#reddit#odnoklassniki#foursquare#naver#teamsnap#livejournal#github#tumblr#wiebo#wechat#renren#baidu#mailru#qq';
						$all_apps = explode( '#', $all_apps );
						$count    = 0;
						foreach ( $all_apps as $apps ) {
							if ( $count == 0 ) {
								?>
								<tr>
								<?php
							}
							$count++;
							?>
							<td style="width:20%">
								<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( $apps ) ); ?>
									<input type="checkbox" class="app_enable"/>
									<span class="mo_openid_checkbox_checkmark_disable"></span>
								</label>
							</td>
							<?php
							if ( $count == 5 ) {
								$count = 0;
								?>
								</tr>
								<?php
							}
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<center>
			<input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" disabled />
		</center>
	</div>
	<td>
		<form style="display:none;" id="mosocial_loginform" action="<?php echo esc_attr( get_option( 'mo_openid_host_name' ) ) . '/moas/login'; ?>"
			  target="_blank" method="post" >
			<input type="email" name="username" value="<?php echo esc_attr( get_option( 'mo_openid_admin_email' ) ); ?>" />
			<input type="text" name="redirectUrl" value="<?php echo esc_attr( get_option( 'mo_openid_host_name' ) ) . '/moas/initializepayment'; ?>" />
			<input type="text" name="requestOrigin" id="requestOrigin"/>
		</form>
		<script>
			function mosocial_addonform(planType) {
				jQuery('#requestOrigin').val(planType);
				jQuery('#mosocial_loginform').submit();
			}
		</script>
	</td>
	<td>
		<script type="text/javascript">
			//to set heading name
			var mo_openid_customer_toggle_update_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-customer-toggle-update-nonce' ) ); ?>';
		   jQuery(document).ready(function($){
				jQuery("#mosocial_purchase_hub_verify").on("click",function(){
					jQuery.ajax({
						url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", //the page containing php script
						method: "POST", //request type,
						dataType: 'json',
						data: {
							action: 'mo_register_customer_toggle_update',
							'mo_openid_customer_toggle_update_nonce' : mo_openid_customer_toggle_update_nonce,
						},
						success: function (result){
							if (result.status){
								mo_verify_add_on_license_key();
							}
							else{
								alert("Please register/login with miniOrange to verify key and use the Custom Registration Form Add on");
								window.location.href="<?php echo esc_url( site_url() ); ?>".concat("/wp-admin/admin.php?page=mo_openid_general_settings&tab=profile");
							}
						}
					});
				});
			});

			function mo_verify_add_on_license_key() {
				var mo_openid_verify_addon_license_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-verify-addon-license-nonce' ) ); ?>';
				jQuery.ajax({
					type: 'POST',
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					data: {
						action:'verify_addon_licience',
						plan_name: 'WP_SOCIAL_LOGIN_HUBSPOT_ADDON',
						'mo_openid_verify_addon_license_nonce' : mo_openid_verify_addon_license_nonce,
					},
					crossDomain :!0, dataType:"html",
					success: function(data) {
						var flag=0;
						jQuery("input").each(function(){
							if(jQuery(this).val()=="mo_openid_verify_license") flag=1;
						});
						if(!flag) {
							jQuery(data).insertBefore("#mo_openid_hub_video");
							jQuery("#hub_adv_disp").find(jQuery("#cust_supp")).css("display", "none");
						}
					},
					error: function (data){}
				});
			}
		</script>
	</td>
	<?php
}
