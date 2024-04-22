<?php
$current_user         = STM_LMS_User::get_current_user( null, true, true );
$lms_template_current = get_query_var( 'lms_template' );
$menu_items           = STM_LMS_User_Menu::stm_lms_user_menu_display();
?>

<?php STM_LMS_Templates::show_lms_template( 'account/float_menu/user', compact( 'current_user', 'lms_template_current' ) ); ?>

	<div class="stm_lms_user_float_menu__scrolled">

		<?php
		foreach ( $menu_items as $menu_item ) {
			$item_type = ( ! empty( $menu_item['type'] ) ) ? $menu_item['type'] : 'dynamic_menu_item';
			STM_LMS_Templates::show_lms_template( "account/float_menu/menu_items/{$item_type}", $menu_item );
		}
		?>

		<div class="stm_lms_user_float_menu__scrolled_label">
			<i class="fa fa-chevron-down"></i>
		</div>

	</div>

<?php
STM_LMS_Templates::show_lms_template( 'account/private/parts/logout' );
