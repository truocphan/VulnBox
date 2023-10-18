<?php

use WprAddons\Admin\Includes\WPR_Render_Templates;

/**
 * OceanWP theme compatibility.
 */
class Wpr_OceanWP_Compat {

	/**
	 * Instance of Wpr_OceanWP_Compat.
	 *
	 * @var Wpr_OceanWP_Compat
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
			self::$instance = new Wpr_OceanWP_Compat();

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
			add_action( 'ocean_header', [$this->render_templates, 'replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'add_canvas_header' ] );
		}

		if ( $this->render_templates->is_template_available('footer') ) {
			add_action( 'template_redirect', [ $this, 'setup_footer' ], 10 );
			add_action( 'ocean_footer', [$this->render_templates, 'replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header() {
		remove_action( 'ocean_top_bar', 'oceanwp_top_bar_template' );
		remove_action( 'ocean_header', 'oceanwp_header_template' );
		remove_action( 'ocean_page_header', 'oceanwp_page_header_template' );
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer() {
		remove_action( 'ocean_footer', 'oceanwp_footer_template' );
	}

}

Wpr_OceanWP_Compat::instance();
