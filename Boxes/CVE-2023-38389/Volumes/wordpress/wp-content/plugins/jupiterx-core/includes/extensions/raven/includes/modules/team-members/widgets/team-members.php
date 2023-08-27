<?php

namespace JupiterX_Core\Raven\Modules\Team_Members\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use JupiterX_Core\Raven\Base\Base_Widget;

defined( 'ABSPATH' ) || die();

/**
 * Team members widget class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @since 3.0.0
 */
class Team_Members extends Base_Widget {

	/**
	 * @var string[] List of the social links of the team member with fa icons.
	 */
	protected $social_links = [
		'facebook' => 'facebook-f',
		'twitter' => 'twitter',
		'instagram' => 'instagram',
		'linkedin' => 'linkedin',
		'youtube' => 'youtube',
		'pinterest' => 'pinterest',
		'dribbble' => 'dribbble',
		'github' => 'github',
		'email' => 'envelope',
	];

	public function get_name() {
		return 'raven-team-members';
	}

	public function get_title() {
		return esc_html__( 'Team Members', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-team-members';
	}

	protected function register_controls() {
		$this->register_content_team_members_section();
		$this->register_content_settings_section();
		$this->register_content_content_order_section();
		$this->register_style_container_section();
		$this->register_style_profile_picture_section();
		$this->register_style_name_section();
		$this->register_style_position_section();
		$this->register_style_description_section();
		$this->register_style_social_icons_section();
	}

	private function register_content_team_members_section() {
		$this->start_controls_section(
			'section_content_team_members',
			[
				'label' => esc_html__( 'Team Members', 'jupiterx-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs(
			'team_member_data_tabs'
		);

		$repeater->start_controls_tab(
			'team_member_content',
			[
				'label' => esc_html__( 'Content', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Team Member', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Position', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter member description here which describes the position of member in company.', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'profile_picture',
			[
				'label' => esc_html__( 'Profile Picture', 'jupiterx-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'mask_image',
				'label' => esc_html__( 'Mask Image', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} img',
			]
		);

		$repeater->add_control(
			'link_type',
			[
				'label' => esc_html__( 'Link Type', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'image' => esc_html__( 'Image', 'jupiterx-core' ),
					'title' => esc_html__( 'Title', 'jupiterx-core' ),
				],
			]
		);

		$repeater->add_control(
			'member_link',
			[
				'label' => esc_html__( 'Link', 'jupiterx-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'jupiterx-core' ),
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
					'link_type!' => 'none',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'team_member_links',
			[
				'label' => esc_html__( 'Social Links', 'jupiterx-core' ),
			]
		);

		$repeater->add_control(
			'facebook',
			[
				'label' => esc_html__( 'Facebook', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Facebook page or profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'twitter',
			[
				'label' => esc_html__( 'Twitter', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Twitter profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'instagram',
			[
				'label' => esc_html__( 'Instagram', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Instagram profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#', 'jupiterx-core' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'linkedin',
			[
				'label' => esc_html__( 'Linkedin', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Linkedin profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'youtube',
			[
				'label' => esc_html__( 'YouTube', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Youtube profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'pinterest',
			[
				'label' => esc_html__( 'Pinterest', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Pinterest profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'dribbble',
			[
				'label' => esc_html__( 'Dribbble', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Dribbble profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'github',
			[
				'label' => esc_html__( 'Github', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter Github profile URL of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'email',
			[
				'label' => esc_html__( 'Email', 'jupiterx-core' ),
				'description' => esc_html__( 'Enter email ID of team member', 'jupiterx-core' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'team_members',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'name' => esc_html__( 'Team Member #1', 'jupiterx-core' ),
						'position' => esc_html__( 'WordPress Developer', 'jupiterx-core' ),
						'description' => esc_html__( 'Enter member description here which describes the position of member in company.', 'jupiterx-core' ),
					],
					[
						'name' => esc_html__( 'Team Member #2', 'jupiterx-core' ),
						'position' => esc_html__( 'WordPress Developer', 'jupiterx-core' ),
						'description' => esc_html__( 'Enter member description here which describes the position of member in company.', 'jupiterx-core' ),
					],
					[
						'name' => esc_html__( 'Team Member #3', 'jupiterx-core' ),
						'position' => esc_html__( 'WordPress Developer', 'jupiterx-core' ),
						'description' => esc_html__( 'Enter member description here which describes the position of member in company.', 'jupiterx-core' ),
					],
				],
				'title_field' => '{{{ name }}}',
			]
		);

		$this->end_controls_section();
	}

	private function register_content_settings_section() {
		$this->start_controls_section(
			'section_content_settings',
			[
				'label' => esc_html__( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Members/Row', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'desktop_default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => esc_html__( '1 Column', 'jupiterx-core' ),
					'2' => esc_html__( '2 Columns', 'jupiterx-core' ),
					'3' => esc_html__( '3 Columns', 'jupiterx-core' ),
					'4' => esc_html__( '4 Columns', 'jupiterx-core' ),
					'5' => esc_html__( '5 Columns', 'jupiterx-core' ),
					'6' => esc_html__( '6 Columns', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .raven-team-members-wrapper' => 'grid-template-columns: repeat( {{VALUE}}, 1fr )',
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'standard',
				'prefix_class' => 'card-layout--',
				'options' => [
					'standard' => esc_html__( 'Standard', 'jupiterx-core' ),
					'social-overlay' => esc_html__( 'Social Overlay', 'jupiterx-core' ),
					'creative' => esc_html__( 'Creative', 'jupiterx-core' ),
					'detail-slide' => esc_html__( 'Detail Slide', 'jupiterx-core' ),
					'full-overlay' => esc_html__( 'Full Overlay', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'content_align',
			[
				'label' => esc_html__( 'Content Alignment', 'jupiterx-core' ),
				'prefix_class' => 'content-alignment--',
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}' => '--content-alignment: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full',
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => esc_html__( 'Hover Effect', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'prefix_class' => 'hover-effect--',
				'options' => [
					'none' => esc_html__( 'None', 'jupiterx-core' ),
					'zoom-in' => esc_html__( 'Zoom In', 'jupiterx-core' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'jupiterx-core' ),
					'scale' => esc_html__( 'Scale', 'jupiterx-core' ),
					'grayscale' => esc_html__( 'Grayscale', 'jupiterx-core' ),
					'blur' => esc_html__( 'Blur', 'jupiterx-core' ),
					'bright' => esc_html__( 'Bright', 'jupiterx-core' ),
					'sepia' => esc_html__( 'Sepia', 'jupiterx-core' ),
					'translate' => esc_html__( 'Translate', 'jupiterx-core' ),
				],
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'equal_height',
			[
				'label' => esc_html__( 'Equal Height', 'jupiterx-core' ),
				'description' => esc_html__( 'This option searches for the image with the largest height and applies that height to the other images', 'jupiterx-core' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'jupiterx-core' ),
				'label_off' => esc_html__( 'Hide', 'jupiterx-core' ),
				'prefix_class' => 'equal-height--',
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'custom_height',
			[
				'label' => esc_html__( 'Custom Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 440,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--custom-card-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'equal_height' => 'yes',
					'layout!' => [ 'standard', 'social-overlay' ],
				],
			]
		);

		$this->add_responsive_control(
			'image_custom_height',
			[
				'label' => esc_html__( 'Image Custom Height', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--image-custom-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'layout' => [ 'standard', 'social-overlay' ],
				],
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'team_name_tag',
			[
				'label' => esc_html__( 'Team Name Tag', 'jupiterx-core' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => esc_html__( 'H1', 'jupiterx-core' ),
					'h2' => esc_html__( 'H2', 'jupiterx-core' ),
					'h3' => esc_html__( 'H3', 'jupiterx-core' ),
					'h4' => esc_html__( 'H4', 'jupiterx-core' ),
					'h5' => esc_html__( 'H5', 'jupiterx-core' ),
					'h6' => esc_html__( 'H6', 'jupiterx-core' ),
					'div' => esc_html__( 'div', 'jupiterx-core' ),
					'span' => esc_html__( 'span', 'jupiterx-core' ),
					'p' => esc_html__( 'p', 'jupiterx-core' ),
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_content_content_order_section() {
		$this->start_controls_section(
			'section_content_content_order_settings',
			[
				'label' => esc_html__( 'Content Order', 'jupiterx-core' ),
				'condition' => [
					'layout!' => 'creative',
				],
			]
		);

		$this->add_control(
			'name_order',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 1,
				'selectors' => [
					'{{WRAPPER}} .team-member--name' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'position_order',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 2,
				'selectors' => [
					'{{WRAPPER}} .team-member--position' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'description_order',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 3,
				'selectors' => [
					'{{WRAPPER}} .team-member--description' => 'order: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icons_order',
			[
				'label' => esc_html__( 'Social Icons', 'jupiterx-core' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5,
				'step' => 1,
				'default' => 4,
				'selectors' => [
					'{{WRAPPER}} .social-icons-wrapper' => 'order: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'social-overlay',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_container_section() {
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.card-layout--standard .team-member, {{WRAPPER}}.card-layout--social-overlay .team-member, {{WRAPPER}}.card-layout--creative .team-member--content, {{WRAPPER}}.card-layout--full-overlay .team-member--content, {{WRAPPER}}.card-layout--detail-slide .team-member--content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .team-member',
			]
		);

		$this->add_control(
			'container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'container_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .team-member',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_profile_picture_section() {
		$this->start_controls_section(
			'section_style_profile_picture',
			[
				'label' => esc_html__( 'Profile Picture', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'profile_picture_width',
			[
				'label' => esc_html__( 'Image Width', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .team-member .team-member--image-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => [ 'standard', 'social-overlay' ],
				],
			]
		);

		$this->add_responsive_control(
			'profile_picture_alignment',
			[
				'label' => esc_html__( 'Alignment', 'jupiterx-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .team-member--image-container' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'layout' => [ 'standard', 'social-overlay' ],
				],
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => Controls_Manager::DIVIDER,
				'condition' => [
					'layout' => [ 'standard', 'social-overlay' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'profile_picture_border',
				'selector' => '{{WRAPPER}} .team-member .team-member--image-wrapper',
			]
		);

		$this->add_control(
			'profile_picture_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member .team-member--image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'profile_picture_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member .team-member--image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'layout!' => [ 'creative', 'full-overlay' ],
				],
			]
		);

		$this->start_controls_tabs(
			'profile_picture_style_tabs'
		);

		$this->start_controls_tab(
			'profile_picture_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'profile_picture_normal_custom_css_filters',
				'selector' => '{{WRAPPER}} .team-member .team-member--image-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'profile_picture_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'profile_picture_hover_custom_css_filters',
				'selector' => '{{WRAPPER}} .team-member:hover .team-member--image-wrapper',
			]
		);

		$this->add_control(
			'profile_picture_css_filter_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'range' => [
					's' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 's',
					'size' => 0.4,
				],
				'selectors' => [
					'{{WRAPPER}} .team-member .team-member--image-wrapper' => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr6',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'jupiterx-core' ),
					'multiply' => esc_html__( 'Multiply', 'jupiterx-core' ),
					'screen' => esc_html__( 'Screen', 'jupiterx-core' ),
					'overlay' => esc_html__( 'Overlay', 'jupiterx-core' ),
					'darken' => esc_html__( 'Darken', 'jupiterx-core' ),
					'lighten' => esc_html__( 'Lighten', 'jupiterx-core' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'jupiterx-core' ),
					'saturation' => esc_html__( 'Saturation', 'jupiterx-core' ),
					'color' => esc_html__( 'Color', 'jupiterx-core' ),
					'difference' => esc_html__( 'Difference', 'jupiterx-core' ),
					'exclusion' => esc_html__( 'Exclusion', 'jupiterx-core' ),
					'hue' => esc_html__( 'Hue', 'jupiterx-core' ),
					'luminosity' => esc_html__( 'Luminosity', 'jupiterx-core' ),
				],
				'selectors' => [
					'{{WRAPPER}} .team-member img' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_name_section() {
		$this->start_controls_section(
			'section_style_name',
			[
				'label' => esc_html__( 'Name', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .team-member--name a' => 'color: {{VALUE}}',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .team-member--name',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'name_text_shadow',
				'selector' => '{{WRAPPER}} .team-member--name',
			]
		);

		$this->add_responsive_control(
			'name_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'name_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_position_section() {
		$this->start_controls_section(
			'section_style_position',
			[
				'label' => esc_html__( 'Position', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'position_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--position' => 'color: {{VALUE}}',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'position_typography',
				'selector' => '{{WRAPPER}} .team-member--position',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'position_text_shadow',
				'selector' => '{{WRAPPER}} .team-member--position',
			]
		);

		$this->add_responsive_control(
			'position_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'position_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_description_section() {
		$this->start_controls_section(
			'section_style_description',
			[
				'label' => esc_html__( 'Description', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--description' => 'color: {{VALUE}}',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .team-member--description',
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .team-member--description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_social_icons_section() {
		$this->start_controls_section(
			'section_style_social_icons',
			[
				'label' => esc_html__( 'Social Icons', 'jupiterx-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'social_icons_margin',
			[
				'label' => esc_html__( 'Margin', 'jupiterx-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .social-icons-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'social_icons_shape',
			[
				'label' => esc_html__( 'Shape', 'jupiterx-core' ),
				'type' => Controls_Manager::SELECT,
				'prefix_class' => 'social-icon-shape--',
				'default' => 'rounded',
				'options' => [
					'simple' => esc_html__( 'Simple (no background)', 'jupiterx-core' ),
					'rounded' => esc_html__( 'Rounded', 'jupiterx-core' ),
					'square' => esc_html__( 'Square', 'jupiterx-core' ),
					'circle' => esc_html__( 'Circle', 'jupiterx-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'social_icons_size',
			[
				'label' => esc_html__( 'Size', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .team-member--social i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .team-member--social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .team-member--social a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_icons_padding',
			[
				'label' => esc_html__( 'Padding', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 6,
				],
				'selectors' => [
					'{{WRAPPER}} .team-member--social a' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_icons_spacing',
			[
				'label' => esc_html__( 'Spacing', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .social-icons-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'social_icons_rows_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'jupiterx-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .social-icons-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'social_icons_style_tabs'
		);

		$this->start_controls_tab(
			'social_icons_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'social_icons_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .team-member--social svg' => 'fill: {{VALUE}} !important;',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social a' => 'background-color: {{VALUE}} !important;',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_simple_color',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .team-member--social svg' => 'fill: {{VALUE}}',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_border_options',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'social_icons_border',
				'selector' => '{{WRAPPER}} .team-member--social a',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_icons_box_shadow',
				'selector' => '{{WRAPPER}} .team-member--social a',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_icons_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'social_icons_primary_color_hover',
			[
				'label' => esc_html__( 'Primary Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social:hover i' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .team-member--social:hover svg' => 'fill: {{VALUE}} !important;',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_secondary_color_hover',
			[
				'label' => esc_html__( 'Secondary Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social:hover a' => 'background-color: {{VALUE}} !important;',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_simple_color_hover',
			[
				'label' => esc_html__( 'Color', 'jupiterx-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .team-member--social:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .team-member--social:hover svg' => 'fill: {{VALUE}}',
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'social_icons_shape' => 'simple',
				],
			]
		);

		$this->add_control(
			'social_icons_border_options_hover',
			[
				'label' => esc_html__( 'Border', 'jupiterx-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'social_icons_border_hover',
				'selector' => '{{WRAPPER}} .team-member--social:hover a',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'social_icons_box_shadow_hover',
				'selector' => '{{WRAPPER}} .team-member--social:hover a',
				'condition' => [
					'social_icons_shape!' => 'simple',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		add_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ], 2, 10 );

		$settings = $this->get_settings_for_display();
		?>
		<div class="raven-team-members-wrapper">
			<?php
			foreach ( $settings['team_members'] as $team_member ) :
				$this->add_render_attribute(
					"team-member-{$team_member['_id']}",
					'class',
					[
						"elementor-repeater-item-{$team_member['_id']}",
						'team-member',
					]
				);
				?>
				<div <?php $this->print_render_attribute_string( "team-member-{$team_member['_id']}" ); ?>>
					<?php $this->render_team_member_card( $settings, $team_member ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public function render_team_member_card( $settings, $member ) {
		ob_start();

		if ( in_array( $settings['layout'], [ 'standard', 'social-overlay' ], true ) ) {
			echo '<div class="team-member--image-container">';
		}
		?>
		<div class="team-member--image-wrapper">
			<?php
			if ( 'social-overlay' === $settings['layout'] ) {
				echo $this->get_social_icons( $member );
			}

			if ( isset( $member['link_type'] ) && 'image' === $member['link_type'] ) {
				$link_attributes = $this->get_member_link_attributes( $member );

				echo '<a ' . $link_attributes . '>';
			}
			?>
			<img
				class="team-member--image"
				src="<?php echo $this->get_profile_picture_url( $settings, $member ); ?>"
				alt="<?php echo $this->get_profile_picture_alt( $member ); ?>"
			>
			<?php
			if ( isset( $link_attributes ) ) {
				echo '</a>';
			}
			?>
		</div>
		<?php
		if ( in_array( $settings['layout'], [ 'standard', 'social-overlay' ], true ) ) {
			echo '</div>';
		}

		echo $this->get_member_content( $settings, $member );

		$html = ob_get_clean();

		echo wp_kses_post( $html );

		remove_filter( 'wp_kses_allowed_html', [ $this, 'allow_tags_on_wp_kses_post' ] );
	}

	/**
	 * Returns the content of the member.
	 *
	 * @param $settings
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_member_content( $settings, $member ) {
		ob_start();
		?>
		<div class="team-member--content">
			<?php
			if (
				isset( $member['link_type'] ) &&
				'image' === $member['link_type'] &&
				in_array( $settings['layout'], [ 'creative', 'full-overlay' ], true )
			) {
				$link_attributes = $this->get_member_link_attributes( $member );

				echo '<a ' . $link_attributes . '>';
			}

			echo $this->get_member_name( $settings, $member );
			echo $this->get_position( $member );
			echo $this->get_description( $member );

			if ( isset( $link_attributes ) ) {
				echo '</a>';
			}

			if ( 'social-overlay' !== $settings['layout'] ) {
				echo $this->get_social_icons( $member );
			}
			?>
		</div>
		<?php
		return wp_kses_post( ob_get_clean() );
	}

	/**
	 * Returns all the custom attributes of member link.
	 *
	 * @return array
	 * @since 3.0.0
	 */
	private function get_all_custom_attributes() {
		$settings          = $this->get_settings();
		$custom_attributes = [];
		$separated         = [];

		foreach ( $settings['team_members'] as $field ) {
			if ( 'none' !== $field['link_type'] ) {
				continue;
			}

			if ( ! empty( $field['member_link']['custom_attributes'] ) ) {
				$custom_attributes[] = $field['member_link']['custom_attributes'];
			}
		}

		if ( ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $attribute ) {
				$attribute = explode( ',', $attribute );

				foreach ( $attribute as $attr ) {
					$separated[ explode( '|', $attr )[0] ] = true;
				}
			}
		}

		return $separated;
	}

	/**
	 * Allows all the custom attributes in member link with other required tags and attributes.
	 *
	 * @param $tags
	 * @param $context
	 *
	 * @return mixed
	 * @since 3.0.0
	 */
	public function allow_tags_on_wp_kses_post( $tags, $context ) {
		$custom_attributes = $this->get_all_custom_attributes();

		if ( 'post' === $context ) {
			$tags['svg'] = [
				'xmlns' => true,
				'fill' => true,
				'viewbox' => true,
				'role' => true,
				'aria-hidden' => true,
				'focusable' => true,
			];

			$tags['path'] = [
				'd' => true,
				'fill' => true,
			];

			$tags['a'] = array_merge(
				[
					'href' => true,
					'class' => true,
					'title' => true,
					'target' => true,
					'rel' => true,
				],
				$custom_attributes
			);
		}

		return $tags;
	}

	/**
	 * Returns the name of the member with the proper html.
	 *
	 * @param $settings array
	 * @param $member array
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_member_name( $settings, $member ) {
		$tag  = $settings['team_name_tag'];
		$html = sprintf(
			'<%1$s class="team-member--name">%2$s</%1$s>',
			esc_html( $tag ),
			esc_html( $member['name'] )
		);

		if ( 'title' === $member['link_type'] ) {
			$link = $this->get_member_link_attributes( $member );
			$html = sprintf(
				'<%1$s class="team-member--name"><a %2$s>%3$s</a></%1$s>',
				esc_html( $tag ),
				esc_attr( $link ),
				esc_html( $member['name'] )
			);
		}

		return $html;
	}

	/**
	 * Returns the position of the member with the proper html.
	 *
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_position( $member ) {
		return sprintf(
			'<div class="team-member--position" title="%1$s">%1$s</div>',
			esc_attr( $member['position'] )
		);
	}

	/**
	 * Returns the description of the member with the proper html.
	 *
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_description( $member ) {
		return sprintf(
			'<p class="team-member--description">%s</p>',
			esc_html( $member['description'] )
		);
	}

	/**
	 * Returns the url of the member image.
	 *
	 * @param $settings
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_profile_picture_url( $settings, $member ) {
		if ( empty( $member['profile_picture']['id'] ) ) {
			return $member['profile_picture']['url'];
		}

		return Group_Control_Image_Size::get_attachment_image_src( $member['profile_picture']['id'], 'image', $settings );
	}

	/**
	 * Returns the alt of the member image.
	 *
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_profile_picture_alt( $member ) {
		if ( ! empty( $member['profile_picture']['alt'] ) ) {
			return esc_attr( $member['profile_picture']['alt'] );
		}

		return '';
	}

	/**
	 * Returns the custom attributes of the team member link.
	 *
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_member_link_attributes( $member ) {
		if ( 'none' !== $member['link_type'] && ! empty( $member['member_link']['url'] ) ) {
			$this->add_link_attributes( 'member_link_' . $member['_id'], $member['member_link'] );

			return $this->get_render_attribute_string( 'member_link_' . $member['_id'] );
		}

		return '';
	}

	/**
	 * Returns all the icons of the member.
	 *
	 * @param $member
	 *
	 * @return string
	 * @since 3.0.0
	 */
	private function get_social_icons( $member ) {
		$html = '';

		foreach ( $this->social_links as $social => $icon_class ) {
			if ( empty( $member[ $social ] ) ) {
				continue;
			}

			ob_start();
			Icons_Manager::render_icon(
				[
					'value' => 'fab fa-' . $icon_class,
					'library' => 'fa-solid',
				],
				[ 'aria-hidden' => 'true' ]
			);

			$icon = ob_get_clean();
			$link = esc_html( $member[ $social ] );

			if ( 'email' === $social ) {
				ob_start();

				Icons_Manager::render_icon(
					[
						'value' => 'fas fa-' . $icon_class,
						'library' => 'fa-solid',
					],
					[ 'aria-hidden' => 'true' ]
				);

				$icon = ob_get_clean();
				$link = 'mailto:' . esc_html( $member['email'] );
			}

			$html .= sprintf(
				'<div class="team-member--social"><a class="social-%1$s" title="%1$s" href="%2$s">%3$s</a></div>',
				esc_attr( $social ),
				esc_attr( $link ),
				$icon
			);
		}

		return '<div class="social-icons-wrapper">' . $html . '</div>';
	}
}


