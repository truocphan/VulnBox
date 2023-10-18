<?php

use WprAddons\Admin\Includes\WPR_Render_Templates;

/**
 * Storefront theme compatibility.
 */
class Wpr_Storefront_Compat {

	/**
	 * Instance of Wpr_Storefront_Compat.
	 *
	 * @var Wpr_Storefront_Compat
	 */
	private static $instance;

	/**
	 * WPR_Render_Templates() Class
	 */
	private $render_templates;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Wpr_Storefront_Compat();

			add_action( 'wp', [ self::$instance, 'hooks' ] );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		$this->render_templates = new WPR_Render_Templates( true );

		if ( $this->render_templates->is_template_available('header') ) {
			add_action( 'template_redirect', [ $this, 'setup_header' ], 10 );
			add_action( 'storefront_before_header', [$this->render_templates, 'replace_header'], 500 );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'add_canvas_header' ] );
		}

		if ( $this->render_templates->is_template_available('footer') ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'storefront_after_footer', [$this->render_templates, 'replace_footer'], 500 );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'add_canvas_footer' ] );
		}

		if ( $this->render_templates->is_template_available('header') || $this->render_templates->is_template_available('footer') ) {
			add_action( 'wp_head', [ $this, 'styles' ] );
		}
	}

	/**
	 * Add inline CSS to hide empty divs for header and footer in storefront
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function styles() {
		$css = '<style id="wpr-disable-storefront-hf">';

		if ( $this->render_templates->is_template_available('header') ) {
			$css .= '.site-header {
				display: none;
			}';
		}

		if ( $this->render_templates->is_template_available('footer') ) {
			$css .= '.site-footer {
				display: none;
			}';
		}

		$css .= '</style>';

		// Echo plain CSS (no user input or variables)
		echo ''. $css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header() {
		for ( $priority = 0; $priority < 200; $priority ++ ) {
			remove_all_actions( 'storefront_header', $priority );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer() {
		for ( $priority = 0; $priority < 200; $priority ++ ) {
			remove_all_actions( 'storefront_footer', $priority );
		}
	}

}

Wpr_Storefront_Compat::instance();
