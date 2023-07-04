<?php
function mo_openid_integrations() {
    $active_tab_int = isset( $_GET[ 'tab' ]) ? sanitize_text_field($_GET[ 'tab' ]) : 'integration-woocommerce'; //phpcs:ignore
	if ( $active_tab_int == 'integration' ) {
		$active_tab_int = 'integration-woocommerce';
	}
	?>
	<div id="tab">
		<h2 class="nav-tab-wrapper">
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-woocommerce' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-woocommerce' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">WooCommerce</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-buddypress' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-buddypress' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">BuddyPress</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-mailchimp' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-mailchimp' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">MailChimp</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-hubspot' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-hubspot' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">HubSpot</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-discord' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-discord' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Discord</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-paidmemb' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-paidmemb' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Paid Membershi Pro</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-memberpress' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-memberpress' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">MemberPress</a>
			<a class="mo-nav-tab <?php echo $active_tab_int == 'integration-customregistration' ? 'mo-nav-tab-active' : ''; ?>" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'integration-customregistration' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>">Custom Registration</a>
		</h2>
	</div>

	<?php
	if ( $active_tab_int == 'integration-woocommerce' ) {
		?>
			<div class="mo_openid_table_layout" id="wca_adv_disp" style="display: block">
				<table>
					<tr>
						<td><br/>
							<b>
							<?php
							echo esc_attr(
								mo_sl(
									'With WooCommerce Integration you will get various social login display options using which you can display the Social Login icons on your WooCommerce login, registration, and checkout pages.
You\'ll also have the option to sync Woocommerce checkout fields, which will pre-fill a user\'s billing information with their first name, last name, and email address.'
								)
							);
							?>
								</b>
						</td>
					</tr>
				</table>
				<br>
				<div class="mo_openid_highlight">
					<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'Woocommerce Display Options' ) ); ?></h3>
				</div>
				<br>

				<form id="wa_display" name="wa_display" method="post" action="">
					<div class="mo_openid_wca_table_layout_fake" style="height: 1450px"><br/>
						<div style="width:40%; background:white; float:left; border: 1px transparent;">
							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<input type="checkbox" id="woocommerce_before_login_form" name="mo_openid_woocommerce_before_login_form" <?php checked( get_option( 'mo_openid_woocommerce_before_login_form' ) == 1 ); ?> />
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'Before WooCommerce Login Form' ) ); ?>
								</div>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/1_before_login.png'; ?>">
								</div>
							</label>
							<br>
							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<input type="checkbox" id="woocommerce_center_login_form" name="mo_openid_woocommerce_center_login_form" <?php checked( get_option( 'mo_openid_woocommerce_center_login_form' ) == 1 ); ?> />
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( "Before 'Remember Me' of WooCommerce Login Form" ) ); ?>
								</div>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/2_login.png'; ?>">
								</div>
							</label>
							<br>
							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'After WooCommerce Login Form' ) ); ?>
								</div>
								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/3_after_login.png'; ?>">
								</div>
							</label>

							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'Before WooCommerce Registration Form' ) ); ?>
								</div>

								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/4_before_reg.png'; ?>">
								</div>
							</label>
						</div>
						<div style="width:50%; background:white; float:right; border: 1px transparent;">
							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( "Before 'Register button' of WooCommerce Registration Form" ) ); ?>
								</div>
								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/5_in_reg.png'; ?>">
								</div>
							</label>

							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'After WooCommerce Registration Form' ) ); ?>
								</div>
								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/6_after_reg.png'; ?>">
								</div>
							</label>

							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'Before WooCommerce Checkout Form' ) ); ?>
								</div>
								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/7_before_checkout.png'; ?>">
								</div>
							</label>

							<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
								<div style="padding-left: 7%">
									<?php echo esc_attr( mo_sl( 'After WooCommerce Checkout Form' ) ); ?>
								</div>
								<input type="checkbox"  /><br>
								<span class="mo_openid_checkbox_checkmark_disable"></span>
								<div class="mo_openid_wca_box">
									<br>
									<img style="box-shadow: 4px 4px #888888" class="mo_openid_wca_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/8_after_checkout.png'; ?>">
								</div>
							</label>
						</div>
					</div>
					<br>
					<input style="width: 126px;margin-left: 2%" disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?> "  class="button button-primary button-large" />
					<br>
				</form>

				<br><br><br>

				<form>
					<br>
					<div class="mo_openid_highlight">
						<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'Woocommerce Integration' ) ); ?></h3>
					</div>
					<table id="woocommerce_integration"><tr><td>
								<p><b><?php echo esc_attr( mo_sl( 'WooCommerce Integration pre-fills the first name, last name and email in Billing Details on the WooCommerce Checkout Page.' ) ); ?></b></p>
								<label class="mo_openid_checkbox_container_disable"><strong><?php echo esc_attr( mo_sl( 'Sync Woocommerce checkout fields' ) ); ?></strong>
									<input  type="checkbox"/>
									<span class="mo_openid_checkbox_checkmark_disable"></span>
								</label>

								<br>
								<img class="mo_openid_wcai_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/wca/wcai.png'; ?>">
								<br>
								<input style="width: 126px;" disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" class="button button-primary button-large" />
								<br>
								<label class="mo_openid_note_style" style="cursor: auto">For more information please refer to the <a href="https://plugins.miniorange.com/guide-to-configure-woocommerce-with-wordpress-social-login" target="_blank">WooCommerce Guide</a>  /  <a href="https://youtu.be/M20AR-wbKNI" target="_blank">WooCommerce Video</a></label>
							</td></tr></table>
				</form>

			</div>
			<td>

			</td>
			<?php
	} elseif ( $active_tab_int == 'integration-buddypress' ) {
		?>
			<div id="bp_addon_head" style="display: block">
				<table>
					<tr>
						<td>
							<br/><b>
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
			</div>
			<br><br>
			<div class="mo_openid_highlight">
				<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'BuddyPress / BuddyBoss Display Options' ) ); ?></h3>
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

			</td>
			<?php
	} elseif ( $active_tab_int == 'integration-mailchimp' ) {
		?>

			<div class="mo_openid_table_layout" id="customization_ins" style="display: block">
				<table>
					<tr>
						<td>
							<br/><b><?php echo esc_attr( mo_sl( 'Mailchimp is a marketing platform that allows you to manage and communicate with your companies, consumers, and other interested parties in one place. When a user registers on your website, the user\'s first name, last name, and email address are sent to your mailchimp account\'s mailing list, and the user is identified as subscribed.' ) ); ?>.</b>
						</td>
					</tr>
				</table>
				<table class="mo_openid_display_table table" id="mo_openid_mailchimp_video"></table>
				<br>
				<hr>
				<form>
					<table><tr><td>
								<p><b>
								<?php
								echo esc_attr(
									mo_sl(
										'A user is added as a subscriber to a mailing list in MailChimp when that user registers using social login. First name, last name and email are also captured for that user in the Mailing List.</b></p>
                        (List ID in MailChimp : Lists -> Select your List -> Settings -> List Name and Defaults -> List ID) <br>
                        (API Key in MailChimp : Profile -> Extras -> API Keys -> Your API Key'
									)
								);
								?>
										 )<br><br>
										<b><?php echo esc_attr( mo_sl( 'List Id' ) ); ?>:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input size="50" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width:43%" type="text" disabled="disabled" > <br><br>
										<b><?php echo esc_attr( mo_sl( 'API Key' ) ); ?>: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input size="50" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2;width:43%" type="text" disabled="disabled" ><br><br>
										<label class="mo_openid_checkbox_container_disable"><strong><?php echo esc_attr( mo_sl( "User's Choice" ) ); ?></strong>
											<input type="checkbox" />
											<span class="mo_openid_checkbox_checkmark"></span>
										</label>
										<strong><?php echo esc_attr( mo_sl( 'Ask user for permission to be added in MailChimp Subscriber list' ) ); ?> </strong>
										<br><?php echo esc_attr( mo_sl( '(If unchecked, user will get subscribed during registration.)' ) ); ?>
										<br><h3 style="float: left">Edit MailChimp subscription form </h3>
										<img src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ); ?>includes/images/demo_mailchimp.png">
										<br><br>
										<b><?php echo esc_attr( mo_sl( 'Click on Download button to get a list of emails of WordPress users registered on your site. You can import this file in MailChimp' ) ); ?>.<br><br>
											<input type="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?> " disabled="disabled"  class="button button-primary button-large" />
											<a style="width:190px;" disabled="disabled" class="button button-primary button-large" href="#">
												<?php echo esc_attr( mo_sl( 'Download emails of users' ) ); ?>
											</a><br>
							</td></tr></table>
				</form>
			</div>
			<?php
	} elseif ( $active_tab_int == 'integration-discord' ) {
		?>
			<div class="mo_openid_table_layout">
				<h4><span style="color: red">Note:</span> Discord Integration is not included with any of the premium plans and must be purchased as an add-on separately.</h4>
		<?php
		mo_openid_discord_add_on_display();
		?>
			</div>
		<?php
	} elseif ( $active_tab_int == 'integration-customregistration' ) {
		?>

			<div class="mo_openid_table_layout" id="customization_ins" style="display: block">
				<table>
					<tr>
						<td>
							<b><?php echo esc_attr( mo_sl( 'The miniOrange Custom Registration Form Add-On allows you to obtain additional information from your customers. You can build your own custom form with as many fields as you want, even those returned by social sites, and the user will be redirected to it after a successful registration form social login. The meta table will save all of the details entered by the user.' ) ); ?></b>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td style="vertical-align:top; ">
							<div class="mo_openid_table_layout"><br/>
								<form method="post">
									<h3><?php echo esc_attr( mo_sl( 'Customization Fields' ) ); ?></h3>
									<input type="checkbox" disabled="disabled"
										   value="1" checked
									<b><?php echo esc_attr( mo_sl( 'Enable Auto Field Registration Form' ) ); ?></b>

									<style>
										.tableborder {
											border-collapse: collapse;
											width: 100%;
											border-color: #eee;
										}

										.tableborder th, .tableborder td {
											text-align: left;
											padding: 8px;
											border-color: #eee;
										}

										.tableborder tr:nth-child(even) {
											background-color: #f2f2f2
										}
									</style>
									<!--mo_openid_custom_field_update-->
									<table id="custom_field" style="width:100%; text-align: center;" class="table mo_openid_mywatermark">
										<div id="myCheck">
											<h4><?php echo esc_attr( mo_sl( 'Registration page link' ) ); ?> <input type="text"
																									 style="width: 350px" disabled="disabled"
																									 required/></h4>
											<thead>
											<tr>
												<th><?php echo esc_attr( mo_sl( 'Existing Field' ) ); ?></th>
												<th><?php echo esc_attr( mo_sl( 'Field' ) ); ?></th>
												<th><?php echo esc_attr( mo_sl( 'Custom name' ) ); ?></th>
												<th><?php echo esc_attr( mo_sl( 'Field Type' ) ); ?></th>
												<th><?php echo esc_attr( mo_sl( 'Field Options' ) ); ?></th>
												<th><?php echo esc_attr( mo_sl( 'Required Field' ) ); ?></th>
												<th></th>
											</tr>
											</thead>
																					<tr>
												<td style="width: 15%"><br><input type="text" disabled="disabled" placeholder="Existing meta field"
																				  style="width:90%;"/></td>
												<td style="width: 15%"><br><select id="field_1_name" disabled="disabled"
																				   onchange="myFunction('field_1_name','opt_field_1_name','field_1_value','additional_field_1_value')"
																				   style="width:80%">
														<option value=""><?php echo esc_attr( mo_sl( 'Select Field' ) ); ?></option>
													</select></td>
												<td style="width: 15%"><br><input type="text" id="opt_field_1_name" disabled="disabled"
																				  placeholder="Custom Field Name"
																				  style="width:90%;"/></td>
												<td style="width: 15%"><br><select id="field_1_value" name="field_1_value" disabled="disabled"
																				   onchange="myFunction2('field_1_name','opt_field_1_name','field_1_value','additional_field_1_value')"
																				   style="width:80%">
														<option value="default"><?php echo esc_attr( mo_sl( 'Select Type' ) ); ?></option>
													</select></td>
												<td style="width: 20%"><br><input type="text" id="additional_field_1_value" disabled="disabled"
																				  placeholder="e.g. opt1;opt2;opt3"
																				  style="width:90%;"/></td>
												<td style="width: 10%"><br><select name="mo_openid_custom_field_1_Required" disabled="disabled"
																				   style="width:57%">
														<option value="no"><?php echo esc_attr( mo_sl( 'No' ) ); ?></option>
													</select></td>
												<td style="width: 10%"><br><input type="button" disabled="disabled"
																				  value="+"
																				  class=" button-primary"/>&nbsp;
													<input type="button" name="mo_remove_attribute" value="-" disabled="disabled"
														   class=" button-primary"/>
												</td>
											</tr>
										</div>
										<tr id="mo_openid_custom_field">
											<td align="center" colspan="7"><br>
												<input name="mo_openid_save_config_element" type="submit" disabled="disabled"
													   value="Save"
													   class="button button-primary button-large"/>
												&nbsp &nbsp <a class="button button-primary button-large" disabled="disabled"><?php echo esc_attr( mo_sl( 'Cancel' ) ); ?></a>
											</td>
										</tr>
										<tr>
											<td align="left" colspan="7">
												<h3><?php echo esc_attr( mo_sl( 'Instructions to setup' ) ); ?>:</h3>
												<p>
												<ol>
													<li> <?php echo esc_attr( mo_sl( 'Create a page and use shortcode' ) ); ?> <b>[miniorange_social_custom_fields]</b>
													<?php echo esc_attr( mo_sl( 'where you want your form to be displayed' ) ); ?>.
													</li>
													<li>
													<?php
													echo esc_attr(
														mo_sl(
															'Copy the page link and paste it in the above field <b>Registration page
                                                    link'
														)
													);
													?>
														</b>.
													</li>
													<li><?php echo esc_attr( mo_sl( "If you have any existing wp_usermeta field then enter that field's name in" ) ); ?>
														<b>
														<?php
														echo esc_attr(
															mo_sl(
																'Existing
                                                    Field'
															)
														);
														?>
															</b> <?php echo esc_attr( mo_sl( 'column. For example, if you are saving' ) ); ?> <b><?php echo esc_attr( mo_sl( 'First Name' ) ); ?></b> <?php echo esc_attr( mo_sl( 'as' ) ); ?>
														<b><?php echo esc_attr( mo_sl( 'fname' ) ); ?></b>
														<?php
														echo esc_attr(
															mo_sl(
																'in wp_usermeta field then enter <b>fname</b> in <b>Existing Field</b>
                                                column.'
															)
														);
														?>
													</li>
													<li> <?php echo esc_attr( mo_sl( 'Select field name under the ' ) ); ?><b><?php echo esc_attr( mo_sl( 'Field' ) ); ?></b> <?php echo esc_attr( mo_sl( 'dropdown' ) ); ?>.</li>
													<li> <?php echo esc_attr( mo_sl( 'If selected field is other than custom, then' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Field Type' ) ); ?></b> 
																	<?php
																	echo esc_attr(
																		mo_sl(
																			'will
                                                automatically be'
																		)
																	);
																	?>
															 <b><?php echo esc_attr( mo_sl( 'Textbox' ) ); ?></b> <?php echo esc_attr( mo_sl( 'and there is no need to enter' ) ); ?> <b>
				 <?php
												echo esc_attr(
													mo_sl(
														'Custom
                                                    name'
													)
												);
					?>
</b> <?php echo esc_attr( mo_sl( 'and' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Field options' ) ); ?></b>.
													</li>
													<li> <?php echo esc_attr( mo_sl( 'If selected field is custom, then enter' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Custom name' ) ); ?></b>.</li>
													<li> <?php echo esc_attr( mo_sl( 'Select' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Field Type' ) ); ?></b>, <?php echo esc_attr( mo_sl( 'if selected' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Field Type' ) ); ?></b> <?php echo esc_attr( mo_sl( 'is' ) ); ?>
														<b><?php echo esc_attr( mo_sl( 'Checkbox' ) ); ?></b><?php echo esc_attr( mo_sl( 'or' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Dropdown' ) ); ?></b> <?php ( 'then enter the desire options in' ); ?> <b>
																	  <?php
																		echo esc_attr(
																			mo_sl(
																				'Field
                                                    Options'
																			)
																		);
																		?>
															</b> <?php echo esc_attr( mo_sl( 'seprated by semicolon ' ) ); ?><b>;</b>'<?php echo esc_attr( mo_sl( 'otherwise leave' ) ); ?> <b>
				  <?php
													echo esc_attr(
														mo_sl(
															'Field
                                                    Options'
														)
													);
					?>
</b> <?php echo esc_attr( mo_sl( 'blank.' ) ); ?>
													</li>
													<li> <?php echo esc_attr( mo_sl( 'Select' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Required Field' ) ); ?></b> <?php echo esc_attr( mo_sl( 'as' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Yes' ) ); ?></b> 
																	<?php
																	echo esc_attr(
																		mo_sl(
																			'if you want to make that field
                                                compulsory for user'
																		)
																	);
																	?>
															.
													</li>
													<li> <?php echo esc_attr( mo_sl( 'If you want to add more than 1 fields at a time click on' ) ); ?> <b>"+"</b>.</li>
													<li> <?php echo esc_attr( mo_sl( 'Last click on' ) ); ?> <b><?php echo esc_attr( mo_sl( 'Save' ) ); ?></b> <?php echo esc_attr( mo_sl( 'button' ) ); ?>.</li>
												</ol>
												</p>
											</td>
										</tr>
									</table>
								</form>
								<br>
								<hr>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<?php
	} elseif ( $active_tab_int == 'integration-hubspot' ) {
		?>
			<div class="mo_openid_table_layout">
			<h4><span style="color: red">Note:</span> HubSpot Integration is not included with any of the premium plans and must be purchased as an add-on separately. With Hubspot integration, you can monitor a user's activities on your website.</h4>
		<?php
		mo_openid_hubspot_add_on_display();
		?>
			</div>
		<?php
	} elseif ( $active_tab_int == 'integration-memberpress' ) {
		?>
			<div id="mmp_head" style="display: block">
				<table>
					<tr>
						<td>
							<br/><b>
							<?php
							echo esc_attr(
								mo_sl(
									'Membership is the member management and membership subscriptions plugin. With Membership Integration you will get various social login display options using which you can display the Social Login icons on your Membership Login, Account, and checkout page.
You\'ll also have the choice of assigning a default membership level to users or allowing them to select from a list of available membership levels when they register.'
								)
							);
							?>
									</b>
						</td>
					</tr>
				</table>
			</div>
			<br><br>
			<div class="mo_openid_highlight">
				<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'MemberPress Display Options' ) ); ?></h3>
			</div>

			<form id="mmp_display" name="mmp_display" method="post" action="">
				<div class="mo_openid_mmp_table_layout_fake" style="height: 430px"><br/>
					<div style="width:45%; background:white; float:left; border: 1px transparent; margin-left: 25px">
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox"/>
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'After MemberPress Login Form' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_mmp_box">
								<br>
								<img style="box-shadow: 4px 4px #888888; width: 100%; height: 75%" class="mo_openid_mmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/mmp/membership-2.png'; ?>">
							</div>
						</label>
						<br/>
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox" />
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'After MemberPress Account Form' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_mmp_box">
								<br>
								<img style="box-shadow: 4px 4px #888888; width: 100%; height: 75%" class="mo_openid_mmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/mmp/membership-1.png'; ?>">
							</div>
						</label>



					</div>
					<div style="width:45%; background:white; float:right; border: 1px transparent;">
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox" />
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'After MemberPress Checkout Form' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_mmp_box">
								<br>
								<img style="box-shadow: 4px 4px #888888; width: 100%; height: 100%" class="mo_openid_mmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/mmp/membership-3.png'; ?>">
							</div>
						</label>
					</div>
				</div>
				<br>
				<input style="width: 126px;margin-left: 1%; margin-top:33%" disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?> "  class="button button-primary button-large" />
				<br>
			</form>
			<br><br>

			<div class="mo_openid_highlight">
				<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'MemberPress Levels Integration' ) ); ?></h3>
			</div>
			<form>
				<br>
				<div style="margin-left: 1.5%">
					<table>
						<tr>
							<div class="mo-openid-ppm-img">
								<img width="70%" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/mmp/mmp_4.png">
							</div>
						</tr>
					</table>
				</div>
			</form>
			<td>

			</td>
			<?php
	} elseif ( $active_tab_int == 'integration-paidmemb' ) {
		?>
			<div id="pmp_head" style="display: block">
				<table>
					<tr>
						<td>
							<b>
							<?php
							echo esc_attr(
								mo_sl(
									'Paid Membership Pro is the member management and membership subscriptions plugin. With Paid Membership Integration you will get various social login display options using which you can display the Social Login icons on your Membership Pro checkout page.
You\'ll also have the choice of assigning a default membership level to users or allowing them to select from a list of available membership levels when they register.'
								)
							);
							?>
								</b>
						</td>
					</tr>
				</table>
			</div>
			<br><br>
			<div class="mo_openid_highlight">
				<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'Paid Membership Pro Display Options' ) ); ?></h3>
			</div>

			<form id="pmp_display" name="pmp_display" method="post" action="">
				<div class="mo_openid_pmp_table_layout_fake" style="height: 550px"><br/>
					<div style="width:45%; background:white; float:left; border: 1px transparent; margin-left: 25px">
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox" id="pmp_after_checkout_page_level_cost" name="pmp_after_checkout_page_level_cost" />
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'After Paid Memberships Pro Checkout Page Level Cost' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_pmp_box">
								<br>
								<img style="width: 100%; height: 100%" class="mo_openid_pmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/pmp/pmp_2.png'; ?>">
							</div>
						</label>
						<br/>
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox" id="pmp_before_checkout_page_submit_button" name="pmp_before_checkout_page_submit_button" />
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'Before Paid Memberships Pro Checkout Page Submit Button' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_pmp_box">
								<br>
								<img style="width: 100%; height: 100%" class="mo_openid_pmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/pmp/pmp_1.png'; ?>">
							</div>
						</label>



					</div>
					<div style="width:45%; background:white; float:right; border: 1px transparent;">
						<label style="padding-left: 0px;" class="mo_openid_checkbox_container_disable">
							<input type="checkbox" id="pmp_after_checkout_page_username" name="pmp_after_checkout_page_username" />
							<div style="padding-left: 7%">
								<?php echo esc_attr( mo_sl( 'After Paid Memberships Pro Checkout Page Username' ) ); ?>
							</div>
							<span class="mo_openid_checkbox_checkmark_disable"></span>
							<div class="mo_openid_pmp_box">
								<br>
								<img style="width: 100%; height: 100%" class="mo_openid_pmp_images" src="<?php echo esc_url( plugin_dir_url( dirname( __DIR__ ) ) ) . 'includes/images/pmp/pmp_3.png'; ?>">
							</div>
						</label>
					</div>
				</div>
				<br>
				<input style="width: 126px;margin-left: 1%; margin-top:33%" disabled type="button" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?> "  class="button button-primary button-large" />
				<br>
			</form>
			<br><br>

			<div class="mo_openid_highlight">
				<h3 style="margin-left: 2%;line-height: 210%;color: white;"><?php echo esc_attr( mo_sl( 'Paid Membership Levels Integration' ) ); ?></h3>
			</div>
			<form>
				<br>
				<div style="margin-left: 1.5%">
					<table>
						<tr>
							<div class="mo-openid-ppm-img">
								<img width="70%" src="<?php echo esc_url( plugin_dir_url( dirname( dirname( __FILE__ ) ) ) ); ?>includes/images/pmp/pmp_4.png">
							</div>
						</tr>
						<tr>
							<label class="mo_openid_note_style" style="cursor: auto">For more information please refer to the <a href="https://plugins.miniorange.com/guide-to-configure-paid-membership-pro-with-wordpress-social-login" target="_blank">Paid Membership Pro Guide</a>  /  <a href="https://youtu.be/DHgIR6kyX3A" target="_blank">Paid Membership Pro Video</a></label>
						</tr>
					</table>
				</div>
			</form>
			<td>

			</td>
			<?php
	}
}
