<?php
/**
 * Export and Import API: JupiterX_Core_Control_Panel_Export_Import base class
 *
 * @package JupiterX_Core\Control_Panel\Export_Import
 * @since 1.0
 *
 * @todo Clean up.
 *
 * phpcs:ignoreFile
 * @SuppressWarnings(PHPMD)
 */
if ( ! class_exists( 'JupiterX_Core_Control_Panel_Export_Import' ) ) {
	/**
	 * Export/Import Site Content, Widgets, Settings.
	 *
	 * @author Artbees Team
	 * @since 1.0
	 * @SuppressWarnings(PHPMD.StaticAccess)
	 * @SuppressWarnings(PHPMD.ExcessiveClassComplexitys)
	 */
	class JupiterX_Core_Control_Panel_Export_Import {

		/**
		 * $jupiterx_filesystem instance.
		 *
		 * @since 1.0
		 * @var array
		 */
		private $jupiterx_filesystem;


		/**
		 * $supported_plugins instance.
		 *
		 * @since 1.0
		 * @var array
		 */
		private $supported_plugins;

		/**
		 * Export and Import directory’s path and url.
		 *
		 * @since 1.0
		 * @var array
		 */
		private $folder = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$upload_dir                 = wp_upload_dir();
			$this->folder['export_url'] = $upload_dir['baseurl'] . '/jupiterx/export';
			$this->folder['export_dir'] = $upload_dir['basedir'] . '/jupiterx/export';
			$this->folder['import_url'] = $upload_dir['baseurl'] . '/jupiterx/import';
			$this->folder['import_dir'] = $upload_dir['basedir'] . '/jupiterx/import';

			$this->supported_plugins = array(
				'woocommerce',
				'advanced-custom-fields',
				'elementor',
				'customizer-reset-by-wpzoom',
				'customizer-export-import',
				'jupiterx-core',
				'menu-icons',
			);

			if ( jupiterx_is_premium() ) {
				$this->supported_plugins = array_merge(
					$this->supported_plugins,
					array(
						'advanced-custom-fields-pro',
						'js_composer_theme',
						'LayerSlider',
						'masterslider',
						'revslider',
						'jet-booking',
						'jet-elements',
						'jet-menu',
						'jet-popup',
						'jet-tabs',
						'jet-woo-builder',
						'jet-tricks',
						'jet-engine',
						'jet-blog',
						'jet-smart-filters',
						'raven',
						'jupiterx-pro',
					)
				);
			}

			add_action( 'wp_ajax_jupiterx_core_cp_export_import', array( $this, 'ajax_handler' ) );
		}

		/**
		 * Map the requests to proper methods.
		 *
		 * @since 1.0
		 */
		public function ajax_handler() {
			check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

			$type          = filter_input( INPUT_POST, 'type' );
			$step          = filter_input( INPUT_POST, 'step' );
			$attachment_id = filter_input( INPUT_POST, 'attachment_id' );

			if ( empty( $type ) ) {
				wp_send_json_error(
					__( 'Type param is missing.', 'jupiterx-core' )
				);
			}

			if ( empty( $step ) ) {
				wp_send_json_error(
					__( 'Step param is missing.', 'jupiterx-core' )
				);
			}

			if ( 'Export' === $type ) {
				$this->jupiterx_filesystem = new JupiterX_Core_Control_Panel_Filesystem(
					array(
						'context' => $this->folder['export_dir'],
					)
				);
				return $this->export( $step );
			}

			if ( 'Import' === $type ) {

				if ( empty( $attachment_id ) ) {
					wp_send_json_error(
						__( 'Attachment ID param is missing.', 'jupiterx-core' )
					);
				}

				$this->jupiterx_filesystem = new JupiterX_Core_Control_Panel_Filesystem(
					array(
						'context' => $this->folder['import_dir'],
					)
				);
				return $this->import( $step, $attachment_id );
			}

			wp_send_json_error(
				sprintf( __( 'Type param (%s) is not valid.', 'jupiterx-core' ), $type )
			);
		}

		/**
		 * Run proper export method based on step.
		 *
		 * @since 1.0
		 * @param string $step The export step.
		 * @return void
		 */
		private function export( $step ) {
			switch ( $step ) {
				case 'Start':
					$this->export_start();
					break;

				case 'Content':
					$this->export_content();
					break;

				case 'Widgets':
					$this->export_widgets();
					break;

				case 'Settings':
					$this->export_settings();
					break;

				case 'Custom Tables':
					$this->export_custom_tables();
					break;

				case 'End':
					$this->export_end();
					break;

				case 'Discard':
					$this->discard( $this->folder['export_dir'] );
					break;
			}

			wp_send_json_error(
				sprintf( __( 'Step param (%s) is not valid.', 'jupiterx-core' ), $step )
			);
		}

		/**
		 * Start export process by cleaning the export directory.
		 *
		 * @throws Exception If can not clean export folder.
		 *
		 * @since 1.0
		 */
		private function export_start() {
			try {
				if ( $this->jupiterx_filesystem->rmdir( $this->folder['export_dir'], true ) ) {
					return wp_send_json_success(
						array(
							'step' => 'Start',
						)
					);
				}

				throw new Exception( __( 'A problem occurred in cleaning export directory.', 'jupiterx-core' ) );
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Export content.
		 *
		 * @throws Exception If can not export Content.
		 *
		 * @since 1.0
		 */
		private function export_content() {
			try {
				require_once ABSPATH . 'wp-admin/includes/export.php';

				ob_start();
				export_wp();
				$content = ob_get_clean();
				ob_end_clean();

				$file_name = 'theme_content.xml';
				$file_path = $this->folder['export_dir'] . '/' . $file_name;

				if ( ! $this->jupiterx_filesystem->put_contents( $file_path, $content ) ) {
					throw new Exception( __( 'A problem occurred in exporting Content.', 'jupiterx-core' ) );
				}

				$this->export_plugins();

				return wp_send_json_success(
					array(
						'step' => 'Content',
					)
				);
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Export plugins content.
		 *
		 * @since 1.0.3
		 */
		public function export_plugins() {
			$active_plugins = get_option( 'active_plugins' );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins' ) );
			}

			foreach ( $active_plugins as $plugin ) {
				$plugins_slug[] = substr( $plugin, 0, strrpos( $plugin, '/' ) );
			}

			$supported_plugins = array_intersect( $plugins_slug, $this->supported_plugins );

			foreach ( $supported_plugins as $plugin ) {
				if ( is_callable( [ $this, "export_{$plugin}_content" ] ) ) {
					call_user_func( [ $this, "export_{$plugin}_content" ] );
				}
			}
		}

		/**
		 * Export Revolution Slider slides.
		 *
		 * @since 1.0.3
		 */
		public function export_revslider_content() {
			if ( ! class_exists( 'RevSliderSlider' ) ) {
				return;
			}

			// Initialize Revolution Slider.
			$revslider = new RevSlider();

			$sliders = $revslider->get_sliders();

			if ( empty( $sliders ) ) {
				return;
			}

			// Create download url.
			$base_arg = [
				'action'        => 'revslider_ajax_action',
				'client_action' => 'export_slider',
				'dummy'         => 'false',
				'nonce'         => wp_create_nonce( 'revslider_actions' ),
			];

			$base_url = add_query_arg( $base_arg, admin_url( 'admin-ajax.php' ) );

			$export_dir = "{$this->folder['export_dir']}/revslider/";

			// Create and pass cookie.
			$cookies = [];

			foreach ( $_COOKIE as $name => $value ) {
				$cookies[] = new WP_Http_Cookie( array( 'name' => $name, 'value' => $value ) );
			}

			$remote_args = [
				'cookies' => $cookies,
			];

			// Go through each slides.
			foreach ( $sliders as $slider ) {
				$revslider->init_by_alias( $slider->alias );

				$download_args = [
					'id' => $revslider->get_id(),
				];

				$download_url = add_query_arg( $download_args, $base_url );

				JupiterX_Core_Control_Panel_Helpers::upload_from_url(
					$download_url,
					"{$slider->alias}.zip",
					$export_dir,
					$remote_args
				);
			}
		}

		public function availableWidgets() {
			global $wp_registered_widget_controls;
			$widget_controls   = $wp_registered_widget_controls;
			$available_widgets = array();
			foreach ( $widget_controls as $widget ) {
				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
					$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
					$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
				}
			}

			return apply_filters( 'available_widgets', $available_widgets );
		}

		/**
		 * Export widgets.
		 *
		 * @throws Exception If can not export Widgets.
		 *
		 * @since 1.0
		 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
		 */
		private function export_widgets() {
			try {
				$available_widgets = $this->availableWidgets();

				// Get all widget instances for each widget.
				$widget_instances = array();

				// Loop widgets.
				foreach ( $available_widgets as $widget_data ) {
					// Get all instances for this ID base.
					$instances = get_option( 'widget_' . $widget_data['id_base'] );
					// Have instances.
					if ( ! empty( $instances ) ) {
						// Loop instances.
						foreach ( $instances as $instance_id => $instance_data ) {
							// Key is ID (not _multiwidget).
							if ( is_numeric( $instance_id ) ) {
								$unique_instance_id                      = $widget_data['id_base'] . '-' . $instance_id;
								$widget_instances[ $unique_instance_id ] = $instance_data;
							}
						}
					}
				}

				// Gather sidebars with their widget instances.
				$sidebars_widgets    = get_option( 'sidebars_widgets' );
				$sidebars_widget_ins = array();
				foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

					// Skip inactive widgets.
					if ( 'wp_inactive_widgets' === $sidebar_id ) {
						continue;
					}

					// Skip if no data or not an array (array_version).
					if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
						continue;
					}

					// Loop widget IDs for this sidebar.
					foreach ( $widget_ids as $widget_id ) {
						// Is there an instance for this widget ID?
						if ( isset( $widget_instances[ $widget_id ] ) ) {
							// Add to array.
							$sidebars_widget_ins[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];
						}
					}
				}

				$content = wp_json_encode( $sidebars_widget_ins );

				$file_name = 'widget_data.wie';
				$file_path = $this->folder['export_dir'] . '/' . $file_name;

				if ( $this->jupiterx_filesystem->put_contents( $file_path, $content ) ) {
					return wp_send_json_success(
						array(
							'step' => 'Widgets',
						)
					);
				}

				throw new Exception( __( 'A problem occurred in exporting widgets.', 'jupiterx-core' ) );
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			} // End try().
		}


		/**
		 * An array of core options that shouldn't be imported.
		 *
		 * @since 1.0
		 * @access private
		 * @var array $core_options
		 */
		static private $core_options = array(
			'blogname',
			'blogdescription'
		);


		/**
		 * Export Settings.
		 *
		 * @throws Exception If can not export Settings.
		 *
		 * @since 1.0
		 */
		private function export_settings() {
			try {
				$data = [
					'template' => get_template(),
					'mods'     => [],
					'options'  => [],
				];

				$data = $this->_export_settings_customizer_mods( $data );

				$data = $this->_export_settings_customizer_options( $data );

				$data = $this->_export_settings_plugins( $data );

				$data = $this->_export_settings_options( $data );

				// WP custom CSS.
				if ( function_exists( 'wp_get_custom_css_post' ) ) {
					$data['wp_css'] = wp_get_custom_css();
				}

				$file_name = 'settings.json';
				$file_path = $this->folder['export_dir'] . '/' . $file_name;

				if ( ! is_array( $data ) ) {
					throw new Exception( __( 'All settings in Settings are set to default. Uncheck the Settings option or change one setting in Settings then export.', 'jupiterx-core' ) );
				}

				if ( ! $this->jupiterx_filesystem->put_contents( $file_path, wp_json_encode( $data ) ) ) {
					throw new Exception( __( 'A problem occurred in exporting Settings.', 'jupiterx-core' ) );
				}

				return wp_send_json_success( [ 'step' => 'Settings' ] );

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Export custom tables.
		 *
		 * @since 1.11.0
		 */
		private function export_custom_tables() {
			try {
				$file = $this->folder['export_dir'] . '/tables.sql';

				if ( ! $this->jupiterx_filesystem->put_contents( $file, '' ) ) {
					throw new Exception( __( 'A problem occurred while exporting Content.', 'jupiterx-core' ) );
				}

				$db_manager = new JupiterX_Core_Control_Panel_PHP_DB_Manager();

				$supported_plugins = $this->get_supported_plugins();

				$supported_tables = array_filter( $this->get_supported_tables(), function( $plugin ) use ( $supported_plugins ) {
					return in_array( $plugin, $supported_plugins, true );
				}, ARRAY_FILTER_USE_KEY );

				$tables = [];

				// Prepare table names.
				foreach ( $supported_tables as $plugin_tables ) {
					foreach ( $plugin_tables as $table ) {
						array_push( $tables, $db_manager->get_table_prefix() . $table );
					}
				}

				if ( ! empty( $tables ) ) {
					$dump_tables = $db_manager->dump_tables( $file, $tables );

					if ( $dump_tables !== true ) {
						throw new Exception( $dump_tables );
					}
				}

				return wp_send_json_success( [ 'step' => 'Custom Tables' ] );
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Export customizer mods.
		 *
		 * @since 1.0.4
		 */
		private function _export_settings_customizer_mods( $data ) {
			$mods = get_theme_mods();

			if ( ! empty( $mods ) ) {
				unset( $mods['sidebars_widgets'] );
				$data['mods'] = $mods;
			}

			return $data;
		}

		/**
		 * Export customizer options.
		 *
		 * @since 1.0.4
		 */
		private function _export_settings_customizer_options( $data ) {
			require_once ABSPATH . 'wp-includes/class-wp-customize-manager.php';

			$wp_customize = new WP_Customize_Manager();
			$settings     = $wp_customize->settings();

			foreach ( $settings as $key => $setting ) {
				if ( 'option' == $setting->type ) {

					// Don't save widget data.
					if ( stristr( $key, 'widget_' ) ) {
						continue;
					}

					// Don't save sidebar data.
					if ( stristr( $key, 'sidebars_' ) ) {
						continue;
					}

					// Don't save core options.
					if ( in_array( $key, self::$core_options ) ) {
						continue;
					}

					$data['options'][ $key ] = $setting->value();
				}
			}

			return $data;
		}

		/**
		 * Export active supported plugins.
		 *
		 * @since 1.0.4
		 */
		private function _export_settings_plugins( $data ) {
			$all_active_plugins = get_option( 'active_plugins' );

			foreach ( $all_active_plugins as $plugin ) {
				$active_plugins[] = substr( $plugin, 0, strrpos( $plugin, '/' ) );
			}

			if ( is_multisite() ) {
				$sitewide_all_active_plugins = get_site_option( 'active_sitewide_plugins' );

				foreach ( $sitewide_all_active_plugins as $plugin => $id ) {
					$active_plugins[] = substr( $plugin, 0, strrpos( $plugin, '/' ) );
				}
			}

			$supported_active_plugins = array_intersect( $active_plugins, $this->supported_plugins );

			foreach ( $supported_active_plugins as $plugins ) {
				$data['options']['jupiterx_support_plugins'][] = $plugins;
			}

			return $data;
		}

		/**
		 * Export options.
		 *
		 * @since 1.0.4
		 */
		private function _export_settings_options( $data ) {
			/**
			 * Extra options.
			 *
			 * Any option that can be exported & imported without modifications.
			 */
			$option_keys = apply_filters( 'jupiterx_extra_export_option_keys', [
				'elementor_scheme_color',
				'elementor_scheme_typography',
				'elementor_scheme_color-picker',
				'elementor_cpt_support',
				'elementor_disable_color_schemes',
				'elementor_disable_typography_schemes',
				'elementor_default_generic_fonts',
				'elementor_container_width',
				'elementor_space_between_widgets',
				'elementor_stretched_section_container',
				'elementor_page_title_selector',
				'elementor_viewport_lg',
				'elementor_viewport_md',
				'elementor_global_image_lightbox',
				'elementor_lightbox_color',
				'elementor_lightbox_ui_color',
				'elementor_lightbox_ui_color_hover',
				'elementor_enable_lightbox_in_editor',
				'elementor_global_image_lightbox',
				'woocommerce_single_image_width',
				'woocommerce_thumbnail_image_width',
				'woocommerce_thumbnail_cropping',
				'woocommerce_thumbnail_cropping_custom_width',
				'woocommerce_thumbnail_cropping_custom_height',
				'elementor_active_kit',
			] );

			foreach ( $option_keys as $option_key ) {
				$option = get_option( $option_key, null );

				if ( ! is_null( $option ) ) {
					$data['options']['extra'][ $option_key ] = $option;
				}
			}

			// Front page.
			$page_on_front = get_option( 'page_on_front' );

			if ( ! empty( $page_on_front ) ) {
				$data['options']['page_on_front'] = get_the_title( $page_on_front );
			}

			// Menu locations.
			$get_nav_locations = get_theme_mod( 'nav_menu_locations' );

			foreach ( $get_nav_locations as $location => $id ) {
				$get_term = get_term_by( 'id', $id, 'nav_menu' );
				$data['options']['jupiterx_menu_locations'][ $location ] = $get_term->name;
			}

			// WooCommerce.
			$woocommerce_shop_page_id = get_option( 'woocommerce_shop_page_id' );

			if ( ! empty( $woocommerce_shop_page_id ) ) {
				$data['options']['woocommerce_shop_page_id'] = get_the_title( $woocommerce_shop_page_id );
			}

			$woocommerce_cart_page_id = get_option( 'woocommerce_cart_page_id' );

			if ( ! empty( $woocommerce_cart_page_id ) ) {
				$data['options']['woocommerce_cart_page_id'] = get_the_title( $woocommerce_cart_page_id );
			}

			$woocommerce_checkout_page_id = get_option( 'woocommerce_checkout_page_id' );

			if ( ! empty( $woocommerce_checkout_page_id ) ) {
				$data['options']['woocommerce_checkout_page_id'] = get_the_title( $woocommerce_checkout_page_id );
			}

			$woocommerce_myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );

			if ( ! empty( $woocommerce_checkout_page_id ) ) {
				$data['options']['woocommerce_myaccount_page_id'] = get_the_title( $woocommerce_myaccount_page_id );
			}

			// Jet Engine.
			$jet_engine_modules = get_option( 'jet_engine_modules' );

			if ( ! empty( $jet_engine_modules ) && in_array( 'jet-engine', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet_engine_modules'] = $jet_engine_modules;
			}

			// Jet Menu.
			$jet_menu_options = get_option( 'jet_menu_options' );

			if ( ! empty( $jet_menu_options ) && in_array( 'jet-menu', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet_menu_options'] = $jet_menu_options;
			}

			// Jet Blog.
			$jet_blog_settings = get_option( 'jet-blog-settings' );

			if ( is_array( $jet_blog_settings ) && in_array( 'jet-blog', $data['options']['jupiterx_support_plugins'], true ) ) {

				if ( ! empty( $jet_blog_settings['youtube_api_key'] ) ) {
					$jet_blog_settings['youtube_api_key'] = '';
				}

				$data['options']['jet_blog_settings'] = $jet_blog_settings;
			}

			// Jet Booking.
			$jet_booking_options = get_option( 'jet-abaf' );

			if ( ! empty( $jet_booking_options ) && in_array( 'jet-booking', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet-abaf'] = $jet_booking_options;
			}

			// Smart Filter.
			$jet_filter_conditions = get_option( 'jet-smart-filters-settings' );

			if ( ! empty( $jet_filter_conditions ) && in_array( 'jet-smart-filters', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet-smart-filters-settings'] = $jet_filter_conditions;
			}

			// Jet Popup.
			$jet_popup_conditions = get_option( 'jet_popup_conditions' );

			if ( ! empty( $jet_popup_conditions ) && in_array( 'jet-popup', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet_popup_conditions'] = $jet_popup_conditions;
			}

			// Jet Woo Builder Custom Shop Page.
			$jet_woo_builder = get_option( 'jet_woo_builder' );

			if ( ! empty( $jet_woo_builder['custom_shop_page'] ) && in_array( 'jet-woo-builder', $data['options']['jupiterx_support_plugins'], true ) ) {
				$data['options']['jet_custom_shop_page'] = $jet_woo_builder['custom_shop_page'];
			}

			// Jupiter X custom siderbars.
			$jupiterx_custom_sidebars = jupiterx_get_option( 'custom_sidebars' );

			if ( ! empty( $jupiterx_custom_sidebars ) ) {
				$data['options']['jupiterx_custom_sidebars'] = $jupiterx_custom_sidebars;
			}

			return $data;
		}

		/**
		 * End export process by creating the zip file and download url.
		 *
		 * @since 1.0
		 */
		private function export_end() {
			try {
				$this->jupiterx_filesystem->zip_folder( $this->folder['export_dir'], "{$this->folder['export_dir']}/{$this->_prepare_directory_name()}.zip", $this->_prepare_directory_name() );

				return wp_send_json_success(
					array(
						'step'         => 'End',
						'download_url' => $this->folder['export_url'] . '/' . $this->_prepare_directory_name() . '.zip',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Prepare the export zip file name.
		 *
		 * @since 1.0.0
		 */
		private function _prepare_directory_name() {
			$site_title = ! empty( get_bloginfo( 'name' ) ) ? get_bloginfo( 'name' ) : 'package';
			$form_data  = jupiterx_post( 'data' );

			if ( ! empty( $form_data['filename'] ) ) {
				return sanitize_title( $form_data['filename'] );
			}

			return sanitize_title( $site_title ) . '-jupiterx';
		}

		/**
		 * Run proper import method based on step.
		 *
		 * @since 1.0
		 * @param string  $step          The import step.
		 * @param integer $attachment_id The uploaded zip file ID.
		 * @return void
		 */
		private function import( $step, $attachment_id ) {
			switch ( $step ) {
				case 'Start':
					$this->import_start( $attachment_id );
					break;

				case 'Content':
					$this->import_content();
					break;

				case 'Widgets':
					$this->import_widgets();
					break;

				case 'Settings':
					$this->import_settings();
					break;

				case 'End':
					$this->import_end();
					break;

				case 'Discard':
					$this->discard( $this->folder['import_dir'] );
					break;
			}

			wp_send_json_error(
				sprintf( __( 'Step param (%s) is not valid.', 'jupiterx-core' ), $step )
			);
		}

		/**
		 * Start import process by cleaning import directory and
		 * unzipping file to directory Import directory.
		 *
		 * @since 1.0
		 * @param integer $attachment_id The uploaded zip file ID.
		 */
		private function import_start( $attachment_id ) {
			try {
				$this->jupiterx_filesystem->rmdir( $this->folder['import_dir'], true );

				$this->jupiterx_filesystem->unzip_custom(
					get_attached_file( $attachment_id ),
					$this->folder['import_dir']
				);

				return wp_send_json_success(
					array(
						'step' => 'Start',
					)
				);
			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Import Content
		 *
		 * @throws Exception If required file is missing.
		 * @throws Exception If can not parse file..
		 *
		 * @since 1.0
		 */
		private function import_content() {
			try {
				$file_name         = 'theme_content.xml';
				$file              = $this->_get_import_package_dir_path( $file_name );
				$fetch_attachments = true;

				if ( ! file_exists( $file ) ) {
					throw new Exception(
						sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'jupiterx-core' ), $file_name )
					);
				}

				// Include wordpress-importer class.
				JupiterX_Core_Control_Panel_Helpers::include_wordpress_importer();

				$options = array(
					'fetch_attachments' => filter_var( $fetch_attachments, FILTER_VALIDATE_BOOLEAN ),
					'default_author'    => get_current_user_id(),
				);

				// Create new instance for Importer.
				$importer = new JupiterX_Core_Control_Panel_WXR_Importer( $options );
				$logger   = new JupiterX_Core_Control_Panel_Importer_Logger_ServerSentEvents();
				$importer->set_logger( $logger );

				$data = $importer->get_preliminary_information( $file );

				if ( is_wp_error( $data ) ) {
					throw new Exception(
						sprintf( __( 'Error in parsing %s.', 'jupiterx-core' ), $file_name )
					);
				}

				// Run import process.
				ob_start();
				$importer->import( $file );
				ob_end_clean();

				return wp_send_json_success(
					array(
						'step' => 'Content',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			} // End try().
		}

		/**
		 * Import widgets' data.
		 *
		 * @throws Exception If can not read widget data.
		 *
		 * @since 5.7.0
		 *        6.0.4 Make it public.
		 * @param  array $data Widgets' data.
		 * @return boolean
		 */
		public function import_widget_data( $data ) {
			global $wp_registered_sidebars;

			$available_widgets = $this->availableWidgets();
			$widget_instances  = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}
			if ( empty( $data ) || ! is_object( $data ) ) {
				throw new Exception( 'Widget data could not be read. Please try a different file.' );
			}
			$results = array();
			foreach ( $data as $sidebar_id => $widgets ) {
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}
				if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
					$sidebar_available    = true;
					$use_sidebar_id       = $sidebar_id;
					$sidebar_message_type = 'success';
					$sidebar_message      = '';
				} else {
					$sidebar_available    = false;
					$use_sidebar_id       = 'wp_inactive_widgets';
					$sidebar_message_type = 'error';
					$sidebar_message      = 'Sidebar does not exist in theme (using Inactive)';
				}
				$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
				$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
				$results[ $sidebar_id ]['message']      = $sidebar_message;
				$results[ $sidebar_id ]['widgets']      = array();
				foreach ( $widgets as $widget_instance_id => $widget ) {
					$fail               = false;
					$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
					$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );
					if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
						$fail                = true;
						$widget_message_type = 'error';
						$widget_message      = 'Site does not support widget';
					}
					$widget = apply_filters( 'jupiterx_widget_settings', $widget );
					if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {
						$sidebars_widgets        = get_option( 'sidebars_widgets' );
						$sidebar_widgets         = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array();
						$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {
							if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
								$fail                = true;
								$widget_message_type = 'warning';
								$widget_message      = 'Widget already exists';
								break;
							}
						}
					}
					if ( ! $fail ) {
						$single_widget_instances = get_option( 'widget_' . $id_base );
						$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
							'_multiwidget' => 1,
						);
						$single_widget_instances[] = (array) $widget;
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number                           = 1;
							$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}
						update_option( 'widget_' . $id_base, $single_widget_instances );
						$sidebars_widgets                    = get_option( 'sidebars_widgets' );
						$new_instance_id                     = $id_base . '-' . $new_instance_id_number;
						$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;
						update_option( 'sidebars_widgets', $sidebars_widgets );
						if ( $sidebar_available ) {
							$widget_message_type = 'success';
							$widget_message      = 'Imported';
						} else {
							$widget_message_type = 'warning';
							$widget_message      = 'Imported to Inactive';
						}
					}
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base;
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget->title ) ? $widget->title : '';
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;
				} // End foreach().
			} // End foreach().

			return true;
		}

		/**
		 * Import Widgets.
		 *
		 * @throws Exception If required file is missing.
		 * @throws Exception If can not import Widgets.
		 *
		 * @since 1.0
		 */
		private function import_widgets() {
			try {
				$file_name = 'widget_data.wie';

				if ( ! file_exists( $this->_get_import_package_dir_path( $file_name ) ) ) {
					throw new Exception(
						sprintf( __( 'A required file (%s) is missing in the selected zip file.', 'jupiterx-core' ), $file_name )
					);
				}

				$import_data = JupiterX_Core_Control_Panel_Helpers::getFileBody(
					$this->_get_import_package_dir_url( $file_name ),
					$this->_get_import_package_dir_path( $file_name )
				);

				$data = json_decode( $import_data );

				if ( ! $this->import_widget_data( $data ) ) {
					throw new Exception( __( 'A problem occurred in importing Widgets.', 'jupiterx-core' ) );
				}

				return wp_send_json_success(
					array(
						'step' => 'Widgets',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Import Settings.
		 *
		 * @throws Exception If required file is missing.
		 * @throws Exception If can not import Settings.
		 *
		 * @since 1.0
		 */
		private function import_settings() {
			try {

				require_once ABSPATH . 'wp-includes/class-wp-customize-manager.php';
				$wp_customize = new WP_Customize_Manager();

				$file_name = 'settings.json';

				if ( ! file_exists( $this->_get_import_package_dir_path( $file_name ) ) ) {
					throw new Exception(
						sprintf( __( '%s is missing in the selected zip file.', 'jupiterx-core' ), $file_name )
					);
				}

				$import_data = JupiterX_Core_Control_Panel_Helpers::getFileBody(
					$this->_get_import_package_dir_url( $file_name ),
					$this->_get_import_package_dir_path( $file_name )
				);

				$data = json_decode( $import_data, true );

				// Data checks.
				if ( 'array' != gettype( $data ) ) {
					throw new Exception(
						sprintf( __( 'Error importing settings! Please check that you uploaded (%s) a Settings export file.', 'jupiterx-core' ), $file_name )
					);
				}
				if ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) {
					throw new Exception(
						sprintf( __( 'Error importing settings! template Please check that you uploaded (%s) a Settings export file.', 'jupiterx-core' ), $file_name )
					);
				}

				$data['mods'] = self::_import_images( $data['mods'] );

				// Import custom options.
				// if ( isset( $data['options'] ) ) {

				// 	foreach ( $data['options'] as $option_key => $option_value ) {

				// 		$option = new JupiterX_Customizer_Option(
				// 			$wp_customize, $option_key, array(
				// 				'default'       => '',
				// 				'type'          => 'option',
				// 				'capability'    => 'edit_theme_options',
				// 			)
				// 		);

				// 		$option->import( $option_value );
				// 	}
				// }

				// If wp_css is set then import it.
				if ( function_exists( 'wp_update_custom_css_post' ) && isset( $data['wp_css'] ) && '' !== $data['wp_css'] ) {
					wp_update_custom_css_post( $data['wp_css'] );
				}

				// Loop through the mods.
				foreach ( $data['mods'] as $key => $val ) {

					// Save the mod.
					set_theme_mod( $key, $val );
				}

				return wp_send_json_success(
					array(
						'step' => 'Settings',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * End Import process by deleting Import directory and clearing theme cache.
		 *
		 * @since 1.0
		 */
		private function import_end() {
			try {

				$this->jupiterx_filesystem->rmdir( $this->folder['import_dir'], true );

				return wp_send_json_success(
					array(
						'step' => 'End',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Discard Export/Import process by deleting the the directory.
		 *
		 * @since 1.0
		 * @param string $dir The Export/Import directory.
		 */
		private function discard( $dir ) {
			try {
				$this->jupiterx_filesystem->rmdir( $dir, true );

				return wp_send_json_success(
					array(
						'step' => 'Discard',
					)
				);

			} catch ( Exception $e ) {
				return wp_send_json_error( $e->getMessage() );
			}
		}

		/**
		 * Get import package directory name.
		 *
		 * @since 1.0
		 */
		private function _get_import_package_dir_name() {
			return end( @scandir( $this->folder['import_dir'] ) );
		}

		/**
		 * Get import package directory full path.
		 *
		 * @param array $$file_name The file name.
		 *
		 * @since 1.0
		 */
		private function _get_import_package_dir_path( $file_name ) {
			return $this->folder['import_dir'] . '/' . $this->_get_import_package_dir_name() . '/' . $file_name;
		}

		/**
		 * Get import package directory full url.
		 *
		 * @param array $file_name The file name.
		 *
		 * @since 1.0
		 */
		private function _get_import_package_dir_url( $file_name ) {
			return $this->folder['import_url'] . '/' . $this->_get_import_package_dir_name() . '/' . $file_name;
		}

		/**
		 * Imports images for settings saved as mods.
		 *
		 * @since 1.0
		 * @access private
		 * @param array $mods An array of customizer mods.
		 * @return array The mods array with any new import data.
		 */
		static public function _import_images( $mods ) {
			foreach ( $mods as $key => $val ) {

				if ( self::_is_image_url( $val ) ) {

					$data = self::_sideload_image( $val );

					if ( ! is_wp_error( $data ) ) {

						$mods[ $key ] = $data->url;

						// Handle header image controls.
						if ( isset( $mods[ $key . '_data' ] ) ) {
							$mods[ $key . '_data' ] = $data;
							update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
						}
					}
				}
			}

			return $mods;
		}

		/**
		 * Taken from the core media_sideload_image function and
		 * modified to return an array of data instead of html.
		 *
		 * @since 1.0
		 * @access private
		 * @param string $file The image file path.
		 * @return array An array of image data.
		 */
		static private function _sideload_image( $file ) {
			$data = new stdClass();

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}
			if ( ! empty( $file ) ) {

				// Set variables for storage, fix file filename for query strings.
				preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
				$file_array = array();
				$file_array['name'] = basename( $matches[0] );

				// Download file to temp location.
				$file_array['tmp_name'] = download_url( $file );

				// If error storing temporarily, return the error.
				if ( is_wp_error( $file_array['tmp_name'] ) ) {
					return $file_array['tmp_name'];
				}

				// Do the validation and storage stuff.
				$id = media_handle_sideload( $file_array, 0 );

				// If error storing permanently, unlink.
				if ( is_wp_error( $id ) ) {
					@unlink( $file_array['tmp_name'] );
					return $id;
				}

				// Build the object to return.
				$meta                   = wp_get_attachment_metadata( $id );
				$data->attachment_id    = $id;
				$data->url              = wp_get_attachment_url( $id );
				$data->thumbnail_url    = wp_get_attachment_thumb_url( $id );
				$data->height           = $meta['height'];
				$data->width            = $meta['width'];
			}

			return $data;
		}

		/**
		 * Checks to see whether a string is an image url or not.
		 *
		 * @since 1.0
		 * @access private
		 * @param string $string The string to check.
		 * @return bool Whether the string is an image url or not.
		 */
		static private function _is_image_url( $string = '' ) {
			if ( is_string( $string ) ) {

				if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get supported plugins.
		 *
		 * @since 1.11.0
		 *
		 * @return array Supported plugins.
		 */
		private function get_supported_plugins() {
			$active_plugins = get_option( 'active_plugins' );

			if ( is_multisite() ) {
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins' ) );
			}

			$plugins = [];

			foreach ( $active_plugins as $plugin ) {
				$plugins[] = substr( $plugin, 0, strrpos( $plugin, '/' ) );
			}

			$supported_plugins = array_intersect( $plugins, $this->supported_plugins );

			return $supported_plugins;
		}

		/**
		 * Get supported tables to export.
		 *
		 * @since 1.11.0
		 *
		 * @return array Supported tables.
		 */
		private function get_supported_tables() {
			return [
				'jet-engine' => [ 'jet_post_types', 'jet_taxonomies' ],
				'jet-booking' => [ 'jet_apartment_units', 'jet_apartment_bookings' ],
			];
		}
	}

	new JupiterX_Core_Control_Panel_Export_Import();
}
