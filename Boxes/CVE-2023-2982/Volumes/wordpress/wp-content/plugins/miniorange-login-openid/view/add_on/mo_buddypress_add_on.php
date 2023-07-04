<?php

function mo_openid_buddypress_addon_display() {  ?>
	<div id="bp_addon_head" style="display: block">
		<table>
			<tr>
				<td >
					<h3><?php echo esc_attr( mo_sl( 'Buddypress Add-on' ) ); ?>
						<input type="button" value="<?php echo esc_attr( mo_sl( 'Purchase' ) ); ?>"
							   onclick="mosocial_addonform('wp_social_login_buddypress_addon')"
							   id="mosocial_purchase_buddy_addon"
							   class="button button-primary button-large"
							   style="float: right; margin-left: 10px;">
						<input type="button" value="<?php echo esc_attr( mo_sl( 'Verify Key' ) ); ?>"
							   id="mosocial_purchase_buddypress_addon_verify"
							   class="button button-primary button-large"
							   style="float: right;">
					</h3>
					<b>
					<?php
					echo esc_attr(
						mo_sl(
							'With BuddyPress/BuddyBoss Integration you will get various social login display options using which you can display the Social Login icons on your BuddyPress registration pages.
The BuddyPress integration with social login can be done using any Social login app. If you have BuddyPress Integration enabled then user information will be mapped to their profile page.'
						)
					);
					?>
						</b>
				</td>
			</tr>
		</table>
		<table class="mo_openid_display_table table" id="mo_openid_buddypress_addon_video">
			<tr>
				<td colspan="2">
					<hr>
					<p>
						<br><center>
						<iframe width="450" height="250" src="https://www.youtube.com/embed/Iia1skKRYBU"
								frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
								style=""></iframe></center>
					</p>
				</td>
			</tr>
		</table>
	</div>
	<br><br>
	<div class="mo_openid_highlight">
		<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'BuddyPress / BuddyBoss Display Option' ) ); ?></h3>
	</div>

	<form id="wa_display" name="wa_display" method="post" action="">
			<div class="mo_openid_wca_table_layout_fake" style="height: 550px"><br/>
				<div style="width:40%; background:white; float:left; border: 1px transparent; margin-left: 25px">
					<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
						<input type="checkbox" id="bp_before_register_page" name="mo_openid_bp_before_register_page" <?php checked( get_option( 'mo_openid_bp_before_register_page' ) == 1 ); ?> />
						<div style="padding-left: 7%">
							<?php echo esc_attr( mo_sl( 'Before registration form' ) ); ?>
						</div>
						<span class="mo_openid_checkbox_checkmark_disable"></span>
						<div class="mo_openid_wca_box">
							<br>
							<img style="width: 100%; height: 100%" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/bp_add/new_before_reg.png'; ?>">
						</div>
					</label>
					<br>
					<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
						<div style="padding-left: 7%; width: 60% !important">
						<?php echo esc_attr( mo_sl( 'Before Account Details' ) ); ?>
						</div>
						<input type="checkbox"  /><br>
						<span class="mo_openid_checkbox_checkmark_disable"></span>
						<div class="mo_openid_wca_box">
							<img style="width: 100%; height: 100%" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/bp_add/new_before_account.png'; ?>">
						</div>
					</label>

				</div>
				<div style="width:50%; background:white; float:right; border: 1px transparent;">
					<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
						<div style="padding-left: 7%">
						<?php echo esc_attr( mo_sl( 'After Registration Form' ) ); ?>
						</div>
						<input type="checkbox"  /><br>
						<span class="mo_openid_checkbox_checkmark_disable"></span>
						<div class="mo_openid_wca_box">
							<img style="width: 79%; height: 100%" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/bp_add/new_after_reg.png'; ?>">
						</div>
					</label>
				</div>
			</div>
			<br>
			<input style="width: 126px;margin-left: 2%" disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?> "  class="button button-primary button-large" />
			<br>
		</form>
		<br><br>
		<div class="mo_openid_highlight">
		<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'BuddyPress Extended Mapping Attributes' ) ); ?></h3>
	</div>
		<form>
			<br>
			<div style="margin-left: 1.5%">
		<table>
			<tr>
				
					<h3><b>Buddypress / BuddyBoss Extended Attributes Mapping</b></h3>
				
			</tr>
			<tr>
				<td style="width: 60%">
					<font color="#FF0000">*</font>
					Select Application:
				</td>
				<td>
					<select class="mo_table_textbox" required="true" style="width:100%; height: 27px;" name="mo_oauth_bp_app_name" onchange="selectapp()" disabled="true" >
					<option value="" disabled selected hidden>Google</option>
				</td>
			</tr>
			<tr>
				<td style="width: 60%">
					<font color="#FF0000">*</font>
					Name:
				</td>
				<td>
					<select class="mo_table_textbox" required="true" style="width:100%; height: 27px; " name="mo_oauth_bp_app_name" onchange="selectapp()" disabled="true">
					<option value="" disabled selected hidden>User Name</option>
				</td>
			</tr>
			<tr>
				<td style="width: 60%">
					<font color="#FF0000">*</font>
					Email:
				</td>
				<td>
					<select class="mo_table_textbox" required="true" style="width:100%; height: 27px; " name="mo_oauth_bp_app_name" onchange="selectapp()" disabled="true">
					<option value="" disabled selected hidden>User Email_ID</option>
				</td>
			</tr>
			<tr>
				<td style="width: 60%">
					<font color="#FF0000">*</font>
					User First Name:
				</td>
				<td>
					<select class="mo_table_textbox" required="true" style="width:100%; height: 27px; " name="mo_oauth_bp_app_name" onchange="selectapp()" disabled="true">
					<option value="" disabled selected hidden>First Name</option>
				</td>
			</tr>
			<tr>
				<td style="width: 60%">
					<font color="#FF0000">*</font>
					User Last Name
				</td>
				<td>
					<select class="mo_table_textbox" required="true" style="width:100%; height: 27px; " name="mo_oauth_bp_app_name" onchange="selectapp()" disabled="true">
					<option value="" disabled selected hidden>Last Name</option>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					<input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save settings' ) ); ?>" class="button button-primary button-large" disabled />
						<input type="button" name="back" onclick="goBack();" value="<?php echo esc_attr( mo_sl( 'Back' ) ); ?>" class="button button-primary button-large" disabled/>
				</td>
			</tr>
		</table>
		<br><br>
		<table>
			<tr>
				<div class="mo-openid-bp-addon-img">
				<img src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/bp_add/integrations.png">
			</div>
			</tr>
			<tr>
				
					<label class="mo_openid_note_style" style="cursor: auto">For more information please refer to the <a href="https://plugins.miniorange.com/guide-to-configure-buddypress-with-wordpress-social-login" target="_blank">BuddyPress Guide</a>  /  <a href="https://youtu.be/Iia1skKRYBU" target="_blank">BuddyPress Video</a></label>
				
			</tr>
		</table>
	</div>
		</form>
		

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
			jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'BuddyPress Add On' ) ); ?>');
			var mo_openid_customer_toggle_update_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-customer-toggle-update-nonce' ) ); ?>';
			jQuery(document).ready(function($){
				jQuery("#mosocial_purchase_buddypress_addon_verify").on("click",function(){
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
						plan_name:'WP_SOCIAL_LOGIN_BUDDYPRESS_ADDON',
						'mo_openid_verify_addon_license_nonce' : mo_openid_verify_addon_license_nonce,
					},
					crossDomain :!0, dataType:"html",
					success: function(data) {
						var flag=0;
						jQuery("input").each(function(){
							if($(this).val()=="mo_openid_verify_license") flag=1;
						});
						if(!flag) {
							jQuery(data).insertBefore("#mo_openid_buddypress_addon_video");
							jQuery("#customization_ins").find(jQuery("#cust_supp")).css("display", "none");
						}
					},
					error: function (data){}
				});
			}
		</script>
	</td>
	<?php
}
