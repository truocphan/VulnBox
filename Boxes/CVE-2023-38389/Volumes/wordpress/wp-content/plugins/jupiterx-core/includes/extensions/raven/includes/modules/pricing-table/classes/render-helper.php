<?php

namespace JupiterX_Core\Raven\Modules\Pricing_Table\Classes;

use Elementor\Icons_Manager;
use Elementor\Utils;
use JupiterX_Core\Raven\Modules\Pricing_Table\Widgets\Pricing_Table;

defined( 'ABSPATH' ) || die();

class Render_Helper {
	/**
	 * @var Pricing_Table
	 */
	protected $widget;

	/**
	 * Constructor of control class get Price_table widget instance.
	 *
	 * @param $widget Pricing_Table
	 */
	public function __construct( $widget ) {
		$this->widget = $widget;
	}

	/**
	 * Show heading in pricing table.
	 *
	 * @param $settings
	 *
	 * @return void
	 */
	public function show_heading( $settings ) {
		if ( ! $settings['heading'] && ! $settings['sub_heading'] ) {
			return;
		}

		$heading_tag = Utils::validate_html_tag( $settings['heading_tag'] );
		ob_start();
		Utils::print_validated_html_tag( $heading_tag );
		$heading_tag = ob_get_clean();

		ob_start();
		$this->widget->print_render_attribute_string( 'heading' );
		$heading_attr = ob_get_clean();
		?>
		<div class="raven-pricing-table__header">
		<?php if ( ! empty( $settings['heading'] ) ) : ?>
			<?php echo '<' . $heading_tag . ' ' . $heading_attr . '>'; ?>
			<?php $this->widget->print_unescaped_setting( 'heading' ); ?>
			</<?php Utils::print_validated_html_tag( $heading_tag ); ?>>
		<?php endif; ?>
		<?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
			<span <?php $this->widget->print_render_attribute_string( 'sub_heading' ); ?>>
				<?php $this->widget->print_unescaped_setting( 'sub_heading' ); ?>
			</span>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Show price in pricing table.
	 *
	 * @param $settings
	 *
	 * @return void
	 */
	public function show_price( $settings ) {
		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$currency_symbol = empty( $settings['currency_symbol'] ) ? '$' : $settings['currency_symbol'];
		$price           = explode( $currency_format, $settings['price'] );
		$intpart         = $price[0] ?? '';
		$fraction        = 2 === count( $price ) ? $price[1] : '';
		$symbol          = 'custom' !== $currency_symbol ? $this->get_currency_symbol( $currency_symbol ) : $settings['currency_symbol_custom'];
		$period_position = $settings['period_position'];
		$price_period    = $settings['period'] ?? '';
		$period_element  = '<span ' . $this->widget->get_render_attribute_string( 'period' ) . '>' . $price_period . '</span>';
		?>
		<div class="raven-pricing-table__price">
			<?php
			$this->show_original_price( $settings, $symbol );
			$this->render_currency_symbol( $symbol, 'before' );
			$this->show_intpart( $intpart );
			$this->show_after_price( $fraction, $price_period, $period_position, $period_element );
			$this->render_currency_symbol( $symbol, 'after' );
			if ( $price_period && 'below' === $period_position ) :
				echo $period_element;
			endif;
			?>
		</div>
		<?php
	}

	/**
	 * Get currency symbol.
	 *
	 * @param $symbol_name
	 *
	 * @return string
	 */
	public function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar' => '&#36;',
			'euro' => '&#128;',
			'franc' => '&#8355;',
			'pound' => '&#163;',
			'ruble' => '&#8381;',
			'shekel' => '&#8362;',
			'baht' => '&#3647;',
			'yen' => '&#165;',
			'won' => '&#8361;',
			'guilder' => '&fnof;',
			'peso' => '&#8369;',
			'peseta' => '&#8359',
			'lira' => '&#8356;',
			'rupee' => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real' => 'R$',
			'krona' => 'kr',
		];

