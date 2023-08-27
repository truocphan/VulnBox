<?php
/**
 * This class handles survey notification bar notice.
 *
 * @since 1.18.0
 *
 * @package JupiterX\Framework\Admin\Notices
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manager class.
 *
 * @since 1.18.0
 *
 * @package JupiterX\Framework\Admin\Notices
 */
class JupiterX_Admin_Notice_Feedback_Notification_Bar {
	/**
	 * Current user.
	 *
	 * @var WP_User
	 */
	public $user;

	/**
	 * Meta key.
	 */
	const META_KEY = 'feedback_notification_bar';

	/**
	 * Class Constructor.
	 *
	 * @since 1.18.0
	 * @access public
	 */
	public function __construct() {
		$version = jupiterx_is_pro() ? '' : '_lite';

		add_action( 'publish_page', [ $this, 'update_elementor_pages' ] );
		add_action( 'wxr_importer.processed.post', [ $this, 'update_imported_elementor_pages' ], 10, 2 );
		add_action( 'admin_notices', [ $this, "admin_notice{$version}" ] );
		add_action( 'wp_ajax_jupiterx_dismiss_feedback_notification_bar_notice', [ $this, 'dismiss_notice' ] );

		$this->user = wp_get_current_user();
	}

	/**
	 * Dismiss notice
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @return void
	 */
	public function dismiss_notice() {
		if ( ! current_user_can( 'edit_others_posts' ) || ! current_user_can( 'edit_others_pages' ) ) {
			wp_send_json_error( 'You do not have access to this section', 'jupiterx' );
		}

		check_ajax_referer( 'jupiterx_feedback_notification_bar_nonce' );

		update_user_meta( $this->user->ID, self::META_KEY . '_dismissed', 1 );

		wp_send_json_success();
	}
	/**
	 * Register notice.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( ! $this->show_notice() ) {
			return;
		}

		$nonce = wp_create_nonce( 'jupiterx_feedback_notification_bar_nonce' );
		?>
			<div data-nonce="<?php echo esc_attr( $nonce ); ?>" class="jupiterx-feedback-notification-bar-notice notice notice-warning is-dismissible">
				<div class="jupiterx-feedback-notification-bar-notice-inner">
					<div class="jupiterx-feedback-notification-bar-notice-logo">
						<img src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/jupiterx-notice-logo.png' ); ?>" alt="<?php esc_attr_e( 'Jupiter X', 'jupiterx' ); ?>" />
					</div>
					<div class="jupiterx-feedback-notification-bar-notice-content">
						<!-- STEP 1 -->
						<div class="jupiterx-feedback-notification-bar-notice-step" data-step="1">
							<p><?php esc_html_e( 'How do you like Jupiter X?', 'jupiterx' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<button class="button button-primary" data-step="2"><?php esc_html_e( 'Liked it', 'jupiterx' ); ?></button>
								<button class="button-secondary" data-step="3"><?php esc_html_e( 'Disliked it', 'jupiterx' ); ?></button>
							</div>
						</div>
						<!-- STEP 2 -->
						<div class="jupiterx-feedback-notification-bar-notice-step hidden" data-step="2">
							<p><?php esc_html_e( 'Please help us by rating Jupiter X', 'jupiterx' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<a href="<?php echo esc_url( 'https://themeforest.net/downloads?utm_source=JXDashboardRatingRequest' ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Rate Jupiter X', 'jupiterx' ); ?></a>
								<button class="button-secondary"><?php esc_html_e( 'Discard', 'jupiterx' ); ?> </button>
							</div>
						</div>
						<!-- STEP 3 -->
						<div class="jupiterx-feedback-notification-bar-notice-step hidden" data-step="3">
							<p><?php esc_html_e( 'Would you like to share the problem with us?', 'jupiterx' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<a href="<?php echo esc_url( 'https://themes.artbees.net/support/jupiterx/' ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Contact support', 'jupiterx' ); ?></a>
								<a href="<?php echo esc_url( 'https://themes.artbees.net/report-a-bug/' ); ?>" class="button-secondary" target="_blank"><?php esc_html_e( 'Report a bug', 'jupiterx' ); ?></a>
								<button class="button-secondary"><?php esc_html_e( 'Discard', 'jupiterx' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php
	}

	/**
	 * Register notice for jupiterx lite.
	 *
	 * @since 1.23.0
	 * @access public
	 *
	 * @return void
	 */
	public function admin_notice_lite() {
		if ( ! $this->show_notice() ) {
			return;
		}

		$nonce = wp_create_nonce( 'jupiterx_feedback_notification_bar_nonce' );
		?>
			<div data-nonce="<?php echo esc_attr( $nonce ); ?>" class="jupiterx-feedback-notification-bar-notice notice notice-warning is-dismissible">
				<div class="jupiterx-feedback-notification-bar-notice-inner">
					<div class="jupiterx-feedback-notification-bar-notice-logo">
						<img src="<?php echo esc_url( JUPITERX_ADMIN_ASSETS_URL . 'images/jupiterx-notice-logo.png' ); ?>" alt="<?php esc_attr_e( 'JupiterX Lite', 'jupiterx-lite' ); ?>" />
					</div>
					<div class="jupiterx-feedback-notification-bar-notice-content">
						<!-- STEP 1 -->
						<div class="jupiterx-feedback-notification-bar-notice-step" data-step="1">
							<p><?php esc_html_e( 'How do you like JupiterX Lite?', 'jupiterx-lite' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<button class="button button-primary" data-step="2"><?php esc_html_e( 'Liked it', 'jupiterx-lite' ); ?></button>
								<button class="button-secondary" data-step="3"><?php esc_html_e( 'Disliked it', 'jupiterx-lite' ); ?></button>
							</div>
						</div>
						<!-- STEP 2 -->
						<div class="jupiterx-feedback-notification-bar-notice-step hidden" data-step="2">
							<p><?php esc_html_e( 'Please help us by rating JupiterX Lite', 'jupiterx-lite' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<a href="<?php echo esc_url( 'https://wordpress.org/themes/jupiterx-lite/' ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Rate JupiterX Lite', 'jupiterx-lite' ); ?></a>
								<button class="button-secondary"><?php esc_html_e( 'Discard', 'jupiterx-lite' ); ?> </button>
							</div>
						</div>
						<!-- STEP 3 -->
						<div class="jupiterx-feedback-notification-bar-notice-step hidden" data-step="3">
							<p><?php esc_html_e( 'Would you like to share the problem with us?', 'jupiterx-lite' ); ?></p>
							<div class="jupiterx-feedback-notification-bar-notice-step-actions">
								<a href="<?php echo esc_url( 'https://themes.artbees.net/support/jupiterx/' ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Contact support', 'jupiterx-lite' ); ?></a>
								<a href="<?php echo esc_url( 'https://themes.artbees.net/report-a-bug/' ); ?>" class="button-secondary" target="_blank"><?php esc_html_e( 'Report a bug', 'jupiterx-lite' ); ?></a>
								<button class="button-secondary"><?php esc_html_e( 'Discard', 'jupiterx-lite' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Show notice if all conditions are satisfied.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function show_notice() {
		if ( function_exists( 'jupiterx_is_white_label' ) && jupiterx_is_white_label() ) {
			return false;
		}

		if ( ! jupiterx_is_pro() ) {
			return false;
		}

		if ( ! in_array( 'administrator', (array) $this->user->roles, true ) ) {
			return false;
		}

		if ( strval( 1 ) === get_user_meta( $this->user->ID, self::META_KEY . '_dismissed', true ) ) {
			return false;
		}

		if ( ! $this->has_elementor_pages() ) {
			return false;
		}

		if ( ! $this->show_since_last_page_created( 10 ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check user has atleast n number of elementor pages when Jupiter X was active.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function has_elementor_pages() {
		$pages = get_user_meta( $this->user->ID, 'jupiterx_elementor_pages_created', true );
		$count = 0;

		if ( is_array( $pages ) ) {
			$count = count( $pages );
		}

		if ( ! empty( jupiterx_get_option( 'template_installed_id' ) ) ) {
			return $count >= 10;
		}

		return $count >= 5;
	}

	/**
	 * Keep record of elementor pages created when Jupiter X was active.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @param integer $post_id Page Id.
	 * @return void
	 */
	public function update_elementor_pages( $post_id ) {
		if ( ! jupiterx_is_pro() ) {
			return;
		}

		$built_with_elementor = ! ! get_post_meta( $post_id, '_elementor_edit_mode', true );

		if ( ! $built_with_elementor ) {
			return;
		}

		$pages = get_user_meta( $this->user->ID, 'jupiterx_elementor_pages_created', true );

		if ( empty( $pages ) || ! is_array( $pages ) ) {
			$pages = [];
		}

		if ( ! in_array( $post_id, $pages, true ) ) {
			update_user_meta( $this->user->ID, 'jupiterx_elementor_last_page_created_at', time() );
		}

		$pages[] = $post_id;
		$pages   = array_unique( $pages );

		update_user_meta( $this->user->ID, 'jupiterx_elementor_pages_created', $pages );
	}

	/**
	 * Update Elementor pages count on full/partial import.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @param integer $post_id Post Id.
	 * @param array   $data Post details.
	 * @return void
	 */
	public function update_imported_elementor_pages( $post_id, $data ) {
		if ( 'page' !== $data['post_type'] || 'publish' !== $data['post_status'] ) {
			return;
		}

		$this->update_elementor_pages( $post_id );
	}

	/**
	 * Show after $days since last elementor page is created while Jupiter X was active.
	 *
	 * @since 1.18.0
	 * @access public
	 *
	 * @param integer $days Number of days.
	 *
	 * @return boolean
	 */
	public function show_since_last_page_created( $days ) {
		$current              = time();
		$last_page_created_at = get_user_meta( $this->user->ID, 'jupiterx_elementor_last_page_created_at', true );

		if ( empty( $last_page_created_at ) ) {
			return false;
		}

		$diff = $current - $last_page_created_at;

		$day_in_seconds = apply_filters( 'jupiterx_feedback_notification_bar_dis', DAY_IN_SECONDS );

		return abs( round( $diff / $day_in_seconds ) ) >= $days;
	}
}

new JupiterX_Admin_Notice_Feedback_Notification_Bar();
