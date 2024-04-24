<?php
/**
 * The template for editing user profile.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering edit-account section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_edit_account_tab_content' );

?>

<div class="masteriyo-edt-account masteriyo-tabs">

	<div class="masteriyo-edt-account--tab-menu masteriyo-flex">
		<div data-tab="edit-profile-tab" class="masteriyo-tab masteriyo-active-tab"><?php echo esc_html__( 'Edit Profile', 'masteriyo' ); ?></div>
		<div data-tab="password-security-tab" class="masteriyo-tab"><?php echo esc_html__( 'Password & Security', 'masteriyo' ); ?></div>
	</div>

	<div id="edit-profile-tab" class="masteriyo-edt-account--content masteriyo-tab-content">
		<div class="masteriyo-edt-profile masteriyo-flex masteriyo-flex-xcenter">
			<div class="masteriyo-edt-profile--wrap">
				<img src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" class="masteriyo-edt-account--img" alt="">
			</div>
		</div>

		<form id="masteriyo-edit-profile-form" class="masteriyo-edt-account--form">
				<div class="masteriyo-username">
					<label for="user-email" class="masteriyo-label"><?php echo esc_html__( 'Username', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_display_name() ); ?>" id="username" name="text" type="text" required class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-fname-lname masteriyo-col-2 masteriyo-flex">
					<div class="masteriyo-fname">
						<label for="user-first-name" class="masteriyo-label"><?php echo esc_html__( 'First Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_first_name() ); ?>" id="user-first-name" name="text" type="text" class="masteriyo-input" placeholder="">
					</div>

					<div class="masteriyo-lname">
						<label for="user-last-name" class="masteriyo-label"><?php echo esc_html__( 'Last Name', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_last_name() ); ?>" id="user-last-name" name="text" type="text" class="masteriyo-input" placeholder="">
					</div>
				</div>

				<div class="masteriyo-email">
					<label for="user-email" class="masteriyo-label"><?php echo esc_html__( 'Email', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_email() ); ?>" id="user-email" name="text" type="email" required class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-address">
					<label for="user-address" class="masteriyo-label"><?php echo esc_html__( 'Address', 'masteriyo' ); ?></label>
					<input value="<?php echo esc_attr( $user->get_billing_address() ); ?>" id="user-address" name="text" type="text" class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-city-state masteriyo-col-2 masteriyo-flex">
					<div class="masteriyo-city">
						<label for="user-city" class="masteriyo-label"><?php echo esc_html__( 'City', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_city() ); ?>" id="user-city" name="text" type="text" class="masteriyo-input" placeholder="">
					</div>

					<div class="masteriyo-state">
						<label for="user-state" class="masteriyo-label"><?php echo esc_html__( 'State', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_state() ); ?>" id="user-state" name="text" type="text" class="masteriyo-input" placeholder="">
					</div>
				</div>

				<div class="masteriyo-zip-country masteriyo-col-2 masteriyo-flex">
					<div class="masteriyo-zip">
						<label for="user-zip-code" class="masteriyo-label"><?php echo esc_html__( 'Zip Code', 'masteriyo' ); ?></label>
						<input value="<?php echo esc_attr( $user->get_billing_postcode() ); ?>" id="user-zip-code" name="text" type="text" class="masteriyo-input" placeholder="">
					</div>

					<div class="masteriyo-country">
						<label for="user-country" class="masteriyo-label"><?php echo esc_html__( 'Country', 'masteriyo' ); ?></label>
						<select id="user-country" class="masteriyo-input">
							<?php masteriyo( 'countries' )->country_dropdown_options( $user->get_billing_country(), '*' ); ?>
						</select>
					</div>
				</div>

				<div class="masteriyo-submit-btn">
					<button id="masteriyo-btn-submit-edit-profile-form" type="submit" class="masteriyo-edt-account--btn masteriyo-btn masteriyo-btn-primary">
						<?php echo esc_html__( 'Save', 'masteriyo' ); ?>
					</button>
				</div>
		</form>
	</div>
	<div id="password-security-tab" class="masteriyo-pwd-security masteriyo-tab-content masteriyo-hidden">
			<h3 class="masteriyo-pwd-security--title"><?php echo esc_html__( 'Change Password', 'masteriyo' ); ?></h3>

			<form class="masteriyo-pwd-security--form" method="POST">
				<div class="masteriyo-cr-pwd">
					<label for="current_password" class="masteriyo-label"><?php echo esc_html__( 'Current Password', 'masteriyo' ); ?></label>
					<input id="current_password" name="current_password" type="password" required class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-nw-pwd password_1">
					<label for="password_1" class="masteriyo-label"><?php echo esc_html__( 'New Password', 'masteriyo' ); ?></label>
					<input id="password_1" name="password_1" type="password" required autocomplete="new-password" class="masteriyo-input" placeholder="">
				</div>

				<div class="masteriyo-cf-pwd password_2">
					<label for="password_2" class="masteriyo-label"><?php echo esc_html__( 'Confirm Password', 'masteriyo' ); ?></label>
					<input id="password_2" name="password_2" type="password" required autocomplete="new-password" class="masteriyo-input" placeholder="">
				</div>
				<div class="masteriyo-cpwd-btn">
					<button type="submit" name="masteriyo-change-password" value="yes" class="masteriyo-pwd-security--btn masteriyo-btn masteriyo-btn-primary">
						<?php echo esc_html__( 'Change Password', 'masteriyo' ); ?>
					</button>
				</div>

				<?php wp_nonce_field( 'masteriyo-change-password' ); ?>
			</form>
	</div>
</div>

<?php

/**
 * Fires after rendering edit-account section in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_edit_account_tab_content' );
