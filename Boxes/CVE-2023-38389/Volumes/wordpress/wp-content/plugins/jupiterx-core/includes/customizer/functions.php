<?php
/**
 * The Jupiter Customizer component.
 *
 * @package JupiterX_Core\Customizer
 */

/**
 * Load Kirki library.
 *
 * @since 1.0.0
 */
function jupiterx_customizer_kirki() {
	jupiterx_core()->load_files( [ 'customizer/vendors/kirki/kirki' ] );
}

add_action( 'jupiterx_init', 'jupiterx_load_customizer_dependencies', 5 );
/**
 * Load Customzier.
 *
 * @since 1.9.0
 *
 * @return void
 */
function jupiterx_load_customizer_dependencies() {
	jupiterx_core()->load_files( [ 'customizer/api/customizer' ] );
	jupiterx_core()->load_files( [ 'customizer/api/init' ] );
}

if ( ! function_exists( 'jupiterx_core_customizer_include' ) ) {
	add_action( 'init', 'jupiterx_core_customizer_include', 15 );
	/**
	 * Include customizer setting file.
	 *
	 * With loading customizer on init, we have access to custom post types and custom taxonomies.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	function jupiterx_core_customizer_include() {
		/**
		 * Hook after registering theme customizer settings.
		 *
		 * @since 1.3.0
		 */
		do_action( 'jupiterx_before_customizer_register' );

		/**
		 * Load customizer settings.
		 */
		require_once jupiterx_core()->plugin_dir() . 'includes/customizer/settings/settings.php';

		/**
		 * Hook after registering theme customizer settings.
		 *
		 * @since 1.3.0
		 */
		do_action( 'jupiterx_after_customizer_register' );
	}
}

if ( version_compare( JUPITERX_VERSION, '1.17.1', '>' ) ) {
	add_action(
		'wp_ajax_jupiterx_core_customizer_preview_redirect_url',
		'jupiterx_core_customizer_preview_redirect_url'
	);
}

