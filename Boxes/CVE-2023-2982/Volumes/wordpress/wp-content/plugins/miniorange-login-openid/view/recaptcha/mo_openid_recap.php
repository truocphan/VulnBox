<?php
function mo_openid_configure_recaptcha() {
	?>
	<form>
		<table style="padding: 2%" width="100%">
			<tr>
				<td>
					<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'Enable reCAPTCHA' ) ); ?>
						<input  type="checkbox"/>
						<span class="mo_openid_checkbox_checkmark_disable"></span>
					</label>
					<div>
						<p class="mo_openid_note_style"><b><?php echo esc_attr( mo_sl( 'Prerequisite' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Before you can use reCAPTCHA, you need to register your domain/website.' ) ); ?> <a><b><?php echo esc_attr( mo_sl( 'Click here' ) ); ?></b></a>.</p>
						<p><?php echo esc_attr( mo_sl( 'Enter Site key and Secret key that you get after registration.' ) ); ?></p>
						<table style="width: 100%;">
							<tr>
								<td colspan="2" style="width:30%"><?php echo esc_attr( mo_sl( 'Select type of reCAPTCHA ' ) ); ?>:

									<label class="mo-openid-radio-container_disable"><?php echo esc_attr( mo_sl( 'reCAPTCHA v3' ) ); ?>
										<input type="checkbox"  />
										<span class="mo-openid-radio-checkmark_disable"></span>
									</label>



									<label class="mo-openid-radio-container_disable"><?php echo esc_attr( mo_sl( 'reCAPTCHA v2' ) ); ?>
										<input type="radio"  />
										<span class="mo-openid-radio-checkmark_disable"></span>
									</label>

								</td>
							</tr>
							<tr>
								<td style="width:15%"><?php echo esc_attr( mo_sl( 'Site key' ) ); ?>  : </td>
								<td style="width:85%"><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" placeholder="site key" disabled/></td>
							</tr>
							<tr>
								<td><?php echo esc_attr( mo_sl( 'Secret key' ) ); ?> : </td>
								<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" type="text" disabled/></td>
							</tr>
							<tr id="mo_limit_recaptcha_for">
								<td colspan="2" style="vertical-align:top;"><br><b><?php echo esc_attr( mo_sl( 'Enable reCAPTCHA for ' ) ); ?>:</b></td>
							</tr>
							<tr>
								<td colspan="2">
									<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'WordPress Login form' ) ); ?>
										<input  type="checkbox"/>
										<span class="mo_openid_checkbox_checkmark_disable"></span>
									</label>


								</td>
							</tr>
							<tr>
								<td colspan="2">

									<label class="mo_openid_checkbox_container_disable"><?php echo esc_attr( mo_sl( 'WordPress Registration form' ) ); ?>
										<input  type="checkbox"/>
										<span class="mo_openid_checkbox_checkmark_disable"></span>
									</label>


								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<input disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save Settings' ) ); ?>" class="button button-primary button-large" />
					<input disabled id="mo_limit_recaptcha_test" type="button" value="<?php echo esc_attr( mo_sl( 'Test reCAPTCHA Configuration' ) ); ?>" class="button button-primary button-large" />
				</td>
			</tr>
		</table>
		<script>
			//to set heading name
			var temp = jQuery("<a style=\"left: 1%; padding:4px; position: relative; text-decoration: none\" class=\"mo-openid-premium\" href=\"<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>\">PRO</a>");

			var win_height = jQuery('#mo_openid_menu_height').height();
			//win_height=win_height+18;
			jQuery(".mo_container").css({height:win_height});
		</script>

	</form>
	<div class="mo_openid_highlight">
		<h3 style="margin-left: 1%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'GDPR Settings' ) ); ?></h3>
	</div><br/>
	<?php
	if ( mo_openid_restrict_user() ) {
		$disable = 'disabled';
	} else {
		$disable = '';
	}
	?>
	<form id="gdpr" name="gdpr" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_enable_gdpr" />
		<input type="hidden" name="mo_openid_enable_gdpr_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-gdpr-nonce' ) ); ?>"/>
		<div class="mo_openid_table_layout">
			<label class=" mo_openid_note_style" style="font-size:small;padding:22px;"><?php echo esc_attr( mo_sl( 'If GDPR check is enabled, users will be asked to give consent before using Social Login. Users who will not give consent will not be able to log in. This setting stands true only when users are registering using Social Login. This will not interfere with users registering through the regular WordPress' ) ); ?>.<br><br>(<?php echo esc_attr( mo_sl( 'Click' ) ); ?> <a target="_blank" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'privacy_policy' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'here' ) ); ?> </a> <?php echo esc_attr( mo_sl( "to read miniOrange Social Login Privacy Policy. Please update your website's privacy policy accordingly and enter the URL to your privacy policy below." ) ); ?></label>
			<br/>
			<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Take consent from users' ) ); ?>
				<input style="padding-left: 15px" type="checkbox" id="mo_openid_gdpr_consent_" name="mo_openid_gdpr_consent_enable" value="1"
					<?php checked( get_option( 'mo_openid_gdpr_consent_enable' ) == 1 ); ?> />
				<br>
				<?php if ( $disable ) { ?>
					<span class="mo_openid_checkbox_checkmark_disable"></span>
				<?php } else { ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				<?php } ?>
			</label>
			<label style="font-size: 12px"><?php echo esc_attr( mo_sl( 'Enter the Consent message:' ) ); ?> </label><br/>
			<input type="text" <?php echo esc_attr( $disable ); ?> class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 50%" name="mo_openid_gdpr_consent_message" value="<?php echo esc_textarea( get_option( 'mo_openid_gdpr_consent_message' ) ); ?>"/>
			<br><br>
			<label style="font-size: 12px"> <?php echo esc_attr( mo_sl( 'Enter the text to be displayed for the Privacy Policy URL' ) ); ?> :</label><br/>
			<input type="text" <?php echo esc_attr( $disable ); ?> class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 50%" name="mo_openid_privacy_policy_text" value="<?php echo esc_attr( get_option( 'mo_openid_privacy_policy_text' ) ); ?>" />
			<br><br>
			<label style="font-size: 12px"><?php echo esc_attr( mo_sl( 'Enter Privacy Policy URL' ) ); ?>: </label><br/>
			<input type="text" <?php echo esc_attr( $disable ); ?> class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 50%" name="mo_openid_privacy_policy_url" value="<?php echo esc_url( get_option( 'mo_openid_privacy_policy_url' ) ); ?>" />
			<br/><br/><b><input type="submit" <?php echo esc_attr( $disable ); ?> name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;background-color:#0867b2;color:white;box-shadow:none;text-shadow: none;"  class="button button-primary button-large" /></b>
		</div>
	</form>
	<?php
}
