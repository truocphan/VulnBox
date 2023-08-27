<?php

namespace JupiterX_Core\Raven\Modules\Custom_Scripts;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 15, 2 );
		add_action( 'elementor/css-file/post/parse', [ $this, 'page_custom_css' ] );
		add_action( 'wp_print_footer_scripts', [ $this, 'page_custom_js' ], 999 );
	}

	public function register_controls( \Elementor\Controls_Stack $controls_stack, $section_id ) {
		$allowed_post_types = [
			'wp-post',
			'wp-page',
		];

		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		if ( ! in_array( $controls_stack->get_name(), $allowed_post_types, true ) ) {
			return;
		}

		$controls_stack->start_controls_section(
			'section_raven_custom_scripts',
			[
				'label' => __( 'Custom CSS/JS', 'jupiterx-core' ),
				'tab' => 'advanced',
			]
		);

		$controls_stack->add_control(
			'raven_custom_css',
			[
				'type' => 'code',
				'label' => __( 'Custom CSS', 'jupiterx-core' ),
				'language' => 'css',
				'render_type' => 'ui',
				'show_label' => true,
				'separator' => 'none',
			]
		);

		$controls_stack->add_control(
			'raven_custom_js',
			[
				'type' => 'code',
				'label' => __( 'Custom JS', 'jupiterx-core' ),
				'language' => 'javascript',
				'render_type' => 'none',
				'show_label' => true,
				'separator' => 'none',
			]
		);

		$controls_stack->end_controls_section();
	}

	public function page_custom_css( $post_css ) {
		$document = \Elementor\Plugin::instance()->documents->get( $post_css->get_post_id() );

		if ( ! $document ) {
			return;
		}

		$custom_css = $document->get_settings( 'raven_custom_css' );

		$custom_css = ! empty( $custom_css ) ? trim( $custom_css ) : '';

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	public function page_custom_js() {
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() || \Elementor\Plugin::instance()->preview->is_preview_mode() ) {
			return;
		}

		$document = \Elementor\Plugin::instance()->documents->get( get_the_ID() );

		if ( ! $document ) {
			return;
		}

		$custom_js = $document->get_settings( 'raven_custom_js' );

		if ( empty( $custom_js ) ) {
			return;
		}

		echo "<script type='text/javascript'>{$custom_js}</script>";
	}

}
