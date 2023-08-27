<?php
/**
 * Echo the widget area and widget loop structural markup. It also calls the widget area and widget loop
 * action hooks.
 *
 * @package JupiterX\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

// This includes everything added to wp hooks before the widgets.
echo jupiterx_get_widget_area( 'before_widgets' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Widget area has to be echoed.

	// phpcs:disable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
	if ( 'grid' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {
		jupiterx_open_markup_e(
			'jupiterx_widget_area_grid' . _jupiterx_widget_area_subfilters(),
			'div',
			array(
				'class' => 'row',
			)
		);
	}

	if ( 'offcanvas' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {

		jupiterx_open_markup_e(
			'jupiterx_widget_area_offcanvas_wrap' . _jupiterx_widget_area_subfilters(),
			'div',
			array(
				'id'    => jupiterx_get_widget_area( 'id' ), // Automatically escaped.
				'class' => 'uk-offcanvas',
			)
		);

			jupiterx_open_markup_e( 'jupiterx_widget_area_offcanvas_bar' . _jupiterx_widget_area_subfilters(), 'div', array( 'class' => 'uk-offcanvas-bar' ) );
	}

		// Widgets.
		if ( jupiterx_have_widgets() ) :

			/**
			 * Fires before widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_before_widgets_loop' );

				while ( jupiterx_have_widgets() ) :
					jupiterx_setup_widget();

					if ( 'grid' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {
						jupiterx_open_markup_e( 'jupiterx_widget_grid' . _jupiterx_widget_subfilters(), 'div', jupiterx_widget_shortcodes( 'class=uk-width-medium-1-{count}' ) );
					}

						/**
						 * Fires in before each widget card structural HTML.
						 *
						 * @since 1.0.0
						 */
						do_action( 'jupiterx_before_widget' );

						jupiterx_open_markup_e(
							'jupiterx_widget_card' . _jupiterx_widget_subfilters(),
							'div',
							[
								'id'    => jupiterx_widget_shortcodes( '{id}' ),
								'class' => jupiterx_widget_shortcodes( 'jupiterx-widget widget_{type} {id} {classname}' ),
							]
						);

							/**
							 * Fires in each widget card structural HTML.
							 *
							 * @since 1.0.0
							 */
							do_action( 'jupiterx_widget' );

						jupiterx_close_markup_e( 'jupiterx_widget_card' . _jupiterx_widget_subfilters(), 'div' );

						/**
						 * Fires in after each widget card structural HTML.
						 *
						 * @since 1.0.0
						 */
						do_action( 'jupiterx_after_widget' );

					if ( 'grid' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {
						jupiterx_close_markup_e( 'jupiterx_widget_grid' . _jupiterx_widget_subfilters(), 'div' );
					}
				endwhile;

			/**
			 * Fires after the widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_after_widgets_loop' );
		else :

			/**
			 * Fires if no widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'jupiterx_no_widget' );
		endif;

	if ( 'offcanvas' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {

			jupiterx_close_markup_e( 'jupiterx_widget_area_offcanvas_bar' . _jupiterx_widget_area_subfilters(), 'div' );

		jupiterx_close_markup_e( 'jupiterx_widget_area_offcanvas_wrap' . _jupiterx_widget_area_subfilters(), 'div' );
	}

	if ( 'grid' === jupiterx_get_widget_area( 'jupiterx_type' ) ) {
		jupiterx_close_markup_e( 'jupiterx_widget_area_grid' . _jupiterx_widget_area_subfilters(), 'div' );
	}

// This includes everything added to wp hooks after the widgets.
echo jupiterx_get_widget_area( 'after_widgets' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Widget area has to be echoed.

// phpcs:enable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
