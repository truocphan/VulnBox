<?php

namespace AM\LastViewedPosts;

use WP_Widget;

/**
 * Last viewed posts widget.
 *
 * @package AM\LastViewedPosts
 */
class Widget extends WP_Widget {

	/**
	 * Initialize the last viewed posts widget.
	 */
	public function __construct() {
		$id_base    = 'last-viewed-posts-redo';
		$name       = __( 'Last Viewed Posts Redo', 'last-viewed-posts' );
		$class_name = 'zg_lwp_widget am.last-viewed-posts.display-none';
		parent::__construct(
			$id_base,
			$name,
			array(
				'classname' => $class_name,
			)
		);
	}

	/**
	 * Echo HTML markup for widget.
	 *
	 * @param array $args     The theme's settings for the widget area.
	 * @param array $instance The settings for this instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// Developer arguments.
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];

		// Instance settings.
		$title = $instance['title'];

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, (before widget comes from PHP code).
		echo $before_widget;

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, (before title comes from PHP code).
		echo $before_title;

		echo esc_html( $title );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, (after title comes from PHP code).
		echo $after_title;

		echo '<ul class="viewed_posts">';
		echo '</ul>';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, (after widget comes from PHP code).
		echo $after_widget;
	}

	/**
	 * Handles updating settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            self::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$new_instance      = wp_parse_args(
			(array) $new_instance,
			array(
				'title' => __( 'Last Viewed Posts', 'last-viewed-posts' ),
			)
		);
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => __( 'Last Viewed Posts', 'last-viewed-posts' ),
			)
		);
		// `get_field_*` functions are pre-escaped.
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'last-viewed-posts' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
