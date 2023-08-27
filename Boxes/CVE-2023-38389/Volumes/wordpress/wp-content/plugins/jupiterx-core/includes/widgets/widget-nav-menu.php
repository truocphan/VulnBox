<?php
/**
 * Navigation Menu Widget.
 *
 * Extends existing navigation menu widget using ACF.
 *
 * @package JupiterX_Core\Widgets
 *
 * @since 1.0.0
 */

add_filter( 'widget_display_callback', 'jupiterx_nav_menu_widget', 10, 3 );
add_filter( 'widget_nav_menu_args', 'jupiterx_modify_nav_menu_widget', 10, 4 );

/**
 * Modify Navigation menu widget output with custom class name and styles.
 *
 * @since 1.0.0
 *
 * @param object $instance Widget instance.
 * @param object $widget   Widget object.
 * @param array  $args     Widget args.
 *
 * @return bool|object True on nav-menu widget|widget instance.
 */
function jupiterx_nav_menu_widget( $instance, $widget, $args ) {
	if ( 'nav_menu' !== $widget->id_base ) {
		return $instance;
	}

	$space       = isset( $instance['widget_nav_menu_space_between'] ) ? $instance['widget_nav_menu_space_between'] : '';
	$orientation = isset( $instance['widget_nav_menu_orientation'] ) ? $instance['widget_nav_menu_orientation'] : 'horizontal';

	$selector = '.jupiterx-widget-nav-menu-' . $orientation . ' > .menu-item';
	$property = 'vertical' === $orientation ? 'margin-bottom' : 'margin-right';

	jupiterx_open_markup_e( 'jupiterx_nav_menu_widget_styles', 'style' );

		echo $selector . '{' . $property . ':' . $space . 'px}'; //phpcs:ignore

	jupiterx_close_markup_e( 'jupiterx_nav_menu_widget_styles', 'style' );

	$widget->widget( $args, $instance );

	// Override the default widget output.
	return true;
}

/**
 * Add custom class name based on menu orientation option to navigation menu widget.
 *
 * @since 1.0.0
 *
 * @param array  $nav_menu_args wp_nav_menu function args.
 * @param object $nav_menu      Navigation menu object that we want to load in widget.
 * @param array  $args          Widget args.
 * @param object $instance      Widget instance.
 *
 * @return array $nav_menu_args Modified navigation menu args.
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_modify_nav_menu_widget( $nav_menu_args, $nav_menu, $args, $instance ) {
	$orientation = isset( $instance['widget_nav_menu_orientation'] ) ? $instance['widget_nav_menu_orientation'] : 'horizontal';

	$nav_menu_args['menu_class'] = 'jupiterx-widget-nav-menu-' . $orientation;

	if ( 'horizontal' === $orientation ) {
		$nav_menu_args['depth'] = 1;
	}

	return $nav_menu_args;
}


add_action( 'in_widget_form', 'jupiterx_modify_nav_menu_form', 10, 3 );
/**
 * Show custom fields in nav menu widget form.
 *
 * @since 1.0.0
 *
 * @param object $widget   Widget class object.
 * @param null   $return   Return null if new fields are added.
 * @param object $instance Widget settings instance.
 *
 * @return void
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function jupiterx_modify_nav_menu_form( $widget, $return, $instance ) {
	if ( 'nav_menu' !== $widget->id_base ) {
		return;
	}

	$orientation      = 'widget_nav_menu_orientation';
	$orientation_id   = $widget->get_field_id( $orientation );
	$orientation_name = $widget->get_field_name( $orientation );

	$space      = 'widget_nav_menu_space_between';
	$space_id   = $widget->get_field_id( $space );
	$space_name = $widget->get_field_name( $space );

	$value       = isset( $instance[ $orientation ] ) ? $instance[ $orientation ] : '';
	$space_value = isset( $instance[ $space ] ) ? $instance[ $space ] : false;

	$options = [
		'vertical'   => esc_html__( 'Vertical', 'jupiterx-core' ),
		'horizontal' => esc_html__( 'Horizontal', 'jupiterx-core' ),
	];

	jupiterx_open_markup_e( 'jupiterx_widget_control_wrapper', 'div', 'class=jupiterx-widget-control jupiterx-control-select' );

		jupiterx_open_markup_e( 'jupiterx_widget_control_select_label', 'label', [ 'for' => $orientation_id ] );

			echo esc_html_e( 'Orientation:', 'jupiterx-core' );

		jupiterx_close_markup_e( 'jupiterx_widget_control_select_label', 'label' );

		jupiterx_open_markup_e( 'jupiterx_widget_control_select', 'select', [
			'class' => 'widefat ' . esc_attr( $orientation ),
			'id'    => $orientation_id,
			'name'  => $orientation_name,
		] );

			foreach ( $options as $key => $label ) {
				$attributes = [ 'value' => $key ];

				// Check for selected field.
				if ( $value === $key ) {
					$attributes['selected'] = 'selected';
				}

				jupiterx_open_markup_e( 'jupiterx_widget_control_select_option', 'option', $attributes );

					echo esc_html( $label );

				jupiterx_close_markup_e( 'jupiterx_widget_control_select_option', 'option' );
			}

		jupiterx_close_markup_e( 'jupiterx_widget_control_select', 'select' ); // @phpcs:ignore

	jupiterx_close_markup_e( 'jupiterx_widget_control_wrapper', 'div' ); // @phpcs:ignore

	jupiterx_open_markup_e( 'jupiterx_widget_control_wrapper', 'div', 'class=jupiterx-widget-control jupiterx-control-text' ); // @phpcs:ignore

		jupiterx_open_markup_e( 'jupiterx_widget_control_text_label', 'label', [ 'for' => $space_id ] ); // @phpcs:ignore

			echo esc_html_e( 'Space between:', 'jupiterx-core' );

		jupiterx_close_markup_e( 'jupiterx_widget_control_text_label', 'label' ); // @phpcs:ignore

		jupiterx_selfclose_markup_e( 'jupiterx_widget_control_text_input', 'input', [ // @phpcs:ignore
			'class' => 'widefat ' . esc_attr( $space_id ),
			'id'    => $space_id,
			'name'  => $space_name,
			'type'  => 'number',
			'value' => $space_value,
		] );

	jupiterx_close_markup_e( 'jupiterx_widget_control_wrapper', 'div' ); // @phpcs:ignore
}

add_action( 'widget_update_callback', 'jupiterx_modify_nav_menu_update', 10, 2 );
/**
 * Processing widget options on save.
 *
 * @since 1.0.0
 *
 * @param array $instance     Current widget instance settings.
 * @param array $new_instance Array of new widget settings.
 *
 * @return $settings Modified widget instance.
 */
function jupiterx_modify_nav_menu_update( $instance, $new_instance ) {
	$settings = $instance;

	if ( isset( $new_instance['widget_nav_menu_orientation'] ) ) {
		$settings['widget_nav_menu_orientation'] = $new_instance['widget_nav_menu_orientation'];
	}

	if ( isset( $new_instance['widget_nav_menu_space_between'] ) ) {
		$settings['widget_nav_menu_space_between'] = $new_instance['widget_nav_menu_space_between'];
	}

	return $settings;
}
