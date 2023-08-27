<?php
namespace JupiterX_Core\Raven\Modules\Site_Logo\Widgets;

use Elementor\Utils;
use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Core\Responsive\Responsive;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Site_Logo extends Base_Widget {

	public function get_name() {
		return 'raven-site-logo';
	}

	public function get_title() {
		return __( 'Site Logo', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-site-logo';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Logo', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'logo_source',
			[
				'label' => __( 'Logo Source', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'customizer' => __( 'Customizer', 'jupiterx-core' ),
					'custom-source' => __( 'Custom Logo', 'jupiterx-core' ),
				],
				'default' => 'customizer',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'important_note',
			[
				'type' => 'raw_html',
				'raw' => sprintf(
					/* translators: %1$s: Choose logo name | %2$s: Link to Customizer page */
					__( 'Please select or upload your <strong>Logo</strong> in the <a target="_blank" href="%1$s"><em>Customizer</em></a>.', 'jupiterx-core' ),
					add_query_arg( [ 'autofocus[section]' => 'jupiterx_logo' ],
					admin_url( 'customize.php' ) )
				),
				'content_classes' => 'elementor-control-field-description',
				'condition' => [
					'logo_source' => 'customizer',
				],
			]
		);

		$this->add_responsive_control(
			'logo',
			[
				'label' => __( 'Choose Logo', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'primary'   => __( 'Primary', 'jupiterx-core' ),
					'secondary' => __( 'Secondary', 'jupiterx-core' ),
					'sticky'    => __( 'Sticky', 'jupiterx-core' ),
					'mobile'    => __( 'Mobile', 'jupiterx-core' ),
				],
				'default' => 'primary',
				'tablet_default' => 'primary',
				'mobile_default' => 'primary',
				'condition' => [
					'logo_source' => 'customizer',
				],
			]
		);

		$this->add_responsive_control(
			'image',
			[
				'label' => __( 'Choose Image', 'jupiterx-core' ),
				'type' => 'media',
				'condition' => [
					'logo_source' => 'custom-source',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_logo',
			[
				'label' => __( 'Logo', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo img, {{WRAPPER}} .raven-site-logo svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_width',
			[
				'label' => __( 'Max Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo img, {{WRAPPER}} .raven-site-logo svg' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'  => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => is_rtl() ? 'right' : 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-site-logo' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Temporary suppressed.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$link = $settings['link'];

		$wrapper_class = 'raven-site-logo';

		if ( 'customizer' === $settings['logo_source'] ) {
			$wrapper_class .= ' raven-site-logo-customizer';
		}

		$this->add_render_attribute(
			'logo-wrapper',
			'class',
			$wrapper_class
		);

		if ( ! isset( $link['url'] ) || empty( $link['url'] ) ) {
			$link['url'] = get_bloginfo( 'url' );
		}

		if ( ! empty( $link['url'] ) ) {
			$this->add_render_attribute( 'link', 'class', 'raven-site-logo-link' );

			$this->add_render_attribute( 'link', 'href', esc_url( $link['url'] ) );

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}
		// Custom Logo Source
		$source_control = $settings['logo_source'];
		?>
		<div class="raven-widget-wrapper">
			<div <?php echo $this->get_render_attribute_string( 'logo-wrapper' ); ?>>
				<?php if ( ! empty( $link['url'] ) ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
				<?php endif; ?>
				<?php

					if ( 'custom-source' === $source_control ) {
						$this->custom_logo_render();
					}

					if ( 'customizer' === $source_control ) {
						$this->customizer_logo_render();
					}

				?>
				<?php if ( ! empty( $link['url'] ) ) : ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function custom_logo_render() {
		if ( Elementor::$instance->experiments->is_feature_active( 'additional_custom_breakpoints' ) ) {
			return $this->custom_logo_custom_breakpoints_render();
		}

		$settings            = $this->get_settings_for_display();
		$custom_logo_default = ! empty( $settings['image']['url'] ) ? $settings['image']['url'] : '';
		$custom_logo_mobile  = ! empty( $settings['image_mobile']['url'] ) ? $settings['image_mobile']['url'] : '';
		$custom_logo_tablet  = ! empty( $settings['image_tablet']['url'] ) ? $settings['image_tablet']['url'] : '';
		$breakpoints         = Responsive::get_breakpoints();
		$picture_content     = '';

		$devices = [
			'desktop' => '',
			'tablet'  => '_tablet',
			'mobile'  => '_mobile',
		];

		foreach ( $devices as $device => $device_setting_key ) {
			$this->add_render_attribute( 'custom-logo', 'class', 'raven-site-logo-' . $device );
		}

		$this->add_render_attribute( 'custom-logo', [
			'alt'   => get_bloginfo( 'title' ),
			'data-no-lazy' => 1,
		] );

		if ( empty( $custom_logo_default ) ) {
			$custom_logo_default = Utils::get_placeholder_image_src();
		}

		$picture_content .= '<picture>';

		if ( ! empty( $custom_logo_mobile ) ) {
			$picture_content .= "<source media='(max-width:{$breakpoints['md']}px)' srcset=' $custom_logo_mobile '>";
		}

		if ( ! empty( $custom_logo_tablet ) ) {
			$picture_content .= "<source media='(max-width:{$breakpoints['lg']}px)' srcset=' $custom_logo_tablet '>";
		}

		$picture_content .= "<img {$this->get_render_attribute_string( 'custom-logo' )} src=' $custom_logo_default '>";
		$picture_content .= '</picture>';

		Utils::print_unescaped_internal_string( $picture_content );
	}

	protected function custom_logo_custom_breakpoints_render() {
		$settings            = $this->get_settings_for_display();
		$custom_logo_default = ! empty( $settings['image']['url'] ) ? $settings['image']['url'] : '';
		$picture_content     = '';

		$this->add_render_attribute( 'custom-logo', [
			'alt' => get_bloginfo( 'title' ),
			'data-no-lazy' => 1,
		] );

		if ( empty( $custom_logo_default ) ) {
			$custom_logo_default = Utils::get_placeholder_image_src();
		}

		$picture_content .= '<picture>';

		foreach ( Elementor::$instance->breakpoints->get_active_breakpoints() as $breakpoint ) {
			$breakpoint_name = $breakpoint->get_name();

			if ( empty( $settings[ "image_{$breakpoint_name}" ]['url'] ) ) {
				continue;
			}

			$image_url = $settings[ "image_{$breakpoint_name}" ]['url'];

			$picture_content .= "<source media='({$breakpoint->get_direction()}-width:{$breakpoint->get_value()}px)' srcset=' $image_url '>";
		}

		$picture_content .= "<img {$this->get_render_attribute_string( 'custom-logo' )} src=' $custom_logo_default '>";
		$picture_content .= '</picture>';

		Utils::print_unescaped_internal_string( $picture_content );
	}

	protected function customizer_logo_render() {
		if ( Elementor::$instance->experiments->is_feature_active( 'additional_custom_breakpoints' ) ) {
			return $this->customizer_logo_custom_breakpoints_render();
		}

		$settings = $this->get_settings_for_display();

		$devices = [
			'desktop' => '',
			'tablet'  => '_tablet',
			'mobile'  => '_mobile',
		];

		$logos = [];

		foreach ( $devices as $device => $device_setting_key ) {
			$device_setting = $settings[ 'logo' . $device_setting_key ];

			$logo = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}" : 'jupiterx_logo';

			if ( in_array( $logo, $logos, true ) ) {
				$this->add_render_attribute( $logos[ $logo ], 'class', 'raven-site-logo-' . $device );
				continue;
			}

			$logos[ $logo ] = $logo;

			$image_src = get_theme_mod( $logo, '' );

			if ( empty( $image_src ) ) {
				$image_src = Utils::get_placeholder_image_src();
			}

			$retina_logo = 'primary' !== $device_setting ? "jupiterx_logo_{$device_setting}_retina" : 'jupiterx_logo_retina';

			$retina_image_src = get_theme_mod( $retina_logo, '' );

			if ( ! empty( $retina_image_src ) ) {
				$this->add_render_attribute( $logo, 'srcset', "{$image_src} 1x, {$retina_image_src} 2x" );
			}

			$this->add_render_attribute( $logo, [
				'src'   => esc_url( $image_src ),
				'alt'   => get_bloginfo( 'title' ),
				'class' => 'raven-site-logo-' . $device,
				'data-no-lazy' => 1,
			] );
		}

		foreach ( $logos as $device_logo ) :
			echo '<img ' . $this->get_render_attribute_string( $device_logo ) . ' />';
		endforeach;

	}

	protected function customizer_logo_custom_breakpoints_render() {
		$settings          = $this->get_settings_for_display();
		$default_logo_type = ! empty( $settings['logo'] ) ? str_replace( '_primary', '', '_' . $settings['logo'] ) : '';
		$placeholder_url   = Utils::get_placeholder_image_src();
		$default_logo_url  = get_theme_mod( "jupiterx_logo{$default_logo_type}", $placeholder_url );
		$picture_content   = '<picture>';

		foreach ( Elementor::$instance->breakpoints->get_active_breakpoints() as $breakpoint ) {
			$breakpoint_name = $breakpoint->get_name();

			if ( empty( $settings[ "logo_{$breakpoint_name}" ] ) ) {
				continue;
			}

			$logo_type       = str_replace( '_primary', '', '_' . $settings[ "logo_{$breakpoint_name}" ] );
			$logo_url        = get_theme_mod( "jupiterx_logo{$logo_type}" );
			$logo_retina_url = get_theme_mod( "jupiterx_logo{$logo_type}_retina" );

			if ( 'primary' === $settings[ "logo_{$breakpoint_name}" ] && empty( $logo_type ) ) {
				$logo_type       = '_primary';
				$logo_url        = get_theme_mod( 'jupiterx_logo' );
				$logo_retina_url = get_theme_mod( 'jupiterx_logo_retina' );
			}

			if ( ! empty( $logo_retina_url ) ) {
				$logo_url = "{$logo_url} 1x, {$logo_retina_url} 2x";
			}

			$picture_content .= "<source media='({$breakpoint->get_direction()}-width:{$breakpoint->get_value()}px)' srcset='{$logo_url}'>";
		}

		if (
			( ! empty( $settings['logo'] ) && 'primary' === $settings['logo'] ) &&
			empty( $default_logo_type )
		) {
			$default_logo_url = get_theme_mod( 'jupiterx_logo', $placeholder_url );
			$logo_retina_url  = get_theme_mod( 'jupiterx_logo_retina' );

			if ( ! empty( $logo_retina_url ) ) {
				$this->add_render_attribute( 'customizer-default-logo', 'srcset', "{$default_logo_url} 1x, {$logo_retina_url} 2x" );
			}
		}

		$this->add_render_attribute( 'customizer-default-logo', [
			'src' => esc_url( $default_logo_url ),
			'alt' => get_bloginfo( 'title' ),
			'data-no-lazy' => 1,
		] );

		$picture_content .= '<img ' . $this->get_render_attribute_string( 'customizer-default-logo' ) . ' />';
		$picture_content .= '</picture>';

		Utils::print_unescaped_internal_string( $picture_content );
	}
}
