<?php

use WprAddons\Admin\Includes\WPR_Render_Templates;

/**
 * Wpr_Astra_Compat setup
 *
 */

/**
 * Astra theme compatibility.
 */
class Wpr_Astra_Compat {

	/**
	 * Instance of Wpr_Astra_Compat.
	 *
	 * @var Wpr_Astra_Compat
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
			self::$instance = new Wpr_Astra_Compat();

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
			add_action( 'template_redirect', [ $this, 'astra_setup_header' ], 10 );
			add_action( 'astra_header', [$this->render_templates, 'replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'add_canvas_header' ] );
		}

		if ( $this->render_templates->is_template_available('footer') ) {
			add_action( 'template_redirect', [ $this, 'astra_setup_footer' ], 10 );
			add_action( 'astra_footer', [$this->render_templates, 'replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function astra_setup_header() {
		remove_action( 'astra_header', 'astra_header_markup' );

		// Remove the new header builder action.
		if ( class_exists( '\Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_header', [ Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ] );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function astra_setup_footer() {
		remove_action( 'astra_footer', 'astra_footer_markup' );

		// Remove the new footer builder action.
		if ( class_exists( '\Astra_Builder_Helper' ) && \Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_footer', [ Astra_Builder_Footer::get_instance(), 'footer_markup' ] );
		}
	}
}

Wpr_Astra_Compat::instance();