if ( ! function_exists( 'jupiterx_core_customizer_preview_redirect_url' ) ) {
	/**
	 * Get Customizer redirect URL.
	 *
	 * @since 1.16.0
	 *
	 * @return void
	 */
	function jupiterx_core_customizer_preview_redirect_url() {
		check_ajax_referer( 'jupiterx_core_get_customizer_preview_redirect_url' );

		$section = wp_unslash( filter_input( INPUT_POST, 'section' ) );
		$options = wp_unslash( json_decode( filter_input( INPUT_POST, 'options' ), true ) );

		if ( empty( $section ) ) {
			wp_send_json_error();
		}

		if ( ! is_array( $options ) ) {
			$options = [];
		}

		$url = jupiterx_core_get_customizer_preview_redirect_url( $section, $options );

		if ( empty( $url ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( [ 'redirectUrl' => $url ] );
	}
}

/**
 * Ignore phpmd erros.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_customizer_preview_redirect' ) ) {
	// phpcs:disable
	if ( version_compare( JUPITERX_VERSION, '1.17.1', '<=' ) ) {
		add_action( 'template_redirect', 'jupiterx_core_customizer_preview_redirect' );
	}
	// phpcs:enable
	/**
	 * Redircet to desired template while selecting a pop-up.
	 *
	 * @since 1.9.0
	 *
	 * @param string $section Customizer Section.
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	function jupiterx_core_customizer_preview_redirect( $section = '' ) {
		$section = jupiterx_get( 'jupiterx' );

		switch ( $section ) {
			case 'jupiterx_post_single':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'blog_single' );
				if ( ! is_singular( 'post' ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_portfolio_single':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'portfolio_single' );
				if ( ! is_singular( 'portfolio' ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_search':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'search' );
				if ( ! is_search() && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_404':
				$template = get_theme_mod( 'jupiterx_404_template' );
				if ( ! empty( $template ) ) {
					wp_safe_redirect( get_permalink( intval( $template ) ) );
					exit;
				}

				global $wp_query;

				$wp_query->set_404();
				status_header( 404 );

				break;

			case 'jupiterx_maintenance':
				$template = get_theme_mod( 'jupiterx_maintenance_template' );
				if ( ! empty( $template ) ) {
					wp_safe_redirect( get_permalink( intval( $template ) ) );
					exit;
				}
				break;

			case 'jupiterx_blog_archive':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'blog_archive' );
				if ( ! is_post_type_archive( 'post' ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_portfolio_archive':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'portfolio_archive' );
				if ( $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_checkout_cart':
				if ( class_exists( 'WooCommerce' ) ) {
					$url = get_permalink( wc_get_page_id( 'cart' ) );
					if ( ! is_cart() && ! is_checkout() && $url ) {
						wp_safe_redirect( $url );
						exit;
					}
				}
				break;

			case 'jupiterx_product_archive':
				if ( class_exists( 'WooCommerce' ) ) {
					$url = JupiterX_Customizer_Utils::get_preview_url( 'product_archive' );
					if ( ! is_product_category() && $url ) {
						wp_safe_redirect( $url );
						exit;
					}
				}
				break;

			case 'jupiterx_product_page':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'product_single' );
				if ( ! is_singular( 'product' ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			case 'jupiterx_product_list':
				if ( class_exists( 'WooCommerce' ) ) {
					$url = get_permalink( wc_get_page_id( 'shop' ) );
					if ( ! is_shop() && $url ) {
						wp_safe_redirect( $url );
						exit;
					}
				}
				break;

			case 'jupiterx_page_single':
				$url = JupiterX_Customizer_Utils::get_preview_url( 'single_page' );
				if ( ! is_singular( 'page' ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;

			default:
				$post_type = jupiterx_get( 'post_type' );

				if ( $post_type && jupiterx_get( 'single' ) ) {
					$url = JupiterX_Customizer_Utils::get_permalink( JupiterX_Customizer_Utils::get_random_post( $post_type ) );
				} elseif ( $post_type && jupiterx_get( 'archive' ) ) {
					$url = get_post_type_archive_link( $post_type );
				}

				if ( isset( $url ) && $url ) {
					wp_safe_redirect( $url );
					exit;
				}
				break;
		}
	}
}

add_filter( 'template_include', 'jupiterx_core_404_page_template' );

/**
 * Check function exists.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_404_page_template' ) ) {
	/**
	 * Replace 404 default template with custom.
	 *
	 * @since 1.17.0
	 *
	 * @param string $template Template path.
	 * @return string
	 */
	function jupiterx_core_404_page_template( $template ) {
		$section = jupiterx_get( 'jupiterx' );

		if ( ! is_404() && 'jupiterx_force_404' !== $section ) {
			return $template;
		}

		$page_id = intval( get_theme_mod( 'jupiterx_404_template' ) );
		$post_id = get_queried_object_id();

		if ( empty( $page_id ) || ! in_array( get_post_status( $page_id ), [ 'publish', 'private' ], true ) ) {
			return $template;
		}

		if ( $post_id === $page_id ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

			return $template;
		}

		$is_built_with_elementor = ! ! get_post_meta( $page_id, '_elementor_edit_mode', true );

		if ( ! $is_built_with_elementor ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

			jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $page_id ) {
				$query = new WP_Query( [
					'post_type' => 'page',
					'post__in' => [ $page_id ],
				] );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						the_content();
					}
				}

				wp_reset_postdata();
			} );

			return JUPITERX_THEME_PATH . '/full-width.php';
		}

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return $template;
		}

		$elementor = \Elementor\Plugin::$instance;

		if ( $elementor->preview->is_preview_mode() ) {
			return $template;
		}

		if ( class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			$conditions = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_component( 'conditions' );

			if ( ! empty( $conditions->get_location_templates( '404' ) ) ) {
				return $template;
			}
		}

		jupiterx_add_filter( 'jupiterx_layout', 'c' );
		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

		$current_template     = get_post_meta( $page_id, '_wp_page_template', true );
		$page_template_module = $elementor->modules_manager->get_modules( 'page-templates' );
		$page_templates       = [ 'full-width.php', $page_template_module::TEMPLATE_CANVAS, $page_template_module::TEMPLATE_HEADER_FOOTER ];

		// Switch to full layout for Elementor Canvas, Elementor Full Width and Full Width page templates.
		if ( in_array( $current_template, $page_templates, true ) ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
		}

		// Remove header & footer for Elementor Canvas page template.
		if ( $current_template === $page_template_module::TEMPLATE_CANVAS ) {
			jupiterx_remove_action( 'jupiterx_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_footer_partial_template' );
		}

		jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $page_id ) {
			jupiterx_output_e( 'jupiterx_custom_single_template', jupiterx_get_custom_template( $page_id ) );
		} );

		return JUPITERX_THEME_PATH . '/full-width.php';
	}
}

add_action( 'template_redirect', 'jupiterx_core_404_page_redirect' );

if ( ! function_exists( 'jupiterx_core_404_page_redirect' ) ) {
	/**
	 * Redirect for only default 404 page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @SuppressWarnings(PHPMD.ExitExpression)
	 */
	function jupiterx_core_404_page_redirect() {
		$template = intval( get_theme_mod( 'jupiterx_404_template' ) );

		if ( ! empty( $template ) ) {
			return;
		}

		$section = jupiterx_get( 'jupiterx' );

		if ( 'jupiterx_force_404' !== $section ) {
			return;
		}

		global $wp_query;

		$wp_query->set_404();

		status_header( 404 );
	}
}

