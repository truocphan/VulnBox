<?php
/**
 * Welcart Login Widget
 *
 * @package Welcart
 */

/**
 * Welcart_login Class
 */
class Welcart_login extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( false, $name = 'Welcart ' . __( 'Log-in', 'usces' ) );
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
		$title    = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Log-in', 'usces' ) : $instance['title'];
		$icon     = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		$img_path = file_exists( get_stylesheet_directory() . '/images/login.png' ) ? get_stylesheet_directory_uri() . '/images/login.png' : USCES_FRONT_PLUGIN_URL . '/images/login.png';
		if ( 1 === $icon ) {
			$before_title .= '<img src="' . $img_path . '" alt="' . $title . '" />';
		}

		wel_esc_script_e( $before_widget );
		wel_esc_script_e( $before_title . esc_html( $title ) . $after_title );
		?>

		<ul class="ucart_login_body ucart_widget_body"><li>

		<?php ob_start(); ?>

		<div class="loginbox">
		<?php if ( ! usces_is_login() ) : ?>
			<form name="loginwidget" id="loginformw" action="<?php echo esc_url( USCES_MEMBER_URL ); ?>" method="post">
			<p>
			<label><?php esc_html_e( 'e-mail adress', 'usces' ); ?><br />
			<input type="text" name="loginmail" id="loginmailw" class="loginmail" value="<?php usces_remembername(); ?>" size="20" /></label><br />
			<label><?php esc_html_e( 'password', 'usces' ); ?><br />
			<input type="password" name="loginpass" id="loginpassw" class="loginpass" size="20" autocomplete="off" /></label><br />
			<label><input name="rememberme" type="checkbox" id="remembermew" value="forever" /> <?php esc_html_e( 'Remember Me', 'usces' ); ?></label></p>
			<p class="submit">
			<input type="submit" name="member_login" id="member_loginw" value="<?php esc_attr_e( 'Log-in', 'usces' ); ?>" />
			</p>
			<?php
			echo apply_filters( 'usces_filter_login_inform', null );
			$noncekey = 'post_member' . $usces->get_uscesid( false );
			wp_nonce_field( $noncekey, 'wel_nonce' );
			?>
			</form>
			<a href="<?php echo esc_url( USCES_LOSTMEMBERPASSWORD_URL ); ?>" title="<?php esc_attr_e( 'Pssword Lost and Found', 'usces' ); ?>"><?php esc_html_e( 'Lost your password?', 'usces' ); ?></a><br />
			<a href="<?php echo esc_url( USCES_NEWMEMBER_URL ); ?>" title="<?php esc_attr_e( 'New enrollment for membership.', 'usces' ); ?>"><?php esc_html_e( 'New enrollment for membership.', 'usces' ); ?></a>
			<?php
		else :
			?>
			<div><?php echo sprintf( _x( '%s', 'honorific', 'usces' ), usces_the_member_name( 'return' ) ); ?></div>
			<?php wel_esc_script_e( usces_loginout() ); ?><br />
			<a href="<?php echo esc_url( USCES_MEMBER_URL ); ?>" class="login_widget_mem_info_a"><?php esc_html_e( 'Membership information', 'usces' ); ?></a>
		<?php endif; ?>
		</div>

		<?php
		$loginbox = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'usces_filter_login_widget', $loginbox, $args, $instance );
		?>

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
		$title = ( ! isset( $instance['title'] ) || WCUtils::is_blank( $instance['title'] ) ) ? 'Welcart ' . __( 'Log-in', 'usces' ) : esc_attr( $instance['title'] );
		$icon  = ( ! isset( $instance['icon'] ) || WCUtils::is_blank( $instance['icon'] ) ) ? 1 : (int) $instance['icon'];
		?>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'usces' ); ?> <input class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'title' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php wel_esc_script_e( $title ); ?>" /></label></p>
		<p><label for="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>"><?php esc_html_e( 'display of icon', 'usces' ); ?>: <select class="widefat" id="<?php wel_esc_script_e( $this->get_field_id( 'icon' ) ); ?>" name="<?php wel_esc_script_e( $this->get_field_name( 'icon' ) ); ?>">
			<option value="1"<?php selected( $icon, 1 ); ?>><?php esc_html_e( 'Indication', 'usces' ); ?></option>
			<option value="2"<?php selected( $icon, 2 ); ?>><?php esc_html_e( 'Non-indication', 'usces' ); ?></option></select></label>
		</p>
		<?php
	}
}
