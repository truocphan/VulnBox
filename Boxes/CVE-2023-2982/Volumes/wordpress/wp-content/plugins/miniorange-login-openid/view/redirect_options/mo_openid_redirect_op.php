<?php
function mo_openid_redirect_opt() {     ?>
	<form id="mo_openid_redirect" name="redirect" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_enable_redirect" />
		<input type="hidden" name="mo_openid_enable_redirect_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-enable-redirect-nonce' ) ); ?>"/>
		<div class="mo_openid_table_layout">
			<b><h3><?php echo esc_attr( mo_sl( 'Redirect URL after login:' ) ); ?></h3></b>
			<table style="width: 100%;">
				<tr>
					<td colspan="2">
						<label class="mo-openid-radio-container"><b>*</b><?php echo esc_attr( mo_sl( 'Same page where user logged in' ) ); ?>
							<input type="radio" id="login_redirect_same_page" name="mo_openid_login_redirect" value="same" onclick="login_redirect()"
								<?php checked( get_option( 'mo_openid_login_redirect' ) == 'same' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
				</tr>
				<tr>
					<td>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Homepage' ) ); ?>
							<input type="radio" id="login_redirect_homepage" name="mo_openid_login_redirect" value="homepage" onclick="login_redirect()"
								<?php checked( get_option( 'mo_openid_login_redirect' ) == 'homepage' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
					<td>
						<span style="width:100%;border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Account dashboard' ) ); ?>
							<input type="radio" id="login_redirect_dashboard" name="mo_openid_login_redirect" value="dashboard" onclick="login_redirect()"
								<?php checked( get_option( 'mo_openid_login_redirect' ) == 'dashboard' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
				</tr>
				<tr>
					<td style="width: 30%">
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Custom URL' ) ); ?>
							<input type="radio" id="login_redirect_customurl" name="mo_openid_login_redirect" value="custom" onclick="login_redirect()"
								<?php checked( get_option( 'mo_openid_login_redirect' ) == 'custom' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
					<td style="width: 70%">
						<input type="text"  id="login_redirect_url" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 60%" name="mo_openid_login_redirect_url" value="<?php echo esc_url( get_option( 'mo_openid_login_redirect_url' ) ); ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 30%">
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Relative URL' ) ); ?>
							<input type="radio" id="login_redirect_relativeurl" name="mo_openid_login_redirect" onclick="login_redirect()" value="relative" <?php checked( get_option( 'mo_openid_login_redirect' ) == 'relative' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
					<td style="width: 70%">
						<span style="border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span><input type="text" id="login_redirect_url_relative" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 35%" name="mo_openid_relative_login_redirect_url" value="<?php echo esc_url( get_option( 'mo_openid_relative_login_redirect_url' ) ); ?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<br>
						<label style="cursor: auto" class="mo_openid_note_style"><?php echo esc_attr( mo_sl( '*NOTE: If you login through WordPress default login page after login you will be redirected to Homepage' ) ); ?>.</label>
					</td>
				</tr>
				<tr>
					<td>
						<br><br>

						<label class="mo_openid_checkbox_container"><b><?php echo esc_attr( mo_sl( 'Redirect URL after logout:' ) ); ?></b>
							<input type="checkbox" id="logout_redirection_enable" name="mo_openid_logout_redirection_enable" value="1"
								<?php checked( get_option( 'mo_openid_logout_redirection_enable' ) == 1 ); ?> /><b></b>
							<span class="mo_openid_checkbox_checkmark"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td>
						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Home Page' ) ); ?>
							<input type="radio" id="logout_redirect_home" name="mo_openid_logout_redirect" value="homepage" onclick="logout_redirect();"
								<?php checked( get_option( 'mo_openid_logout_redirect' ) == 'homepage' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
					<td>
						<span style="width:100%;border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span>
					</td>
				</tr>
				<tr  colspan="2">
					<td>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Current Page' ) ); ?>
							<input type="radio" id="logout_redirect_current" name="mo_openid_logout_redirect" value="currentpage" onclick="logout_redirect();"
								<?php checked( get_option( 'mo_openid_logout_redirect' ) == 'currentpage' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>

					</td>
				</tr>
				<tr  colspan="2">
					<td>

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Login Page' ) ); ?>
							<input type="radio" id="logout_redirect_login" name="mo_openid_logout_redirect" value="login" onclick="logout_redirect();"
								<?php checked( get_option( 'mo_openid_logout_redirect' ) == 'login' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>

					</td>
				</tr>
				<tr>
					<td style="width: 30%">

						<label class="mo-openid-radio-container"><?php echo esc_attr( mo_sl( 'Relative URL' ) ); ?>
							<input type="radio" id="logout_redirect_relativeurl" name="mo_openid_logout_redirect" value="custom" onclick="logout_redirect();"
								<?php checked( get_option( 'mo_openid_logout_redirect' ) == 'custom' ); ?> />
							<span class="mo-openid-radio-checkmark"></span></label>

					</td>
					<td style="width: 70%">
						<span style="border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span><input type="text" id="logout_redirect_url" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 35%" name="mo_openid_logout_redirect_url" value="<?php echo esc_attr( get_option( 'mo_openid_logout_redirect_url' ) ); ?>" />
					</td>
				</tr>

				<tr>
					<td>
						<br><br>
						<label class="mo_openid_checkbox_container_disable"> <b>  <?php echo esc_attr( mo_sl( 'Enable Registration Redirection' ) ); ?></b><a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a>
							<input type="checkbox"  /><br>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
						</label>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label class="mo-openid-radio-container_disable">
							<input type="radio" /><b>*</b><?php echo esc_attr( mo_sl( 'Same page where user logged in' ) ); ?>
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
				</tr>
				<tr>
					<td>
						<label class="mo-openid-radio-container_disable">
							<input type="radio" /><?php echo esc_attr( mo_sl( 'Homepage' ) ); ?>
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>

					<td>
						<span style="width:100%;border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<label class="mo-openid-radio-container_disable">
							<input type="radio" /><?php echo esc_attr( mo_sl( 'Account dashboard' ) ); ?>
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
				</tr>
				<tr>
					<td style="width: 30%">
						<label class="mo-openid-radio-container_disable"><?php echo esc_attr( mo_sl( 'Custom URL' ) ); ?>
							<input type="radio" />
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>

					<td style="width: 60%">
						<input type="url" disabled class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 60%"  value="<?php echo esc_url( get_option( 'mo_openid_registration_redirect_url' ) ); ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 30%">
						<label class="mo-openid-radio-container_disable">
							<input type="radio" /><?php echo esc_attr( mo_sl( 'Relative URL' ) ); ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<span class="mo-openid-radio-checkmark"></span></label>
					</td>
					<td style="width: 60%">
						<span style="border: 1px #0867b2 solid;padding: 4px 6px;background-color: #ddedf9;margin-right: -5px;"><?php echo esc_url( site_url() ); ?></span>&nbsp;<input disabled type="text" id="registration_redirect_url" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width: 35%"/>
					</td>
				</tr>
				<tr>
					<td colspan="2"><br/><b><input type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;text-shadow: none;background-color:#0867b2;color:white;box-shadow:none;"  class="button button-primary button-large" /></b>
					</td>
				</tr>
			</table>
		</div>
		<script>
			//to set heading name
			var win_height = jQuery('#mo_openid_menu_height').height();
			//win_height=win_height+18;
			jQuery(".mo_container").css({height:win_height});
			function login_redirect() {
				if(document.getElementById('login_redirect_customurl').checked)
				{
					document.getElementById('login_redirect_url').setAttribute("required", "");
					document.getElementById('login_redirect_url_relative').removeAttribute("required", "");
				}
				else if(document.getElementById('login_redirect_relativeurl').checked)
				{
					document.getElementById('login_redirect_url').removeAttribute("required", "");
					document.getElementById('login_redirect_url_relative').setAttribute("required", "");
				}
				else {
					document.getElementById('login_redirect_url_relative').removeAttribute("required", "");
					document.getElementById('login_redirect_url').removeAttribute("required", "");
				}
			}

			function logout_redirect() {
				if(document.getElementById('logout_redirect_relativeurl').checked)
				{
					document.getElementById('logout_redirect_url').setAttribute("required", "");
				}
				else
				{
					document.getElementById('logout_redirect_url').removeAttribute("required", "");
				}
			}
		</script>
	</form>
	<?php
}
