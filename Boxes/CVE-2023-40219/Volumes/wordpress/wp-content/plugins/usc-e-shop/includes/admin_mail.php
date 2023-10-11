<?php
/**
 * Mail settings.
 *
 * @package  Welcart
 * @since    2.3
 */

$mail_datas          = usces_mail_data();
$smtp_hostname       = ( ! isset( $this->options['smtp_hostname'] ) || empty( $this->options['smtp_hostname'] ) ) ? 'localhost' : $this->options['smtp_hostname'];
if ( isset( $this->options['newmem_admin_mail'] ) && 1 == $this->options['newmem_admin_mail'] ) {
	$newmem_admin_mail_0 = '';
	$newmem_admin_mail_1 = ' checked="checked"';
} else {
	$newmem_admin_mail_0 = ' checked="checked"';
	$newmem_admin_mail_1 = '';
}
if ( isset( $this->options['updmem_admin_mail'] ) && 1 == $this->options['updmem_admin_mail'] ) {
	$updmem_admin_mail_0 = '';
	$updmem_admin_mail_1 = ' checked="checked"';
} else {
	$updmem_admin_mail_0 = ' checked="checked"';
	$updmem_admin_mail_1 = '';
}
if ( isset( $this->options['updmem_customer_mail'] ) && 1 == $this->options['updmem_customer_mail'] ) {
	$updmem_customer_mail_0 = '';
	$updmem_customer_mail_1 = ' checked="checked"';
} else {
	$updmem_customer_mail_0 = ' checked="checked"';
	$updmem_customer_mail_1 = '';
}
if ( isset( $this->options['delmem_admin_mail'] ) && 1 == $this->options['delmem_admin_mail'] ) {
	$delmem_admin_mail_0 = '';
	$delmem_admin_mail_1 = ' checked="checked"';
} else {
	$delmem_admin_mail_0 = ' checked="checked"';
	$delmem_admin_mail_1 = '';
}
if ( isset( $this->options['delmem_customer_mail'] ) && 1 == $this->options['delmem_customer_mail'] ) {
	$delmem_customer_mail_0 = '';
	$delmem_customer_mail_1 = ' checked="checked"';
} else {
	$delmem_customer_mail_0 = ' checked="checked"';
	$delmem_customer_mail_1 = '';
}
if ( isset( $this->options['put_customer_name'] ) && 1 == $this->options['put_customer_name'] ) {
	$put_customer_name_0 = '';
	$put_customer_name_1 = ' checked="checked"';
} else {
	$put_customer_name_0 = ' checked="checked"';
	$put_customer_name_1 = '';
}
if ( isset( $this->options['email_attach_feature'] ) && 1 == $this->options['email_attach_feature'] ) {
	$email_attach_feature_0 = '';
	$email_attach_feature_1 = ' checked="checked"';
} else {
	$email_attach_feature_0 = ' checked="checked"';
	$email_attach_feature_1 = '';
}
if ( isset( $this->options['add_html_email_option'] ) && 1 == $this->options['add_html_email_option'] ) {
	$add_html_email_option_0 = '';
	$add_html_email_option_1 = ' checked="checked"';
	$add_html_email_option   = true;
} else {
	$add_html_email_option_0 = ' checked="checked"';
	$add_html_email_option_1 = '';
	$add_html_email_option   = false;
}
$put_email_attach_file_extension = ( ! isset( $this->options['email_attach_file_extension'] ) || empty( $this->options['email_attach_file_extension'] ) ) ? 'jpg,png,pdf' : $this->options['email_attach_file_extension'];
$put_email_attach_file_size      = ( ! isset( $this->options['email_attach_file_size'] ) || empty( $this->options['email_attach_file_size'] ) ) ? 3 : $this->options['email_attach_file_size'];
?>
<?php if ( $add_html_email_option ) { ?>
<style>
	.wp-editor-wrap {
		margin-top: 10px;
		background-color: #f0f0f1;
	}
	#dialog_parent {z-index: 100}
	#wrap_icon_loading {
		display: inline-block;
		padding-left: 10px;
	}
