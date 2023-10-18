<?php
namespace WprAddons\Modules\TeamMember\Widgets;

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
use Elementor\Utils;
use Elementor\Icons;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Team_Member extends Widget_Base {
		
	public function get_name() {
		return 'wpr-team-member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-image-box';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'team member' ];
	}

	public function get_style_depends() {
		return [ 'wpr-animations-css', 'wpr-button-animations-css' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-team-member-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	public function add_section_layout() {}

	public function add_section_image_overlay() {}

	public function add_section_style_overlay() {}

	protected function register_controls() {

		// Section: General ----------
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
			]
		);

		Utilities::wpr_library_buttons( $this, Controls_Manager::RAW_HTML );

		$this->add_control(
			'member_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'member_name',
			[
				'label' => esc_html__( 'Name', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'John Doe',
			]
		);

		$this->add_control(
			'member_name_tag',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'HTML Tag', 'wpr-addons' ),
				'default' => 'h3',
				'options' => [
					'h1' => esc_html__( 'H1', 'wpr-addons' ),
					'h2' => esc_html__( 'H2', 'wpr-addons' ),
					'h3' => esc_html__( 'H3', 'wpr-addons' ),
					'h4' => esc_html__( 'H4', 'wpr-addons' ),
					'h5' => esc_html__( 'H5', 'wpr-addons' ),
					'h6' => esc_html__( 'H6', 'wpr-addons' ),
					'div' => esc_html__( 'div', 'wpr-addons' ),
					'span' => esc_html__( 'span', 'wpr-addons' ),
					'p' => esc_html__( 'p', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'member_job',
			[
				'label' => esc_html__( 'Job', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Sony CEO',
			]
		);

		$this->add_control(
			'member_description',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet cursus volutpat. Aliquam sit amet ligula et justo tincidunt laorsoet non vitae lorem.',
			]
		);

		$this->add_control(
            'member_description_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

		$this->add_control(
			'member_divider',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

        $this->add_control(
			'member_divider_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'before_job' => esc_html__( 'Before Job', 'wpr-addons' ),
					'after_job' => esc_html__( 'After Job', 'wpr-addons' ),
				],
				'default' => 'after_job',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$this->add_control(
				'team_member_pro_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => '<span style="color:#2a2a2a;">Postioning Elements over Media and <br>Media Overlay</span> options are available<br> in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-panel-team-member-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong>',
					// 'raw' => '<span style="color:#2a2a2a;">Postioning Elements over Media and <br>Media Overlay</span> options are available<br> in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong>',
					'content_classes' => 'wpr-pro-notice',
				]
			);
		}

		$this->end_controls_section(); // End Controls Section

		// Section: Layout -----------
		$this->add_section_layout();

		// Section: Social Media -----
		$this->start_controls_section(
			'section_social_media',
			[
				'label' => esc_html__( 'Social Media', 'wpr-addons' ),
			]
		);
		$this->add_control(
			'social_media',
			[
				'label' => esc_html__( 'Show Social Media', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'social_media_is_external',
			[
				'label' => esc_html__( 'Open in new window', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_media_nofollow',
			[
				'label' => esc_html__( 'Add nofollow', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_1',
			[
				'label' => esc_html__( 'Social 1', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_1',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-facebook-f',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_1',
			[
				'label' => esc_html__( 'Social URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_2',
			[
				'label' => esc_html__( 'Social 2', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_2',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_2',
			[
				'label' => esc_html__( 'Social URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_3',
			[
				'label' => esc_html__( 'Social 3', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_3',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fab fa-linkedin-in',
					'library' => 'fa-brands',
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_3',
			[
				'label' => esc_html__( 'Social URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_4',
			[
				'label' => esc_html__( 'Social 4', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_4',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_4',
			[
				'label' => esc_html__( 'Social URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

        $this->add_control(
			'social_section_5',
			[
				'label' => esc_html__( 'Social 5', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_icon_5',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_url_5',
			[
				'label' => esc_html__( 'Social URL', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'show_external' => false,
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
		
		// Section: Buttom -----------
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'member_btn',
			[
				'label' => esc_html__( 'Show Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'member_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'About Me',
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'member_btn_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wpr-addons' ),
				'condition' => [
					'member_btn' => 'yes',
				],
				'show_label' => false,
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Overlay ---------------
		$this->add_section_image_overlay();

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Section: Pro Features
		Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, 'team-member', [
			'Advanced Layout options - Move Elements over Image (Title, Job, Social Icons, etc...)',
			'Advanced Image Overlay Hover Animations',
		] );
		
		// Styles
		// Section: Image ------------
		$this->start_controls_section(
			'wpr__section_style_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-media' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'default' => 'solid',
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
				'selector' => '{{WRAPPER}} .wpr-member-media',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Content ----------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-member-content'
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 20,
					'right' => 15,
					'bottom' => 50,
					'left' => 15,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .wpr-member-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-member-name',
			]
		);

		$this->add_responsive_control(
			'name_distance',
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
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-name' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'name_align',
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
					'{{WRAPPER}} .wpr-member-name' => 'text-align: {{VALUE}};',
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
				'default' => '#9e9e9e',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-job' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-member-job',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-job' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'job_align',
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
					'{{WRAPPER}} .wpr-member-job' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Description
		$this->add_control(
			'description_section',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#545454',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-member-description',
			]
		);

		$this->add_responsive_control(
			'description_distance',
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
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'description_align',
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
					'{{WRAPPER}} .wpr-member-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Divider
		$this->add_control(
			'divider_section',
			[
				'label' => esc_html__( 'Divider', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1d1d1',				
				'selectors' => [
					'{{WRAPPER}} .wpr-member-divider:after' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'solid',		
				'selectors' => [
					'{{WRAPPER}} .wpr-member-divider:after' => 'border-bottom-style: {{VALUE}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => esc_html__( 'Weight', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-divider:after' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
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
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-divider:after' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_distance',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-divider:after' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'member_divider' => 'yes',
				],	
			]
		);

		$this->add_control(
			'divider_align',
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
                'prefix_class'	=> 'wpr-team-member-divider-',
				'condition' => [
					'member_divider' => 'yes',
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
				'condition' => [
					'social_media' => 'yes',
				],
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
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'border-color: {{VALUE}}',
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
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'social_trans_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

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
					'{{WRAPPER}} .wpr-member-social' => 'transition-duration: {{VALUE}}s',
				],
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
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 17,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'font-size: {{SIZE}}{{UNIT}};',
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
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 37,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-member-social i' => 'line-height: {{SIZE}}{{UNIT}};',
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
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'margin-right: {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_responsive_control(
			'social_distance',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_align',
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
                'prefix_class'	=> 'wpr-team-member-social-media-',
				'separator' => 'before',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'border-style: {{VALUE}};',
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
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .wpr-member-social',
			]
		);

		$this->end_controls_section();

		// Styles
		// Section: Button -----------
		$this->start_controls_section(
			'wpr__section_style_btn',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6d71e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#6d71e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-member-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474de8',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn.wpr-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-member-btn:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-member-btn:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#474de8',
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-member-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_section_anim_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-member-btn:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-member-btn:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->add_control(
			'btn_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="wpr-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="wpr-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'btn_animation' => [ 
						'wpr-button-underline-from-left',
						'wpr-button-underline-from-center',
						'wpr-button-underline-from-right',
						'wpr-button-underline-reveal',
						'wpr-button-overline-reveal',
						'wpr-button-overline-from-left',
						'wpr-button-overline-from-center',
						'wpr-button-overline-from-right'
					]
				],
			]
		);

		$this->add_control(
			'btn_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-member-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_align',
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
					'{{WRAPPER}} .wpr-member-btn-wrap' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 8,
					'right' => 35,
					'bottom' => 8,
					'left' => 35,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}} .wpr-member-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_border_width',
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
					'{{WRAPPER}} .wpr-member-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Styles
		// Section: Overlay ---------------
		$this->add_section_style_overlay();

	}

	protected function team_member_social_media() {
		// Get Settings
		$settings = $this->get_settings();
		
		if ( '' !== $settings['social_icon_1']['value'] || '' !== $settings['social_icon_2']['value'] || '' !== $settings['social_icon_3']['value'] || '' !== $settings['social_icon_4']['value'] || '' !== $settings['social_icon_5']['value'] ) : 
		
		$this->add_render_attribute( 'social_attribute', 'class', 'wpr-member-social' );
				
		if ( $settings['social_media_is_external'] ) {
			$this->add_render_attribute( 'social_attribute', 'target', '_blank' );
		}

		if ( $settings['social_media_nofollow'] ) {
			$this->add_render_attribute( 'social_attribute', 'nofollow', '' );
		}

		?>

		<div class="wpr-member-social-media">
			
			<?php if ( $settings['social_icon_1']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_1']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<i class="<?php echo esc_html( $settings['social_icon_1']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>
		
			<?php if ( $settings['social_icon_2']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_2']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<i class="<?php echo esc_html( $settings['social_icon_2']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_3']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_3']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<i class="<?php echo esc_html( $settings['social_icon_3']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_4']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_4']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<i class="<?php echo esc_html( $settings['social_icon_4']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( $settings['social_icon_5']['value'] ) : ?>
				<a href="<?php echo esc_url( $settings['social_url_5']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' ); ?>>
					<i class="<?php echo esc_html( $settings['social_icon_5']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

		</div>
		
		<?php endif;
	}

	protected function team_member_button() {
		// Get Settings 
		$settings = $this->get_settings();
		
		if ( '' !== $settings['member_btn_text'] ) {

			$this->add_render_attribute( 'btn_attribute', 'class', 'wpr-member-btn wpr-button-effect '. $this->get_settings()['btn_animation'] );
			$this->add_render_attribute( 'btn_attribute', 'href', $settings['member_btn_url']['url'] );

			if ( $settings['member_btn_url']['is_external'] ) {
				$this->add_render_attribute( 'btn_attribute', 'target', '_blank' );
			}

			if ( $settings['member_btn_url']['nofollow'] ) {
				$this->add_render_attribute( 'btn_attribute', 'nofollow', '' );
			}

			echo '<div class="wpr-member-btn-wrap">';
				echo '<a '. $this->get_render_attribute_string( 'btn_attribute' ) .'>';
					echo '<span>'. esc_html($settings['member_btn_text']) .'</span>';
				echo '</a>';
			echo '</div>';

		}
	}

	protected function team_member_content() {
		// Get Settings 
		$settings = $this->get_settings();

		if ( ! wpr_fs()->can_use_premium_code() ) {
			$settings['member_name_location'] = 'below';
			$settings['member_job_location'] = 'below';
			$settings['member_description_location'] = 'below';
			$settings['member_social_media_location'] = 'below';
			$settings['member_btn_location'] = 'below';
			$settings['member_divider_location'] = 'below';
		}
		
		if ( ( '' !== $settings['member_name'] && 'below' === $settings['member_name_location'] ) || 
			( '' !== $settings['member_job'] && 'below' === $settings['member_job_location'] ) || 
			( '' !== $settings['member_description'] && 'below' === $settings['member_description_location'] ) || 
			( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) || 
			( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) ) : ?>

		<div class="wpr-member-content">
			<?php
				if ( '' !== $settings['member_name'] && 'below' === $settings['member_name_location'] ) {
					echo '<'. esc_attr( $settings['member_name_tag'] ) .' class="wpr-member-name">';
						echo wp_kses_post( $settings['member_name'] );
					echo '</'. esc_attr( $settings['member_name_tag'] ) .'>';
				}
			?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position'] ) : ?>
				<div class="wpr-member-divider"></div>
			<?php endif; ?>

			<?php if ( '' !== $settings['member_job'] && 'below' === $settings['member_job_location'] ) : ?>
				<div class="wpr-member-job"><?php echo esc_html( $settings['member_job'] ); ?></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['member_divider'] && 'below' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position'] ) : ?>
				<div class="wpr-member-divider"></div>
			<?php endif; ?>

			<?php if ( '' !== $settings['member_description'] && 'below' === $settings['member_description_location'] ) : ?>
				<div class="wpr-member-description"><?php echo wp_kses_post( $settings['member_description'] ); ?></div>
			<?php endif; ?>
			
			<?php 
				if( 'yes' === $settings['social_media'] && 'below' === $settings['member_social_media_location'] ) {
					$this->team_member_social_media();
				}
	 			
	 			if ( 'yes' === $settings['member_btn'] && 'below' === $settings['member_btn_location'] ) {
	 				$this->team_member_button();
	 			}
 			?>
		</div>

		<?php endif;

	}

	protected function team_member_overlay() {}

	protected function render() {
	// Get Settings 
	$settings = $this->get_settings();
	
	?>

	<div class="wpr-team-member">
		
		<?php if ( '' !== $settings['member_image']['url'] ) : ?>
			<?php 
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $settings['member_image']['id'], 'image_size', $settings );

				if ( ! $image_src ) {
					$image_src = $settings['member_image']['url'];
				}
			?>

			<div class="wpr-member-media">
				<div class="wpr-member-image">
					<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $settings['member_name'] ); ?>">
				</div>
				<?php $this->team_member_overlay(); ?>
			</div>
		<?php endif; ?>
		
		<?php $this->team_member_content(); ?>
	</div>

	<?php
	}
}