add_filter( 'template_include', 'jupiterx_core_maintenance_page_template', 99999, 1 );

/**
 * Check function exists.
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_maintenance_page_template' ) ) {
	/**
	 * Load custom maintenance template.
	 *
	 * @since 1.17.0
	 *
	 * @param string $template Template path.
	 * @return string
	 */
	function jupiterx_core_maintenance_page_template( $template ) {
		$section = jupiterx_get( 'jupiterx' );

		$is_enabled = get_theme_mod( 'jupiterx_maintenance', false );

		if ( ! $is_enabled && 'jupiterx_force_maintenance' !== $section ) {
			return $template;
		}

		if ( is_user_logged_in() && 'jupiterx_force_maintenance' !== $section ) {
			return $template;
		}

		$page_id = intval( get_theme_mod( 'jupiterx_maintenance_template' ) );
		$post_id = get_queried_object_id();

		if ( $post_id === $page_id ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

			return $template;
		}

		$is_built_with_elementor = ! ! get_post_meta( $page_id, '_elementor_edit_mode', true );

		if ( ! $is_built_with_elementor ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
			jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );

			jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $page_id ) {
				$query = new WP_Query( [
					'post_type' => 'page',
					'post__in' => [ $page_id ],
				] );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();

						the_content();
					}
				}

				wp_reset_postdata();
			} );

			return JUPITERX_THEME_PATH . '/full-width.php';
		}

		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return $template;
		}

		$elementor = \Elementor\Plugin::$instance;

		if ( $elementor->preview->is_preview_mode() ) {
			return $template;
		}

		if ( ! empty( $elementor->maintenance_mode->get( 'mode' ) ) ) {
			return $template;
		}

		$protocol = wp_get_server_protocol();
		header( "$protocol 503 Service Unavailable", true, 503 );
		header( 'Content-Type: text/html; charset=utf-8' );
		header( 'Retry-After: 600' );

		$current_template     = get_post_meta( $page_id, '_wp_page_template', true );
		$page_template_module = $elementor->modules_manager->get_modules( 'page-templates' );
		$page_templates       = [ 'full-width.php', $page_template_module::TEMPLATE_CANVAS, $page_template_module::TEMPLATE_HEADER_FOOTER ];

		// Switch to full layout for Elementor Canvas, Elementor Full Width and Full Width page templates.
		if ( in_array( $current_template, $page_templates, true ) ) {
			jupiterx_add_filter( 'jupiterx_layout', 'c' );
		}

		// Remove header & footer for Elementor Canvas page template.
		if ( $current_template === $page_template_module::TEMPLATE_CANVAS ) {
			jupiterx_remove_action( 'jupiterx_header_partial_template' );
			jupiterx_remove_action( 'jupiterx_footer_partial_template' );
		}

		jupiterx_modify_action( 'jupiterx_fullwidth_template_content', null, function () use ( $page_id ) {
			jupiterx_output_e( 'jupiterx_custom_single_template', jupiterx_get_custom_template( $page_id ) );
		} );

		return JUPITERX_THEME_PATH . '/full-width.php';
	}
}

