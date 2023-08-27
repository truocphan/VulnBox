<?php
namespace JupiterX_Core\Raven\Modules\Social_Share\Widgets;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Social_Share\Module;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Plugin;

/**
 * Social share widget class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @since 2.5.9
 */
class Social_Share extends Base_Widget {

	public function get_name() {
		return 'raven-social-share';
	}

	public function get_title() {
		return esc_html__( 'Social Share', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-social-share';
	}

	protected function register_controls() {
		$this->register_content_tab();
		$this->register_style_tab();
	}

	public function register_content_tab() {
		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Share Buttons', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'network',
			[
				'label' => esc_html__( 'Network', 'jupiterx-core' ),
				'type' => 'select',
				'options' => Module::supported_social_media(),
				'default' => 'facebook',
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'Custom Label', 'jupiterx-core' ),
				'type'  => 'text',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'networks',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'network' => 'facebook',
					],
					[
						'network' => 'twitter',
					],
					[
						'network' => 'linkedin',
					],
				],
				'title_field' => '<i class="{{ elementor.config.share_buttons[ network ] }}" ></i> <span style="text-transform: capitalize;">{{{ network }}} </span>',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'jupiterx-core' ),
				'separator' => 'before',
				'type'  => 'select',
				'default' => 'icon-text',
				'options' => [
					'icon-text' => esc_html__( 'Icon & Text', 'jupiterx-core' ),
					'icon' => esc_html__( 'Icon', 'jupiterx-core' ),
					'text' => esc_html__( 'Text', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'show_label',
			[
				'label' => esc_html__( 'Label', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'skin',
			[
				'label' => esc_html__( 'Skin', 'jupiterx-core' ),
				'type'  => 'select',
				'default' => 'gradient',
				'options' => [
					'gradient' => esc_html__( 'Gradient', 'jupiterx-core' ),
					'minimal' => esc_html__( 'Minimal', 'jupiterx-core' ),
					'framed' => esc_html__( 'Framed', 'jupiterx-core' ),
					'boxed' => esc_html__( 'Boxed Icon', 'jupiterx-core' ),
					'flat' => esc_html__( 'Flat', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'shape',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type'  => 'select',
				'default' => '0px',
				'options' => [
					'0px' => esc_html__( 'Square', 'jupiterx-core' ),
					'0.5em' => esc_html__( 'Rounded', 'jupiterx-core' ),
					'99.9em;' => esc_html__( 'Circle', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-button' => 'border-radius: {{VALUE}};',
					'{{WRAPPER}} .jupiterx-social-share-button-icon' => 'border-radius: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', 'jupiterx-core' ),
				'type'  => 'select',
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Auto', 'jupiterx-core' ),
					'1' => esc_html__( '1', 'jupiterx-core' ),
					'2' => esc_html__( '2', 'jupiterx-core' ),
					'3' => esc_html__( '3', 'jupiterx-core' ),
					'4' => esc_html__( '4', 'jupiterx-core' ),
					'5' => esc_html__( '5', 'jupiterx-core' ),
					'6' => esc_html__( '6', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-wrapper' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Justify', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'columns' => '0',
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-wrapper' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'target',
			[
				'label' => esc_html__( 'Target URL', 'jupiterx-core' ),
				'separator' => 'before',
				'type'  => 'select',
				'default' => 'current',
				'options' => [
					'current' => esc_html__( 'Current Page', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'target_url',
			[
				'type'  => 'text',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'target' => 'custom',
				],
			]
		);

		$this->end_controls_section();
	}

	public function register_style_tab() {
		$this->start_controls_section(
			'styles',
			[
				'label' => esc_html__( 'Share Buttons', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Row Gap', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_size',
			[
				'label' => esc_html__( 'Button Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 4,
						'step' => 0.05,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-button' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => 0.5,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-button-icon > *' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jupiterx-social-share-button-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label' => esc_html__( 'Button Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
					'em' => [
						'min' => 1,
						'max' => 7,
						'step' => 0.5,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_size',
			[
				'label' => esc_html__( 'Border Size', 'jupiterx-core' ),
				'separator' => 'after',
				'type' => 'slider',
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'skin' => [ 'framed', 'boxed' ],
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type'  => 'select',
				'default' => 'official',
				'options' => [
					'official' => esc_html__( 'Official', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$this->start_controls_tabs(
			'style_color_tabs',
			[
				'separator' => 'after',
				'condition' => [
					'color' => 'custom',
				],
			]
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'normal_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div > .jupiterx-social-share-button-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div > .jupiterx-social-share-button-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-framed > div' => 'color: {{VALUE}} !important; border-color: {{VALUE}};',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div ' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div > .jupiterx-social-share-button-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div > .jupiterx-social-share-button-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div > .jupiterx-social-share-button-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed' => 'border: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'normal_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div > .jupiterx-social-share-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div > .jupiterx-social-share-button-icon > svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div > .jupiterx-social-share-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div > .jupiterx-social-share-button-icon > svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'skin!' => 'framed',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'hover_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div:hover > .jupiterx-social-share-button-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div:hover > .jupiterx-social-share-button-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-framed > div:hover' => 'color: {{VALUE}} !important; border-color: {{VALUE}};',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div:hover ' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div:hover > .jupiterx-social-share-button-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div:hover > .jupiterx-social-share-button-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed:hover' => 'border: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-gradient > div:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div:hover > .jupiterx-social-share-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-minimal > div:hover > .jupiterx-social-share-button-icon > svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div:hover > .jupiterx-social-share-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-boxed > div:hover > .jupiterx-social-share-button-icon > svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jupiterx-social-share-skin-flat > div:hover svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'skin!' => 'framed',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			'typography',
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .jupiterx-social-share-button-title',
				'exclude' => [ 'line_height' ],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$networks = Module::supported_social_media();
		$target   = get_permalink();

		if ( 'custom' === $settings['target'] && ! empty( $settings['target_url'] ) ) {
			$target = $settings['target_url'];
		}

		if ( empty( $settings['networks'] ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class' => [
					'jupiterx-social-share-wrapper',
					'jupiterx-social-share-wrapper-col-' . $settings['columns'],
					'jupiterx-social-share-view-' . $settings['view'],
				],
				'id'    => 'jupiterx-social-share-widget-wrapper',
			]
		);

		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> >
		<?php foreach ( $settings['networks'] as $network ) : ?>
			<?php
				$label   = $network['label'];
				$network = $network['network'];

				$this->add_render_attribute(
					'wrapper_' . $network,
					[
						'class'      => [ 'jupiterx-social-share-skin-' . $settings['skin'] ],
						'role'       => 'button',
						'aria-label' => 'hidden',
					]
				);

				$this->add_render_attribute(
					'button_' . $network,
					[
						'class'        => [ 'jupiterx-social-share-button', 'jupiterx-social-share-widget-' . $network ],
						'data-network' => esc_attr( $network ),
					]
				);
			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper_' . $network ); ?> >
				<div <?php echo $this->get_render_attribute_string( 'button_' . $network ); ?> >
					<?php if ( 'text' !== $settings['view'] ) : ?>
						<span class="jupiterx-social-share-button-icon">
							<?php self::render_share_icon( $network ); ?>
						</span>
					<?php endif; ?>

					<?php if ( 'icon' !== $settings['view'] && 'yes' === $settings['show_label'] ) : ?>
						<span class="jupiterx-social-share-button-title">
							<?php echo ( ! empty( $label ) ) ? wp_kses_post( $label ) : wp_kses_post( $networks[ $network ] ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
			<input type="hidden" id="jupiterx-social-share-target" value="<?php echo esc_attr( $target ); ?>" >
			<input type="hidden" id="jupiterx-social-share-title" value="<?php echo esc_attr( get_the_title( get_the_ID() ) ); ?>" >
			<input type="hidden" id="jupiterx-social-share-image" value="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID() ) ); ?>" >
		</div>

		<?php
	}

	private static $networks_class_dictionary = [
		'pocket' => [
			'value' => 'fa fa-get-pocket',
		],
		'email' => [
			'value' => 'fa fa-envelope',
		],
	];

	private static $networks_icon_mapping = [
		'pocket' => [
			'value' => 'fab fa-get-pocket',
			'library' => 'fa-brands',
		],
		'email' => [
			'value' => 'fas fa-envelope',
			'library' => 'fa-solid',
		],
		'print' => [
			'value' => 'fas fa-print',
			'library' => 'fa-solid',
		],
	];

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			];
		}
		return [];
	}

	public static function get_network_icon_data( $network_name ) {
		$prefix  = 'fa ';
		$library = '';

		if ( Icons_Manager::is_migration_allowed() ) {
			if ( isset( self::$networks_icon_mapping[ $network_name ] ) ) {
				return self::$networks_icon_mapping[ $network_name ];
			}
			$prefix  = 'fab ';
			$library = 'fa-brands';
		}

		if ( isset( self::$networks_class_dictionary[ $network_name ] ) ) {
			return self::$networks_class_dictionary[ $network_name ];
		}

		return [
			'value' => $prefix . 'fa-' . $network_name,
			'library' => $library,
		];
	}

	private static function render_share_icon( $network_name ) {
		$network_icon_data = self::get_network_icon_data( $network_name );
		$icon              = sprintf( '<i class="%s" aria-hidden="true"></i>', $network_icon_data['value'] );

		if ( Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
			$icon = Icons_Manager::render_font_icon( $network_icon_data );
		}

		Utils::print_unescaped_internal_string( $icon );
	}
}
