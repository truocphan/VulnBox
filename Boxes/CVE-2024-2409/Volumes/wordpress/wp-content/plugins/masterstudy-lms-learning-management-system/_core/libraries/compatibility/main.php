<?php

new STM_LMS_Compatibility();

class STM_LMS_Compatibility {

	private static $free         = '2.9.38';
	private static $theme        = '4.6.0';
	private static $pro          = '4.0.0';
	private static $theme_banner = false;

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'init' ) );
	}

	public function check_theme_version() {
		$current_theme = wp_get_theme();
		return version_compare( self::$theme, $current_theme->version ) > 0;
	}

	public function check_pro_plugin_version() {
		if ( defined( 'STM_LMS_PRO_FILE' ) ) {
			$plugin_data = get_plugin_data( STM_LMS_PRO_FILE );
			return version_compare( self::$pro, $plugin_data['Version'] ) > 0;
		}
		return false;
	}

	public function check_free_plugin_version() {
		if ( defined( 'STM_LMS_VERSION' ) ) {
			return STM_LMS_VERSION === self::$free;
		}
		return false;
	}

	public function init() {
		if ( self::$theme_banner && $this->check_theme_version() ) { ?>
			<div class="notice notice-lms notice-lms-go-to-pages notice-lms-compatibility">
				<div class="notice-lms-go-to-pages-icon">
					<i class="fa fa-exclamation"></i>
				</div>
				<div class="notice-lms-go-to-pages-content">
					<p>
						<strong>
							<?php esc_html_e( 'Please update MasterStudy Theme!', 'masterstudy-lms-learning-management-system' ); ?>
						</strong>
					</p>
					<p>
						<?php esc_html_e( 'The current version of MasterStudy LMS is not compatible with old versions of the MasterStudy Theme, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system' ); ?>
					</p>
				</div>
			</div>
			<?php
		}
		if ( $this->check_pro_plugin_version() ) {
			?>
			<div class="notice notice-lms notice-lms-go-to-pages notice-lms-compatibility">
				<div class="notice-lms-go-to-pages-icon">
					<i class="fa fa-exclamation"></i>
				</div>
				<?php if ( stm_lms_is_masterstudy_theme() && $this->check_theme_version() ) { ?>
					<div class="notice-lms-go-to-pages-content">
						<p>
							<strong>
								<?php esc_html_e( 'Please update MasterStudy Theme and MasterStudy LMS Learning Management System PRO!', 'masterstudy-lms-learning-management-system' ); ?>
							</strong>
						</p>
						<p>
							<?php esc_html_e( 'The current version of MasterStudy LMS is not compatible with old versions of MasterStudy Theme and MasterStudy LMS Learning Management System PRO, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
					</div>
					<div class="notice-lms-go-to-pages-button">
						<a href="https://docs.stylemixthemes.com/masterstudy-theme-documentation/getting-started/how-to-update-masterstudy" target="_blank">update theme</a>
						<a href="<?php echo esc_attr( get_admin_url() ) . 'admin.php?page=stm-admin-plugins#has_update'; ?>" target="_blank">update plugin</a>
					</div>
				<?php } elseif ( stm_lms_is_masterstudy_theme() && ! $this->check_theme_version() ) { ?>
					<div class="notice-lms-go-to-pages-content">
						<p>
							<strong>
								<?php esc_html_e( 'Please update MasterStudy LMS Learning Management System PRO!', 'masterstudy-lms-learning-management-system' ); ?>
							</strong>
						</p>
						<p>
							<?php esc_html_e( 'The current version of MasterStudy LMS is not compatible with old versions of MasterStudy LMS Learning Management System PRO, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
					</div>
					<div class="notice-lms-go-to-pages-button">
						<a href="<?php echo esc_attr( get_admin_url() ) . 'admin.php?page=stm-admin-plugins#has_update'; ?>" target="_blank">update plugin</a>
					</div>
				<?php } elseif ( function_exists( 'mslms_appsumo' ) ) { ?>
					<div class="notice-lms-go-to-pages-content">
						<p>
							<strong>
								<?php esc_html_e( 'Please update MasterStudy LMS Learning Management System PRO!', 'masterstudy-lms-learning-management-system' ); ?>
							</strong>
						</p>
						<p>
							<?php esc_html_e( 'The current version of MasterStudy LMS is not compatible with old versions of the MasterStudy LMS Learning Management System PRO, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
					</div>
					<div class="notice-lms-go-to-pages-button">
						<a href="https://support.stylemixthemes.com/my-account/appsumo" target="_blank">download</a>
					</div>
				<?php } else { ?>
					<div class="notice-lms-go-to-pages-content">
						<p>
							<strong>
								<?php esc_html_e( 'Please update MasterStudy LMS Learning Management System PRO!', 'masterstudy-lms-learning-management-system' ); ?>
							</strong>
						</p>
						<p>
							<?php esc_html_e( 'The current version of MasterStudy LMS is not compatible with old versions of the MasterStudy LMS Learning Management System PRO, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
					</div>
					<div class="notice-lms-go-to-pages-button">
						<a href="<?php echo esc_attr( get_admin_url() ) . 'plugins.php'; ?>" target="_blank">update</a>
					</div>
				<?php } ?>
			</div>
			<?php
		}
		global $pagenow;
		if ( $this->check_free_plugin_version() && 'update.php' !== $pagenow && 'post.php' !== $pagenow ) {
			?>
			<div class="notice notice-lms-new-widget">
				<a href="https://youtu.be/_yJdPSVVLo8" class="notice-lms-new-widget_link" target="_blank"></a>
				<div class="notice-lms-new-widget_wrapper">
					<div class="notice-lms-new-widget_icon_wrapper">
						<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/table_updater/notice_icon.png' ); ?>" class="notice-lms-new-widget_icon">
					</div>
					<span class="notice-lms-new-widget_divider"></span>
					<div class="notice-lms-new-widget_text">
						<h3 class="notice-lms-new-widget_text_title">
							<?php
							echo sprintf(
								/* translators: %s: string */
								esc_html__( '4-in-1 %s widgets', 'masterstudy-lms-learning-management-system' ),
								'<span class="notice-lms-new-widget_text_title_highlight">'
								. esc_html__( 'elementor', 'masterstudy-lms-learning-management-system' ) . '</span>'
							);
							?>
						</h3>
						<p class="notice-lms-new-widget_text_subtitle">
							<?php echo esc_html_e( 'Simpler, faster & just as powerful', 'masterstudy-lms-learning-management-system' ); ?>
						</p>
					</div>
					<div class="notice-lms-new-widget_button_wrapper">
						<a href="https://youtu.be/_yJdPSVVLo8" class="notice-lms-new-widget_button">
							<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/table_updater/play_video.png' ); ?>" class="notice-lms-new-widget_button_icon">
							<?php echo esc_html_e( 'Watch video', 'masterstudy-lms-learning-management-system' ); ?>
						</a>
					</div>
					<img src="<?php echo esc_url( STM_LMS_URL . 'assets/img/table_updater/course_card.png' ); ?>" class="notice-lms-new-widget_card-img">
				</div>
				<div class="notice-lms-new-widget_bg"></div>
			</div>
			<?php
		}
	}
}
