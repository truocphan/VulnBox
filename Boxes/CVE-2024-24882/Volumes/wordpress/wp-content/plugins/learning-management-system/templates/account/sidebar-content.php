<?php
/**
 * The template for sidebar in account.
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fires before rendering sidebar in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_before_account_page_sidebar_content' );

?>

<div class="masteriyo-sidebar-wrapper">
	<div class="masteriyo-profile">
		<img class="masteriyo-profile--img" src="<?php echo esc_attr( $user->get_avatar_url() ); ?>" alt="" />
		<div class="masteriyo-profile--details">
			<div class="masteriyo-profile--name">
				<a
					id="label-username"
					href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'view-account' ) ); ?>"
				>
					<?php echo esc_attr( $user->get_display_name() ); ?>
				</a>
				<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( 'edit-account' ) ); ?>">
					<?php masteriyo_get_svg( 'edit', true ); ?>
				</a>
			</div>
		</div>
	</div>
	<nav class="masteriyo-menu">
		<ul>
			<?php foreach ( $menu_items as $slug => $endpoint ) : ?>
				<li class="<?php echo esc_attr( $slug === $current_endpoint['endpoint'] ? 'active' : '' ); ?>">
					<a href="<?php echo esc_url( masteriyo_get_account_endpoint_url( $slug ) ); ?>">
						<?php echo wp_kses( $endpoint['icon'], masteriyo_get_allowed_svg_elements() ); ?>
						<span><?php echo esc_html( $endpoint['label'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>

<?php

/**
 * Fires after rendering sidebar in account page.
 *
 * @since 1.0.0
 */
do_action( 'masteriyo_after_account_page_sidebar_content' );
