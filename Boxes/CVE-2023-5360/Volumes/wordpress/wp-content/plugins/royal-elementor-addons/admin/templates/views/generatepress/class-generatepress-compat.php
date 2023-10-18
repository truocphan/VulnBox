<?php

use WprAddons\Admin\Includes\WPR_Render_Templates;

/**
 * Wpr_GeneratePress_Compat setup
 *
 * @since 1.0
 */
class Wpr_GeneratePress_Compat {

	/**
	 * Instance of Wpr_GeneratePress_Compat
	 *
	 * @var Wpr_GeneratePress_Compat
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
			self::$instance = new Wpr_GeneratePress_Compat();

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
			add_action( 'template_redirect', [ $this, 'generatepress_setup_header' ] );
			add_action( 'generate_header', [$this->render_templates, 'replace_header'] );
			add_action( 'elementor/page_templates/canvas/before_content', [ $this->render_templates, 'add_canvas_header' ] );
		}

		if ( $this->render_templates->is_template_available('footer') ) {
			add_action( 'template_redirect', [ $this, 'generatepress_setup_footer' ] );
			add_action( 'generate_footer', [$this->render_templates, 'replace_footer'] );
			add_action( 'elementor/page_templates/canvas/after_content', [ $this->render_templates, 'add_canvas_footer' ] );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function generatepress_setup_header() {
		remove_action( 'generate_header', 'generate_construct_header' );
	}

	/**
	 * Disable footer from the theme.
	 */
	public function generatepress_setup_footer() {
		remove_action( 'generate_footer', 'generate_construct_footer_widgets', 5 );
		remove_action( 'generate_footer', 'generate_construct_footer' );
	}

}

Wpr_GeneratePress_Compat::instance();
