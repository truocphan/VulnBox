<?php
/**
 * Welcart Page Widget
 *
 * @package Welcart
 */

/**
 * Welcart_page Class
 */
class Welcart_page extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'Page', 'usces' ) );
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
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Page', 'usces' ) : $instance['title'];
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$page     = ( ! isset( $instance['page'] ) || WCUtils::is_blank( $instance['page'] ) ) ? '' : $instance['page'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/page.png' ) ? get_stylesheet_directory_uri() . '/images/page.png' : USCES_FRONT_PLUGIN_URL . '/images/page.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . esc_html( $title ) . $after_title );
		?>

		<ul class="ucart_widget_body">
		<?php wp_list_pages( apply_filters( 'usces_filter_wc_widget_page_arg', ( 'title_li=&include=' . $page ), $page ) ); ?>
		</ul>

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
		$title = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Page', 'usces' ) : esc_attr( $instance['title'] );
		$icon  = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$page  = ( ! isset( $instance['page'] ) || WCUtils::is_blank( $instance['page'] ) ) ? '' : $instance['page'];
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'page' ) ); ?>"><?php esc_html_e( 'Page ID(comma separate)', 'usces' ); ?>: <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'page' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'page' ) ); ?>" type="text" value="<?php wel_esc_script_e( $page ); ?>" /></label></p>
		<?php
	}
}