</style>
<?php } ?>
<script type="text/javascript">
jQuery( function($) {
	$( "#uscestabs_mail" ).tabs({
		active: ( $.cookie( "uscestabs_mail" ) ) ? $.cookie( "uscestabs_mail" ) : 0
		, activate: function( event, ui ) {
			$.cookie( "uscestabs_mail", $( this ).tabs( "option", "active" ) );
		}
	});
<?php do_action( 'usces_action_mail_script' ); ?>
});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php _e( 'E-mail Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<div id="uscestabs_mail" class="uscestabs usces_mail">
	<ul>
		<li><a href="#uscestabs_mail_top"><?php _e( 'Mail Options', 'usces' ); ?></a></li>
		<li><a href="#uscestabs_mail_auto"><?php _e( 'Automatic emails', 'usces' ); ?></a></li>
		<li><a href="#uscestabs_mail_manual"><?php _e( 'Manual transmission emails', 'usces' ); ?></a></li>
		<li><a href="#uscestabs_mail_custom"><?php _e( 'Other emails', 'usces' ); ?></a></li>
		<?php do_action( 'usces_action_mail_tab_title' ); ?>
	</ul>

	<div id="uscestabs_mail_top">
		<form action="" method="post" name="option_form_top" id="option_form_top">
			<!-- SMTP hostname -->
			<div class="postbox">
			<h3><span><?php _e('SMTP server host','usces'); ?></span><a class="explanation-label" id="label_ex_smtp_host"> (<?php _e('explanation', 'usces'); ?>) </a></h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Host name','usces'); ?></th>
					<td><input name="smtp_hostname" id="smtp_hostname" type="text" class="mail_title" value="<?php echo esc_attr( $smtp_hostname ); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_smtp_host" class="explanation"><?php _e('This is a field setting the host name of a server for email transmission of a message. When the transmission of a message is not possible in localhost, a SMTP server is necessary.','usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Options -->
			<div class="postbox">
			<h3><span><?php _e('Mail Options','usces'); ?></span><a class="explanation-label" id="label_ex_mail_options"> (<?php _e('explanation', 'usces'); ?>) </a></h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_newmem_admin_mail"><?php _e('New sign-in completion email','usces'); ?></a></th>
					<td width="10"><input name="newmem_admin_mail" type="radio" id="newmem_admin_mail_0" value="0"<?php echo esc_attr( $newmem_admin_mail_0 ); ?> /></td>
					<td width="100"><label for="newmem_admin_mail_0"><?php _e("Don't send",'usces'); ?></label></td>
					<td width="10"><input name="newmem_admin_mail" type="radio" id="newmem_admin_mail_1" value="1"<?php echo esc_attr( $newmem_admin_mail_1 ); ?> /></td>
					<td width="100"><label for="newmem_admin_mail_1"><?php _e("Send",'usces'); ?></label></td>
					<td><div id="ex_newmem_admin_mail" class="explanation"><?php _e('When there is a new sign-in, transmit a report email to a manager.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_updmem_admin_mail"><?php _e('Member update completion email to admin','usces'); ?></a></th>
					<td width="10"><input name="updmem_admin_mail" type="radio" id="updmem_admin_mail_0" value="0"<?php echo esc_attr( $updmem_admin_mail_0 ); ?> /></td>
					<td width="100"><label for="updmem_admin_mail_0"><?php _e("Don't send",'usces'); ?></label></td>
					<td width="10"><input name="updmem_admin_mail" type="radio" id="updmem_admin_mail_1" value="1"<?php echo esc_attr( $updmem_admin_mail_1 ); ?> /></td>
					<td width="100"><label for="updmem_admin_mail_1"><?php _e("Send",'usces'); ?></label></td>
					<td><div id="ex_updmem_admin_mail" class="explanation"><?php _e('When the member information has been changed, transmit a report mail to the manager.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_updmem_customer_mail"><?php _e('Member update completion email to customer','usces'); ?></a></th>
					<td width="10"><input name="updmem_customer_mail" type="radio" id="updmem_customer_mail_0" value="0"<?php echo esc_attr( $updmem_customer_mail_0 ); ?> /></td>
					<td width="100"><label for="updmem_customer_mail_0"><?php _e("Don't send",'usces'); ?></label></td>
					<td width="10"><input name="updmem_customer_mail" type="radio" id="updmem_customer_mail_1" value="1"<?php echo esc_attr( $updmem_customer_mail_1 ); ?> /></td>
					<td width="100"><label for="updmem_customer_mail_1"><?php _e("Send",'usces'); ?></label></td>
					<td><div id="ex_updmem_customer_mail" class="explanation"><?php _e('When the member information has been changed, , transmit a guide mail to customer.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_delmem_admin_mail"><?php _e('Member removal completion email to admin','usces'); ?></a></th>
					<td width="10"><input name="delmem_admin_mail" type="radio" id="delmem_admin_mail_0" value="0"<?php echo esc_attr( $delmem_admin_mail_0 ); ?> /></td>
					<td width="100"><label for="delmem_admin_mail_0"><?php _e("Don't send",'usces'); ?></label></td>
					<td width="10"><input name="delmem_admin_mail" type="radio" id="delmem_admin_mail_1" value="1"<?php echo esc_attr( $delmem_admin_mail_1 ); ?> /></td>
					<td width="100"><label for="delmem_admin_mail_1"><?php _e("Send",'usces'); ?></label></td>
					<td><div id="ex_delmem_admin_mail" class="explanation"><?php _e('When a member is unsubscribed, transmit a report mail to the manager.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_delmem_customer_mail"><?php _e('Member removal completion email to customer','usces'); ?></a></th>
					<td width="10"><input name="delmem_customer_mail" type="radio" id="delmem_customer_mail_0" value="0"<?php echo esc_attr( $delmem_customer_mail_0 ); ?> /></td>
					<td width="100"><label for="delmem_customer_mail_0"><?php _e("Don't send",'usces'); ?></label></td>
					<td width="10"><input name="delmem_customer_mail" type="radio" id="delmem_customer_mail_1" value="1"<?php echo esc_attr( $delmem_customer_mail_1 ); ?> /></td>
					<td width="100"><label for="delmem_customer_mail_1"><?php _e("Send",'usces'); ?></label></td>
					<td><div id="ex_delmem_customer_mail" class="explanation"><?php _e('When a member is unsubscribed, transmit a guide mail to customer.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_put_customer_name"><?php _e('Customer name','usces'); ?></a></th>
					<td width="10"><input name="put_customer_name" type="radio" id="put_customer_name_0" value="0"<?php echo esc_attr( $put_customer_name_0 ); ?> /></td>
					<td width="100"><label for="put_customer_name_0"><?php _e("Non-indication",'usces'); ?></label></td>
					<td width="10"><input name="put_customer_name" type="radio" id="put_customer_name_1" value="1"<?php echo esc_attr( $put_customer_name_1 ); ?> /></td>
					<td width="100"><label for="put_customer_name_1"><?php _e("Indication",'usces'); ?></label></td>
					<td><div id="ex_put_customer_name" class="explanation"><?php _e('At the beginning of the mail text, put customer name.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_email_attach_feature"><?php _e('Attachment file for administrator email','usces'); ?></a></th>
					<td width="10"><input name="email_attach_feature" type="radio" id="email_attach_feature_0" value="0"<?php echo esc_attr( $email_attach_feature_0 ); ?> /></td>
					<td width="100"><label for="email_attach_feature_0"><?php _e("Do not Use",'usces'); ?></label></td>
					<td width="10"><input name="email_attach_feature" type="radio" id="email_attach_feature_1" value="1"<?php echo esc_attr( $email_attach_feature_1 ); ?> /></td>
					<td width="100"><label for="email_attach_feature_1"><?php _e("Use",'usces'); ?></label></td>
					<td><div id="ex_email_attach_feature" class="explanation"><?php _e('A file can be attached to the management e-mail sent from the order data edit page.', 'usces'); ?></div></td>
				</tr>
				<tr class="email_attach_feature">
					<th width="150"><a class="explanation-label" id="label_ex_put_file_extension"><?php _e('Extensions of attachments', 'usces'); ?></a></th>
					<td colspan="4"><input name="email_attach_file_extension" id="email_attach_file_extension" type="text" class="regular-text" value="<?php echo esc_attr($put_email_attach_file_extension); ?>" /></td>
					<td><div id="ex_put_file_extension" class="explanation"><?php _e('Enter the file extension of the email attachment (jpg, png, pdf, etc.) separated by commas.', 'usces'); ?><?php _e('If not entered, the extension will not be checked.', 'usces'); ?></div></td>
				</tr>
				<tr class="email_attach_feature">
					<th width="150"><a class="explanation-label" id="label_ex_put_file_size"><?php _e('Maximum size of email attachment', 'usces'); ?></a></th>
					<td colspan="4"><input name="email_attach_file_size" id="email_attach_file_size" type="number" min="0" step="1" class="small-text" value="<?php echo esc_attr($put_email_attach_file_size); ?>" />Mb</td>
					<td><div id="ex_put_file_size" class="explanation"><?php _e('Enter the maximum file size (MB) of the email attached file.', 'usces'); ?><?php _e('If not entered, the file size is not checked.', 'usces'); ?></div></td>
				</tr>
				<tr>
					<th width="150"><a class="explanation-label" id="label_ex_add_html_email_option"><?php _e( 'HTML mail format', 'usces' ); ?></a></th>
					<td width="10"><input name="add_html_email_option" type="radio" id="add_html_email_option_0" value="0"<?php echo esc_attr( $add_html_email_option_0 ); ?> /></td>
					<td width="100"><label for="add_html_email_option_0"><?php _e("Do not Use",'usces'); ?></label></td>
					<td width="10"><input name="add_html_email_option" type="radio" id="add_html_email_option_1" value="1"<?php echo esc_attr( $add_html_email_option_1 ); ?> /></td>
					<td width="100"><label for="add_html_email_option_1"><?php _e("Use",'usces'); ?></label></td>
					<td><div id="ex_add_html_email_option" class="explanation"><?php _e('Send e-mails in HTML format.', 'usces'); ?></div></td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_mail_options" class="explanation"><?php _e('Notification mail transmission setting, other.','usces'); ?></div>
			</div>
			</div><!--postbox-->
			<input name="usces_option_update_top" id="usces_option_update_mail_top" type="submit" class="button button-primary" value="<?php _e( 'change decision', 'usces' ); ?>" />
			<?php wp_nonce_field( 'admin_mail', 'wc_nonce' ); ?>
			<?php do_action( 'usces_action_admin_mailoption' ); ?>
		</form>
	</div><!-- uscestabs_mail_top -->

	<div id="uscestabs_mail_auto">
		<form action="" method="post" name="option_form_auto" id="option_form_auto">
			<!-- Thankyou mail -->
			<div class="postbox">
			<h3>
				<span id="title_thankyou"><?php _e('Thanks email(automatic transmission)', 'usces'); ?></span><a class="explanation-label" id="label_ex_thakyou_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('thankyou')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[thankyou]" id="title[thankyou]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['thankyou']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['thankyou'],
							'headerthankyou',
							array(
								'tabfocus_elements' => ':next',
								'dfw'               => true,
								'textarea_name'     => 'header[thankyou]',
								'textarea_rows'     => 10,
								'editor_class'      => 'mail_header_html',
								'editor_height'     => '234'
							)
						);
					} else {
						?>
							<textarea name="header[thankyou]" id="header[thankyou]" class="mail_header"><?php echo esc_html( $mail_datas['header']['thankyou'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['thankyou'],
							'footerthankyou',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[thankyou]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
						<textarea name="footer[thankyou]" id="footer[thankyou]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['thankyou'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_thakyou_mail" class="explanation"><?php _e('This is an email transmitting a message for a visitor at the time of an order.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Order mail -->
			<div class="postbox">
			<h3>
				<span id="title_order"><?php _e('Order email(automatic transmission)', 'usces'); ?></span><a class="explanation-label" id="label_ex_order_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('order')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[order]" id="title[order]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['order']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['order'],
							'headerorder',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[order]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[order]" id="header[order]" class="mail_header"><?php echo esc_html( $mail_datas['header']['order'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['order'],
							'footerorder',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[order]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[order]" id="footer[order]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['order'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_order_mail" class="explanation"><?php echo sprintf(__('This is an email transmitting a message for owner of shop(%s).', 'usces'), $this->options['order_mail']); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Inquiry receipt mail -->
			<div class="postbox">
			<h3>
				<span id="title_inquiry"><?php _e('Inquiry receptionist email(automatic transmission)', 'usces'); ?></span><a class="explanation-label" id="label_ex_inquiry_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('inquiry')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[inquiry]" id="title[inquiry]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['inquiry']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['inquiry'],
							'headerinquiry',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[inquiry]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[inquiry]" id="header[inquiry]" class="mail_header"><?php echo esc_html( $mail_datas['header']['inquiry'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['inquiry'],
							'footerinquiry',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[inquiry]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[inquiry]" id="footer[inquiry]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['inquiry'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_inquiry_mail" class="explanation"><?php _e('This is an e-mail which will be sent aytomatically to your customers at the contact from customer.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Membership completion mail -->
			<div class="postbox">
			<h3>
				<span id="title_membercomp"><?php _e('Conformation e-mail of membership registeration.', 'usces'); ?></span><a class="explanation-label" id="label_ex_membercomp_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('membercomp')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[membercomp]" id="title[membercomp]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['membercomp']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['membercomp'],
							'headermembercomp',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[membercomp]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[membercomp]" id="header[membercomp]" class="mail_header"><?php echo esc_html( $mail_datas['header']['membercomp'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['membercomp'],
							'footermembercomp',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[membercomp]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[membercomp]" id="footer[membercomp]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['membercomp'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_membercomp_mail" class="explanation"><?php _e('This is an e-mail which will be snnt automatically to your customers when their membership registration is completed.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<?php do_action( 'usces_action_admin_mailoption_auto' ); ?>
            <input name="usces_option_update_auto" id="usces_option_update_mail_auto" type="submit" class="button button-primary" value="<?php _e( 'change decision', 'usces' ); ?>" />
			<?php wp_nonce_field( 'admin_mail', 'wc_nonce' ); ?>
		</form>
	</div><!-- uscestabs_mail_auto -->

	<div id="uscestabs_mail_manual">
		<form action="" method="post" name="option_form_manual" id="option_form_manual">
			<!-- Shipping completion mail -->
			<div class="postbox">
			<h3>
				<span id="title_completionmail"><?php _e('Shipping complete email (sent from the admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_completionmail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('completionmail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[completionmail]" id="title[completionmail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['completionmail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['completionmail'],
							'headercompletionmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[completionmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[completionmail]" id="header[completionmail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['completionmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['completionmail'],
							'footercompletionmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[completionmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[completionmail]" id="footer[completionmail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['completionmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_completionmail_mail" class="explanation"><?php _e('This is an email transmitted manual operation to than a management screen, when the shipment of the article was completed.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Order confirmation mail -->
			<div class="postbox">
			<h3>
				<span id="title_ordermail"><?php _e('Order confirmation email (sent from the admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_ordermail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('ordermail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[ordermail]" id="title[ordermail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['ordermail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['ordermail'],
							'headerordermail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[ordermail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[ordermail]" id="header[ordermail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['ordermail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['ordermail'],
							'footerordermail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[ordermail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[ordermail]" id="footer[ordermail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['ordermail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_ordermail_mail" class="explanation"><?php _e('This is an email transmitted manual operation to than a management screen, when you registered a new order.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Order change confirmation mail -->
			<div class="postbox">
			<h3>
				<span id="title_changemail"><?php _e('Confirmation mail for change of orders. (sent by admin.)', 'usces'); ?></span><a class="explanation-label" id="label_ex_changemail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('changemail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[changemail]" id="title[changemail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['changemail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['changemail'],
							'headerchangemail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[changemail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[changemail]" id="header[changemail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['changemail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['changemail'],
							'footerchangemail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[changemail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[changemail]" id="footer[changemail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['changemail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_changemail_mail" class="explanation"><?php _e('This is an e-mail which will be sent manually from admin screen, when there is changes of order.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Payment confirmation mail -->
			<div class="postbox">
			<h3>
				<span id="title_receiptmail"><?php _e('Mail for Confirmation of payment. ( sent from admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_receiptmail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('receiptmail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[receiptmail]" id="title[receiptmail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['receiptmail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['receiptmail'],
							'headerreceiptmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[receiptmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[receiptmail]" id="header[receiptmail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['receiptmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['receiptmail'],
							'footerreceiptmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[receiptmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else { 
						?>
							<textarea name="footer[receiptmail]" id="footer[receiptmail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['receiptmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_receiptmail_mail" class="explanation"><?php _e('This is an e-mail which will be sent to customers when their transfer payment is confirmed.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Quotation mail -->
			<div class="postbox">
			<h3>
				<span id="title_mitumorimail"><?php _e('Estimate email (sent from the admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_mitumorimail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('mitumorimail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[mitumorimail]" id="title[mitumorimail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['mitumorimail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['mitumorimail'],
							'headermitumorimail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[mitumorimail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[mitumorimail]" id="header[mitumorimail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['mitumorimail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['mitumorimail'],
							'footermitumorimail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[mitumorimail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[mitumorimail]" id="footer[mitumorimail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['mitumorimail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_mitumorimail_mail" class="explanation"><?php _e('This is an email transmitted manual operation to than a management screen, when you  registered an estimate.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<!-- Cancellation confirmation mail -->
			<div class="postbox">
			<h3>
				<span id="title_cancelmail"><?php _e('E-mail for confirmation of cancellation. (sent from admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_cancelmail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('cancelmail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[cancelmail]" id="title[cancelmail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['cancelmail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['cancelmail'],
							'headercancelmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[cancelmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[cancelmail]" id="header[cancelmail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['cancelmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['cancelmail'],
							'footercancelmail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[cancelmail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[cancelmail]" id="footer[cancelmail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['cancelmail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_cancelmail_mail" class="explanation"><?php _e('This is an e-mail which will be sent when order has been canselled.', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<?php do_action( 'usces_action_admin_mailoption_manual' ); ?>
            <input name="usces_option_update_manual" id="usces_option_update_mail_manual" type="submit" class="button button-primary" value="<?php _e( 'change decision', 'usces' ); ?>" />
			<?php wp_nonce_field( 'admin_mail', 'wc_nonce' ); ?>
		</form>
	</div><!-- uscestabs_mail_manual -->

	<div id="uscestabs_mail_custom">
		<form action="" method="post" name="option_form" id="option_form">
			<!-- Other mail -->
			<div class="postbox">
			<h3>
				<span id="title_othermail"><?php _e('Other e-mails(sent from admin)', 'usces'); ?></span><a class="explanation-label" id="label_ex_othermail_mail"> (<?php _e('explanation', 'usces'); ?>) </a>
				<?php if ( $add_html_email_option ) { ?>
					<button type="button" class="email-preview button-primary" onclick="showPopupPreviewEmail('othermail')"><?php esc_attr_e( 'Preview', 'usces' ); ?></button>
				<?php } ?>
			</h3>
			<div class="inside">
			<table class="form_table">
				<tr>
					<th width="150"><?php _e('Title', 'usces'); ?></th>
					<td><input name="title[othermail]" id="title[othermail]" type="text" class="mail_title" value="<?php echo esc_attr($mail_datas['title']['othermail']); ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('header', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['header']['othermail'],
							'headerothermail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'header[othermail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_header_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="header[othermail]" id="header[othermail]" class="mail_header"><?php echo esc_html( $mail_datas['header']['othermail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th><?php _e('footer', 'usces'); ?></th>
					<td>
					<?php
					if ( $add_html_email_option ) {
						wp_editor(
							$mail_datas['footer']['othermail'],
							'footerothermail',
							array(
								'dfw'           => true,
								'tabindex'      => 1,
								'textarea_name' => 'footer[othermail]',
								'textarea_rows' => 10,
								'editor_class'  => 'mail_footer_html',
								'editor_height' => '234'
							)
						);
					} else {
						?>
							<textarea name="footer[othermail]" id="footer[othermail]" class="mail_footer"><?php echo esc_html( $mail_datas['footer']['othermail'] ); ?></textarea>
					<?php } ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<hr size="1" color="#CCCCCC" />
			<div id="ex_othermail_mail" class="explanation"><?php _e('e-mail which will be sent on temporaly basis', 'usces'); ?></div>
			</div>
			</div><!--postbox-->
			<?php do_action( 'usces_action_admin_mailform' ); ?>
			<input name="usces_option_update" id="usces_option_update_mail" type="submit" class="button button-primary" value="<?php _e( 'change decision', 'usces' ); ?>" />
			<?php wp_nonce_field( 'admin_mail', 'wc_nonce' ); ?>
		</form>
	</div><!-- uscestabs_mail_custom -->
	<?php do_action( 'usces_action_mail_tab_body' ); ?>
</div><!--uscestabs-->
</div><!--usces_admin-->
</div><!--wrap-->
<?php do_action( 'usces_action_auto_mailform' ); ?>
<?php if ( $add_html_email_option ) { ?>
<div id="dialog_parent" style="position:fixed"></div>
<div id="previewEmailDialog" title="">
	<iframe src="" width="760" height="1000" frameborder="0" class="content_email_preview" id="iframePreviewEmail"></iframe>
</div>
<script type='text/javascript'>
	jQuery(function($){
		$("#previewEmailDialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 650,
			width: 800,
			resizable: true,
			modal: true,
			buttons: [
				{
					text: "<?php esc_attr_e( 'close', 'usces' ); ?>",
					click: function() {    
						$(this).dialog('close');
					}
				}
			],
			appendTo:"#dialog_parent",
			close: function() {
				jQuery('#previewEmailDialog').dialog('option', 'title', "");
				var dstFrame = document.getElementById('iframePreviewEmail');
				var dstDoc = dstFrame.contentDocument || dstFrame.contentWindow.document;
				dstDoc.write("");
				dstDoc.close();
			}
		});
	});


usces_admin_mail = {
	settings: {
		url: uscesL10n.requestFile,
		type: 'POST',
		cache: false
	},
	filterContentbeforePreview: function( elEmail ) {
		var elEmailHeader = 'header'+elEmail;
		var elEmailfooter = 'footer'+elEmail;
		var elTitleDialog = 'title_'+elEmail;
		var parent_preview = jQuery("#"+elTitleDialog).parent();
		var img_loading = '<div id="wrap_icon_loading"><img src="' + uscesL10n.USCES_PLUGIN_URL + 'images/loading.gif" /></div>';
		var element_preview = jQuery(parent_preview).children(".email-preview");
		jQuery(img_loading).insertAfter(element_preview);
		jQuery(element_preview).prop("disabled", true);
		var titleDialog = "<?php esc_attr_e( 'Preview', 'usces' ); ?>: " + jQuery("#"+elTitleDialog).text();
		var contentEmailHeader = '';
		var contentEmailFooter = '';
		if (typeof tinymce !== 'undefined' && tinymce.get(elEmailHeader)) {
			if ( jQuery("#"+elEmailHeader).is(':visible') ) {
				// TEXT TAB active.
				contentEmailHeader = jQuery("#"+elEmailHeader).val();
			} else {
				// VISUAL TAB active.
				contentEmailHeader = tinymce.get(elEmailHeader).getContent();
			}
		} else {
			contentEmailHeader = jQuery("#"+elEmailHeader).val();
		}
		if (typeof tinymce !== 'undefined' && tinymce.get(elEmailfooter)) {
			if ( jQuery("#"+elEmailHeader).is(':visible') ) {
				// TEXT TAB active.
				contentEmailFooter = jQuery("#"+elEmailfooter).val();
			} else {
				// VISUAL TAB active.
				contentEmailFooter = tinymce.get(elEmailfooter).getContent();
			}
		} else {
			contentEmailFooter = jQuery("#"+elEmailfooter).val();
		}
		var s = usces_admin_mail.settings;
		s.data = {
			'action': 'usces_filter_content_wp_editor_preview',
			'mode': 'preview_email',
			'wc_nonce': '<?php echo wp_create_nonce( 'wc_preview_editor_nonce' ); ?>',
			'content_header': contentEmailHeader.replace(/<script>/g,'&lt;script&gt;').replace(/<\/script>/g,'&lt;/script&gt;'),
			'content_footer': contentEmailFooter.replace(/<script>/g,'&lt;script&gt;').replace(/<\/script>/g,'&lt;/script&gt;'),
		};
		jQuery.ajax(s).done(function(res) {
			jQuery("#wrap_icon_loading").remove();
			if (res && res.status) {
				var content = usces_admin_mail.contentHtmlShow( res.content_header, res.content_footer );
				var dstFrame = document.getElementById('iframePreviewEmail');
				var dstDoc = dstFrame.contentDocument || dstFrame.contentWindow.document;
				dstDoc.write(content);
				dstDoc.close();

				jQuery('#previewEmailDialog').dialog('option', 'title', titleDialog);
				jQuery('#previewEmailDialog').dialog('open');
			} else {
				usces_admin_mail.contentPreviewErrorShow( '<?php _e( 'Failure preview email.', 'usces' ); ?>' );
			}
			jQuery(element_preview).removeAttr('disabled');
		}).fail(function(msg) {
			jQuery("#wrap_icon_loading").remove();
			jQuery(element_preview).removeAttr('disabled');
			usces_admin_mail.contentPreviewErrorShow( msg );
		});
	},
	contentHtmlShow: function( contentEmailHeader, contentEmailFooter ) {
		var content = '<table bgcolor="#eeeeee" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td>';
		content += '<table style="font-size: 15px; margin-right:auto;margin-left:auto;" bgcolor="#ffffff" width="600" cellpadding="0" cellspacing="0" border="0" align="center">';
		content += '<tr><td style="padding:20px 30px;">';
		content += contentEmailHeader;
		content += '</td></tr>';
		content += '<tr><td style="padding:20px 30px;">';
		content += '<strong>';
		content += "<?php esc_attr_e( '- Order details -', 'usces' ); ?>";
		content += '</strong>';
		content += '</td></tr>';
		content += '<tr><td style="padding:20px 30px;">';
		content += contentEmailFooter;
		content += '</td></tr></table></td></tr></table>';
		return content;
	},
	contentPreviewErrorShow: function( msg ) {
		var dstFrame = document.getElementById('iframePreviewEmail');
		var dstDoc = dstFrame.contentDocument || dstFrame.contentWindow.document;
		dstDoc.write( msg );
		dstDoc.close();
		jQuery('#previewEmailDialog').dialog('option', 'title', 'ERROR');
		jQuery('#previewEmailDialog').dialog('open');
	}
};

function showPopupPreviewEmail( elEmail ) {
	usces_admin_mail.filterContentbeforePreview( elEmail );
}
</script>
<?php } ?>
