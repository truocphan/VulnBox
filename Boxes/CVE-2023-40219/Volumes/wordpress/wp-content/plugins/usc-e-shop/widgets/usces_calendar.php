<?php
/**
 * Welcart Calendar Widget
 *
 * @package Welcart
 */

/**
 * Welcart_calendar Class
 *
 * @see WP_Widget
 */
class Welcart_calendar extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'Calendar', 'usces' ) );
	}

	/**
	 * Echoes the widget content.
	 *
	 * @see WP_Widget::widget
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Business Calendar', 'usces' ) : $instance['title'];
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/calendar.png' ) ? get_stylesheet_directory_uri() . '/images/calendar.png' : USCES_FRONT_PLUGIN_URL . '/images/calendar.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . esc_html( $title ) . $after_title );
		?>

		<ul class="ucart_calendar_body ucart_widget_body"><li>
		<?php usces_the_calendar(); ?>
		</li></ul>

		<?php
		wel_esc_script_e( $after_widget );
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget::update
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget::form
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Business Calendar', 'usces' ) : esc_attr( $instance['title'] );
		$icon  = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<p><?php esc_html_e( "The setting of the business day, In a 'business day setting' page of the admin screen.", 'usces' ); ?></p>
		<?php
	}
}
