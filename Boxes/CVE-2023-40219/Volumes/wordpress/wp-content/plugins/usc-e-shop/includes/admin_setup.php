<?php
/**
 * Admin - Basic Setting Page.
 *
 * @package Welcart
 */

?>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'General Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<form action="" method="post" name="option_form" id="option_form">
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<div id="poststuff" class="metabox-holder">

<div class="postbox">
<h3><span><?php esc_html_e( 'Business settings', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('business_setting');"> (<?php esc_html_e( 'explanation', 'usces' ); ?>) </a></h3>
<div class="inside">
<table class="form_table">
<?php
if ( $this->display_mode ) :
	?>
	<tr height="50">
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_display_mode');"><?php esc_html_e( 'Display Modes', 'usces' ); ?></a></th>
	<?php
	foreach ( (array) $this->display_mode as $key => $label ) :
		if ( 'Promotionsale' == $key ) {
			continue;
		}
		?>
		<td width="10"><input name="display_mode" type="radio" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php checked( $this->options['display_mode'], $key ); ?> /></td>
		<td width="100"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></td>
		<?php
	endforeach;
	?>
		<td><div id="ex_display_mode" class="explanation">
		<?php echo wp_kses_post( __( '<strong>Normal operating</strong> -Normal display', 'usces' ) ); ?><br />
		<?php echo wp_kses_post( __( '<strong>Maintenance</strong> ---Showing  maintenance page. Administrater is able to see the page with normal display.', 'usces' ) ); ?></div>
		</td>
	</tr>
<?php endif; ?>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_cat');"><?php esc_html_e( 'Campaign target items', 'usces' ); ?></a></th>
		<td>
<?php
$dropdown_selected = ( USCES_ITEM_CAT_PARENT_ID == $this->options['campaign_category'] ) ? 0 : $this->options['campaign_category'];
$dropdown_options  = array(
	'show_option_all' => __( 'all the items', 'usces' ),
	'hide_empty'      => 0,
	'hierarchical'    => 1,
	'orderby'         => 'name',
	'child_of'        => USCES_ITEM_CAT_PARENT_ID,
	'selected'        => $dropdown_selected,
);
wp_dropdown_categories( $dropdown_options );
?>
		</td>
		<td><div id="ex_cat" class="explanation"><?php esc_html_e( "This is a category of the objects to add a privilege to in the case of a campaign mode. You can choose a campaign object article freely by installing a 'campaign' category for an article.", 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_cat_privilege');"><?php esc_html_e( 'Campaign Award', 'usces' ); ?></a></th>
		<td><input name="cat_privilege" type="radio" id="privilege_point" value="point"<?php checked( $this->options['campaign_privilege'], 'point' ); ?> /></td><td><label for="privilege_point"><?php esc_html_e( 'Points', 'usces' ); ?></label></td>
		<td><input name="point_num" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['privilege_point'] ); ?>" /><?php esc_html_e( 'times', 'usces' ); ?></td>
		<td rowspan="2"><div id="ex_cat_privilege" class="explanation"><?php echo wp_kses_post( __( "'Points' award applies only for monmbers. You specify the ratio of rate points. <br />'Discount'is specified in the discount rate applies to all buyers.", 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<td><input name="cat_privilege" type="radio" id="privilege_discount" value="discount"<?php checked( $this->options['campaign_privilege'], 'discount' ); ?> /></td><td><label for="privilege_discount"><?php esc_html_e( 'Discount', 'usces' ); ?></label></td>
		<td><input name="discount_num" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['privilege_discount'] ); ?>" />%</td>
	</tr>
</table>

<?php do_action( 'usces_action_amdin_setup_first_box' ); ?>

<hr />
<div id="business_setting" class="explanation"><?php esc_html_e( 'Configuration of management and display mode of shop.', 'usces' ); ?></div>
</div>
</div><!--postbox-->

<div class="postbox">
<h3><span><?php esc_html_e( 'Shop setting', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('shop_setting');"> (<?php esc_html_e( 'explanation', 'usces' ); ?>) </a></h3>
<div class="inside">
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_company_name');"><?php esc_html_e( 'The company name', 'usces' ); ?></a></th>
		<td><input name="company_name" type="text" class="long_str" value="<?php echo esc_attr( $this->options['company_name'] ); ?>" /></td>
		<td><div id="ex_company_name" class="explanation"><?php esc_html_e( 'Fill out this if you are a corporation.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Zip/Postal Code', 'usces' ); ?></th>
		<td><input name="zip_code" type="text" class="short_str" value="<?php echo esc_attr( $this->options['zip_code'] ); ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Address', 'usces' ); ?>1</th>
		<td><input name="address1" type="text" class="long_str" value="<?php echo esc_attr( $this->options['address1'] ); ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Address', 'usces' ); ?>2</th>
		<td><input name="address2" type="text" class="long_str" value="<?php echo esc_attr( $this->options['address2'] ); ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'Phone number', 'usces' ); ?></th>
		<td><input name="tel_number" type="text" class="long_str" value="<?php echo esc_attr( $this->options['tel_number'] ); ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<th><?php esc_html_e( 'FAX number', 'usces' ); ?></th>
		<td><input name="fax_number" type="text" class="long_str" value="<?php echo esc_attr( $this->options['fax_number'] ); ?>" /></td>
		<td></td>
	</tr>
	<tr>
		<th><em><?php esc_html_e( '*', 'usces' ); ?> </em><a style="cursor:pointer;" onclick="toggleVisibility('ex_order_mail');"><?php esc_html_e( 'E-mail address for ordering', 'usces' ); ?></a></th>
		<td><input name="order_mail" type="text" class="long_str" value="<?php echo esc_attr( $this->options['order_mail'] ); ?>" /></td>
		<td><div id="ex_order_mail" class="explanation"><?php echo wp_kses_post( __( "<em>[Required]</em> the administrator's e-mail address to receive the order", 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_inquiry_mail');"><?php esc_html_e( 'Contact e-mail address', 'usces' ); ?></a></th>
		<td><input name="inquiry_mail" type="text" class="long_str" value="<?php echo esc_attr( $this->options['inquiry_mail'] ); ?>" /></td>
		<td><div id="ex_inquiry_mail" class="explanation"><?php echo wp_kses_post( __( "<em>[Optional]</em> the administrator's e-mail address to receive the contact meils.", 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<th><em><?php esc_html_e( '*', 'usces' ); ?> </em><a style="cursor:pointer;" onclick="toggleVisibility('ex_sender_mail');"><?php esc_html_e( "Sender's e-mail address", 'usces' ); ?></a></th>
		<td><input name="sender_mail" type="text" class="long_str" value="<?php echo esc_attr( $this->options['sender_mail'] ); ?>" /></td>
		<td><div id="ex_sender_mail" class="explanation"><?php echo wp_kses_post( __( "<em>[Required]</em> the sender's e-mail address to send 'thank you e-mail' to cuscomers.", 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<th><em><?php esc_html_e( '*', 'usces' ); ?> </em><a style="cursor:pointer;" onclick="toggleVisibility('ex_error_mail');"><?php esc_html_e( 'Address for Error mail', 'usces' ); ?></a></th>
		<td><input name="error_mail" type="text" class="long_str" value="<?php echo esc_attr( $this->options['error_mail'] ); ?>" /></td>
		<td><div id="ex_error_mail" class="explanation"><?php echo wp_kses_post( __( '<em>[Required]</em>The transmission of a message ahead of the error email.', 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_copyright');"><?php esc_html_e( 'copy rights', 'usces' ); ?></a></th>
		<td><input name="copyright" type="text" class="long_str" value="<?php echo esc_attr( $this->options['copyright'] ); ?>" /></td>
		<td><div id="ex_copyright" class="explanation"><?php esc_html_e( 'Example)', 'usces' ); ?>Copyright(c) <?php echo esc_html( wp_date( 'Y' ) ); ?> Welcart.inc</div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_business_registration_number');"><?php esc_html_e( 'Business registration number', 'usces' ); ?></a></th>
		<td><input name="business_registration_number" type="text" class="long_str" value="<?php echo esc_attr( $this->options['business_registration_number'] ); ?>" /></td>
		<td><div id="ex_business_registration_number" class="explanation"><?php esc_html_e( 'Eligible invoice issuer registration number', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_postage_privilege');"><?php esc_html_e( 'Conditions for free shipping', 'usces' ); ?></a></th>
		<td><input name="postage_privilege" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['postage_privilege'] ); ?>" /><?php esc_html_e( 'Above', 'usces' ); ?></td>
		<td><div id="ex_postage_privilege" class="explanation"><?php esc_html_e( 'Total amount of items that will receive free shipping. Leave blank if not required.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_purchase_limit');"><?php esc_html_e( 'default limitation number of purchase', 'usces' ); ?></a></th>
		<td><input name="purchase_limit" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['purchase_limit'] ); ?>" /><?php esc_html_e( 'maximum amount', 'usces' ); ?></td>
		<td><div id="ex_purchase_limit" class="explanation"><?php esc_html_e( 'initial value at registration of items. Leave it blank if it is not necessary.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_shipping_rule');"><?php esc_html_e( 'initial value of date of sending out.', 'usces' ); ?></a></th>
		<td><select name="shipping_rule" class="short_select">
<?php foreach ( (array) $this->shipping_rule as $key => $label ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $this->options['shipping_rule'], $key ); ?>><?php echo esc_html( $label ); ?></option>
<?php endforeach; ?>
		</select></td>
		<td><div id="ex_shipping_rule" class="explanation"><?php esc_html_e( 'initial value at registration of items. Do not chose this if it does not apply to you.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_tax_display');"><?php esc_html_e( 'Tax display', 'usces' ); ?></a></th>
		<td width="10"><input name="tax_display" id="tax_display_activate" type="radio" value="activate"<?php checked( $this->options['tax_display'], 'activate' ); ?> /></td><td width="100"><label for="tax_display_activate"><?php esc_html_e( 'Indication', 'usces' ); ?></label></td>
		<td width="10"><input name="tax_display" id="tax_display_deactivate" type="radio" value="deactivate"<?php checked( $this->options['tax_display'], 'deactivate' ); ?> /></td><td width="100"><label for="tax_display_deactivate"><?php esc_html_e( 'Non-indication', 'usces' ); ?></label></td>
		<td><div id="ex_tax_display" class="explanation"><?php esc_html_e( "When you select the 'Non-indication', does not Calculation and display the amount of tax.", 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_tax_mode');"><?php esc_html_e( 'Tax treatment', 'usces' ); ?></a></th>
		<td width="10"><input name="tax_mode" id="tax_mode_include" type="radio" value="include"<?php checked( $this->options['tax_mode'], 'include' ); ?> /></td><td width="100"><label for="tax_mode_include"><?php esc_html_e( 'Included', 'usces' ); ?></label></td>
		<td width="10"><input name="tax_mode" id="tax_mode_exclude" type="radio" value="exclude"<?php checked( $this->options['tax_mode'], 'exclude' ); ?> /></td><td width="100"><label for="tax_mode_exclude"><?php esc_html_e( 'Excluded', 'usces' ); ?></label></td>
		<td><div id="ex_tax_mode" class="explanation"><?php esc_html_e( 'You can choose consumption tax is whether it is included in the product price.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_tax_target');"><?php esc_html_e( 'Tax target', 'usces' ); ?></a></th>
		<td width="10"><input name="tax_target" id="tax_mode_products" type="radio" value="products"<?php checked( $this->options['tax_target'], 'products' ); ?> /></td><td width="60"><label for="tax_mode_products"><?php esc_html_e( 'Only Products', 'usces' ); ?></label></td>
		<td width="10"><input name="tax_target" id="tax_mode_all" type="radio" value="all"<?php checked( $this->options['tax_target'], 'all' ); ?> /></td><td width="60"><label for="tax_mode_all"><?php esc_html_e( 'All Amount', 'usces' ); ?></label></td>
		<td><div id="ex_tax_target" class="explanation"><?php esc_html_e( 'You can select the subject of the consumption tax. One is only the product price, and the other contains all the commission.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_applicable_taxrate');"><?php esc_html_e( 'Applicable tax rate', 'usces' ); ?></a></th>
		<td width="10"><input name="applicable_taxrate" id="applicable_taxrate_standard" type="radio" value="standard"<?php checked( $this->options['applicable_taxrate'], 'standard' ); ?> /></td><td width="60"><label for="applicable_taxrate_standard"><?php esc_html_e( 'Standard tax rate', 'usces' ); ?></label></td>
		<td width="10"><input name="applicable_taxrate" id="applicable_taxrate_reduced" type="radio" value="reduced"<?php checked( $this->options['applicable_taxrate'], 'reduced' ); ?> /></td><td width="60"><label for="applicable_taxrate_reduced"><?php esc_html_e( 'Reduced tax rate', 'usces' ); ?></label></td>
		<td><div id="ex_applicable_taxrate" class="explanation"><?php esc_html_e( 'Select whether to applying reduced tax rate.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_tax_rate');"><?php esc_html_e( 'Percentage of Consumption tax', 'usces' ); ?></a></th>
		<td><input name="tax_rate" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['tax_rate'] ); ?>" />%</td>
		<td><div id="ex_tax_rate" class="explanation"><?php esc_html_e( 'Entry required.', 'usces' ); ?></div></td>
	</tr>
	<tr id="tax_rate_reduced">
		<th><a style="cursor:pointer;" onclick="toggleVisibility( 'ex_tax_rate_reduced' );"><?php esc_html_e( 'Reduced tax rate', 'usces' ); ?></a></th>
		<td><input name="tax_rate_reduced" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['tax_rate_reduced'] ); ?>" />%</td>
		<td><div id="ex_tax_rate_reduced" class="explanation"><?php esc_html_e( 'Entry required when applying reduced tax rate.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><?php esc_html_e( 'method of Calculation of the tax', 'usces' ); ?></th>
		<td width="10"><input name="tax_method" id="tax_method_cutting" type="radio" value="cutting"<?php checked( $this->options['tax_method'], 'cutting' ); ?> /></td><td width="60"><label for="tax_method_cutting"><?php esc_html_e( 'drop fractions', 'usces' ); ?></label></td>
		<td width="10"><input name="tax_method" id="tax_method_bring" type="radio" value="bring"<?php checked( $this->options['tax_method'], 'bring' ); ?> /></td><td width="60"><label for="tax_method_bring"><?php esc_html_e( 'raise to a unit', 'usces' ); ?></label></td>
		<td width="10"><input name="tax_method" id="tax_method_rounding" type="radio" value="rounding"<?php checked( $this->options['tax_method'], 'rounding' ); ?> /></td><td width="60"><label for="tax_method_rounding"><?php esc_html_e( 'round up numbers of five and above and round down anything under', 'usces' ); ?></label></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_cod_fee');"><?php esc_html_e( 'C.O.D', 'usces' ); ?></a></th>
		<td id="cod_type_field" colspan="2"></td><td colspan="2"><input type="button" class="button" name="button_cod_detail" value="<?php esc_attr_e( 'Detailed setting', 'usces' ); ?>" id="detailed_setting" /></td>
		<td><div id="ex_cod_fee" class="explanation"><?php esc_html_e( 'Cost for C.O.D. leave it blank if it in not necessary.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_fee_subject');"><?php esc_html_e( 'Subject to fees', 'usces' ); ?></a></th>
		<td width="10"><input name="fee_subject" id="fee_subject_products" type="radio" value="products"<?php checked( $this->options['fee_subject'], 'products' ); ?> /></td><td width="100"><label for="fee_subject_products"><?php esc_html_e( 'Only Products', 'usces' ); ?></label></td>
		<td width="10"><input name="fee_subject" id="fee_subject_all" type="radio" value="all"<?php checked( $this->options['fee_subject'], 'all' ); ?> /></td><td width="100"><label for="fee_subject_all"><?php esc_html_e( 'All Amount', 'usces' ); ?></label></td>
		<td><div id="ex_fee_subject" class="explanation"><?php esc_html_e( 'Select whether you want the fee to be applied to the product price only or to the total amount including shipping charges.', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_transferee');"><?php esc_html_e( 'Account information for transfer', 'usces' ); ?></a></th>
		<td><textarea name="transferee" class="long_txt"><?php echo esc_html( $this->options['transferee'] ); ?></textarea></td>
		<td><div id="ex_transferee" class="explanation"><?php esc_html_e( 'The acount number for bank transfer. You can enter the information as you wish.  The contents will apear in the mail.', 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_tatransfer_limit');"><?php esc_html_e( 'Days to deposit deadline', 'usces' ); ?></a></th>
		<td><input name="tatransfer_limit" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['tatransfer_limit'] ); ?>" /><?php esc_html_e( 'days', 'usces' ); ?></td>
		<td><div id="ex_tatransfer_limit" class="explanation"><?php esc_html_e( 'When the number of days is set, the content confirmation page will display the payment period for "Transfer (prepayment)" or "Transfer (postpay)".', 'usces' ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_membersystem_state');"><?php esc_html_e( 'membership syetem', 'usces' ); ?></a></th>
		<td width="10"><input name="membersystem_state" id="membersystem_state_activate" type="radio" value="activate"<?php checked( $this->options['membersystem_state'], 'activate' ); ?> /></td><td width="100"><label for="membersystem_state_activate"><?php esc_html_e( 'to use', 'usces' ); ?></label></td>
		<td width="10"><input name="membersystem_state" id="membersystem_state_deactivate" type="radio" value="deactivate"<?php checked( $this->options['membersystem_state'], 'deactivate' ); ?> /></td><td width="100"><label for="membersystem_state_deactivate"><?php esc_html_e( 'not to use', 'usces' ); ?></label></td>
		<td><div id="ex_membersystem_state" class="explanation"><?php esc_html_e( 'Would you like to use membership system or not?', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_membersystem_point');"><?php esc_html_e( 'membership points', 'usces' ); ?></a></th>
		<td width="10"><input name="membersystem_point" id="membersystem_point_activate" type="radio" value="activate"<?php checked( $this->options['membersystem_point'], 'activate' ); ?> /></td><td width="100"><label for="membersystem_point_activate"><?php esc_html_e( 'to use', 'usces' ); ?></label></td>
		<td width="10"><input name="membersystem_point" id="membersystem_point_deactivate" type="radio" value="deactivate"<?php checked( $this->options['membersystem_point'], 'deactivate' ); ?> /></td><td width="100"><label for="membersystem_point_deactivate"><?php esc_html_e( 'not to use', 'usces' ); ?></label></td>
		<td><div id="ex_membersystem_point" class="explanation"><?php esc_html_e( "Would you like to use 'points granting syetem' when you choose to use membership system?", 'usces' ); ?></div></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_point_rate');"><?php esc_html_e( 'The initial value of point rate', 'usces' ); ?></a></th>
		<td colspan="2"><input name="point_rate" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['point_rate'] ); ?>" />%</td>
		<td><div id="ex_point_rate" class="explanation"><?php esc_html_e( 'initial value at registration of items. Leave it blank if it is not necessary.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_start_point');"><?php esc_html_e( 'Point at the registration of membership', 'usces' ); ?></a></th>
		<td colspan="2"><input name="start_point" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['start_point'] ); ?>" /><?php esc_html_e( 'points', 'usces' ); ?></td>
		<td><div id="ex_start_point" class="explanation"><?php esc_html_e( 'Points granted  at the first membersip registration.', 'usces' ); ?></div></td>
	</tr>
	<tr>
		<th rowspan="2"><a style="cursor:pointer;" onclick="toggleVisibility('ex_point_coverage');"><?php esc_html_e( 'Areas of Point Redemption', 'usces' ); ?></a></th>
		<td><input name="point_coverage" type="radio" id="point_coverage0" value="0"<?php checked( WCUtils::is_zero( $this->options['point_coverage'] ), true ); ?> /></td><td width="220"><label for="point_coverage0"><?php esc_html_e( 'Limited Only to Total Merchandise Price', 'usces' ); ?></label></td>
		<td rowspan="2"><div id="ex_point_coverage" class="explanation"><?php echo wp_kses_post( __( "Select the scope of the point that customers can use.<br />Default is 'Limited Only to Total Merchandise Price'. 'Applicable to Total Merchandise Price and Handling Fee' If you choose, you can pay shipping and COD fee in points.", 'usces' ) ); ?></div></td>
	</tr>
	<tr>
		<td><input name="point_coverage" type="radio" id="point_coverage1" value="1"<?php checked( $this->options['point_coverage'], 1 ); ?> /></td><td width="220"><label for="point_coverage1"><?php esc_html_e( 'Applicable to Total Merchandise Price and Handling Fee', 'usces' ); ?></label></td>
	</tr>
</table>
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_point_assign');"><?php esc_html_e( 'Timing point of grant', 'usces' ); ?></a></th>
		<td width="10"><input name="point_assign" id="point_assign_receipt" type="radio" value="1"<?php checked( $this->options['point_assign'], 1 ); ?> /></td><td width="60"><label for="point_assign_receipt"><?php esc_html_e( 'Payment at the time', 'usces' ); ?></label></td>
		<td width="10"><input name="point_assign" id="point_assign_immediately" type="radio" value="0"<?php checked( WCUtils::is_zero( $this->options['point_assign'] ), true ); ?> /></td><td width="60"><label for="point_assign_immediately"><?php esc_html_e( 'Immediately', 'usces' ); ?></label></td>
		<td><div id="ex_point_assign" class="explanation"><?php echo wp_kses_post( __( "The 'immediate', points are given to the shopping upon completion. In the 'payment at the time', bank transfer, or convenience store settlement, points will not be given to the shopping upon completion, grant the point when it becomes a pre-payment.", 'usces' ) ); ?></div></td>
	</tr>
</table>
<hr />
<table class="form_table">
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_address_search');"><?php esc_html_e( 'Postal code address search', 'usces' ); ?></a></th>
		<td width="10"><input name="address_search" id="address_search_activate" type="radio" value="activate"<?php checked( $this->options['address_search'], 'activate' ); ?> /></td><td width="100"><label for="address_search_activate"><?php esc_html_e( 'to use', 'usces' ); ?></label></td>
		<td width="10"><input name="address_search" id="address_search_deactivate" type="radio" value="deactivate"<?php checked( $this->options['address_search'], 'deactivate' ); ?> /></td><td width="100"><label for="address_search_deactivate"><?php esc_html_e( 'not to use', 'usces' ); ?></label></td>
		<td><div id="ex_address_search" class="explanation"><?php echo wp_kses_post( __( "'Address search button' is added to 'new member registration form' 'member information edit form' 'customer information form' 'delivery information form'.<br />When the 'postal code' is input, and 'address search' is clicked, 'province' 'city' are indicated automatically.", 'usces' ) ); ?></div></td>
	</tr>
</table>
<hr />
<?php
$stock_status_label = array();
for ( $i = 0; $i <= 4; $i++ ) {
	$stock_status_label[ $i ] = ( ! empty( $this->options['stock_status_label'][ $i ] ) ) ? $this->options['stock_status_label'][ $i ] : '';
}
$order_acceptable_label = ( ! empty( $this->options['order_acceptable_label'] ) ) ? $this->options['order_acceptable_label'] : '';
?>
<table class="form_table">
	<tr><th rowspan="5"><a style="cursor:pointer;" onclick="toggleVisibility('ex_stock_status_label');"><?php esc_html_e( 'Label of stock status', 'usces' ); ?></a></th>
		<td><input name="stock_status_label[0]" type="text" class="long_str" value="<?php echo esc_attr( $stock_status_label[0] ); ?>" placeholder="<?php esc_attr_e( 'In Stock', 'usces' ); ?>" /><?php esc_html_e( 'In Stock', 'usces' ); ?></td>
		<td rowspan="5"><div id="ex_stock_status_label" class="explanation"><?php esc_html_e( 'You can change the label of stock status. Changing the order or adding the status are not allowed.', 'usces' ); ?></div></td>
	</tr>
	<tr><td><input name="stock_status_label[1]" type="text" class="long_str" value="<?php echo esc_attr( $stock_status_label[1] ); ?>" placeholder="<?php esc_attr_e( 'A Few Stock', 'usces' ); ?>" /><?php esc_html_e( 'A Few Stock', 'usces' ); ?></td></tr>
	<tr><td><input name="stock_status_label[2]" type="text" class="long_str" value="<?php echo esc_attr( $stock_status_label[2] ); ?>" placeholder="<?php esc_attr_e( 'Sold Out', 'usces' ); ?>" /><?php esc_html_e( 'Sold Out', 'usces' ); ?></td></tr>
	<tr><td><input name="stock_status_label[3]" type="text" class="long_str" value="<?php echo esc_attr( $stock_status_label[3] ); ?>" placeholder="<?php esc_attr_e( 'Out Of Stock', 'usces' ); ?>" /><?php esc_html_e( 'Out Of Stock', 'usces' ); ?></td></tr>
	<tr><td><input name="stock_status_label[4]" type="text" class="long_str" value="<?php echo esc_attr( $stock_status_label[4] ); ?>" placeholder="<?php esc_attr_e( 'Out of print', 'usces' ); ?>" /><?php esc_html_e( 'Out of print', 'usces' ); ?></td></tr>
	<tr>
		<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_order_acceptable_label');"><?php esc_html_e( 'Label of Order acceptable when sold out', 'usces' ); ?></a></th>
		<td><input name="order_acceptable_label" type="text" class="long_str" value="<?php echo esc_attr( $order_acceptable_label ); ?>" placeholder="<?php esc_attr_e( 'Order acceptable', 'usces' ); ?>" /><?php esc_html_e( 'Order acceptable', 'usces' ); ?></td>
		<td><div id="ex_order_acceptable_label" class="explanation"><?php esc_html_e( "You can change the label of stock status when the status is 'Order acceptable'.", 'usces' ); ?></div></td>
	</tr>
</table>
<?php do_action( 'usces_action_amdin_setup' ); ?>
<hr />
<div id="shop_setting" class="explanation"><?php esc_html_e( 'the initial rate of the shop', 'usces' ); ?></div>
</div>
</div><!--postbox-->

<div class="postbox">
<h3 id="payment_method_setting"><span><?php esc_html_e( 'payment method', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('payment_setting');"> (<?php esc_html_e( 'explanation', 'usces' ); ?>) </a></h3>
<div class="inside">
	<div id="postpayment"><div id="payment-response"></div>
<?php
$option_value = usces_get_system_option( 'usces_payment_method', 'sort' );
payment_list( $option_value );
payment_form();
?>
<hr />
<div id="Commonoption" class="explanation"><?php echo wp_kses_post( __( '<em>[Required]</em>possible payment method', 'usces' ) ); ?></div>
<div id="payment_setting" class="explanation"><?php esc_html_e( "If you 'Deactivate', it does not appear in the payment method of site.", 'usces' ); ?></div>
</div>
</div>
</div><!--postbox-->

<div class="postbox">
<h3><span><?php esc_html_e( 'Common Options', 'usces' ); ?></span></h3>
<div class="inside">
	<div id="postoptcustomstuff"><div id="ajax-response"></div>
<?php
$opts = usces_get_opts( USCES_CART_NUMBER );
list_item_option_meta( $opts );
common_option_meta_form();
?>
<hr />
<div id="Commonoption" class="explanation"><?php esc_html_e( 'Conditions which will be selected at the purchase. You can use the options which you hve registered here, as an option in the master items.', 'usces' ); ?></div>
</div>
</div>
</div><!--postbox-->
</div><!--poststuff-->
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo esc_attr( USCES_CART_NUMBER ); ?>" />
<?php
$cod_fee          = ( isset( $this->options['cod_fee'] ) ) ? $this->options['cod_fee'] : '';
$cod_limit_amount = ( isset( $this->options['cod_limit_amount'] ) && 0 < (int) $this->options['cod_limit_amount'] ) ? $this->options['cod_limit_amount'] : '';
$cod_first_amount = ( isset( $this->options['cod_first_amount'] ) ) ? $this->options['cod_first_amount'] : '';
$cod_first_fee    = ( isset( $this->options['cod_first_fee'] ) ) ? $this->options['cod_first_fee'] : '';
$cod_end_fee      = ( isset( $this->options['cod_end_fee'] ) ) ? $this->options['cod_end_fee'] : '';
?>
<div id="cod_dialog" class="cod_dialog" title="<?php esc_attr_e( 'C.O.D. Detailed setting', 'usces' ); ?>">
	<p id="cod-response"><?php esc_html_e( 'Please be settled for a fee in spite of Update.', 'usces' ); ?></p>
	<fieldset>
	<table id="cod_type_table" class="cod_type_table">
		<tr><th><?php esc_html_e( 'Type of the fee', 'usces' ); ?></th><td class="radio"><input name="cod_type" id="cod_type_fix" type="radio" value="fix"<?php checked( $this->options['cod_type'], 'fix' ); ?> /></td>
			<td><label for="cod_type_fix"><?php esc_html_e( 'Fixation C.O.D.', 'usces' ); ?></label></td>
			<td class="radio"><input name="cod_type" id="cod_type_change" type="radio" value="change"<?php checked( $this->options['cod_type'], 'change' ); ?> /></td>
			<td><label for="cod_type_change"><?php esc_html_e( 'Variable C.O.D.', 'usces' ); ?></label></td>
		</tr>
	</table>
	<table id="cod_fix_table" class="cod_fix_table">
		<tr><th><?php esc_html_e( 'Fee', 'usces' ); ?></th><td><input name="cod_fee" type="text" class="short_str num" value="<?php echo esc_attr( $cod_fee ); ?>" /><?php usces_crcode(); ?></td></tr>
		<tr><th><?php esc_html_e( 'The upper limit of the C.O.D.', 'usces' ); ?></th><td><input name="cod_limit_amount" id="cod_limit_amount_fix" type="text" class="short_str num" value="<?php echo esc_attr( $cod_limit_amount ); ?>" /><?php usces_crcode(); ?></td></tr>
	</table>
	<div id="cod_change_table" class="cod_change_table">
	<input name="addrow" id="add_row" class="button" type="button" value="<?php esc_attr_e( 'Add row', 'usces' ); ?>" />&nbsp;<input name="delrow" class="button" type="button" id="del_row" value="<?php esc_attr_e( 'Delete row', 'usces' ); ?>" />
	<table>
		<thead>
			<tr>
				<th colspan="3"><?php esc_html_e( 'A purchase amount', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
				<th><?php esc_html_e( 'Fee', 'usces' ); ?>(<?php usces_crcode(); ?>)</th>
			</tr>
			<tr><td class="cod_f">0</td><td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td><td class="cod_e"><input name="cod_first_amount" type="text" class="short_str num" value="<?php echo esc_attr( $cod_first_amount ); ?>" /></td>
				<td class="cod_cod"><input name="cod_first_fee" type="text" class="short_str num" value="<?php echo esc_attr( $cod_first_fee ); ?>" /></td>
			</tr>
		</thead>
		<tbody id="cod_change_field">
<?php
if ( isset( $this->options['cod_amounts'] ) && isset( $this->options['cod_fees'] ) ) {
	foreach ( (array) $this->options['cod_amounts'] as $key => $value ) {
		if ( 0 === $key ) {
			$amount = ( isset( $this->options['cod_first_amount'] ) ? $this->options['cod_first_amount'] : 0 ) + 1;
		} else {
			$amount = $this->options['cod_amounts'][ ( $key - 1 ) ] + 1;
		}
		?>
			<tr id="tr_<?php echo esc_attr( $key ); ?>">
				<td class="cod_f"><span id="amount_<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $amount ); ?></span></td>
				<td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td>
				<td class="cod_e"><input name="cod_amounts[<?php echo esc_attr( $key ); ?>]" type="text" class="short_str num" value="<?php echo esc_attr( $value ); ?>" /></td>
				<td class="cod_cod"><input name="cod_fees[<?php echo esc_attr( $key ); ?>]" type="text" class="short_str num" value="<?php echo esc_attr( $this->options['cod_fees'][ $key ] ); ?>" /></td>
			</tr>
		<?php
	}
}
if ( ! isset( $this->options['cod_amounts'] ) || empty( $this->options['cod_amounts'] ) ) {
	$end_amount = ( isset( $this->options['cod_first_amount'] ) ? $this->options['cod_first_amount'] : 0 ) + 1;
} else {
	$cod_last   = count( $this->options['cod_amounts'] ) - 1;
	$end_amount = $this->options['cod_amounts'][ $cod_last ] + 1;
}
?>
		</tbody>
		<tfoot>
			<tr>
				<td class="cod_f"><span id="end_amount"><?php echo esc_attr( $end_amount ); ?></span></td>
				<td class="cod_m"><?php esc_html_e( ' - ', 'usces' ); ?></td><td class="cod_e"><input name="cod_limit_amount" id="cod_limit_amount_change" type="text" class="short_str num" value="<?php echo esc_attr( $cod_limit_amount ); ?>" /></td>
				<td class="cod_cod"><input name="cod_end_fee" type="text" class="short_str num" value="<?php echo esc_attr( $cod_end_fee ); ?>" /></td>
			</tr>
		</tfoot>
	</table>
	</div>
	</fieldset>
</div>
<?php wp_nonce_field( 'admin_setup', 'wc_nonce' ); ?>
</form>
</div><!--usces_admin-->
</div><!--wrap-->
