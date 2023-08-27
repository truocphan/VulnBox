<?php

namespace JupiterX_Core\Raven\Modules\Pricing_Table\Widgets;

use Elementor\Icons_Manager;
use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Pricing_Table\Classes\Controls;
use JupiterX_Core\Raven\Modules\Pricing_Table\Classes\Render_Helper;

defined( 'ABSPATH' ) || die();

class Pricing_Table extends Base_Widget {
	/**
	 * @var Controls The controls.
	 */
	protected $controls;
	/**
	 * @var Render_Helper $render_helper
	 */
	protected $render_helper;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->controls      = new Controls( $this );
		$this->render_helper = new Render_Helper( $this );
		Icons_Manager::enqueue_shim();
	}

	public function get_name() {
		return 'raven-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-pricing-table';
	}

	protected function register_controls() {
		$this->controls->header_controls_section();
		$this->controls->pricing_controls_section();
		$this->controls->features_controls_section();
		$this->controls->footer_controls_section();
		$this->controls->ribbon_controls_section();
		$this->controls->header_style_controls_section();
		$this->controls->pricing_style_controls_section();
		$this->controls->features_style_controls_section();
		$this->controls->footer_style_controls_section();
		$this->controls->ribbon_style_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->render_attributes( $settings );
		$migration_allowed = Icons_Manager::is_migration_allowed();
		?>
		<div class="raven-pricing-table">
			<?php
			$this->render_helper->show_heading( $settings );
			$this->render_helper->show_price( $settings );
			$this->show_featured_list( $settings, $migration_allowed );
			$this->render_helper->show_footer( $settings );
			?>
		</div>
		<?php
		$this->render_helper->show_ribbon( $settings );
	}

	/**
	 * Render attributes.
	 *
	 * @param $settings
	 *
	 * @return void
	 */
	public function render_attributes( $settings ) {
		$this->add_render_attribute( 'button_text', 'class', [
			'raven-pricing-table__button',
			'raven-button',
			'raven-size-' . $settings['button_size'],
		] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}

		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute( 'heading', 'class', 'raven-pricing-table__heading' );
		$this->add_render_attribute( 'sub_heading', 'class', 'raven-pricing-table__subheading' );
		$this->add_render_attribute( 'period', 'class', [
			'raven-pricing-table__period',
			'raven-typo-excluded',
		] );
		$this->add_render_attribute( 'footer_additional_info', 'class', 'raven-pricing-table__additional_info' );
		$this->add_render_attribute( 'ribbon_title', 'class', 'raven-pricing-table__ribbon-inner' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );
		$this->add_inline_editing_attributes( 'footer_additional_info' );
		$this->add_inline_editing_attributes( 'button_text' );
		$this->add_inline_editing_attributes( 'ribbon_title' );
	}


	/**
	 * Show featured list in pricing table.
	 *
	 * @param $settings
	 * @param $migration_allowed
	 *
	 * @return void
	 */
	public function show_featured_list( $settings, $migration_allowed ) {
		if ( ! empty( $settings['features_list'] ) ) :
			?>
			<ul class="raven-pricing-table__features-list">
				<?php
				foreach ( $settings['features_list'] as $index => $item ) :
					$repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
					$this->add_inline_editing_attributes( $repeater_setting_key );
					$migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );

					if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
						$item['item_icon'] = 'fa fa-check-circle';
					}

					$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<div class="raven-pricing-table__feature-inner">
							<?php
							if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :
								if ( $is_new || $migrated ) :
									Icons_Manager::render_icon( $item['selected_item_icon'], [ 'aria-hidden' => 'true' ] );
								else : ?>
									<i class="<?php echo esc_attr( $item['item_icon'] ); ?>" aria-hidden="true"></i>
									<?php
								endif;
							endif;
							if ( ! empty( $item['item_text'] ) ) :
								?>
								<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>
									<?php $this->print_unescaped_setting( 'item_text', 'features_list', $index ); ?>
								</span>
								<?php
							else :
								echo '&nbsp;';
							endif;
							?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
	}
}
