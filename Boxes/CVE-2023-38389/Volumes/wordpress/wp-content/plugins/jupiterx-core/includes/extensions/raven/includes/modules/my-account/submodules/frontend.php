<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Submodules;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;

/**
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Frontend {
	private $navigation;
	private $settings;

	public function __construct( $widget ) {
		$this->navigation          = new Navigation( $widget );
		$this->settings            = $widget->get_settings_for_display();
		$this->default_content_key = -1;
	}

	/**
	 * Renders the content of My Account widget on the front-end.
	 *
	 * Renders by means of woocommerce native shortcode, but wrapped in a div for styling purposes.
	 *
	 * @param object $widget my account widget instance
	 * @access public
	 * @since 2.5.0
	 */
	public function render_frontend() {
		$this->is_hidden_dashboard = $this->check_dashboard_status();
		$current_endpoint          = $this->get_current_endpoint( $this->navigation->tabs );

		// Add actions & filters before displaying our Widget.
		if ( true === $this->is_hidden_dashboard && 'dashboard' === $current_endpoint ) {
			remove_all_actions( 'woocommerce_account_content' );
		}

		add_action( 'woocommerce_account_navigation', [ $this->navigation, 'render_custom_navigation' ], 1 );
		add_action( 'woocommerce_account_' . JX_MY_ACCOUNT_CUSTOM_ENDPOINT . '_endpoint', [ $this, 'add_custom_tabs_template_hook' ] );
		add_filter( 'woocommerce_account_menu_item_classes', [ $this, 'add_custom_tabs_classes' ], 10, 1 );
		add_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 2 );
		add_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		add_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'get_myaccount_override_permalink' ], 10, 1 );
		add_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'get_logout_override_redirect_url' ], 10, 1 );

		?>
		<div class="<?php echo 'raven-my-account-tab raven-my-account-tab__' . $current_endpoint; ?>">
			<span class="elementor-hidden">[[woocommerce_my_account]]</span>
			<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
		</div>
		<?php

		// Remove actions & filters after displaying our Widget.
		remove_action( 'woocommerce_account_navigation', [ $this->navigation, 'render_custom_navigation' ], 2 );
		remove_filter( 'woocommerce_account_menu_item_classes', [ $this, 'add_custom_tabs_classes' ], 10 );
		remove_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 5 );
		remove_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		remove_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'get_myaccount_override_permalink' ], 10, 1 );
		remove_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'get_logout_override_redirect_url' ], 10, 1 );
	}

	/**
	 * Get Current Endpoint
	 *
	 * Used to determine which page Account Page the user is on currently.
	 * This is used so we can add a unique wrapper class around the page's content.
	 *
	 * @access private
	 * @static
	 * @return string
	 * @since 2.5.0
	 */
	private function get_current_endpoint( $tabs ) {
		global $wp_query;
		$current = '';

		if ( isset( $wp_query->query[ JX_MY_ACCOUNT_CUSTOM_ENDPOINT ] ) ) {
			$current = $wp_query->query[ JX_MY_ACCOUNT_CUSTOM_ENDPOINT ];

			return $current;
		}

		foreach ( $tabs as $endpoint => $data ) {
			if ( isset( $wp_query->query[ $endpoint ] ) ) {
				$current = $endpoint;
				break;
			}
		}

		// Dashboard is not an endpoint so it needs a custom check.
		if ( '' === $current && isset( $wp_query->query_vars['page'] ) ) {
			$current = 'dashboard';
		}

		return $current;
	}

	/**
	 * WooCommerce uses hooks with format of woocommerce_account_XXX_endpoint to render content of XXX endpoint.
	 * This function returns content of custom tabs to this hook.
	 *
	 * @param string|int $template_id
	 * @access public
	 * @since 2.5.0
	 */
	public function add_custom_tabs_template_hook( $template_slug ) {
		$id = $this->find_template_id_by_slug( $template_slug );

		if ( $id ) {
			echo do_shortcode( sprintf( '[elementor-template id="%s"]', $id ) );
			return;
		}

		echo esc_html__( 'Template not found!', 'jupiterx-core' );
	}

	/**
	 * Callback function for before the woocommerce_account_content hook.
	 *
	 * Output opening tag of the wrapper element.
	 * This eliminates the need for template overrides.
	 *
	 * @since 2.5.0
	 */
	public function before_account_content() {
		echo '<div class="woocommerce-MyAccount-content-wrapper">';

		$current_endpoint = $this->get_current_endpoint( $this->navigation->tabs );

		if ( 'dashboard' !== $current_endpoint ) {
			return;
		}

		$default_settings = $this->settings['tabs'][ $this->default_content_key ];
		$default_tab      = $default_settings['field_key'];
		$default_active   = $default_tab;

		if ( 'yes' === $default_settings['custom_template_enabled'] ) {
			$default_active = $default_settings['custom_template'];
		}

		// No matter what if dashboard tab is present, we display its content for my-account endpoint.
		if ( false === $this->is_hidden_dashboard && 'dashboard' === $current_endpoint ) {
			foreach ( $this->settings['tabs'] as $tab ) {
				if ( 'dashboard' === $tab['field_key'] && 'yes' === $tab['custom_template_enabled'] ) {
					$default_active = $tab['custom_template'];
				}

				if ( 'dashboard' === $tab['field_key'] && 'yes' !== $tab['custom_template_enabled'] ) {
					$default_active = 'dashboard';
				}
			}

			echo '<script type="text/javascript">document.querySelector( ".woocommerce-MyAccount-navigation-link--' . esc_js( $default_active ) . '" ).classList.add("is-active")</script>';
			return;
		}

		// Custom template.
		if ( 'yes' === $default_settings['custom_template_enabled'] ) {
			echo Elementor::instance()->frontend->get_builder_content_for_display( $default_settings['custom_template'], true );
			echo '<script type="text/javascript">document.querySelector( ".woocommerce-MyAccount-navigation-link--' . esc_js( $default_active ) . '" ).classList.add("is-active")</script>';
			return;
		}

		// Edit address default template should be loaded this way.
		if ( 'edit-address' === $default_tab ) {
			wc_get_template(
				'myaccount/my-address.php',
				[
					'current_user' => get_user_by( 'id', get_current_user_id() ),
				]
			);
		}

		// Other default templates.
		if ( 'edit-address' !== $default_tab ) {
			do_action( 'woocommerce_account_' . $default_tab . '_endpoint', $default_tab );
		}

		echo '<script type="text/javascript">document.querySelector( ".woocommerce-MyAccount-navigation-link--' . esc_js( $default_active ) . '" ).classList.add("is-active")</script>';
	}

	/**
	 * Callback function for after the woocommerce_account_content hook.
	 *
	 * Outputs closing tag of the wrapper element.
	 *
	 * @since 2.5.0
	 */
	public function after_account_content() {
		echo '</div>';
	}

	/**
	 * Callback function for the woocommerce_get_myaccount_page_permalink filter.
	 *
	 * Modify the permalinks of the My Account menu items. By default the permalinks will go to the
	 * set WooCommerce My Account Page, even if the widget is on a different page. This function will override
	 * the permalinks to use the widget page URL as the base URL instead.
	 *
	 * @return string
	 * @access public
	 * @since 2.5.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_myaccount_override_permalink( $bool ) {
		return get_permalink();
	}

	/**
	 * Checks if the query contains current active tab and adds "is-active" class to it.
	 *
	 * @param array $classes classes already allocated to the <li> by WooCommerce
	 * @access public
	 * @return array
	 * @since 2.5.0
	 */
	public function add_custom_tabs_classes( $classes ) {
		global $wp;

		if ( ! isset( $wp->query_vars[ JX_MY_ACCOUNT_CUSTOM_ENDPOINT ] ) ) {
			return $classes;
		}

		$id = $this->find_template_id_by_slug( $wp->query_vars[ JX_MY_ACCOUNT_CUSTOM_ENDPOINT ] );

		if ( ! $id ) {
			return $classes;
		}

		$target_class = 'woocommerce-MyAccount-navigation-link--' . $id;

		if ( in_array( $target_class, $classes, true ) ) {
			$classes[] = 'is-active';
		}

		return $classes;
	}

	private function find_template_id_by_slug( $slug ) {
		foreach ( $this->navigation->tabs as $endpoint => $data ) {
			if ( isset( $data['template'] ) && $slug === $data['template'] ) {
				return $endpoint;
			}
		}

		return false;
	}

	/**
	 * Callback function for the woocommerce_logout_default_redirect_url filter.
	 *
	 * Modify the permalink of the My Account Logout menu item. We add this so that we can add custom
	 * parameters to the URL, which we can later access to log the user out and redirect back to the widget
	 * page. Without this WooCommerce would have always just redirect back to the set My Account Page
	 * after log out.
	 *
	 * @return string
	 * @access public
	 * @since 2.5.0
	 */
	public function get_logout_override_redirect_url( $redirect ) {
		return $redirect . '?elementor_wc_logout=true&elementor_my_account_redirect=' . esc_url( get_permalink() );
	}

	private function check_dashboard_status() {
		$tabs   = $this->settings['tabs'];
		$hidden = true;

		foreach ( $tabs as $key => $tab ) {
			if ( 'dashboard' === $tab['field_key'] && 'yes' !== $tab['hide_tab'] ) {
				$hidden = false;
			}

			if ( $this->default_content_key < 0 && 'yes' !== $tab['hide_tab'] ) {
				$this->default_content_key = $key;
			}
		}

		return $hidden;
	}
}