/**
 * Ignore phpmd erros.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_get_customizer_preview_redirect_url' ) ) {
	/**
	 * Calculate redirect URL for customizer preview.
	 *
	 * @since 1.16.0
	 *
	 * @param string $section Section open in customizer preview.
	 * @param array  $options Options changed in customizer preview.
	 *
	 * @return string
	 */
	function jupiterx_core_get_customizer_preview_redirect_url( $section = '', $options = [] ) {
		switch ( $section ) {
			case 'jupiterx_post_single':
				return JupiterX_Customizer_Utils::get_preview_url( 'blog_single' );

			case 'jupiterx_portfolio_single':
				return JupiterX_Customizer_Utils::get_preview_url( 'portfolio_single' );

			case 'jupiterx_search':
				return JupiterX_Customizer_Utils::get_preview_url( 'search' );

			case 'jupiterx_404':
				$template = intval( get_theme_mod( 'jupiterx_404_template' ) );

				if ( isset( $options['jupiterx_404_template'] ) ) {
					$template = $options['jupiterx_404_template'];
				}

				if ( ! empty( $template ) && 'publish' === get_post_status( $template ) ) {
					$url  = get_permalink( $template );
					$url .= ( wp_parse_url( $url, PHP_URL_QUERY ) ? '&' : '?' ) . 'jupiterx=jupiterx_force_404';

					return $url;
				}

				return get_site_url() . '?jupiterx=jupiterx_force_404';

			case 'jupiterx_maintenance':
				$template = intval( get_theme_mod( 'jupiterx_maintenance_template' ) );

				if ( isset( $options['jupiterx_maintenance_template'] ) ) {
					$template = $options['jupiterx_maintenance_template'];
				}

				if ( ! empty( $template ) && 'publish' === get_post_status( $template ) ) {
					$url  = get_permalink( $template );
					$url .= ( wp_parse_url( $url, PHP_URL_QUERY ) ? '&' : '?' ) . 'jupiterx=jupiterx_force_maintenance';

					return $url;
				}

				return get_site_url();

			case 'jupiterx_blog_archive':
				return JupiterX_Customizer_Utils::get_preview_url( 'blog_archive' );

			case 'jupiterx_portfolio_archive':
				return JupiterX_Customizer_Utils::get_preview_url( 'portfolio_archive' );

			case 'jupiterx_checkout_cart':
				if ( class_exists( 'WooCommerce' ) ) {
					return get_permalink( wc_get_page_id( 'cart' ) );
				}
				break;

			case 'jupiterx_product_archive':
				if ( class_exists( 'WooCommerce' ) ) {
					return JupiterX_Customizer_Utils::get_preview_url( 'product_archive' );
				}
				break;

			case 'jupiterx_product_page':
				return JupiterX_Customizer_Utils::get_preview_url( 'product_single' );

			case 'jupiterx_product_list':
				if ( class_exists( 'WooCommerce' ) ) {
					return get_permalink( wc_get_page_id( 'shop' ) );
				}
				break;

			case 'jupiterx_page_single':
				return JupiterX_Customizer_Utils::get_preview_url( 'single_page' );

			case 'jupiterx_title_bar_settings':
				return jupiterx_core_customizer_exceptions_control_redirect_url(
					'jupiterx_title_bar_exceptions',
					$options
				);

			case 'jupiterx_sidebar_settings':
				return jupiterx_core_customizer_exceptions_control_redirect_url(
					'jupiterx_sidebar_exceptions',
					$options
				);

			default:
				$post_type = ! empty( $options['post_type'] ) ? $options['post_type'] : '';
				$url       = get_bloginfo( 'url' );

				if ( $post_type && ! empty( $options['single'] ) ) {
					$url = JupiterX_Customizer_Utils::get_permalink( JupiterX_Customizer_Utils::get_random_post( $post_type ) );
				} elseif ( $post_type && ! empty( $options['archive'] ) ) {
					$url = get_post_type_archive_link( $post_type );
				}

				return $url;
		}
	}
}

/**
 * Ignore phpmd erros.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExitExpression)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
if ( ! function_exists( 'jupiterx_core_customizer_exceptions_control_redirect_url' ) ) {
	/**
	 * Redirect Jupiter X Custom Exception Control changes to relevant page.
	 *
	 * @since 1.16.0
	 *
	 * @param string $exception Exception section.
	 * @param array  $options   Options changed within Exception control.
	 *
	 * @return mixed
	 */
	function jupiterx_core_customizer_exceptions_control_redirect_url( $exception, $options ) {
		$exception_changed = '';
		$exceptions        = [];

		if ( ! empty( $options[ $exception ] ) && is_array( $options[ $exception ] ) ) {
			$exceptions = $options[ $exception ];
		}

		if ( empty( $options ) || ! is_array( $options ) ) {
			return null;
		}

		$option_keys  = array_keys( $options );
		$option_id    = array_shift( $option_keys );
		$option_value = $options[ $option_id ];

		foreach ( $exceptions as $e_key => $e_value ) {
			if ( 0 === strpos( $option_id, $exception . '_' . $e_key ) ) {
				$exception_changed = $e_key;
				break;
			}
		}

		if ( $exception === $option_id ) {
			$exception_changed = $option_value;
			$exception_changed = empty( $exception_changed ) ? '' : json_decode( $exception_changed, true );

			if ( is_array( $exception_changed ) ) {
				$exception_changed = array_pop( $exception_changed );
			}
		}

		if ( empty( $exception_changed ) ) {
			jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_post_single' );

			return null;
		}

		$custom_post_types = array_keys( jupiterx_get_post_types( 'label' ) );

		if ( in_array( $exception_changed, $custom_post_types, true ) ) {
			$url = JupiterX_Customizer_Utils::get_permalink(
				JupiterX_Customizer_Utils::get_random_post( $exception_changed )
			);

			if ( ! is_singular( $exception_changed ) && $url ) {
				return $url;
			}
		}

		$custom_post_types_archives = array_keys( jupiterx_get_post_types_archives() );

		if ( in_array( $exception_changed, $custom_post_types_archives, true ) ) {
			$url = get_post_type_archive_link( str_replace( 'archive__', '', $exception_changed ) );

			if ( ! is_post_type_archive( $exception_changed ) && $url ) {
				return $url;
			}
		}

		switch ( $exception_changed ) {
			case 'page':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_page_single' );
			case 'post':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_post_single' );
			case 'search':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_search' );
			case 'product':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_product_list' );
			case 'archive':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_blog_archive' );
			case 'portfolio':
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_portfolio_single' );
			default:
				return jupiterx_core_get_customizer_preview_redirect_url( 'jupiterx_404' );
		}
	}
}

