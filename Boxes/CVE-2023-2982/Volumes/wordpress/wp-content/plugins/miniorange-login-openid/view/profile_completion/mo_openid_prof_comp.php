<?php
function mo_openid_profile_completion() {
	?><br>
	<div class="mo_openid_table_layout">
		<form id="profile_completion" name="profile_completion" method="post" action="" >
			<input type="hidden" name="option" value="mo_openid_profile_completion" />
			<input type="hidden" name="mo_openid_enable_profile_completion_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-premium-feature-nonce' ) ); ?>"/>
			<div class="mo_openid_table_layout">
				<table style="width: 100%;">
					<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Prompt users for username &amp; email when unavailable (profile completion)' ) ); ?><br/>
						<?php echo esc_attr( mo_sl( 'In case of unavailability of username or email from the social media application, user is prompted to input the same' ) ); ?>
						<input type="checkbox" id="profile_completion_enable" name="mo_openid_enable_profile_completion" value="1" <?php checked( get_option( 'mo_openid_enable_profile_completion' ) == '1' ); ?> />
						<span class="mo_openid_checkbox_checkmark"></span>
					</label>
					<label class="mo_openid_checkbox_container">
						<input type="checkbox" id="mo_openid_enable_profile_completion1">Prompt user for username and email without email verification. (profile completion).<a style="left: 1%; position: static; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">PRO</a>
						<br>For getting profile completion without Otp verification through your email,you need to enable the both the checkboxes.</br>
						<span class="mo_openid_checkbox_checkmark_disable"></span>
					</label>
					<p class=" mo_openid_note_style" style="color:#000000;">
						<b><?php echo esc_attr( mo_sl( '*NOTE:' ) ); ?></b> <?php echo esc_attr( mo_sl( "Disabling profile completion is not recommended. Instagram and Twitter don't return email address. Please keep this enabled if you are using Instagram or Twitter. This feature requires SMTP to be setup for your WordPress website since we send a code to users over email to verify their email address." ) ); ?>
					</p>
					<br/>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2"><h3 style="float: left"><?php echo esc_attr( mo_sl( 'Customize Text for Profile Completion' ) ); ?></h3><a style="float: right;margin-right: 300px;margin-top: 20px" onclick="customize_profile_completion_img()"><?php echo esc_attr( mo_sl( 'Preview Profile Completion form' ) ); ?></a></td></tr>
						<tr id="profile_completion_img_verify"><td colspan="2"></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" style="width: 40%">1.<?php echo esc_attr( mo_sl( 'Enter title of Profle Completion' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" name="mo_profile_complete_title" value="<?php echo esc_attr( get_option( 'mo_profile_complete_title' ) ); ?>" /></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize">2.<?php echo esc_attr( mo_sl( 'Enter Username Label text' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_profile_complete_username_label" value="<?php echo esc_attr( get_option( 'mo_profile_complete_username_label' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize">3.<?php echo esc_attr( mo_sl( 'Enter Email Label text' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_profile_complete_email_label" value="<?php echo esc_attr( get_option( 'mo_profile_complete_email_label' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize">4. <?php echo esc_attr( mo_sl( 'Enter Submit button text' ) ); ?>:</td><td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_profile_complete_submit_button" value="<?php echo esc_attr( get_option( 'mo_profile_complete_submit_button' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2">5.<?php echo esc_attr( mo_sl( 'Enter instruction for Profile Completion' ) ); ?>:<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" style="width:98%;margin-left: 0px;margin-bottom:5px" type="text" name="mo_profile_complete_instruction" value="<?php echo esc_attr( get_option( 'mo_profile_complete_instruction' ) ); ?>"/></td></tr>
						<tr><td></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2">6.<?php echo esc_attr( mo_sl( 'Enter extra instruction for Profile Completion ' ) ); ?>:<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" type="text" name="mo_profile_complete_extra_instruction" value="<?php echo esc_attr( get_option( 'mo_profile_complete_extra_instruction' ) ); ?>"/></td></tr>
						<tr><td></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2"><?php echo esc_attr( mo_sl( 'Enter username already exists warning message text ' ) ); ?>:<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" type="text" name="mo_profile_complete_uname_exist" value="<?php echo esc_attr( get_option( 'mo_profile_complete_uname_exist' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2"><h3 style="float: left"><?php echo esc_attr( mo_sl( 'Customize Text for Email Verification' ) ); ?></h3><a style="float: right;margin-right: 300px;margin-top: 20px" onclick="customize_email_verify_img()"><?php echo esc_attr( mo_sl( 'Preview Email Verification form' ) ); ?></a></td></tr>
						<tr id="email_verify"><td colspan="2"></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" style="width: 40%">1. <?php echo esc_attr( mo_sl( 'Enter title of Email Verification form' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_email_verify_title" value="<?php echo esc_attr( get_option( 'mo_email_verify_title' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize">2. <?php echo esc_attr( mo_sl( 'Enter Resend OTP button text' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_email_verify_resend_otp_button" value="<?php echo esc_attr( get_option( 'mo_email_verify_resend_otp_button' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize">3. <?php echo esc_attr( mo_sl( 'Enter Back button text' ) ); ?>:</td><td><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%;margin: 1px" type="text" name="mo_email_verify_back_button" value="<?php echo esc_attr( get_option( 'mo_email_verify_back_button' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2">4.<?php echo esc_attr( mo_sl( 'Enter instruction for Email Verification form' ) ); ?>:<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" type="text" name="mo_email_verify_message" value="<?php echo esc_attr( get_option( 'mo_email_verify_message' ) ); ?>"/></td></tr>
						<tr><td></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2">5.<?php echo esc_attr( mo_sl( 'Enter verification code in Email Verification form' ) ); ?>:<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" type="text" name="mo_email_verify_verification_code_instruction" value="<?php echo esc_attr( get_option( 'mo_email_verify_verification_code_instruction' ) ); ?>"/></td></tr>
						<tr><td></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2"> <?php echo esc_attr( mo_sl( 'Enter Message for wrong OTP' ) ); ?> :<br/><input  class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 100%;margin-top: 6px" type="text" name="mo_email_verify_wrong_otp" value="<?php echo esc_attr( get_option( 'mo_email_verify_wrong_otp' ) ); ?>"/></td></tr>
						<tr id="profile_completion_customized_text"><td class="mo_openid_fix_fontsize" colspan="2"><br><h3> <b><?php echo esc_attr( mo_sl( 'Customized E-mail Message' ) ); ?>:</b> </h3><textarea pattern="/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/" required rows="6" id="mo_openid_email_message_id" style="width:100%" name="custom_otp_msg" ><?php echo esc_html( get_option( 'custom_otp_msg' ) ); ?></textarea></td></tr>
						<tr id='profile_completion_customized_text'><td class="mo_openid_fix_fontsize" colspan="2"><b><?php echo esc_attr( mo_sl( 'NOTE' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Please enter' ) ); ?> <b>##otp##</b><?php echo esc_attr( mo_sl( 'in message where you want to show one time password.' ) ); ?></td></tr><tr id="prof_completion"><td> </td></tr>
						<tr id="prof_logo"><td colspan="2"><br/>
								<label class="mo_openid_checkbox_container"><?php echo esc_attr( mo_sl( 'Display miniOrange logo with social login icons on profile completion forms' ) ); ?>
									<input type="checkbox" id="moopenid_logo_check_prof" name="moopenid_logo_check_prof" value="1"
										<?php checked( get_option( 'moopenid_logo_check_prof' ) == 1 ); ?>  />
									<span class="mo_openid_checkbox_checkmark"></span>
								</label>



								</td></tr>
						<tr id="save_mo_btn"><td colspan="2"><br/><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;text-shadow: none;background-color:#0867b2;color:white;box-shadow:none;"  class="button button-primary button-large" /></td></tr>
											<tr id="prof"><td> </td></tr>
				</table>
			</div>
		</form>
	</div>
	<script>
		//to set heading name
		//var win_height = jQuery('#mo_openid_menu_height').height();
		//win_height=win_height+18;
		//jQuery(".mo_container").css({height:win_height});
		var custom_link;
		var custom_prof_completion;
		var custom_link_img;
		var custom_profile_img=1;
		var custom_email_verify_img=1;
		var checkbox2 = document.getElementById('profile_completion_enable');





		function customize_profile_completion_img(){
			if(custom_profile_img==1){
				jQuery("<tr id=\"profile_completion_img\"><td colspan=\"2\"><img style=\"margin-top: 15px;margin-left: 85px;\" src=\"<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/profile_completion.png\" height=\"400\" width=\"550\"></td></tr>").insertBefore(jQuery("#profile_completion_img_verify"));
				custom_profile_img=2;
			}else{
				jQuery("#profile_completion_img").remove();
				custom_profile_img=1;
			}
		}
		function customize_email_verify_img(){
			if(custom_email_verify_img==1){
				jQuery("<tr id=\"email_verify_img\"><td colspan=\"2\"><img style=\"margin-top: 15px;margin-left: 85px;\" src=\"<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/email_verify.png\" height=\"350\" width=\"550\"></td></tr>").insertBefore(jQuery("#email_verify"));
				custom_email_verify_img=2;
			}else{
				jQuery("#email_verify_img").remove();
				custom_email_verify_img=1;
			}
		}
	</script>
	<?php
}


?>
