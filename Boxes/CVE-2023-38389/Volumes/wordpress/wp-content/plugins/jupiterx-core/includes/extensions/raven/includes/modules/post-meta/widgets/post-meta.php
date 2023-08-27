<?php
namespace JupiterX_Core\Raven\Modules\Post_Meta\Widgets;

use JupiterX_Core\Raven\Base\Base_Widget;
use JupiterX_Core\Raven\Modules\Post_Meta\Module as baseModule;
use Elementor\Repeater;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Temporary supressed.
 *
 * @SuppressWarnings(ExcessiveClassLength)
 * @SuppressWarnings(ExcessiveClassComplexity)
 */
class Post_Meta extends Base_Widget {

	public function get_name() {
		return 'raven-post-meta';
	}

	public function get_title() {
		return esc_html__( 'Post Meta', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-post-meta';
	}

	public function get_keywords() {
		return [ 'post', 'info', 'meta', 'date', 'time', 'author', 'taxonomy', 'comments', 'terms', 'avatar', 'reading' ];
	}

	protected function register_controls() {
		$this->register_settings_section();
		$this->register_list_section();
		$this->register_icon_section();
		$this->register_text_section();
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	protected function register_settings_section() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'type' => 'choose',
				'default' => 'inline',
				'options' => [
					'traditional' => [
						'title' => esc_html__( 'Default', 'jupiterx-core' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => esc_html__( 'Inline', 'jupiterx-core' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'jupiterx-core' ),
				'type' => 'select',
				'default' => 'date',
				'options' => [
					'author' => esc_html__( 'Author', 'jupiterx-core' ),
					'date' => esc_html__( 'Date', 'jupiterx-core' ),
					'time' => esc_html__( 'Time', 'jupiterx-core' ),
					'comments' => esc_html__( 'Comments', 'jupiterx-core' ),
					'terms' => esc_html__( 'Terms', 'jupiterx-core' ),
					'reading time' => esc_html__( 'Reading time', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
			]
		);

		$repeater->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Format', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => esc_html_x( 'July 12, 2019 (F j, Y)', 'Date Format', 'jupiterx-core' ),
					'1' => '2019-07-12 (Y-m-d)',
					'2' => '07/12/2019 (m/d/Y)',
					'3' => '12/07/2019 (d/m/Y)',
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'custom_date_format',
			[
				'label' => esc_html__( 'Custom Date Format', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'F j, Y',
				'label_block' => false,
				'condition' => [
					'type' => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					esc_html__( 'Use the letters: %s', 'jupiterx-core' ),
					'l D d j S F m M n Y y'
				),
			]
		);

		$repeater->add_control(
			'time_format',
			[
				'label' => esc_html__( 'Time Format', 'jupiterx-core' ),
				'type' => 'select',
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => '10:29 pm (g:i a)',
					'1' => '10:29 PM (g:i A)',
					'2' => '22:29 (H:i)',
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'condition' => [
					'type' => 'time',
				],
			]
		);
		$repeater->add_control(
			'custom_time_format',
			[
				'label' => esc_html__( 'Custom Time Format', 'jupiterx-core' ),
				'type' => 'text',
				'default' => 'g:i a',
				'placeholder' => 'g:i a',
				'label_block' => false,
				'condition' => [
					'type' => 'time',
					'time_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed time letters (see: http://php.net/manual/en/function.time.php). */
					esc_html__( 'Use the letters: %s', 'jupiterx-core' ),
					'g G H i a A'
				),
			]
		);

		$repeater->add_control(
			'taxonomy',
			[
				'label' => esc_html__( 'Taxonomy', 'jupiterx-core' ),
				'type' => 'select2',
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'type' => 'terms',
				],
			]
		);

		$repeater->add_control(
			'text_prefix',
			[
				'label' => esc_html__( 'Before', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => false,
				'condition' => [
					'type!' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Avatar', 'jupiterx-core' ),
				'type' => 'switcher',
				'condition' => [
					'type' => 'author',
				],
			]
		);

		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .raven-icon-list-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'comments_custom_strings',
			[
				'label' => esc_html__( 'Custom Format', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => false,
				'condition' => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_no_comments',
			[
				'label' => esc_html__( 'No Comments', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => false,
				'placeholder' => esc_html__( 'No Comments', 'jupiterx-core' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_one_comment',
			[
				'label' => esc_html__( 'One Comment', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => false,
				'placeholder' => esc_html__( 'One Comment', 'jupiterx-core' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_comments',
			[
				'label' => esc_html__( 'Comments', 'jupiterx-core' ),
				'type' => 'text',
				'label_block' => false,
				/* translators: %s: No. of comments. */
				'placeholder' => esc_html__( '%s Comments', 'jupiterx-core' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'custom_text',
			[
				'label' => esc_html__( 'Custom', 'jupiterx-core' ),
				'type' => 'text',
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
				'condition' => [
					'type!' => [ 'time', 'reading time' ],
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => esc_html__( 'Custom URL', 'jupiterx-core' ),
				'type' => 'url',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'default' => esc_html__( 'Default', 'jupiterx-core' ),
					'custom' => esc_html__( 'Custom', 'jupiterx-core' ),
				],
				'default' => 'default',
				'condition' => [
					'show_avatar!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'icon_new',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => 'icons',
				'fa4compatibility' => 'icon',
				'label_block' => true,
				'condition' => [
					'show_icon' => 'custom',
					'show_avatar!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type' => 'author',
						'icon_new' => [
							'value' => 'far fa-user-circle',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'date',
						'icon_new' => [
							'value' => 'fas fa-calendar-alt',
							'library' => 'fa-solid',
						],
					],
					[
						'type' => 'time',
						'icon_new' => [
							'value' => 'far fa-clock',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'comments',
						'icon_new' => [
							'value' => 'far fa-comment-dots',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'reading time',
						'icon_new' => [
							'value' => 'fas fa-stopwatch',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, fallbackControl = ( "undefined" === typeof icon ) ? false : icon; #> {{{ (fallbackControl && !migrated) ? "<i class=\"" + fallbackControl + "\"></i>" : elementor.helpers.renderIcon(null, icon_new, {}, "i", "panel") }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
			]
		);

		$this->end_controls_section();
	}

	protected function register_list_section() {
		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => esc_html__( 'List', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => esc_html__( 'Space Between', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body.rtl {{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => 'choose',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => esc_html__( 'Divider', 'jupiterx-core' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Off', 'jupiterx-core' ),
				'label_on' => esc_html__( 'On', 'jupiterx-core' ),
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'content: ""',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => esc_html__( 'Style', 'jupiterx-core' ),
				'type' => 'select',
				'options' => [
					'solid' => esc_html__( 'Solid', 'jupiterx-core' ),
					'double' => esc_html__( 'Double', 'jupiterx-core' ),
					'dotted' => esc_html__( 'Dotted', 'jupiterx-core' ),
					'dashed' => esc_html__( 'Dashed', 'jupiterx-core' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .raven-icon-list-items.raven-inline-items .raven-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => esc_html__( 'Weight', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-items:not(.raven-inline-items) .raven-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .raven-inline-items .raven-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => esc_html__( 'Height', 'jupiterx-core' ),
				'type' => 'slider',
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '#ddd',
				'scheme' => [
					'type' => 'color',
					'value' => '3',
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_icon_section() {
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .raven-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'scheme' => [
					'type' => 'color',
					'value' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => 'slider',
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-icon-list-icon svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_text_section() {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'jupiterx-core' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label' => esc_html__( 'Indent', 'jupiterx-core' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .raven-icon-list-text' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .raven-icon-list-text' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'jupiterx-core' ),
				'type' => 'color',
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .raven-icon-list-text, {{WRAPPER}} .raven-icon-list-text a' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => 'color',
					'value' => '2',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .raven-icon-list-item a , {{WRAPPER}} .raven-icon-list-item',
				'scheme' => '3',
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$options = [
			'' => esc_html__( 'Choose', 'jupiterx-core' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_author_meta_data( $repeater_item ) {
		$item_data = [];
		$author_id = get_post_field( 'post_author' );

		$item_data['text']     = get_the_author_meta( 'display_name', $author_id );
		$item_data['icon_new'] = [
			'value' => 'fas fa-user-circle',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'author';

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ),
			];
		}

		if ( 'yes' === $repeater_item['show_avatar'] ) {
			$item_data['image'] = get_avatar_url( get_the_author_meta( 'ID', $author_id ), 96 );
		}

		return $item_data;
	}

	protected function get_date_meta_data( $repeater_item ) {
		$item_data = [];

		$custom_date_format = empty( $repeater_item['custom_date_format'] ) ? 'F j, Y' : $repeater_item['custom_date_format'];

		$format_options = [
			'default' => 'F j, Y',
			'0'       => 'F j, Y',
			'1'       => 'Y-m-d',
			'2'       => 'm/d/Y',
			'3'       => 'd/m/Y',
			'custom'  => $custom_date_format,
		];

		$item_data['text']     = get_the_time( $format_options[ $repeater_item['date_format'] ] );
		$item_data['icon_new'] = [
			'value' => 'fas fa-calendar',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'datePublished';

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
			];
		}

		return $item_data;
	}

	protected function get_time_meta_data( $repeater_item ) {
		$item_data = [];

		$custom_time_format = 'g:i a';

		if ( ! empty( $repeater_item['custom_time_format'] ) ) {
			$custom_time_format = $repeater_item['custom_time_format'];
		}

		$format_options = [
			'default' => 'g:i a',
			'0' => 'g:i a',
			'1' => 'g:i A',
			'2' => 'H:i',
			'custom' => $custom_time_format,
		];

		$item_data['text']     = get_the_time( $format_options[ $repeater_item['time_format'] ] );
		$item_data['icon_new'] = [
			'value' => 'fas fa-clock',
			'library' => 'fa-solid',
		];

		return $item_data;
	}

	/**
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function get_comments_meta_data( $repeater_item ) {
		$item_data = [];
		$post_obj  = get_queried_object();

		if (
			function_exists( 'jupiterx_post_element_enabled' ) &&
			is_object( $post_obj ) &&
			! is_null( $post_obj->post_type ) &&
			! jupiterx_post_element_enabled( 'comments' ) &&
			'elementor_library' !== $post_obj->post_type
		) {
			return $item_data;
		}

		if ( ! ( comments_open() || get_comments_number() ) ) {
			return $item_data;
		}

		$default_strings = [
			'string_no_comments' => esc_html__( 'No Comments', 'jupiterx-core' ),
			'string_one_comment' => esc_html__( 'One Comment', 'jupiterx-core' ),
			/* translators: %s: Comment count. */
			'string_comments'    => esc_html__( '%s Comments', 'jupiterx-core' ),
		];

		if ( 'yes' === $repeater_item['comments_custom_strings'] ) {
			if ( ! empty( $repeater_item['string_no_comments'] ) ) {
				$default_strings['string_no_comments'] = $repeater_item['string_no_comments'];
			}

			if ( ! empty( $repeater_item['string_one_comment'] ) ) {
				$default_strings['string_one_comment'] = $repeater_item['string_one_comment'];
			}

			if ( ! empty( $repeater_item['string_comments'] ) ) {
				$default_strings['string_comments'] = $repeater_item['string_comments'];
			}
		}

		$num_comments = intval( get_comments_number() );

		if ( 0 === $num_comments ) {
			$item_data['text'] = $default_strings['string_no_comments'];
		} else {
			$item_data['text'] = sprintf( _n( $default_strings['string_one_comment'], $default_strings['string_comments'], $num_comments, 'jupiterx-core' ), $num_comments ); // phpcs:ignore WordPress.WP.I18n
		}

		if ( 'yes' === $repeater_item['link'] ) {
			$item_data['url'] = [
				'url' => get_comments_link(),
			];
		}

		$item_data['icon_new'] = [
			'value' => 'fas fa-comment-dots',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'commentCount';

		return $item_data;
	}

	protected function get_terms_meta_data( $repeater_item ) {
		$item_data = [];

		$item_data['icon_new'] = [
			'value' => 'fas fa-tags',
			'library' => 'fa-solid',
		];
		$item_data['itemprop'] = 'about';

		$taxonomy = $repeater_item['taxonomy'];
		$terms    = wp_get_post_terms( get_the_ID(), $taxonomy );
		foreach ( $terms as $term ) {
			$item_data['terms_list'][ $term->term_id ]['text'] = $term->name;
			if ( 'yes' === $repeater_item['link'] ) {
				$item_data['terms_list'][ $term->term_id ]['url'] = get_term_link( $term );
			}
		}

		return $item_data;
	}

	protected function get_reading_time_meta_data() {
		$item_data             = [];
		$item_data['icon_new'] = [
			'value' => 'fas fa-stopwatch',
			'library' => 'fa-solid',
		];
		$item_data['text']     = get_post_meta( get_the_ID(), 'jupiterx_reading_time', true );

		if ( empty( $item_data['text'] ) ) {
			$content           = get_the_content();
			$item_data['text'] = baseModule::get_instance()->get_read_time( $content );

			update_post_meta( get_the_ID(), 'jupiterx_reading_time', $item_data['text'] );
		}

		return $item_data;
	}

	protected function get_custom_meta_data( $repeater_item ) {
		$item_data = [];

		$item_data['text']     = $repeater_item['custom_text'];
		$item_data['icon_new'] = [
			'value' => 'fas fa-info-circle',
			'library' => 'fa-solid',
		];

		if ( 'yes' === $repeater_item['link'] && ! empty( $repeater_item['custom_url'] ) ) {
			$item_data['url'] = $repeater_item['custom_url'];
		}

		return $item_data;
	}

	protected function get_meta_data( $repeater_item ) {
		$item_data = [];

		switch ( $repeater_item['type'] ) {
			case 'author':
				$item_data = $this->get_author_meta_data( $repeater_item );
				break;

			case 'date':
				$item_data = $this->get_date_meta_data( $repeater_item );
				break;

			case 'time':
				$item_data = $this->get_time_meta_data( $repeater_item );
				break;

			case 'comments':
				$item_data = $this->get_comments_meta_data( $repeater_item );
				break;

			case 'terms':
				$item_data = $this->get_terms_meta_data( $repeater_item );
				break;

			case 'reading time':
				$item_data = $this->get_reading_time_meta_data();
				break;

			case 'custom':
				$item_data = $this->get_custom_meta_data( $repeater_item );
				break;
		}

		$item_data['type'] = $repeater_item['type'];

		if ( ! empty( $repeater_item['text_prefix'] ) ) {
			$item_data['text_prefix'] = esc_html( $repeater_item['text_prefix'] );
		}

		return $item_data;
	}

	/**
	 * Temporary supressed.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function render_item( $repeater_item ) {
		$item_data      = $this->get_meta_data( $repeater_item );
		$repeater_index = $repeater_item['_id'];

		if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) {
			return;
		}

		$has_link = false;
		$link_key = 'link_' . $repeater_index;
		$item_key = 'item_' . $repeater_index;

		$this->add_render_attribute( $item_key, 'class',
			[
				'raven-icon-list-item',
				'elementor-repeater-item-' . $repeater_item['_id'],
			]
		);

		$active_settings = $this->get_active_settings();

		if ( 'inline' === $active_settings['view'] ) {
			$this->add_render_attribute( $item_key, 'class', 'raven-inline-item' );
		}

		if ( ! empty( $item_data['url']['url'] ) ) {
			$has_link = true;

			$url = $item_data['url'];
			$this->add_render_attribute( $link_key, 'href', $url['url'] );

			if ( ! empty( $url['is_external'] ) ) {
				$this->add_render_attribute( $link_key, 'target', '_blank' );
			}

			if ( ! empty( $url['nofollow'] ) ) {
				$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $item_data['itemprop'] ) ) {
			$this->add_render_attribute( $item_key, 'itemprop', $item_data['itemprop'] );
		}

		?>
		<li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
			<?php if ( $has_link ) : ?>
			<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
				<?php endif; ?>
				<?php $this->render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ); ?>
				<?php $this->render_item_text( $item_data, $repeater_index ); ?>
				<?php if ( $has_link ) : ?>
			</a>
		<?php endif; ?>
		</li>
		<?php
	}

	/**
	 * Render icon or image markups.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	protected function render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ) {

		$migration_allowed = Elementor::$instance->icons_manager->is_migration_allowed();
		$migrated          = isset( $repeater_item['__fa4_migrated']['icon_new'] );
		$is_new            = empty( $repeater_item['icon'] ) && $migration_allowed;

		if ( 'none' === $repeater_item['show_icon'] && empty( $item_data['image'] ) ) {
			return;
		}

		if ( 'default' === $repeater_item['show_icon'] ) {
			$repeater_item['icon_new'] = $is_new ? $item_data['icon_new'] : ( isset( $item_data['icon'] ) ? $item_data['icon'] : '' );
		}

		?>
		<span class="raven-icon-list-icon">
			<?php
			if ( ! empty( $item_data['image'] ) ) :
				$image_data = 'image_' . $repeater_index;
				$this->add_render_attribute( $image_data, 'src', $item_data['image'] );
				$this->add_render_attribute( $image_data, 'alt', $item_data['text'] );
				?>
				<img class="raven-avatar" <?php echo $this->get_render_attribute_string( $image_data ); ?>>
			<?php else : ?>
				<?php
				if ( $is_new || $migrated ) {
					Elementor::$instance->icons_manager->render_icon( $repeater_item['icon_new'], [ 'aria-hidden' => 'true' ] );
				} else {
					?>
					<i class="<?php echo esc_attr( $repeater_item['icon'] ); ?>"></i>
				<?php } ?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render_item_text( $item_data, $repeater_index ) {
		$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $repeater_index );

		$this->add_render_attribute(
			$repeater_setting_key,
			'class',
			[
				'raven-icon-list-text',
				'raven-post-meta-item',
				'raven-post-meta-item-type-' . $item_data['type'],
			]
		);

		if ( ! empty( $item_data['terms_list'] ) ) {
			$this->add_render_attribute( $repeater_setting_key, 'class', 'raven-terms-list' );
		}

		?>
		<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
			<?php if ( ! empty( $item_data['text_prefix'] ) ) : ?>
				<span class="raven-post-meta-item-prefix"><?php echo esc_html( $item_data['text_prefix'] ); ?></span>
			<?php endif; ?>
			<?php
			if ( ! empty( $item_data['terms_list'] ) ) :
				$terms_list = [];
				$item_class = 'raven-post-meta-terms-list-item';
				?>
				<span class="raven-post-meta-terms-list">
				<?php
				foreach ( $item_data['terms_list'] as $term ) :
					if ( ! empty( $term['url'] ) ) :
						$terms_list[] = '<a href="' . esc_attr( $term['url'] ) . '" class="' . $item_class . '">' . esc_html( $term['text'] ) . '</a>';
					else :
						$terms_list[] = '<span class="' . $item_class . '">' . esc_html( $term['text'] ) . '</span>';
					endif;
				endforeach;

				echo implode( ', ', $terms_list );
				?>
				</span>
			<?php else : ?>
				<?php
				echo wp_kses( $item_data['text'], [
					'a' => [
						'href' => [],
						'title' => [],
						'rel' => [],
					],
				] );
				?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render() {
		// Load both f4 & f5 for backward compatibility.
		Elementor::$instance->icons_manager->enqueue_shim();

		$settings = $this->get_settings_for_display();

		ob_start();

		if ( ! empty( $settings['icon_list'] ) ) {
			foreach ( $settings['icon_list'] as $repeater_item ) {
				$this->render_item( $repeater_item );
			}
		}

		$items_markup = ob_get_clean();

		if ( empty( $items_markup ) ) {
			return;
		}

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute(
				'icon_list',
				'class',
				'raven-inline-items'
			);
		}

		$this->add_render_attribute(
			'icon_list',
			'class',
			[ 'raven-icon-list-items', 'raven-post-meta' ]
		);
		?>
		<ul <?php echo $this->get_render_attribute_string( 'icon_list' ); ?>>
			<?php echo $items_markup; ?>
		</ul>
		<?php
	}
}
