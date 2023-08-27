<?php
/**
 * @codingStandardsIgnoreFile
 */

namespace JupiterX_Core\Raven\Modules\Posts\Post\Actions;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Modules\Posts\Classes\Post_Base;

class Post_Classic extends Post_Base {

	protected function register_action_hooks() {
		add_action( 'elementor/element/raven-posts/section_sort_filter/after_section_end', [ $this, 'register_action_controls' ] );
	}

	public function register_action_controls( \Elementor\Widget_Base $widget ) {
		$this->skin = $widget->get_skin( 'classic' );

		$this->register_controls();
		$this->inject_controls();
		$this->update_controls();
	}

	protected function register_controls() {
		$this->register_container_controls();
		$this->register_image_controls();
		$this->register_overlay_controls();
		$this->register_icons_controls();
		$this->register_title_controls();
		$this->register_meta_controls();
		$this->register_excerpt_controls();
		$this->register_button_controls();
	}

	protected function inject_controls() {
		$this->skin->start_injection( [
			'at' => 'before',
			'of' => 'query_posts_per_page',
		] );

		$this->register_image_size_control();

		$this->skin->end_injection();

		$this->skin->start_injection( [
			'at' => 'after',
			'of' => 'query_posts_per_page',
		] );

		$this->register_settings_controls();

		$this->skin->end_injection();

		$this->skin->start_injection( [
			'at' => 'before',
			'of' => $this->skin->get_control_id( 'post_padding' ),
		] );

		$this->skin->add_responsive_control(
			'columns_spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'device_args' => [
					'desktop' => [
						'selectors' => [
							'{{WRAPPER}} .raven-grid, {{WRAPPER}} .raven-masonry' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid-item, {{WRAPPER}} .raven-masonry-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid.raven-grid-1, {{WRAPPER}} .raven-masonry.raven-masonry-1' => 'margin-left: 0; margin-right: 0;',
							'{{WRAPPER}} .raven-grid.raven-grid-1 .raven-grid-item, {{WRAPPER}} .raven-masonry.raven-masonry-1 .raven-masonry-item' => 'padding-left: 0; padding-right: 0;',
						],
					],
					'tablet' => [
						'selectors' => [
							'{{WRAPPER}} .raven-grid, {{WRAPPER}} .raven-masonry' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid-item, {{WRAPPER}} .raven-masonry-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid.raven-grid-tablet-1, {{WRAPPER}} .raven-masonry.raven-masonry-tablet-1' => 'margin-left: 0; margin-right: 0;',
							'{{WRAPPER}} .raven-grid.raven-grid-tablet-1 .raven-grid-item, {{WRAPPER}} .raven-masonry.raven-masonry-tablet-1 .raven-masonry-item' => 'padding-left: 0; padding-right: 0;',
						],
					],
					'mobile' => [
						'selectors' => [
							'{{WRAPPER}} .raven-grid, {{WRAPPER}} .raven-masonry' => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2 ); margin-right: calc( -{{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid-item, {{WRAPPER}} .raven-masonry-item' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
							'{{WRAPPER}} .raven-grid.raven-grid-mobile-1, {{WRAPPER}} .raven-masonry.raven-masonry-mobile-1' => 'margin-left: 0; margin-right: 0;',
							'{{WRAPPER}} .raven-grid.raven-grid-mobile-1 .raven-grid-item, {{WRAPPER}} .raven-masonry.raven-masonry-mobile-1 .raven-masonry-item' => 'padding-left: 0; padding-right: 0;',
						],
					],
				]
			]
		);

		$this->skin->add_responsive_control(
			'rows_spacing',
			[
				'label' => __( 'Row Spacing', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-post-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->skin->end_injection();
	}

	protected function update_controls() {
		$this->skin->update_responsive_control(
			'post_image_height',
			[
				'condition' => [
					'_skin' => $this->skin->get_id(),
					$this->skin->get_control_id( 'layout!' ) => 'masonry',
				],
			]
		);
	}
}
