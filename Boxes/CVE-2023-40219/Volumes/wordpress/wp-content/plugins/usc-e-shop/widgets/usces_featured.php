<?php
/**
 * Welcart Featured Widget
 *
 * @package Welcart
 */

/**
 * Welcart_featured Class
 *
 * @see WP_Widget
 */
class Welcart_featured extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'Items recommended', 'usces' ) );
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
		global $usces;
		extract( $args );
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Items recommended', 'usces' ) : $instance['title'];
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$num      = ( ! isset( $instance['num'] ) || WCUtils::is_blank( $instance['num'] ) ) ? 1 : (int) $instance['num'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/osusume.png' ) ? get_stylesheet_directory_uri() . '/images/osusume.png' : USCES_FRONT_PLUGIN_URL . '/images/osusume.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . esc_html( $title ) . $after_title );
		?>

		<ul class="ucart_featured_body ucart_widget_body">
		<?php
		$myposts    = get_posts( 'numberposts=' . $num . '&category=' . usces_get_cat_id( 'itemreco' ) . '&orderby=rand' );
		$class      = ( 1 === (int) $num ) ? ' featured_single' : '';
		$list_index = 0;
		foreach ( $myposts as $post ) :
			$post_id = $post->ID;
			?>
			<li class="featured_list<?php echo esc_attr( $class ); ?><?php echo apply_filters( 'usces_filter_featured_list_class', null, $list_index, $num ); ?>">
			<?php
			$list  = '<div class="thumimg"><a href="' . get_permalink( $post_id ) . '">' . usces_the_itemImage( 0, 150, 150, $post, 'return' ) . '</a></div>';
			$list .= '<div class="thumtitle"><a href="' . get_permalink( $post_id ) . '" rel="bookmark">' . $usces->getItemName( $post_id ) . '&nbsp;(' . $usces->getItemCode( $post_id ) . ')</a></div>';
			echo apply_filters( 'usces_filter_featured_widget', $list, $post, $list_index, $instance );
			?>
			</li>
			<?php
			$list_index++;
		endforeach;
		?>
		</ul>

		<?php
		wel_esc_script_e( $after_widget );

		wp_reset_postdata();
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
		$title = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Items recommended', 'usces' ) : esc_attr( $instance['title'] );
		$icon  = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$num   = ( ! isset( $instance['num'] ) || WCUtils::is_blank( $instance['num'] ) ) ? 1 : (int) $instance['num'];
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'num' ) ); ?>"><?php esc_html_e( 'number of indication', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'num' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'num' ) ); ?>" type="text" value="<?php wel_esc_script_e( $num ); ?>" /></label></p>
		<?php
	}
}