		return $symbols[ $symbol_name ] ?? '';
	}

	/**
	 * Show original price in pricing table.
	 *
	 * @param $settings
	 * @param $symbol
	 *
	 * @return void
	 */
	public function show_original_price( $settings, $symbol ) {
		if ( 'yes' !== $settings['sale'] && empty( $settings['original_price'] ) ) {
			return;
		}
		?>
		<div class="raven-pricing-table__original-price raven-typo-excluded">
			<?php
			$this->render_currency_symbol( $symbol, 'before' );
			$this->widget->print_unescaped_setting( 'original_price' );
			$this->render_currency_symbol( $symbol, 'after' );
			?>
		</div>
		<?php
	}

	/**
	 * Render currency symbol.
	 *
	 * @param $symbol
	 * @param $location
	 *
	 * @return void
	 */
	public function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->widget->get_settings( 'currency_position' );
		$location_setting  = ! empty( $currency_position ) ? $currency_position : 'before';

		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo '<span class="raven-pricing-table__currency">' . esc_html( $symbol ) . '</span>';
		}
	}

	/**
	 * Show Integer part  of price in pricing table.
	 *
	 * @param $intpart
	 *
	 * @return void
	 */
	public function show_intpart( $intpart ) {
		if ( $intpart || 0 <= $intpart ) :
			?>
			<span class="raven-pricing-table__integer-part">
				<?php echo esc_html( $intpart ); ?>
			</span>
			<?php
		endif;
	}

	/**
	 * Show after price in pricing table.
	 *
	 * @param $fraction
	 * @param $price_period
	 * @param $period_position
	 * @param $period_element
	 *
	 * @return void
	 */
	public function show_after_price( $fraction, $price_period, $period_position, $period_element ) {
		if ( '' !== $fraction || ( $price_period && 'beside' === $period_position ) ) :
			?>
			<div class="raven-pricing-table__after-price">
				<span class="raven-pricing-table__fractional-part">
					<?php echo $fraction; ?>
				</span>
				<?php if ( $price_period && 'beside' === $period_position ) : ?>
					<?php echo $period_element; ?>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}

	/**
	 * Show footer in pricing table.
	 *
	 * @param $settings
	 *
	 * @return void
	 */
	public function show_footer( $settings ) {
		if ( empty( $settings['button_text'] ) && empty( $settings['footer_additional_info'] ) ) {
			return;
		}
		?>
		<div class="raven-pricing-table__footer">
			<?php if ( ! empty( $settings['button_text'] ) ) : ?>
				<a <?php $this->widget->print_render_attribute_string( 'button_text' ); ?>>
					<?php $this->widget->print_unescaped_setting( 'button_text' ); ?>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $settings['footer_additional_info'] ) ) : ?>
				<div <?php $this->widget->print_render_attribute_string( 'footer_additional_info' ); ?>>
					<?php $this->widget->print_unescaped_setting( 'footer_additional_info' ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Show Ribbon if enabled
	 *
	 * @param $settings
	 *
	 * @return void
	 */
	public function show_ribbon( $settings ) {
		if ( 'yes' !== $settings['show_ribbon'] && empty( $settings['ribbon_title'] ) ) {
			return;
		}

		$this->widget->add_render_attribute( 'ribbon-wrapper', 'class', 'raven-pricing-table__ribbon' );
		if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
			$this->widget->add_render_attribute( 'ribbon-wrapper', 'class',
			'raven-ribbon-' . $settings['ribbon_horizontal_position'] );
		endif;
		?>
		<div <?php $this->widget->print_render_attribute_string( 'ribbon-wrapper' ); ?>>
			<div <?php $this->widget->print_render_attribute_string( 'ribbon_title' ); ?>>
				<?php $this->widget->print_unescaped_setting( 'ribbon_title' ); ?>
			</div>
		</div>
		<?php
	}
}
