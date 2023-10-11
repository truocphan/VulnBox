<?php
/**
 * Welcart Bestseller Widget
 *
 * @package Welcart
 */

/**
 * Welcart_bestseller Class
 *
 * @see WP_Widget
 */
class Welcart_bestseller extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'best seller', 'usces' ) );
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

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}
		if ( ! isset( $instance['rows_num'] ) ) {
			$instance['rows_num'] = '';
		}
		if ( ! isset( $instance['days'] ) ) {
			$instance['days'] = '';
		}
		if ( ! isset( $instance['icon'] ) ) {
			$instance['icon'] = '';
		}
		if ( ! isset( $instance['list'] ) ) {
			$instance['list'] = '';
		}
		if ( ! isset( $instance['code1'] ) ) {
			$instance['code1'] = '';
		}
		if ( ! isset( $instance['code2'] ) ) {
			$instance['code2'] = '';
		}
		if ( ! isset( $instance['code3'] ) ) {
			$instance['code3'] = '';
		}
		if ( ! isset( $instance['code4'] ) ) {
			$instance['code4'] = '';
		}
		if ( ! isset( $instance['code5'] ) ) {
			$instance['code5'] = '';
		}
		if ( ! isset( $instance['code6'] ) ) {
			$instance['code6'] = '';
		}
		if ( ! isset( $instance['code7'] ) ) {
			$instance['code7'] = '';
		}
		if ( ! isset( $instance['code8'] ) ) {
			$instance['code8'] = '';
		}
		if ( ! isset( $instance['code9'] ) ) {
			$instance['code9'] = '';
		}
		if ( ! isset( $instance['code10'] ) ) {
			$instance['code10'] = '';
		}

		$title    = WCUtils::is_blank( $instance['title'] ) ? 'Welcart ' . __( 'best seller', 'usces' ) : $instance['title'];
		$rows_num = WCUtils::is_blank( $instance['rows_num'] ) ? 10 : (int) $instance['rows_num'];
		$days     = WCUtils::is_blank( $instance['days'] ) ? 30 : (int) $instance['days'];
		$icon     = WCUtils::is_blank( $instance['icon'] ) ? 1 : (int) $instance['icon'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/bestseller.png' ) ? get_stylesheet_directory_uri() . '/images/bestseller.png' : USCES_FRONT_PLUGIN_URL . '/images/bestseller.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}
		$list = WCUtils::is_blank( $instance['list'] ) ? 1 : (int) $instance['list'];

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . esc_html( $title ) . $after_title );
		?>

		<ul class="ucart_widget_body">
		<?php
		if ( 1 === $list ) {
			usces_list_bestseller( $rows_num, $days );
		} else {
			$htm = '';
			for ( $i = 0; $i < $rows_num; $i++ ) {
				$cname = 'code' . ( $i + 1 );
				$code  = esc_html( trim( $instance[ $cname ] ) );
				if ( WCUtils::is_blank( $code ) ) {
					continue;
				}
				$id = $usces->get_postIDbyCode( $code );
				if ( WCUtils::is_blank( $id ) ) {
					continue;
				}
				$post      = get_post( $id );
				$disp_text = apply_filters( 'usces_widget_bestseller_manual_text', esc_html( $post->post_title ), $id );
				$list      = '<li><a href="' . get_permalink( $id ) . '">' . $disp_text . '</a></li>';
				$htm      .= apply_filters( 'usces_filter_bestseller', $list, $post->ID, $i );
			}
			wel_esc_script_e( $htm );
		}
		?>
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
		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}
		if ( ! isset( $instance['rows_num'] ) ) {
			$instance['rows_num'] = '';
		}
		if ( ! isset( $instance['days'] ) ) {
			$instance['days'] = '';
		}
		if ( ! isset( $instance['icon'] ) ) {
			$instance['icon'] = '';
		}
		if ( ! isset( $instance['list'] ) ) {
			$instance['list'] = '';
		}
		if ( ! isset( $instance['code1'] ) ) {
			$instance['code1'] = '';
		}
		if ( ! isset( $instance['code2'] ) ) {
			$instance['code2'] = '';
		}
		if ( ! isset( $instance['code3'] ) ) {
			$instance['code3'] = '';
		}
		if ( ! isset( $instance['code4'] ) ) {
			$instance['code4'] = '';
		}
		if ( ! isset( $instance['code5'] ) ) {
			$instance['code5'] = '';
		}
		if ( ! isset( $instance['code6'] ) ) {
			$instance['code6'] = '';
		}
		if ( ! isset( $instance['code7'] ) ) {
			$instance['code7'] = '';
		}
		if ( ! isset( $instance['code8'] ) ) {
			$instance['code8'] = '';
		}
		if ( ! isset( $instance['code9'] ) ) {
			$instance['code9'] = '';
		}
		if ( ! isset( $instance['code10'] ) ) {
			$instance['code10'] = '';
		}

		$title    = WCUtils::is_blank( $instance['title'] ) ? 'Welcart ' . __( 'best seller', 'usces' ) : esc_attr( $instance['title'] );
		$rows_num = WCUtils::is_blank( $instance['rows_num'] ) ? 10 : (int) $instance['rows_num'];
		$days     = WCUtils::is_blank( $instance['days'] ) ? 30 : (int) $instance['days'];
		$icon     = WCUtils::is_blank( $instance['icon'] ) ? 1 : (int) $instance['icon'];
		$list     = WCUtils::is_blank( $instance['list'] ) ? 1 : (int) $instance['list'];
		$code1    = esc_attr( $instance['code1'] );
		$code2    = esc_attr( $instance['code2'] );
		$code3    = esc_attr( $instance['code3'] );
		$code4    = esc_attr( $instance['code4'] );
		$code5    = esc_attr( $instance['code5'] );
		$code6    = esc_attr( $instance['code6'] );
		$code7    = esc_attr( $instance['code7'] );
		$code8    = esc_attr( $instance['code8'] );
		$code9    = esc_attr( $instance['code9'] );
		$code10   = esc_attr( $instance['code10'] );
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'rows_num' ) ); ?>"><?php esc_html_e( 'number of indication', 'usces' ); ?>: <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'rows_num' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'rows_num' ) ); ?>" type="text" value="<?php wel_esc_script_e( $rows_num ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'days' ) ); ?>"><?php esc_html_e( 'Aggregation period (days)', 'usces' ); ?>: <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'days' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'days' ) ); ?>" type="text" value="<?php wel_esc_script_e( $days ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'list' ) ); ?>"><?php esc_html_e( 'automatic count', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'list' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'list' ) ); ?>">
			<option value="1"<?php selected( $list, 1 ); ?>><?php esc_html_e( 'automatic list', 'usces' ); ?></option>
			<option value="2"<?php selected( $list, 2 ); ?>><?php esc_html_e( 'handwriting list', 'usces' ); ?></option></select></label>
		</p>
		<fieldset><legend><?php esc_html_e( 'handwriting list', 'usces' ); ?></legend>
			<p><?php esc_html_e( 'Please input an article cord.', 'usces' ); ?></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code1' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>1 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code1' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code1' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code1 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code2' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>2 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code2' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code2' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code2 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code3' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>3 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code3' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code3' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code3 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code4' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>4 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code4' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code4' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code4 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code5' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>5 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code5' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code5' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code5 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code6' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>6 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code6' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code6' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code6 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code7' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>7 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code7' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code7' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code7 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code8' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>8 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code8' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code8' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code8 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code9' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>9 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code9' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code9' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code9 ); ?>" /></label></p>
			<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'code10' ) ); ?>"><?php esc_html_e( 'item code', 'usces' ); ?>10 : <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'code10' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'code10' ) ); ?>" type="text" value="<?php wel_esc_script_e( $code10 ); ?>" /></label></p>
		</fieldset>
		<?php
	}
}
