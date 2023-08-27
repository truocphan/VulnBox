<?php
/**
 * Templates main class file.
 *
 * @package JupiterX_Core\Templates
 *
 * @since 1.9.0
 */

if ( ! class_exists( 'JupiterX_Templates' ) ) :
	/**
	 * Templates class.
	 */
	class JupiterX_Templates {

		/**
		 * Templates instance.
		 *
		 * @access private
		 *
		 * @var JupiterX_Templates
		 */
		private static $instance;

		/**
		 * Returns JupiterX_Templates instance.
		 *
		 * @return JupiterX_Templates
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * The API URL.
		 */
		const API_URL = 'https://themes.artbees.net/wp-json/templates/v1';

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Initialize plugin.
		 */
		protected function init() {
			add_action( 'before_rocket_clean_domain', [ $this, 'clear_transients' ] );
			add_action( 'jupiterx_api_ajax_import_template', [ $this, 'import_template' ] );
			add_action( 'jupiterx_api_ajax_import_template_content', [ $this, 'import_template_content' ] );
			add_action( 'jupiterx_api_ajax_get_template_psd', [ $this, 'get_template_psd' ] );
			add_action( 'jupiterx_api_ajax_get_template_sketch', [ $this, 'get_template_sketch' ] );
			add_action( 'jupiterx_api_ajax_get_templates', [ $this, 'get_templates' ] );
		}

		/**
		 * Clear transients.
		 */
		public function clear_transients() {
			delete_transient( 'jupiterx_templates_filters' );
		}

		/**
		 * Render HTML.
		 *
		 * @param array $attrs Filter params.
		 *
		 * @return void Echoes templates filter.
		 */
		public function html( $attrs = [] ) {
			$attrs = array_merge( [
				'product_id'     => 25348,             // To filter specific product templates, default is Jupiter X.
				'posts_per_page' => 25,                // Set posts per page to get.
				'pagination'     => 'infinite_scroll', // Set pagination type.
			], $attrs );

			$filters = $this->_get_filters();

			$pagination = ! empty( $attrs['pagination'] ) ? 'data-pagination="' . $attrs['pagination'] . '"' : '';

			ob_start();
			?>
			<div class="jupiterx-templates-search" <?php echo esc_attr( $pagination ); ?>>
				<div class="jupiterx-templates-toggle-filters">
					<div class="jupiterx-templates-search-field with-button">
						<input class="form-control" type="search" placeholder="<?php esc_html_e( 'e.g. Portfolio, Real estate, Minimal, Dark, ...', 'jupiterx-core' ); ?>">
						<button class="search-button jupiterx-icon-search-1"></button>
						<a href="#" class="clear-button">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512">
								<path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
							</svg>
						</a>
					</div>
					<button type="button" class="toggle-button">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M496 384H160v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h80v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h336c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160h-80v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h336v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h80c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16C7.2 64 0 71.2 0 80v32c0 8.8 7.2 16 16 16h208v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h208c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16z"></path>
						</svg>
						<div><?php esc_html_e( 'Filters', 'jupiterx-core' ); ?></div>
						<div>(<span class="filters-count">0</span>)</div>
					</button>
					<div class="count-templates">
						<span class="found-posts">0</span>
						<?php esc_html_e( 'Template was found.', 'jupiterx-core' ); ?>
					</div>
				</div>
				<div class="jupiterx-templates-filters-container">
					<div class="jupiterx-templates-filters">
						<div class="jupiterx-templates-header">
							<h3 class="header-name"><?php esc_html_e( 'Filter & Refine', 'jupiterx-core' ); ?></h3>
							<a href="#" class="clear-filters"><?php esc_html_e( 'Clear all', 'jupiterx-core' ); ?></a>
							<button class="close-button" type="button"><?php esc_html_e( 'Done', 'jupiterx-core' ); ?></button>
						</div>
						<div class="jupiterx-templates-filter-hidden">
							<?php // Filter product's template. ?>
							<input class="filter-field" type="hidden" name="product_id" value="<?php echo esc_attr( $attrs['product_id'] ); ?>">
							<?php // Set posts per page. ?>
							<input class="filter-field" type="hidden" name="posts_per_page" value="<?php echo esc_attr( $attrs['posts_per_page'] ); ?>">
						</div>
						<div class="jupiterx-templates-filter jupiterx-templates-filter-content">
							<div class="jupiterx-templates-search-field">
								<input class="form-control filter-field" type="text" name="s" placeholder="<?php esc_html_e( 'e.g. Portfolio, Real estate, Minimal, Dark, ...', 'jupiterx-core' ); ?>">
								<span class="search-icon jupiterx-icon-search-1"></span>
							</div>
						</div>
						<?php
						// Categories.
						$categories = isset( $_GET['category'] ) ? explode( ',', $_GET['category'] ) : []; // phpcs:ignore WordPress.Security

						$this->_render_select(
							'category',
							esc_html__( 'Category', 'jupiterx-core' ),
							$filters['category'],
							$categories
						);

						// Meta fields.
						$select_fields = [
							'style'        => esc_html__( 'Style', 'jupiterx-core' ),
							'header_type'  => esc_html__( 'Header Type', 'jupiterx-core' ),
							'menu_type'    => esc_html__( 'Menu Type', 'jupiterx-core' ),
							'components'   => esc_html__( 'Components', 'jupiterx-core' ),
							'content_type' => esc_html__( 'Content Type', 'jupiterx-core' ),
						];

						foreach ( $select_fields as $id => $name ) {
							$this->_render_select( $id, $name, $filters[ $id ] );
						}
						?>
					</div>
				</div>
				<div class="jupiterx-templates-results"></div>
			</div>
			<?php
			$html = ob_get_clean();
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Template to render select field.
		 *
		 * @param string $id       Base ID.
		 * @param string $name     Select name.
		 * @param array  $options  Select options.
		 * @param array  $selected Selected values.
		 *
		 * @SuppressWarnings(PHPMD.ElseExpression)
		 */
		private function _render_select( $id, $name, $options, $selected = [] ) {
			asort( $options );

			// Get selected values that exist in `$options` var only.
			$selected = array_keys( array_intersect_key( array_flip( $selected ), $options ) );

			if ( ! empty( $selected ) ) {
				// Initial selected labels.
				$selected_labels = implode( ', ', array_intersect_key( $options, array_flip( $selected ) ) );
			} else {
				$selected_labels = esc_html__( 'All', 'jupiterx-core' );
			}
			?>
			<div class="jupiterx-templates-filter jupiterx-templates-filter-<?php echo sanitize_html_class( str_replace( '_', '-', $id ) ); ?>">
				<div class="jupiterx-templates-select-field">
					<input class="filter-field selected-values" type="hidden" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( implode( ',', $selected ) ); ?>">
					<button class="dropdown-toggle" type="button" aria-label="<?php esc_html_e( 'Click to expand', 'jupiterx-core' ); ?>">
						<div class="control-label">
							<span class="filter-name"><?php echo esc_html( $name ); ?></span>
							<a href="#" class="clear-selected <?php echo ! empty( $selected ) ? 'show' : ''; ?>"><?php esc_html_e( 'Clear', 'jupiterx-core' ); ?></a>
							<span class="selected-labels"><?php echo esc_html( $selected_labels ); ?></span>
						</div>
					</button>
					<div class="dropdown-menu">
						<?php foreach ( $options as $value => $label ) { ?>
							<?php $checked = ! empty( $selected ) && in_array( $value, $selected, true ) ? 'checked=checked' : ''; ?>
							<div class="custom-control custom-checkbox">
								<input <?php echo esc_attr( $checked ); ?> type="checkbox" class="custom-control-input" data-label="<?php echo esc_attr( $label ); ?>" name="search-filter-<?php echo esc_attr( $id ); ?>" id="search-filter-<?php echo esc_attr( "{$id}-{$value}" ); ?>" value="<?php echo esc_attr( $value ); ?>">
								<label class="custom-control-label" for="search-filter-<?php echo esc_attr( "{$id}-{$value}" ); ?>" ><?php echo esc_html( $label ); ?></label>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Get API available filters.
		 */
		private function _get_filters() {
			$transient = get_transient( 'jupiterx_templates_filters' );

			// Short circuit when transient is available.
			if ( ! empty( $transient ) ) {
				return $transient;
			}

			$response = json_decode( wp_remote_retrieve_body( wp_remote_get( self::API_URL . '/filter_criterias' ) ) );

			$filters = [];

			// Set categories.
			$filters['category'] = [];
			if ( isset( $response->categories ) ) {
				foreach ( $response->categories as $category ) {
					$filters['category'][ $category->slug ] = $category->name;
				}
			}

			// Get meta fields.
			$meta_fields = [
				'style'        => '_template_style',
				'content_type' => '_content_type',
				'components'   => '_components',
				'menu_type'    => '_menu_type',
				'header_type'  => '_header_type',
			];

			foreach ( $meta_fields as $param => $meta_name ) {
				$filters[ $param ] = $this->_get_filters_meta_options( $meta_name, $response );
			}

			if ( ! empty( $filters ) && ! is_null( $response ) ) {
				set_transient( 'jupiterx_templates_filters', $filters, 1 * HOUR_IN_SECONDS );
			}

			return $filters;
		}

		/**
		 * Get options by meta name.
		 *
		 * @param string $meta_name   Meta name.
		 * @param array  $api_filters Filters result from API.
		 */
		private function _get_filters_meta_options( $meta_name, $api_filters ) {
			$options = [];

			if ( isset( $api_filters->meta_fields ) ) {
				foreach ( $api_filters->meta_fields as $meta ) {
					if ( isset( $meta->name ) && isset( $meta->options ) && $meta_name === $meta->name ) {
						return wp_list_pluck( $meta->options, 'value', 'key' );
					}
				}
			}

			return $options;
		}

		/**
		 * Import template.
		 */
		public function import_template() {
			$templates_manager = new JupiterX_Control_Panel_Install_Template();

			// Install template function.
			$templates_manager->install_template_procedure();
		}

		/**
		 * Import template content.
		 */
		public function import_template_content() {
			$templates_manager = new JupiterX_Control_Panel_Install_Template();

			// Import template content.
			$templates_manager->import_theme_content_sse();
		}

		/**
		 * Get template PSD link.
		 */
		public function get_template_psd() {
			$api_key = jupiterx_get_option( 'api_key' );

			if ( empty( $api_key ) ) {
				wp_send_json_success( [
					'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx' ),
					'status'  => self::ERROR,
				] );
			}

			$templates_manager = new JupiterX_Control_Panel_Install_Template();

			// Template download function.
			$templates_manager->get_template_psd_link();
		}

		/**
		 * Get template SKETCH link.
		 */
		public function get_template_sketch() {
			$api_key = jupiterx_get_option( 'api_key' );

			if ( empty( $api_key ) ) {
				wp_send_json_success( [
					'message' => esc_html__( 'Your API key could not be verified.', 'jupiterx-core' ),
					'status'  => self::ERROR,
				] );
			}

			$templates_manager = new JupiterX_Control_Panel_Install_Template();

			// Template download function.
			$templates_manager->get_template_sketch_link();
		}

		/**
		 * Get templates.
		 */
		public function get_templates() {
			$filters = filter_input( INPUT_POST, 'filters', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( ! $filters ) {
				$filters = [];
			}

			$args = array_merge( [ 'source' => 'control_panel' ], $filters );

			if ( ! jupiterx_is_premium() ) {
				$args['sort_by'] = '_free_template';
			}

			$url = add_query_arg( $args, 'https://themes.artbees.net/wp-json/templates/v1/list' );

			$headers = [
				'timeout'     => 120,
				'httpversion' => '1.1',
			];

			$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $url, $headers ) ) );

			$additional = [
				'is_pro' => jupiterx_is_pro(),
			];

			wp_send_json( array_merge( $additional, (array) $response ) );
		}

		/**
		 * Disables class cloning and throw an error on object clone.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'jupiterx-core' ), '1.0.0' );
		}

		/**
		 * Disables unserializing of the class.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'jupiterx-core' ), '1.0.0' );
		}
	}
endif;

if ( ! function_exists( 'jupiterx_templates' ) ) :
	/**
	 * Returns the Templates application instance.
	 *
	 * @return JupiterX_Templates
	 */
	function jupiterx_templates() {
		return JupiterX_Templates::get_instance();
	}
endif;

/**
 * Initializes the Templates application.
 */
jupiterx_templates();
