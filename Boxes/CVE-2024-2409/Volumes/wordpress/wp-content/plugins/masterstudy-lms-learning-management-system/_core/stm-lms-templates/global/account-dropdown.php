<?php if ( is_user_logged_in() ) :
	stm_lms_register_style( 'lesson' );
	stm_lms_register_style( 'user' );
	$user                 = STM_LMS_User::get_current_user();
	$new_messages         = apply_filters( 'stm_lms_header_messages_counter', STM_LMS_Chat::user_new_messages( $user['id'] ) );
	$lms_template_current = get_query_var( 'lms_template' );
	$object_id            = get_queried_object_id();
	$menu_items           = STM_LMS_User_Menu::stm_lms_user_menu_display( $user, $lms_template_current, $object_id );
	?>

	<div class="stm_lms_account_dropdown">
		<div class="dropdown">

			<?php if ( ! empty( $new_messages ) ) : ?>
				<div class="stm-lms-user_message_btn__counter">
					<?php echo wp_kses_post( $new_messages ); ?>
				</div>
			<?php endif; ?>

			<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="lnr lnr-user"></i>
				<span class="login_name"><?php echo esc_html( stm_lms_minimize_word( sprintf( esc_html__( 'Hey, %s', 'masterstudy-lms-learning-management-system' ), $user['login'] ), 15 ) ); ?></span>
				<span class="caret"></span>
			</button>

			<ul class="dropdown-menu" aria-labelledby="dLabel">
				<li>
					<?php
					if ( STM_LMS_User_Menu::float_menu_enabled() && STM_LMS_Instructor::is_instructor( $user['id'] ) ) {
						?>
						<div class="dropdown_menu_item__divider instructor_area">
							<?php echo esc_html__( 'Instructor area', 'masterstudy-lms-learning-management-system' ); ?>
						</div>
						<?php
					}
					foreach ( $menu_items as $menu_item ) {
						if ( isset( $menu_item['type'] ) && 'divider' === $menu_item['type'] && ! STM_LMS_User_Menu::float_menu_enabled() ) {
							continue;
						}
						$item_type = ( ! empty( $menu_item['type'] ) && STM_LMS_User_Menu::float_menu_enabled() ) ? 'dropdown-' . $menu_item['type'] : 'dropdown_menu_item';
						STM_LMS_Templates::show_lms_template( "account/float_menu/menu_items/{$item_type}", $menu_item );
					}
					?>
					<a href="#" class="stm_lms_logout"><?php esc_html_e( 'Logout', 'masterstudy-lms-learning-management-system' ); ?></a>
				</li>
			</ul>

		</div>
	</div>

	<?php
endif;
