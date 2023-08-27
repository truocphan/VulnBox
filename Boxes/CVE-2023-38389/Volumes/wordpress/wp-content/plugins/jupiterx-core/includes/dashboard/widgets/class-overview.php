<?php
/**
 * Class to register Jupiter X Overview dashboard widget.
 *
 * @package JupiterX_Core\Dashboard
 *
 * @since 1.1.0
 */

/**
 * Registers Jupiter X Overview dashboard widget.
 *
 * @since 1.1.0
 *
 * @package JupiterX_Core\Dashboard
 */
class JupiterX_Overview_Widget {

	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', [ $this, 'register' ] );
		add_action( 'wp_network_dashboard_setup', [ $this, 'register' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Register dashboard widgets.
	 *
	 * @since 1.1.0
	 */
	public function register() {

		if ( ! defined( 'JUPITERX_VERSION' ) ) {
			return;
		}
		/* translators: %s: widget name */
		add_meta_box( 'jupiterx-dashboard-overview', sprintf( esc_html__( '%s Overview', 'jupiterx-core' ), $this->get_widget_name() ), [ $this, 'render_content' ], [ 'dashboard-network', 'dashboard' ], 'side', 'high' );

	}

	/**
	 * Enqueue dashboard scripts.
	 *
	 * @since 1.1.0
	 */
	public function enqueue_scripts() {

		if ( ! defined( 'JUPITERX_VERSION' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, [ 'dashboard-network', 'dashboard' ], true ) ) {
			return;
		}

		$base_folder = version_compare( JUPITERX_VERSION, '1.9.2', '<=' ) ? 'admin/' : '';

		wp_enqueue_style(
			'jupiterx-admin-dashboard',
			JUPITERX_ASSETS_URL . 'dist/css/' . $base_folder . 'dashboard-widgets' . JUPITERX_MIN_CSS . '.css',
			[],
			JUPITERX_VERSION
		);

		wp_enqueue_script(
			'jupiterx-admin-dashboard',
			JUPITERX_ASSETS_URL . 'dist/js/' . $base_folder . 'dashboard-widgets' . JUPITERX_MIN_JS . '.js',
			[ 'jquery' ],
			JUPITERX_VERSION,
			true
		);
	}

	/**
	 * Render content.
	 *
	 * @since 1.1.0
	 */
	public function render_content() {
		$is_network = is_network_admin();

		?>
		<div class="jupiterx-dashboard-widget" data-nonce="<?php echo esc_attr( wp_create_nonce( 'jupiterx_dashboard' ) ); ?>">
		<?php

			$this->render_content_header();

			if ( ! $is_network ) {
				$this->render_content_edits();
			}

			if ( $is_network ) {
				$this->render_content_tools();
			}

			$this->render_content_news();

			$this->render_content_footer();

		?>
		</div>
		<?php
	}

	/**
	 * Render header content partial.
	 *
	 * @since 1.1.0
	 */
	private function render_content_header() {
		?>
		<div class="jupiterx-admin-widget-overview-header">
			<div class="jupiterx-admin-widget-overview-branding">
				<div class="jupiterx-logo">
					<?php $this->get_widget_logo(); ?>
				</div>
				<span class="jupiterx-admin-widget-overview-version"><?php echo esc_html( $this->get_widget_name() ); ?> v<?php echo esc_html( JUPITERX_VERSION ); ?></span>
			</div>
			<?php if ( current_user_can( 'administrator' ) ) : ?>
				<div class="jupiterx-admin-widget-overview-buttons-wrapper">
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary"><?php echo esc_html_e( 'Theme Styles', 'jupiterx-core' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=jupiterx' ) ); ?>" class="button button-secondary"><?php echo esc_html_e( 'Dashboard', 'jupiterx-core' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render recently edited content partial.
	 *
	 * @since 1.1.0
	 */
	private function render_content_edits() {
		$args = [
			'post_type'      => [ 'post', 'portfolio', 'page' ],
			'post_status'    => [ 'publish', 'draft' ],
			'posts_per_page' => '3',
			'orderby'        => 'modified',
		];

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) : ?>
			<div class="jupiterx-admin-widget-overview-recent">
				<h3 class="jupiterx-admin-widget-overview-heading"><?php esc_html_e( 'Recently Edited', 'jupiterx-core' ); ?></h3>
				<ul>
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();

						$date = date_i18n( _x( 'M jS', 'Dashboard Overview Widget Recently Date', 'jupiterx-core' ), get_the_modified_time( 'U' ) );
						?>
						<li class="jupiterx-admin-widget-overview-recent-post">
							<a href="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?> " class="jupiterx-admin-widget-overview-recent-post-link"><?php the_title(); ?> <span class="dashicons dashicons-edit"></span></a> <span><?php echo esc_html( $date ); ?>, <?php the_time(); ?></span>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		<?php endif;
	}

	/**
	 * Render tools content partial.
	 *
	 * @since 1.1.0
	 */
	private function render_content_tools() {
		$tools = [
			'flush-network-cache' => [
				'title' => esc_html__( 'Flush network cache', 'jupiterx-core' ),
				'desc' => esc_html__( "Regenerate Jupiter X and Elementor's cache in all network sites.", 'jupiterx-core' ),
			],
		];

		?>
		<div class="jupiterx-admin-widget-overview-tools">
			<h3 class="jupiterx-admin-widget-overview-heading"><?php esc_html_e( 'Tools', 'jupiterx-core' ); ?></h3>
			<ul>
				<?php foreach ( $tools as $tool_slug => $tool ) : ?>
					<li class="jupiterx-admin-widget-overview-tool">
						<div>
							<span class="jupiterx-admin-widget-overview-tool-title"><?php echo esc_html( $tool['title'] ); ?></span>
							<span class="jupiterx-admin-widget-overview-tool-desc jupiterx-ajax-response"><?php echo esc_html( $tool['desc'] ); ?></span>
						</div>
						<button type="button" class="button button-primary" data-jupiterx-tool="<?php echo esc_html( $tool_slug ); ?>"><?php esc_html_e( 'Run', 'jupiterx-core' ); ?></button>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render news & updates content partial.
	 *
	 * @since 1.1.0
	 */
	private function render_content_news() {
		$blog_feed = $this->get_blog_posts();

		if ( ! empty( $blog_feed ) && $this->has_white_label( 'white_label_news' ) ) : ?>
			<div class="jupiterx-admin-widget-overview-feed">
				<h3 class="jupiterx-admin-widget-overview-heading"><?php esc_html_e( 'News & Updates', 'jupiterx-core' ); ?></h3>
				<ul class="jupiterx-admin-widget-overview-posts">
					<?php $badge = true; ?>
					<?php foreach ( $blog_feed as $feed_item ) :
						$utm = [
							'utm_source' => 'JupiterXOverviewWidget',
							'utm_medium' => 'JupiterXWPDashboard',
						];
						$url = add_query_arg( $utm, $feed_item['link'] );
					?>
						<li class="jupiterx-admin-widget-overview-post">
							<a href="<?php echo esc_url( $url ); ?>" class="jupiterx-admin-widget-overview-post-link" target="_blank">
								<?php if ( $badge ) : ?>
									<span class="jupiterx-admin-widget-overview-badge"><?php esc_html_e( 'New', 'jupiterx-core' ); ?></span>
									<?php $badge = false; ?>
								<?php endif; ?>
								<?php echo esc_html( $feed_item['title']['rendered'] ); ?>
							</a>
							<p class="jupiterx-admin-widget-overview-post-description"><?php echo esc_html( wp_strip_all_tags( $feed_item['excerpt']['rendered'] ) ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif;
	}

	/**
	 * Render footer content partial.
	 *
	 * @since 1.1.0
	 */
	private function render_content_footer() {
		if (
			'1' === jupiterx_get_option( 'white_label' ) &&
			'1' !== jupiterx_get_option( 'white_label_help_links' )
		) {
			return;
		}

		$links = [
			'blog' => [
				'title'  => esc_html__( 'Blog', 'jupiterx-core' ),
				'link'   => 'https://themes.artbees.net/blog/',
				'target' => '_blank',
			],
			'help' => [
				'title'  => esc_html__( 'Help', 'jupiterx-core' ),
				'link'   => 'https://themes.artbees.net/docs/getting-help-from-the-artbees-support/',
				'target' => '_blank',
			],
		];

		if ( function_exists( 'jupiterx_is_pro' ) && ! jupiterx_is_pro() ) {
			$links['upgrade'] = [
				'title'  => __( 'Upgrade', 'jupiterx-core' ),
				'link'   => 'https://themeforest.net/item/jupiter-multipurpose-responsive-theme/5177775?ref=artbees&utm_source=DashboardNewsUpdatesWidgetUpgradeLink&utm_medium=AdminUpgradePopup&utm_campaign=FreeJupiterXAdminUpgradeCampaign',
				'target' => '_blank',
			];
		}

		?>
		<div class="jupiterx-admin-widget-overview-footer">
			<ul>
				<?php foreach ( $links as $link_slug => $link ) : ?>
					<li class="jupiterx-admin-widget-overview-footer-<?php echo esc_attr( $link_slug ); ?>">
						<a href="<?php echo esc_url( $link['link'] ); ?>" <?php echo isset( $link['class'] ) ? 'class="' . esc_attr( $link['class'] ) . '"' : ''; ?> <?php echo isset( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
							<?php echo esc_html( $link['title'] ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'jupiterx-core' ); ?></span>
							<span aria-hidden="true" class="dashicons dashicons-external"></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Function to fetch bog posts.
	 *
	 * @since 1.1.0
	 *
	 * @return array $feed List of blog posts.
	 */
	private function get_blog_posts() {
		$feed = get_transient( 'jupiterx_admin_widget_posts' );

		if ( ! empty( $feed ) ) {
			return $feed;
		}

		$response = wp_safe_remote_get(
			add_query_arg(
				[ 'per_page' => 3 ],
				'https://themes.artbees.net/wp-json/wp/v2/posts'
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$feed = json_decode( wp_remote_retrieve_body( $response ), true );

		set_transient( 'jupiterx_admin_widget_posts', $feed, 12 * HOUR_IN_SECONDS );

		return $feed;
	}

	/**
	 * Function for get widget name.
	 *
	 * @since 1.19.0
	 *
	 * @return string name of the widget.
	 */
	private function get_widget_name() {
		$widget_name = __( 'Jupiter X', 'jupiterx-core' );

		if ( $this->has_white_label( 'white_label_text_occurence' ) ) {
			$widget_name = jupiterx_get_option( 'white_label_text_occurence' );
		}

		return $widget_name;
	}

	/**
	 * Function for get widget logo.
	 *
	 * @since 1.19.0
	 */
	private function get_widget_logo() {
		if ( ! $this->has_white_label( 'white_label_cpanel_logo' ) ) {
			echo '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><path d="M28 0H4C1.8 0 0 1.8 0 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4V4c0-2.2-1.8-4-4-4zm-2.3 25.5h-2.9c-.9 0-1.8-.4-2.4-1.1l-3.9-4.7v-.2l2.3-2.8c.1-.1.2-.1.2 0l6.8 8.3c.3.2.2.5-.1.5zM25.9 7L11.5 24.4c-.6.7-1.4 1.1-2.4 1.1H6.3c-.3 0-.4-.3-.2-.5l7.3-8.8c.1-.1.1-.3 0-.4L6.1 7c-.2-.2-.1-.5.2-.5h2.9c.9 0 1.8.4 2.4 1.1l4.2 5.1c.1.1.4.1.5 0l4.2-5.1c.6-.7 1.4-1.1 2.4-1.1h2.9c.2 0 .3.3.1.5z" fill="#07f"/></svg>';
		} else {
			$white_label_cpanel_logo = jupiterx_get_option( 'white_label_cpanel_logo' );
			$widget_name             = $this->get_widget_name();

			echo "<img src='$white_label_cpanel_logo' width='32px' height='32px' alt='$widget_name'>";
		}
	}

	/**
	 * Function for checking white label.
	 *
	 * @since 1.19.0
	 *
	 * @return bool if it has value return true else return false.
	 */
	private function has_white_label( $white_label_key ) {
		if ( ! function_exists( 'jupiterx_is_white_label' ) ) {
			return false;
		}

		if ( ! jupiterx_is_white_label() || ! jupiterx_get_option( $white_label_key ) ) {
			return false;
		}

		return true;
	}


}

new JupiterX_Overview_Widget();
