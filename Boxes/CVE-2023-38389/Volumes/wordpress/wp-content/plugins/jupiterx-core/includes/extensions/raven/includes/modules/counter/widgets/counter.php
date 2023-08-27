<?php
namespace JupiterX_Core\Raven\Modules\Counter\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

class Counter extends Base_Widget {

	public function get_name() {
		return 'raven-counter';
	}

	public function get_title() {
		return __( 'Counter', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-counter';
	}

	public function get_script_depends() {
		return [ 'jquery-numerator' ];
	}

	protected function register_controls() {
		$this->register_section_content();
		$this->register_section_settings();
		$this->register_section_container();
		$this->register_section_icon();
		$this->register_section_number();
		$this->register_section_title();
	}

	private function register_section_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_new',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'starting_number',
			[
				'label' => __( 'Starting Number', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 0,
			]
		);

		$repeater->add_control(
			'ending_number',
			[
				'label' => __( 'Ending Number', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 1000,
			]
		);

		$repeater->add_control(
			'prefix',
			[
				'label' => __( 'Number Prefix', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => 1,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', 'jupiterx-core' ),
				'type' => 'text',
				'placeholder' => __( 'Plus', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => true,
				'default' => __( 'Cool Number', 'jupiterx-core' ),
				'placeholder' => __( 'Cool Number', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'list',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Item #1', 'jupiterx-core' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'jupiterx-core' ),
					],
					[
						'list_title' => __( 'Item #2', 'jupiterx-core' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'jupiterx-core' ),
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 2,
				'options' => [
					1 => __( '1', 'jupiterx-core' ),
					2 => __( '2', 'jupiterx-core' ),
					3 => __( '3', 'jupiterx-core' ),
					4 => __( '4', 'jupiterx-core' ),
					5 => __( '5', 'jupiterx-core' ),
					6 => __( '6', 'jupiterx-core' ),
				],
				'selectors_dictionary' => [
					1 => 100,
					2 => 50,
					3 => 33.333,
					4 => 25,
					5 => 20,
					6 => 16.666,
				],
				'selectors' => [
					'{{WRAPPER}} .raven-counter-item' => 'width: calc({{VALUE}}% - {{spacing_between.size}}{{spacing_between.unit}} / 2)',
					'{{WRAPPER}} .raven-counter-multi-rows .raven-counter-item' => 'margin-bottom: {{spacing_between.size}}{{spacing_between.unit}}',
				],
			]
		);

		$this->add_control(
			'thousand_separator',
			[
				'label' => __( 'Thousand Separator', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'label_on' => __( 'Show', 'jupiterx-core' ),
				'label_off' => __( 'Hide', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', 'jupiterx-core' ),
				'type' => 'number',
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
		);

		$this->end_controls_section();
	}

	private function register_section_container() {
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => __( 'Container', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'spacing_between',
			[
				'label' => __( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .raven-counter-list' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_icon() {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'icon_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-counter-icon, {{WRAPPER}} .raven-counter-icon i, {{WRAPPER}} .raven-counter-icon svg',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-counter-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-counter-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-counter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_number() {
		$this->start_controls_section(
			'section_style_number',
			[
				'label' => __( 'Number', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'number_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-counter-number-wrapper > span',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'number_typography',
				'selector' => '{{WRAPPER}} .raven-counter-number-wrapper > span',
				'scheme' => '1',
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => __( 'Padding', 'jupiterx-core' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .raven-counter-number-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_section_title() {
		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'raven-text-background',
			[
				'name' => 'title_color',
				'fields_options' => [
					'background' => [
						'label' => __( 'Color Type', 'jupiterx-core' ),
					],
				],
				'selector' => '{{WRAPPER}} .raven-counter-title',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .raven-counter-title',
				'scheme' => '2',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$list     = $settings['list'];

		if ( empty( $list ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			'class',
			'raven-counter-list raven-flex raven-flex-wrap raven-flex-between raven-flex-middle'
		);

		if ( count( $list ) > $settings['columns'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'raven-counter-multi-rows' );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
			foreach ( $list as $index => $item ) :
				$item_count = $index + 1;
				$counter    = 'counter-' . $item_count;

				$migrated = isset( $item['__fa4_migrated']['icon_new'] );
				$is_new   = empty( $item['icon'] ) && $migration_allowed;

				$this->add_render_attribute( $counter, [
					'class' => 'raven-counter-number',
					'data-raven-counter' => '',
					'data-duration' => $settings['duration'],
					'data-to-value' => $item['ending_number'],
				] );

				if ( ! empty( $settings['thousand_separator'] ) ) {
					$this->add_render_attribute( $counter, 'data-delimiter', ',' );
				}
				?>
				<div class="raven-counter-item">
					<div class="raven-counter-icon">
					<?php
					if ( $is_new || $migrated ) {
						Elementor::$instance->icons_manager->render_icon( $item['icon_new'] );
					} else {
						?>
						<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
					<?php } ?>

					</div>
					<div class="raven-counter-number-wrapper">
						<span class="raven-counter-number-prefix"><?php $this->print_unescaped_setting( 'prefix', 'list', $index ); ?></span>
						<span <?php echo $this->get_render_attribute_string( $counter ); ?>><?php $this->print_unescaped_setting( 'starting_number', 'list', $index ); ?></span>
						<span class="raven-counter-number-suffix"><?php $this->print_unescaped_setting( 'suffix', 'list', $index ); ?></span>
					</div>
					<?php if ( $item['title'] ) : ?>
						<div class="raven-counter-title"><?php $this->print_unescaped_setting( 'title', 'list', $index ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		var list = settings.list,
			iconsHTML = {};

		if ( list.length < 1 ) {
			return;
		}

		view.addRenderAttribute(
			'wrapper',
			'class',
			'raven-counter-list raven-flex raven-flex-wrap raven-flex-between raven-flex-middle'
		);

		if ( list.length > settings.columns ) {
			view.addRenderAttribute( 'wrapper', 'class', 'raven-counter-multi-rows' );
		}
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<#
			_.each( list, function( item, index ) {
				var itemCount = index + 1,
					counter = 'counter-' + itemCount,
					migrated = elementor.helpers.isIconMigrated( item, 'icon_new' );

				view.addRenderAttribute( counter, {
					'class': 'raven-counter-number',
					'data-raven-counter': '',
					'data-duration': settings.duration,
					'data-to-value': item.ending_number,
				} );

				if ( settings.thousand_separator.length > 0 ) {
					view.addRenderAttribute( counter, 'data-delimiter', ',' );
				}
			#>
			<div class="raven-counter-item">
				<# if ( item.icon || item.icon_new ) { #>
					<div class="raven-counter-icon">
						<#
							iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.icon_new, {}, 'i', 'object' );
							if ( ( ! item.icon || migrated ) && iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
								{{{ iconsHTML[ index ].value }}}
							<# } else { #>
								<i class="{{{ item.icon }}}" aria-hidden="true"></i>
							<# }
						#>
					</div>
				<# } #>
				<div class="raven-counter-number-wrapper">
					<span class="raven-counter-number-prefix">{{{ item.prefix }}}</span>
					<span {{{ view.getRenderAttributeString( counter ) }}}>{{{ item.starting_number }}}</span>
					<span class="raven-counter-number-suffix">{{{ item.suffix }}}</span>
				</div>
				<# if ( item.title ) { #>
					<div class="raven-counter-title">{{{ item.title }}}</div>
				<# } #>
			</div>
			<# }) #>
		</div>
		<?php
	}
}
