<?php

namespace JupiterX_Core\Raven\Modules\Product_Rating\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Utils;

defined( 'ABSPATH' ) || die();

class Product_Rating extends Base_Widget {

	public function get_name() {
		return 'raven-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Product Rating', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-product-rating';
	}

	public function get_categories() {
		return [ 'jupiterx-core-raven-woo-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'star_color',
			[
				'label'     => esc_html__( 'Star Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#FFC000',
				'selectors' => [
					'{{WRAPPER}} .star-rating span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label'     => esc_html__( 'Empty Star Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#FFC000',
				'selectors' => [
					'{{WRAPPER}} .star-rating::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Link Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#1890ff',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .woocommerce-review-link',
			]
		);

		$this->add_control(
			'star_size',
			[
				'label'     => esc_html__( 'Star Size', 'jupiterx-core' ),
				'type'      => 'slider',
				'default'   => [
					'size' => 1.1,
					'unit' => 'em',
				],
				'range'     => [
					'em' => [
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'space_between',
			[
				'label'      => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'unit' => 'em',
				],
				'range'      => [
					'em' => [
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					],
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'        => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type'         => 'choose',
				'options'      => [
					'start'    => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'   => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justified', 'jupiterx-core' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
			]
		);

		$this->end_controls_section();
	}


	protected function before_widget_wrapper_html() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wc-wrapper', 'class', 'product-rating-wrapper-align-' . $settings['alignment'] );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wc-wrapper' ); ?>><div class="woocommerce-product-rating">
		<?php
	}

	protected function after_widget_wrapper_html() {
		?>
		</div></div>
		<?php
	}

	protected function render() {
		if ( ! post_type_supports( 'product', 'comments' ) ) {
			return;
		}

		Utils::get_product();

		global $product;

		if ( empty( $product ) ) {
			return;
		}

		$rating_count = $product->get_rating_count();

		if ( $rating_count <= 0 ) {
			return;
		}

		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();

		$this->before_widget_wrapper_html();

		echo wc_get_rating_html( $average, $rating_count );

		if ( comments_open( $product->get_id() ) ) : ?>
			<a href="#reviews" class="woocommerce-review-link" rel="nofollow">
				<?php
				echo '(';
				printf(
					/* translators: %s: reviews count */
					_n( '%s customer review', '%s customer reviews',
						$review_count,
					'woocommerce' ),
					'<span class="count">' . esc_html( $review_count ) . '</span>'
				);
				echo ')';
				?>
			</a>
		<?php endif;

		$this->after_widget_wrapper_html();
	}

	/**
	 * Render Plain Content.
	 *
	 * Override the default render behavior, don't render widget content.
	 *
	 * @return void
	 */
	public function render_plain_content() {}
}
