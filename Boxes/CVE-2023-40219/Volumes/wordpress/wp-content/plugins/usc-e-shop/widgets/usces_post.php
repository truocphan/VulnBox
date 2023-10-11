<?php
/**
 * Welcart Post Widget
 *
 * @package Welcart
 */

/**
 * Welcart_post Class
 *
 * @see WP_Widget
 */
class Welcart_post extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'Post', 'usces' ) );
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
		$wid      = str_replace( '-', '_', $this->id );
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Post', 'usces' ) : $instance['title'];
		$rows_num = ( ! isset( $instance['rows_num'] ) || WCUtils::is_blank( $instance['rows_num'] ) ) ? 3 : $instance['rows_num'];
		$category = ( ! isset( $instance['category'] ) || WCUtils::is_blank( $instance['category'] ) ) ? '' : $instance['category'];
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/post.png' ) ? get_stylesheet_directory_uri() . '/images/post.png' : USCES_FRONT_PLUGIN_URL . '/images/post.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . apply_filters( 'usces_filter_post_widget_title', esc_html( $title ), $instance ) . $after_title );
		?>

		<ul class="ucart_widget_body <?php echo esc_attr( $category ); ?>">
		<?php usces_list_post( $category, $rows_num, $wid ); ?>
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
		$wid      = ( 'welcart_post-__i__' !== $this->id ) ? str_replace( '-', '_', $this->id ) : '';
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Post', 'usces' ) : esc_attr( $instance['title'] );
		$rows_num = ( ! isset( $instance['rows_num'] ) || WCUtils::is_blank( $instance['rows_num'] ) ) ? 3 : esc_attr( $instance['rows_num'] );
		$category = ( ! isset( $instance['category'] ) || WCUtils::is_blank( $instance['category'] ) ) ? '' : esc_attr( $instance['category'] );
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		?>
		<p>ID : <?php wel_esc_script_e( $wid ); ?></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'category slug', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'category' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'category' ) ); ?>" type="text" value="<?php wel_esc_script_e( $category ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'rows_num' ) ); ?>"><?php esc_html_e( 'number of indication', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'rows_num' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'rows_num' ) ); ?>" type="text" value="<?php wel_esc_script_e( $rows_num ); ?>" /></label></p>
		<?php
	}
}
