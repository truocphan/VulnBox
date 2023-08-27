<?php
namespace JupiterX_Core\Raven\Modules\My_Account\Submodules;

defined( 'ABSPATH' ) || die();

class Editor {
	private $navigation;

	public function __construct( $widget ) {
		$this->navigation = new Navigation( $widget );
	}

	/**
	 * Render HTML Editor
	 *
	 * This function will output the content in the Editor.
	 * The navigation along with all pages will be rendered and controlled later by JS.
	 *
	 * @param object $widget my account widget instance
	 * @access public
	 * @since 2.5.0
	 */
	public function render_editor() {
		$tabs = $this->navigation->tabs;

		?>
		<div class="raven-my-account-tab raven-my-account-tab__dashboard">
			<span class="elementor-hidden">[[woocommerce_my_account]]</span>
			<div class="woocommerce">
			<?php
			$this->navigation->render_custom_navigation();

			// If there are orders, add "view_order" to endpoints.
			// "view_order" template is rendered when the user clicks on View buttons in "orders" tab.
			$recent_order = $this->get_recent_order();

			if ( false !== $recent_order ) {
				$tabs['view-order'] = '';
			}

			// Render all templates in editor to control their visibility later with JS.
			foreach ( $tabs as $endpoint => $data ) {
				$endpoint_value = 'view-order' === $endpoint ? $recent_order : '';

				$this->setup_mock_query( $tabs, $endpoint, $endpoint_value );
				$this->render_page( $endpoint, $endpoint_value );
			}

			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Retrieves the recent order if available.
	 *
	 * @return mixed
	 * @access private
	 * @since 2.5.0
	 */
	private function get_recent_order() {
		$recent_order = wc_get_orders( [
			'limit'   => 1,
			'orderby' => 'date',
			'order'   => 'DESC',
		] );

		if ( ! empty( $recent_order ) ) {
			return $recent_order[0]->get_id();
		}

		return false;
	}

	/**
	 * Sets up a mock query for woocommerce hooks to work properly.
	 *
	 * @param array $tabs nav items caught from repeater
	 * @param string $enpoint my account endpoint to set query for
	 * @param mixed $endpoint_value value of the query
	 * @access private
	 * @since 2.5.0
	 */
	private function setup_mock_query( $tabs, $endpoint, $endpoint_value ) {
		global $wp_query;

		foreach ( $tabs as $endpoint => $data ) {
			unset( $wp_query->query_vars[ $endpoint ] );
		}

		$wp_query->query_vars[ $endpoint ] = $endpoint_value;
	}

	/**
	 * Render page of the given my account endpoint.
	 *
	 * @param string $endpoint
	 * @param mixed $endpoint_value
	 * @access private
	 * @static
	 * @since 2.5.0
	 */
	private function render_page( $endpoint, $endpoint_value ) {
		?>
		<div class="woocommerce-MyAccount-content" <?php echo $endpoint ? 'raven-my-account-page="' . esc_attr( $endpoint ) . '"' : ''; ?>>
			<div class="woocommerce-MyAccount-content-wrapper">
				<?php
				if ( 'dashboard' === $endpoint ) {
					wc_get_template(
						'myaccount/dashboard.php',
						[
							'current_user' => get_user_by( 'id', get_current_user_id() ),
						]
					);
				} elseif ( 'view-order' === $endpoint || array_key_exists( $endpoint, wc_get_account_menu_items() ) ) {
					do_action( 'woocommerce_account_' . $endpoint . '_endpoint', $endpoint_value );
				} else {
					echo do_shortcode( sprintf( '[elementor-template id="%s"]', $endpoint ) );
				}
				?>
			</div>
		</div>
		<?php
	}
}
