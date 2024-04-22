<?php

if ( ! class_exists( 'StylemixAnnouncements' ) ) {
	class StylemixAnnouncements {
		public $apiurl       = 'https://stylemixthemes.com/api/announcement.json';
		public $announcement = array();

		public function __construct() {
			add_action( 'wp_dashboard_setup', array( $this, 'dashboard_changelog' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'dashboard_news' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'wp_ajax_stm_lms_ajax_add_feedback', array( $this, 'ajax_add_feedback' ) );

			if ( ! get_option( 'stm_lms_feedback_added', false ) ) {
				add_action( 'wpcfto_after_tab_nav', array( $this, 'add_feedback_button' ) );
				add_action( 'admin_footer', array( $this, 'render_feedback_popup' ) );
			}

			add_action( 'wpcfto_after_tab_nav', array( $this, 'add_version' ) );

		}

		public function dashboard_changelog() {
			add_meta_box(
				'pearl_dashboard_announcement',
				'Announcement by StylemixThemes',
				array( $this, 'announcement_screen' ),
				'dashboard',
				'side',
				'high'
			);
		}

		public function dashboard_news() {
			add_meta_box(
				'pearl_dashboard_news',
				'News by StylemixThemes',
				array( $this, 'news_screen' ),
				'dashboard',
				'side',
				'high'
			);
		}

		public function announcement_screen() { ?>
			<div id="pearl-announcement">
				<div v-for="announcement in announcements">
					<div v-html="announcement.content"></div>
				</div>
			</div>
			<?php
		}

		public function news_screen() {
			?>
			<div id="pearl-changelog">
				<ul>
					<li v-for="item in news">
						<a v-bind:href="item.link" v-html="item.title.rendered" target="_blank"></a>
					</li>
				</ul>
				<p class="community-events-footer">
					<a href="https://themeforest.net/user/stylemixthemes/portfolio?ref=stylemixthemes" target="_blank">Themes
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
					</a>
					<a href="https://stylemixthemes.com/wp?utm_source=dashnotice" target="_blank">Blog
						<span aria-hidden="true" class="dashicons dashicons-external"></span>
					</a>
				</p>
			</div>
			<?php
		}

		public function scripts( $hook ) {
			if ( 'index.php' === $hook ) {
				$theme_info = time();
				$assets     = STM_LMS_LIBRARY_URL . 'announcement/assets/';
				wp_enqueue_style( 'milligram', $assets . 'custom.css', null, $theme_info, 'all' );

				wp_enqueue_script( 'vue.js', $assets . 'vue.min.js', null, $theme_info, true );
				wp_enqueue_script( 'vue-resource.js', $assets . 'vue-resource.js', array( 'vue.js' ), $theme_info, true );
				wp_enqueue_script( 'pearl-vue.js', $assets . 'vue.js', array( 'vue.js' ), $theme_info, true );
			}
		}

		public function ajax_add_feedback() {
			update_option( 'stm_lms_feedback_added', true );
		}

		public function add_feedback_button() {
			wp_enqueue_style( 'stm-lms-feedback', STM_LMS_LIBRARY_URL . 'announcement/assets/feedback.css', array(), STM_LMS_VERSION );
			wp_enqueue_script( 'stm-lms-feedback', STM_LMS_LIBRARY_URL . 'announcement/assets/feedback.js', array(), STM_LMS_VERSION, true );

			echo '<a href="#" class="ms-feedback-button">Feedback
				<img src="' . esc_url( STM_LMS_LIBRARY_URL . 'announcement/assets/icons/feedback.svg' ) . '">
			</a>';
		}

		public function add_version() {
			wp_enqueue_style( 'stm-lms-feedback', STM_LMS_LIBRARY_URL . 'announcement/assets/feedback.css', array(), STM_LMS_VERSION );

			$plugin_data = get_plugin_data( MS_LMS_FILE );
			/* translators: %s: Plugin Version */
			echo '<span class="ms-feedback-version">' . esc_html( sprintf( __( 'Version: %s', 'masterstudy-lms-learning-management-system' ), $plugin_data['Version'] ) ) . '</span>';
		}

		public function render_feedback_popup() {
			$feedback_template = STM_LMS_LIBRARY . '/announcement/feedback.php';

			if ( file_exists( $feedback_template ) ) {
				require_once $feedback_template;
			}
		}

		public static function get_ticket_url() {
			$type = defined( 'STM_LMS_PRO_PATH' ) ? 'support' : 'pre-sale';

			return "https://support.stylemixthemes.com/tickets/new/{$type}?item_id=26";
		}

	}

	new StylemixAnnouncements();
}
