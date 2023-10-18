<?php
namespace WprAddons\Modules\Testimonial\Widgets;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;
use Elementor\Utils;
use Elementor\Icons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Testimonial_Carousel extends Widget_Base {
		
	public function get_name() {
		return 'wpr-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'testimonial carousel', 'reviews', 'rating', 'stars' ];
	}
	
	public function get_script_depends() {
		return [ 'imagesloaded', 'wpr-slick' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-testimonials-slider-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_control_testimonial_amount() {
		$this->add_responsive_control(
			'testimonial_amount',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 2,
				'widescreen_default' => 2,
				'laptop_default' => 2,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					'1' => esc_html__( 'One', 'wpr-addons' ),
					'2' => esc_html__( 'Two', 'wpr-addons' ),
					'pro-3' => esc_html__( 'Three (Pro)', 'wpr-addons' ),
					'pro-4' => esc_html__( 'Four (Pro)', 'wpr-addons' ),
					'pro-5' => esc_html__( 'Five (Pro)', 'wpr-addons' ),
					'pro-6' => esc_html__( 'Six (Pro)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-testimonial-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_icon() {
		$this->add_control(
			'testimonial_icon',
			[
				'label' => esc_html__( 'Select Quote Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'fas fa-quote-left' => esc_html__( 'Quote Left', 'wpr-addons' ),
					'fas fa-quote-right' => esc_html__( 'Quote Right', 'wpr-addons' ),
					'pro-svg' => esc_html__( 'SVG Icons (Pro)', 'wpr-addons' ),
				],
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_rating_score() {}

	public function add_repeater_args_social_media() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_media_is_external() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_media_nofollow() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_1() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_2() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_3() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_4() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_section_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_icon_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_repeater_args_social_url_5() {
		return [
			'type' => Controls_Manager::HIDDEN,
			'default' => ''
		];
	}

	public function add_control_stack_testimonial_autoplay() {}

	public function add_control_stack_nav_position() {}

	public function add_control_dots_hr() {}

	protected function register_controls() {

		// Section: Items -----------
		$this->start_controls_section(
			'wpr__section_testimonial_items',
			[
				'label' => esc_html__( 'Items', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );
		
		$repeater = new Repeater();

		$repeater->add_control(
			'testimonial_author',
			[
				'label' => esc_html__( 'Author', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$repeater->add_control(
			'testimonial_job',
			[
				'label' => esc_html__( 'Job', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sony CEO',
			]
		);

		$repeater->add_control(
			'testimonial_image',
			[
				'label' => esc_html__( 'Author Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_image',
			[
				'label' => esc_html__( 'Company Logo', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_logo_url',
			[
				'label' => esc_html__( 'Logo URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'testimonial_logo_image[url]',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
            'testimonial_title_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control(
			'testimonial_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Awesome Theme',
			]
		);

		$repeater->add_control(
			'testimonial_rating_amount',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10,
				'step' => 0.1,
				'default' => 4.5,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.',
			]
		);

		$repeater->add_control(
			'testimonial_date',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '7 Days Ago',
			]
		);

		$repeater->add_control(
            'social_media_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$repeater->add_control( 'social_media', $this->add_repeater_args_social_media() );

		$repeater->add_control( 'social_media_is_external', $this->add_repeater_args_social_media_is_external() );

		$repeater->add_control( 'social_media_nofollow', $this->add_repeater_args_social_media_nofollow() );

		$repeater->add_control( 'social_section_1', $this->add_repeater_args_social_section_1() );

		$repeater->add_control( 'social_icon_1', $this->add_repeater_args_social_icon_1() );

		$repeater->add_control( 'social_url_1', $this->add_repeater_args_social_url_1() );

		$repeater->add_control( 'social_section_2', $this->add_repeater_args_social_section_2() );

		$repeater->add_control( 'social_icon_2', $this->add_repeater_args_social_icon_2() );

		$repeater->add_control( 'social_url_2', $this->add_repeater_args_social_url_2() );

		$repeater->add_control( 'social_section_3', $this->add_repeater_args_social_section_3() );

		$repeater->add_control( 'social_icon_3', $this->add_repeater_args_social_icon_3() );

		$repeater->add_control( 'social_url_3', $this->add_repeater_args_social_url_3() );

		$repeater->add_control( 'social_section_4', $this->add_repeater_args_social_section_4() );

		$repeater->add_control( 'social_icon_4', $this->add_repeater_args_social_icon_4() );

		$repeater->add_control( 'social_url_4', $this->add_repeater_args_social_url_4() );

		$repeater->add_control( 'social_section_5', $this->add_repeater_args_social_section_5() );

		$repeater->add_control( 'social_icon_5', $this->add_repeater_args_social_icon_5() );

		$repeater->add_control( 'social_url_5', $this->add_repeater_args_social_url_5() );

		$this->add_control(
			'testimonial_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4.5,
						'testimonial_title' => esc_html__( 'Awesome Theme', 'wpr-addons' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'wpr-addons' ),
						'testimonial_author' => esc_html__( 'John Doe', 'wpr-addons' ),
						'testimonial_job' => esc_html__( 'Sony CEO', 'wpr-addons' ),
						'testimonial_date' => esc_html__( '7 Days Ago', 'wpr-addons' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[		
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 5,
						'testimonial_title' => esc_html__( 'Simply The Best', 'wpr-addons' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'wpr-addons' ),
						'testimonial_author' => esc_html__( 'Tom Jones', 'wpr-addons' ),
						'testimonial_job' => esc_html__( 'Tesla CMO', 'wpr-addons' ),
						'testimonial_date' => esc_html__( '10.04.2018', 'wpr-addons' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'testimonial_rating_amount' => 4,
						'testimonial_title' => esc_html__( 'Easy To Use', 'wpr-addons' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'wpr-addons' ),
						'testimonial_author' => esc_html__( 'Mark Wilson', 'wpr-addons' ),
						'testimonial_job' => esc_html__( 'Apple Manager', 'wpr-addons' ),
						'testimonial_date' => esc_html__( '5 Month Ago', 'wpr-addons' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
					[	
						'testimonial_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],		
						'testimonial_rating_amount' => 3.5,
						'testimonial_title' => esc_html__( 'Creative', 'wpr-addons' ),
						'testimonial_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laoreet non vitae lorem. Aliquam porttitor tellus enim, eget commodo augue porta ut. Maecenas lobortis ligula vel tellus sagittis ullamcorperv vestibulum pellentesque cursutu.', 'wpr-addons' ),
						'testimonial_author' => esc_html__( 'Bob Smith', 'wpr-addons' ),
						'testimonial_job' => esc_html__( 'Doctor', 'wpr-addons' ),
						'testimonial_date' => esc_html__( '6 Month Ago', 'wpr-addons' ),
						'social_icon_1' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
						'social_icon_2' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
						'social_icon_3' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
					],
				],
				'title_field' => '{{{ testimonial_title }}}',
			]
		);


		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'testimonial_repeater_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => 'More than 4 Testimonials are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-testimonial-carousel-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => 'More than 4 Testimonials are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Settings ---------
		$this->start_controls_section(
			'wpr__section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'type' => Controls_Manager::SECTION,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'testimonial_image_size',
				'default' => 'full',
			]
		);

		$this->add_control_testimonial_amount();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'testimonial_columns_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Testimonial Columns</span> option is fully supported in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-testimonial-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Testimonial Columns</span> option is fully supported in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->add_control(
			'testimonial_slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'frontend_available' => true,
				'default' => 2,
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'testimonial_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'widescreen_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'laptop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_extra_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-carousel .slick-slide' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-testimonial-carousel .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'testimonial_amount!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'testimonial_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'flex'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'display:{{VALUE}} !important;',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class'	=> 'wpr-testimonial-nav-',
				'render_type' => 'template',
				'condition' => [
					'testimonial_nav' => 'yes',
				],
			]
		);


		$this->add_control(
			'testimonial_nav_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'svg-angle-1-left',
				'options' => Utilities::get_svg_icons_array( 'arrows', [
					'fas fa-angle-left' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double-left' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow-left' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle-left' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt-left' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron-left' => esc_html__( 'Chevron', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'condition' => [
					'testimonial_nav' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'testimonial_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'inline-table'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dots' => 'display: {{VALUE}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control_stack_testimonial_autoplay();

		$this->add_control(
			'testimonial_loop',
			[
				'label' => esc_html__( 'Infinite Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'testimonial_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'testimonial_effect_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,	
			]
		);

		// Icon
		$this->add_control_testimonial_icon();

		// Upgrade to Pro Notice
		Utilities::upgrade_pro_notice( $this, Controls_Manager::RAW_HTML, 'testimonial', 'testimonial_icon', ['pro-svg'] );

		$this->add_control(//TODO: This option doesn't work properly
			'testimonial_icon_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Icon Position', 'wpr-addons' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top Content', 'wpr-addons' ),
					'inner' => esc_html__( 'Inner Content', 'wpr-addons' ),
				],
				'condition' => [
					'testimonial_icon!' => 'none',
				],
				'render_type' => 'template',
			]
		);

		// Rating
		$this->add_control(
			'testimonial_rating',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_rating_scale',
			[
				'label' => esc_html__( 'Scale', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 1,
				'max' => 10,
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control_testimonial_rating_score();

		$this->add_control(
			'testimonial_rating_style',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style_1' => 'Style 1',
					'style_2' => 'Style 2',
				],
				'default' => 'style_2',
				'render_type' => 'template',
				'prefix_class' => 'wpr-testimonial-rating-',
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'testimonial_unmarked_rating_style',
			[
				'label' => esc_html__( 'Unmarked Style', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'solid' => [
						'title' => esc_html__( 'Solid', 'wpr-addons' ),
						'icon' => 'fas fa-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'wpr-addons' ),
						'icon' => 'far fa-star',
					],
				],
				'default' => 'outline',
				'condition' => [
					'testimonial_rating' => 'yes',
					'testimonial_rating_score' => '',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'testimonial', [
			'Add Unlimited Testimonials',
			'Columns (Carousel) 1,2,3,4,5,6',
			'Advanced Social Media Icon options',
			'Advanced Rating Styling options',
			'Unlimited Slides to Scroll option',
			'Autoplay options',
			'Advanced Navigation Positioning',
			'Advanced Pagination Positioning',
		] );
		
		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'wpr__section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'general_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-testimonial-item'
			]
		);

		$this->add_responsive_control(
			'general_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 50,
					'left' => 5,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'general_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-testimonial-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'general_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'wpr__section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#f9f9f9',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-testimonial-content-inner'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-testimonial-content-inner',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 25,
					'right' => 25,
					'bottom' => 27,
					'left' => 25,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-left .wpr-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-right .wpr-testimonial-meta' => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-top:not(.wpr-testimonial-meta-align-center) .wpr-testimonial-meta,
					 {{WRAPPER}}.wpr-testimonial-meta-position-bottom:not(.wpr-testimonial-meta-align-center) .wpr-testimonial-meta' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-left .wpr-testimonial-content-inner:before' => 'left: calc(-22px - {{left}}{{UNIT}});',
					'{{WRAPPER}}.wpr-testimonial-meta-position-right .wpr-testimonial-content-inner:before' => 'right: calc(-22px - {{right}}{{UNIT}});',
					'{{WRAPPER}}.wpr-testimonial-meta-position-top .wpr-testimonial-content-inner:before' => 'top: calc(-15px - {{top}}{{UNIT}});',
					'{{WRAPPER}}.wpr-testimonial-meta-position-bottom .wpr-testimonial-content-inner:before' => 'bottom: calc(-15px - {{bottom}}{{UNIT}});',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_border_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'content_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Triangle
		$this->add_control(
			'content_triangle',
			[
				'label' => esc_html__( 'Triangle', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,				
				'default' => 'yes',
				'prefix_class' => 'wpr-testimonial-triangle-',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'triangle_color',
			[
				'label' => esc_html__( 'Triangle Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f7f7f7',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content-inner:before' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'content_triangle' => 'yes',
				],
			]
		);

		// Icon
		$this->add_control(
			'icon_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-testimonial-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-testimonial-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Title
		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-title',
			]
		);

		$this->add_responsive_control(
			'title_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'title_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Content
		$this->add_control(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-content',
			]
		);

		$this->add_responsive_control(
			'content_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Date
		$this->add_control(
			'date_section',
			[
				'label' => esc_html__( 'Date', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#c1c1c1',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-date',
			]
		);

		$this->add_control(
			'date_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-date' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Rating
		$this->add_control(
			'rating_section',
			[
				'label' => esc_html__( 'Rating', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'rating_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'rating_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFD726',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating i:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_unmarked_color',
			[
				'label' => esc_html__( 'Unmarked Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d8d8d8',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_score_color',
			[
				'label' => esc_html__( 'Score Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffd726',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'rating_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'rating_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'rating_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-testimonial-rating span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'rating_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rating_color_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-rating span',
			]
		);

		$this->end_controls_section();	
		
		// Styles
		// Section: Meta -------------
		$this->start_controls_section(
			'section_style_meta',
			[
				'label' => esc_html__( 'Meta', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'meta_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'extra' => esc_html__( 'Extra', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-testimonial-meta-position-',
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'meta_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-testimonial-meta-position-top .wpr-testimonial-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-left .wpr-testimonial-meta' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-right .wpr-testimonial-meta' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-bottom .wpr-testimonial-meta' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-extra .wpr-testimonial-content-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'wpr-testimonial-meta-align-',
				'separator' => 'before',
			]
		);

		// Image
		$this->add_control(
			'image_section',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'prefix_class'	=> 'wpr-testimonial-image-position-',
                'condition' => [
                	'meta_position!' => 'extra'
                ]
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-meta-position-top.wpr-testimonial-meta-align-left .wpr-testimonial-content-inner:before,
					{{WRAPPER}}.wpr-testimonial-meta-position-bottom.wpr-testimonial-meta-align-left .wpr-testimonial-content-inner:before' => 'left: calc( {{content_padding.LEFT}}px + {{content_border_width.LEFT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.wpr-testimonial-meta-position-top.wpr-testimonial-meta-align-right .wpr-testimonial-content-inner:before,
					{{WRAPPER}}.wpr-testimonial-meta-position-bottom.wpr-testimonial-meta-align-right .wpr-testimonial-content-inner:before' => 'right: calc( {{content_padding.RIGHT}}px + {{content_border_width.RIGHT}}px + ({{SIZE}}px / 2) );',
					'{{WRAPPER}}.wpr-testimonial-meta-position-left .wpr-testimonial-content-inner:before,
					{{WRAPPER}}.wpr-testimonial-meta-position-right .wpr-testimonial-content-inner:before' => 'top: calc( {{content_padding.TOP}}px + {{content_border_width.TOP}}px + ({{SIZE}}px / 2) );',
				],
			]
		);

		$this->add_responsive_control(
			'image_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-image-position-right .wpr-testimonial-image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-image-position-left .wpr-testimonial-image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-testimonial-image-position-center .wpr-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-testimonial-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Name
		$this->add_control(
			'name_section',
			[
				'label' => esc_html__( 'Name', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-name',
			]
		);

		$this->add_responsive_control(
			'name_distance_top',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-name' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_position' => [ 'left', 'right' ],
				],
			]
		);

		$this->add_responsive_control(
			'name_distance_bottom',
			[
				'label' => esc_html__( 'Bottom Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Job
		$this->add_control(
			'job_section',
			[
				'label' => esc_html__( 'Job', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'job_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b7b7b7',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-testimonial-job',
			]
		);

		$this->add_responsive_control(
			'job_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		// Image
		$this->add_control(
			'logo_section',
			[
				'label' => esc_html__( 'Logo', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-logo-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-logo-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);
		
		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Social Media -----
		$this->start_controls_section(
			'wpr__section_style_social_media',
			[
				'label' => esc_html__( 'Social Media', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_social_style' );

		$this->start_controls_tab(
			'tab_social_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'social_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#919191',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_social_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'social_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444444',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b5b5',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-testimonial-social i' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 9,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'social_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'social_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'social_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'social_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
            'testimonial_style_social_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-testimonial-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Navigation -------
		$this->start_controls_section(
			'wpr__section_style_nav',
			[
				'label' => esc_html__( 'Navigation', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_nav_style' );

		$this->start_controls_tab(
			'tab_nav_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-testimonial-arrow svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_nav_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-testimonial-arrow:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-testimonial-arrow svg' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_font_size',
			[
				'label' => esc_html__( 'Font Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-testimonial-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 21,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'nav_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'nav_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control_stack_nav_position();

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__( 'Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_dots' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'dots_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'dots_active_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dots .slick-active .wpr-testimonial-dot' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'dots_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dots .slick-active .wpr-testimonial-dot' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dots_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'dots_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
					'unit'		=> '%',
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dots_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'size_units' => ['px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],							
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dot' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control_dots_hr();
		
		$this->add_responsive_control(
			'dots_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 1200,
					],
				],											
				'default' => [
					'unit' => '%',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dots' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
	}

	public function render_testimonial_image( $item ) {
		$settings = $this->get_settings();
		$image_src = Group_Control_Image_Size::get_attachment_image_src( $item['testimonial_image']['id'], 'testimonial_image_size', $settings );

		if ( ! $image_src ) {
			$image_src = $item['testimonial_image']['url'];
		}

		?>

		<?php if ( ! empty( $item['testimonial_image']['url'] ) ) : ?>
			<div class="wpr-testimonial-image">
				<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $item['testimonial_author'] ); ?>">
			</div>
		<?php endif; ?>

	<?php
	}

	public function render_pro_element_social_media( $item, $item_count ) {}	

	public function render_testimonial_meta( $item, $item_count ) {
		$logo_element = 'div'; ?>
		
		<div class="wpr-testimonial-meta-content-wrap">
			<?php if ( ! empty( $item['testimonial_author'] ) ) : ?>
				<div class="wpr-testimonial-name"><?php echo wp_kses_post( $item['testimonial_author'] ); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_job'] ) ) : ?>
				<div class="wpr-testimonial-job"><?php echo wp_kses_post( $item['testimonial_job'] ); ?></div>
			<?php endif; ?>

			<?php
			if ( ! empty( $item['testimonial_logo_image']['url'] ) ) {
				
				$this->add_render_attribute( 'logo_attribute'. $item_count, 'class', 'wpr-testimonial-logo-image elementor-clearfix' );

				if ( ! empty( $item['testimonial_logo_url']['url'] ) ) {

					$logo_element = 'a';

					$this->add_render_attribute( 'logo_attribute'. $item_count, 'href', $item['testimonial_logo_url']['url'] );

					if ( $item['testimonial_logo_url']['is_external'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'target', '_blank' );
					}

					if ( $item['testimonial_logo_url']['nofollow'] ) {
						$this->add_render_attribute( 'logo_attribute'. $item_count, 'nofollow', '' );
					}

				}

				echo '<'. esc_attr( $logo_element ) .' '. $this->get_render_attribute_string( 'logo_attribute'. $item_count ) .'>';
					echo '<img src="'. esc_url(  $item['testimonial_logo_image']['url'] ) .'" alt="'. esc_attr( $item['testimonial_author'] ) .'">';
				echo '</'. esc_attr( $logo_element ) .'>';

			}

			$this->render_pro_element_social_media( $item, $item_count );

			?>

		</div>
		<?php
	}


	public function wpr_testimonial_content( $item ) {
		$settings = $this->get_settings(); ?>

		<div class="wpr-testimonial-content-wrap">
			<div class="wpr-testimonial-content-inner">
			<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'top' ) : ?>
				<div class="wpr-testimonial-icon">
					<?php echo Utilities::get_wpr_icon( $settings['testimonial_icon'], '' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_title'] ) ) : ?>
				<div class="wpr-testimonial-title"><?php echo wp_kses_post( $item['testimonial_title'] ); ?></div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'top' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_content'] ) ) : ?>
				<div class="wpr-testimonial-content">
					<?php if ( $settings['testimonial_icon'] !== 'none' && $settings['testimonial_icon_position'] === 'inner' ) : ?>
					<div class="wpr-testimonial-icon">	
						<?php echo Utilities::get_wpr_icon( $settings['testimonial_icon'], '' ); ?>
					</div>
					<?php endif; ?>

					<p><?php echo wp_kses_post($item['testimonial_content']); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $settings['rating_position'] === 'bottom' ) : ?>
				<?php $this->render_testimonial_rating( $item ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $item['testimonial_date'] ) ) : ?>
				<div class="wpr-testimonial-date"><?php echo esc_html( $item['testimonial_date'] ); ?></div>
			<?php endif; ?>
			</div>
		</div>

	<?php
	}

	public function render_testimonial_rating( $item ) {
		$settings = $this->get_settings();
		$rating_amount = $item['testimonial_rating_amount'];
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

		if ( 'style_1' === $settings['testimonial_rating_style'] ) {
			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style_2' === $settings['testimonial_rating_style'] ) {
			$rating_icon = '&#9733;';

			if ( 'outline' === $settings['testimonial_unmarked_rating_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		if ( 'yes' === $settings['testimonial_rating'] && ! empty( $rating_amount ) ) : ?>	

			<div class="wpr-testimonial-rating">
			<?php for( $i = 1; $i <= $settings['testimonial_rating_scale']; $i++ ) : ?>
				<?php if ( $i <= $rating_amount ) : ?>
					<i class="wpr-rating-icon-full"><?php echo esc_html($rating_icon); ?></i>
				<?php elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) : ?>
					<i class="wpr-rating-icon-<?php echo esc_attr(( $rating_amount - $round_rating ) * 10); ?>"><?php echo esc_html($rating_icon); ?></i>
				<?php else : ?>
					<i class="wpr-rating-icon-empty"><?php echo esc_html($rating_icon); ?></i>
				<?php endif; ?>
	     	<?php endfor; ?>

	     	<?php $this->render_pro_element_testimonial_score($rating_amount); ?>
			</div>

	<?php
		endif;
	}

	public function render_pro_element_testimonial_score($rating_amount) {}

	protected function render() {	
		$settings = $this->get_settings();
		$item_html = '';
		$item_count = 0;

		if ( empty( $settings['testimonial_items'] ) ) {
			return;
		}
		
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		if ( ! wpr_fs()->can_use_premium_code() ) {

			$settings['testimonial_autoplay'] = '';
			$settings['testimonial_autoplay_duration'] = 0;
			$settings['testimonial_pause_on_hover'] = '';
		}

		$options = [
			'rtl' => $is_rtl,
			'infinite' => ( $settings['testimonial_loop'] === 'yes' ),
			'speed' => absint( $settings['testimonial_effect_duration'] * 1000 ),
			'arrows' => true,
			'dots' => true,
			'autoplay' => ( $settings['testimonial_autoplay'] === 'yes' ),
			'autoplaySpeed' => absint( $settings['testimonial_autoplay_duration'] * 1000 ),
			'pauseOnHover' => $settings['testimonial_pause_on_hover'],
			'prevArrow' => '#wpr-testimonial-prev-'. $this->get_id(),
			'nextArrow' => '#wpr-testimonial-next-'. $this->get_id(),
			'sliderSlidesToScroll' => +$settings['testimonial_slides_to_scroll'],
		];

		$this->add_render_attribute( 'testimonial-caousel-attribute', [
			'class' => 'wpr-testimonial-carousel',
			'dir' => esc_attr( $direction ),
			'data-slick' => wp_json_encode( $options ),
		] );

		?>
		<div class="wpr-testimonial-carousel-wrap">
			
			<div <?php echo $this->get_render_attribute_string( 'testimonial-caousel-attribute' ); ?> data-slide-effect="<?php echo esc_attr($settings['testimonial_effect']); ?>">
					
					<?php foreach ( $settings['testimonial_items'] as $key => $item ) : ?>

						<?php if ( ! wpr_fs()->can_use_premium_code() && $key === 4 ) { break; } ?>
					
						<div class="wpr-testimonial-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?> elementor-clearfix">
							
							<div class="wpr-testimonial-meta elementor-clearfix">
								<div class="wpr-testimonial-meta-inner">
								<?php 
								$this->render_testimonial_image( $item );
								if (  $settings['meta_position'] !== 'extra' ) {
									$this->render_testimonial_meta( $item, $item_count );
								}
								?>
								</div>
							</div>

							<?php $this->wpr_testimonial_content( $item ); ?>

							<?php if ( $settings['meta_position'] === 'extra' ) : ?>
								<div class="wpr-testimonial-meta elementor-clearfix">
									<div class="wpr-testimonial-meta-inner">
									<?php 
									if (  $settings['meta_position'] !== 'extra' ) {
										$this->render_testimonial_image( $item );
									}
									$this->render_testimonial_meta( $item, $item_count );
									?>
									</div>	
								</div>
							<?php endif; ?>

						</div>
						<?php
						$item_count++;
					endforeach;
					?>
			</div>

			<div class="wpr-testimonial-controls">
				<div class="wpr-testimonial-dots"></div>
			</div>

			<div class="wpr-testimonial-arrow-container">
				<div class="wpr-testimonial-prev-arrow wpr-testimonial-arrow" id="<?php echo 'wpr-testimonial-prev-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_wpr_icon( $settings['testimonial_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="wpr-testimonial-next-arrow wpr-testimonial-arrow" id="<?php echo 'wpr-testimonial-next-'. esc_attr($this->get_id()); ?>">
					<?php echo Utilities::get_wpr_icon( $settings['testimonial_nav_icon'], '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>

	<?php
	}
}