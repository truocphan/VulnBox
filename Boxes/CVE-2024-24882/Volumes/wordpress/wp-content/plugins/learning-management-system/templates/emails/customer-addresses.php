<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/emails/customer-addresses.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates\Emails
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';
?>
<table id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
		<td style="text-align:<?php echo esc_attr( $text_align ); ?>; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">
			<address class="address">
				<?php if ( $order->get_billing_phone() ) : ?>
					<br/>
					<?php esc_html_e( 'Customer Phone Number:', 'masteriyo' ); ?> <?php echo wp_kses_post( masteriyo_make_phone_clickable( $order->get_billing_phone() ) ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) : ?>
					<br/><?php esc_html_e( 'Customer Email:', 'masteriyo' ); ?> <?php echo esc_html( $order->get_billing_email() ); ?>
				<?php endif; ?>
			</address>
		</td>
	</tr>
</table>
