<?php

function mo_openid_profile() {
	if ( ( get_option( 'mo_openid_verify_customer' ) == 'true' ) || ( trim( get_option( 'mo_openid_admin_email' ) ) != '' && trim( get_option( 'mo_openid_admin_api_key' ) ) == '' && get_option( 'mo_openid_new_registration' ) != 'true' ) ) {
		mo_openid_show_verify_password_page();
	} elseif ( ! mo_openid_is_customer_registered() ) {
		update_option( 'regi_pop_up', 'no' );
		update_option( 'mo_openid_new_registration', 'true' );
		$current_user = wp_get_current_user();
		?>
			<!--Register with miniOrange-->
			<form name="f" method="post" action="" id="register-form">
				<input type="hidden" name="option" value="mo_openid_connect_register_customer" />
				<input type="hidden" name="mo_openid_connect_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-connect-register-nonce' ) ); ?>"/>
				<div class="mo_openid_table_layout">
					<h3><?php echo esc_attr( mo_sl( 'Register with miniOrange' ) ); ?></h3>
					<p style="font-size:14px;"><b><?php echo esc_attr( mo_sl( 'Why should I register?' ) ); ?> </b></p>
					<div id="help_register_desc" style="background: aliceblue; padding: 10px 10px 10px 10px; border-radius: 10px;">
						<?php echo esc_attr( mo_sl( 'By registering with miniOrange we take care of creating applications for you so that you don’t have to worry about creating applications in each social network.' ) ); ?>
						<br/><b><?php echo esc_attr( mo_sl( 'Please Note' ) ); ?>:</b><?php echo esc_attr( mo_sl( 'We do not store any information except the email that you will use to register with us. You can go through our ' ) ); ?><a href="https://www.miniorange.com/usecases/miniOrange_Privacy_Policy.pdf" target="_blank"><?php echo esc_attr( mo_sl( 'Privacy Policy' ) ); ?></a> <?php echo esc_attr( mo_sl( 'for how we use your information. We don’t sell your information to any third-party organization' ) ); ?>.
					</div><br/>
					<table class="mo_openid_settings_table" style="padding-right: 20%">
						<tr>
							<td><b><font color="#FF0000">*</font><?php echo esc_attr( mo_sl( 'Email' ) ); ?>:</b></td>
							<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%" type="email" name="email"
									   required placeholder="person@example.com"
									   value="<?php echo esc_attr( $current_user->user_email ); ?>" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font><?php echo esc_attr( mo_sl( 'Password' ) ); ?>:</b></td>
							<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%" required type="password"
									   name="password" placeholder="Choose your password (Min. length 6)" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font><?php echo esc_attr( mo_sl( 'Confirm Password' ) ); ?>:</b></td>
							<td><input class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 80%" required type="password"
									   name="confirmPassword" placeholder="Confirm your password" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><br /><input type="submit" name="submit" value="Register" style="width:auto;"
											 class="button button-primary button-large" />
								<input type="button" value="Already have an Account?" id="mo_openid_go_back_registration" style="width:auto; margin-left: 2%"
									   class="button button-primary button-large" />
							</td>
						</tr>
					</table>
					<br/><?php echo esc_attr( mo_sl( 'By clicking Submit, you agree to our' ) ); ?> <a href="https://www.miniorange.com/usecases/miniOrange_Privacy_Policy.pdf" target="_blank"><?php echo esc_attr( mo_sl( 'Privacy Policy' ) ); ?></a> <?php echo esc_attr( mo_sl( 'and' ) ); ?> <a href="https://www.miniorange.com/usecases/miniOrange_User_Agreement.pdf" target="_blank"><?php echo esc_attr( mo_sl( 'User Agreement' ) ); ?></a>.
				</div>
			</form>
			<form name="f" method="post" action="" id="openidgobackloginform">
				<input type="hidden" name="option" value="mo_openid_go_back_registration"/>
				<input type="hidden" name="mo_openid_go_back_registration_nonce"
					   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-go-back-register-nonce' ) ); ?>"/>
			</form>
			<script>
				jQuery('#mo_openid_go_back_registration').click(function() {
					jQuery('#openidgobackloginform').submit();
				});
				var text = "&nbsp;&nbsp;We will call only if you need support."
				jQuery('.intl-number-input').append(text);

			</script>
		<?php
	} else {
		?>
		<div class="mo_openid_table_layout">
			<h2><?php echo esc_attr( mo_sl( 'Thank you for registering with miniOrange' ) ); ?>.</h2>
			<table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
				<tbody><tr>
					<td style="width:45%; padding: 10px;"><?php echo esc_attr( mo_sl( 'miniOrange Account Email' ) ); ?></td>
					<td style="width:55%; padding: 10px;"><?php echo esc_attr( get_option( 'mo_openid_admin_email' ) ); ?></td>
				</tr>
				<tr>
					<td style="width:45%; padding: 10px;"><?php echo esc_attr( mo_sl( 'Customer ID' ) ); ?></td>
					<td style="width:55%; padding: 10px;"><?php echo esc_attr( get_option( 'mo_openid_admin_customer_key' ) ); ?></td>
				</tr>
				</tbody>
			</table>
			<br/><label style="cursor: auto"><a href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'Click here' ) ); ?></a><?php echo esc_attr( mo_sl( ' to check our' ) ); ?> <a style="left: 1%; position: static; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a> <?php echo esc_attr( mo_sl( 'plans' ) ); ?></label>
		</div>
		<?php
	}
	?>
	<script>
		//to set heading name
		var win_height = jQuery('#mo_openid_menu_height').height();
		//win_height=win_height+18;
		jQuery(".mo_container").css({height:win_height});
	</script>
	<?php
}
