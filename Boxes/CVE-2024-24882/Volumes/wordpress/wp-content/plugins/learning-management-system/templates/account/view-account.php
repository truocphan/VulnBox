<?php
/**
 * View account template.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering account detail section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_view_account_content' );

?>

<div class="masteriyo-account masteriyo-flex">
	<div class="masteriyo-account--imgwrap">
		<img class="masteriyo-account-img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
	</div>

	<div class="masteriyo-account--detail">
		<div class="masteriyo-account masteriyo-account--header">
			<h2 class="masteriyo-account--name"><?php echo esc_html( $user->get_display_name() ); ?></h2>
		</div>
	</div>

	<div class="masteriyo-account--body masteriyo-flex">
		<ul class="masteriyo-title account-col-1">
			<li><strong><?php echo esc_html__( 'Email', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Address', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'City', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'State', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></strong></li>
			<li><strong><?php echo esc_html__( 'Country', 'masteriyo' ); ?></strong></li>
		</ul>

		<ul class="masteriyo-content account-col-2">
			<li><?php echo esc_html( $user->get_email() ); ?></li>
			<li><?php echo esc_html( $user->get_first_name() ); ?></li>
			<li><?php echo esc_html( $user->get_last_name() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_address() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_city() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_state() ); ?></li>
			<li><?php echo esc_html( $user->get_billing_postcode() ); ?></li>
			<li><?php echo esc_html( masteriyo( 'countries' )->get_country_from_code( $user->get_billing_country() ) ); ?></li>
		</ul>
	</div>
</div>

<?php

/**
 * Fires after rendering account detail section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_view_account_content' );