add_action( 'wp_ajax_jupiterx_core_customizer_get_select2_options', 'jupiterx_core_customizer_get_select2_options' );

if ( ! function_exists( 'jupiterx_core_customizer_get_select2_options' ) ) {
	/**
	 * Get Customizer select2 options.
	 *
	 * @since 1.17.0
	 *
	 * @return void
	 */
	function jupiterx_core_customizer_get_select2_options() {
		check_ajax_referer( 'jupiterx_customizer_preview' );

		$s         = wp_unslash( filter_input( INPUT_GET, 's' ) );
		$post_type = wp_unslash( filter_input( INPUT_GET, 'post_type' ) );

		if ( empty( $s ) ) {
			wp_send_json_error( esc_html__( 'Search param is empty.', 'jupiterx-core' ) );
		}

		$data  = [];
		$posts = get_posts( [
			's'              => $s,
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => [ 'publish', 'private' ],
		] );

		foreach ( $posts as $post ) {
			$data[] = [
				'id'   => $post->ID,
				'text' => $post->post_title,
			];
		}

		wp_send_json_success( $data );
	}
}

add_action( 'wp_ajax_jupiterx_core_customizer_get_post_title', 'jupiterx_core_customizer_get_post_title' );

if ( ! function_exists( 'jupiterx_core_customizer_get_post_title' ) ) {
	/**
	 * Get Customizer post title.
	 *
	 * @since 1.17.0
	 *
	 * @return void
	 */
	function jupiterx_core_customizer_get_post_title() {
		check_ajax_referer( 'jupiterx_customizer_preview' );

		$id = wp_unslash( filter_input( INPUT_GET, 'id' ) );

		if ( empty( $id ) ) {
			wp_send_json_error( esc_html__( 'ID param is empty.', 'jupiterx-core' ) );
		}

		$data = get_the_title( $id );

		if ( empty( $data ) ) {
			wp_send_json_error( esc_html__( 'No valid post is found.', 'jupiterx-core' ) );
		}

		wp_send_json_success( $data );
	}
}

if ( ! function_exists( 'is_multilingual_customizer' ) ) {
	/**
	 * Check if Multilingual Customizer is enabled.
	 *
	 * @since 1.19.1
	 *
	 * @return bool
	 */
	function is_multilingual_customizer() {
		return jupiterx_get_option( 'multilingual_customizer', 0 );
	}
}

if ( ! function_exists( 'jupiterx_dependency_notice_handler' ) ) {
	/**
	 * Check pane dependencies.
	 *
	 * @since 1.20.0
	 *
	 * @return void
	 */
	function jupiterx_dependency_notice_handler( $id ) {
		// it checks if the elementor and ACF was enabled or not.
		if ( did_action( 'elementor/loaded' ) && class_exists( 'ACF' ) ) {
			return;
		}

		/* translators: %s represents admin plugin url. */
		$notice_message = sprintf( __( 'These options require "Required Plugins" to be installed. Please go to <a class="jupiterx-alert-control-link" href="%s"> Control Panel > Plugins</a>, install and activate all the required plugins.', 'jupiterx-core' ),
		admin_url( 'admin.php?page=jupiterx#/plugins' ) );

		JupiterX_Customizer::add_field( [
			'type' => 'jupiterx-alert',
			'settings' => $id . '_warning',
			'section' => $id,
			'label' => $notice_message,
		] );
	}
}

if ( ! function_exists( 'jupiterx_core_get_pro_badge' ) ) {
	/**
	 * Get pro badge.
	 *
	 * @since 2.0.6
	 *
	 * @return string
	 */
	function jupiterx_core_get_pro_badge() {
		if ( version_compare( JUPITERX_VERSION, '2.0.0', '>=' ) ) {
			return JupiterX_Customizer_Utils::get_pro_badge_url();
		}

		return '';
	}
}
