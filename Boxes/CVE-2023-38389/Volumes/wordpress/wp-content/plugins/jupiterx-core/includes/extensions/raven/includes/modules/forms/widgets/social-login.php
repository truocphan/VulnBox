<?php

namespace JupiterX_Core\Raven\Modules\Forms\Widgets;

defined( 'ABSPATH' ) || die();

use Elementor\Icons_Manager;
use JupiterX_Core\Raven\Modules\Forms\Widgets\Form;
use JupiterX_Core\Raven\Utils;
use JupiterX_Core\Raven\Modules\Forms\Classes\Social_Login_Handler\{ Google, Facebook, Twitter };

/**
 * Social login widget.
 *
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.NPathComplexity)
*/
class Social_Login extends Form {
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script(
			'jupiterx-raven-social-facebook',
			'https://connect.facebook.net/en_US/sdk.js',
			[ 'elementor-frontend' ],
			'1.0.0',
			true
		);

		wp_register_script(
			'jupiterx-raven-social-google',
			'https://apis.google.com/js/api:client.js',
			[ 'jquery' ],
			'1.0.0',
			false
		);
	}

	public function get_name() {
		return 'raven-social-login';
	}

	public function get_title() {
		return __( 'Social Login', 'jupiterx-core' );
	}

	public function get_icon() {
		return 'raven-element-icon raven-element-icon-social-login';
	}

	public function get_script_depends() {
		return [ 'jupiterx-raven-social-facebook', 'jupiterx-raven-social-google' ];
	}

	public static function is_active() {
		return true;
	}

	protected function register_controls() {
		$this->register_social_login_content_section_controls();
		$this->register_social_login_box_style_controls();
		$this->register_facebook_controls();
		$this->register_twitter_controls();
		$this->register_google_controls();
		$this->register_social_login_icon_controls();
	}

	private function register_social_login_content_section_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => __( 'Settings', 'jupiterx-core' ),
			]
		);

		$this->add_control(
			'google_note',
			[
				'type'            => 'raw_html',
				/* translators: %1$s: open tag %2$s: url %3$s: close tag */
				'raw'             => sprintf( __( '%1$s Before using social login widget, Please set your api key in %2$s Settings %3$s', 'jupiterx-core' ), '<small>', '<a target="_blank" href="admin.php?page=elementor#tab-raven">JupiterX<i class="fa fa-external-link-square"></i></a>', '</small>' ),
				'content_classes' => 'custom-social-login-alert',
			]
		);

		$this->add_control(
			'enable_google',
			[
				'label'        => __( 'Enable Google', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => __( 'Show', 'jupiterx-core' ),
				'label_off'    => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'google_label',
			[
				'label'     => __( 'Label', 'jupiterx-core' ),
				'type'      => 'text',
				'default'   => __( 'Sign in with Google', 'jupiterx-core' ),
				'condition' => [
					'enable_google' => 'yes',
				],
			]
		);

		$this->add_control(
			'google_icon',
			[
				'label'     => __( 'Icon', 'jupiterx-core' ),
				'type'      => 'icons',
				'default'   => [
					'value'   => 'fab fa-google',
					'library' => 'fa-brands',
				],
				'condition' => [
					'enable_google' => 'yes',
				],
			]
		);

		$this->add_control(
			'hr_1',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'enable_facebook',
			[
				'label'        => __( 'Enable Facebook', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => __( 'Show', 'jupiterx-core' ),
				'label_off'    => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'facebook_label',
			[
				'label'     => __( 'Label', 'jupiterx-core' ),
				'type'      => 'text',
				'default'   => __( 'Sign in with Facebook', 'jupiterx-core' ),
				'condition' => [
					'enable_facebook' => 'yes',
				],
			]
		);

		$this->add_control(
			'facebook_icon',
			[
				'label'     => __( 'Icon', 'jupiterx-core' ),
				'type'      => 'icons',
				'default'   => [
					'value'   => 'fab fa-facebook',
					'library' => 'fa-brands',
				],
				'condition' => [
					'enable_facebook' => 'yes',
				],
			]
		);

		$this->add_control(
			'hr_2',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'enable_twitter',
			[
				'label'        => __( 'Enable Twitter', 'jupiterx-core' ),
				'type'         => 'switcher',
				'label_on'     => __( 'Show', 'jupiterx-core' ),
				'label_off'    => __( 'Hide', 'jupiterx-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'twitter_label',
			[
				'label'     => __( 'Label', 'jupiterx-core' ),
				'type'      => 'text',
				'default'   => __( 'Sign in with Twitter', 'jupiterx-core' ),
				'condition' => [
					'enable_twitter' => 'yes',
				],
			]
		);

		$this->add_control(
			'twitter_icon',
			[
				'label'     => __( 'Icon', 'jupiterx-core' ),
				'type'      => 'icons',
				'default'   => [
					'value'   => 'fab fa-twitter',
					'library' => 'fa-brands',
				],
				'condition' => [
					'enable_twitter' => 'yes',
				],
			]
		);

		$this->add_control(
			'hr_3',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'label' => __( 'Redirect After Login URL', 'jupiterx-core' ),
				'type' => 'url',
				'placeholder' => __( 'Enter your web address', 'jupiterx-core' ),
				'dynamic' => [
					'active' => false,
				],
				'options' => false,
				'label_block' => true,
			]
		);

		$this->add_control(
			'display_method',
			[
				'label'      => __( 'Inline Buttons', 'jupiterx-core' ),
				'type'       => 'choose',
				'options'    => [
					'flex' => [
						'title' => __( 'Enable', 'jupiterx-core' ),
						'icon'  => 'eicon-form-vertical',
					],
					'grid' => [
						'title' => __( 'Disable', 'jupiterx-core' ),
						'icon'  => 'eicon-radio',
					],
				],
				'default'    => 'flex',
				'toggle'     => true,
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'display: {{display_method}};',
				],
			]
		);

		$this->add_control(
			'actions',
			[
				'label' => __( 'Actions', 'jupiterx-core' ),
				'type' => 'text',
				'classes' => 'elementor-control-type-hidden',
				'default' => [ 'social_login' ],
			]
		);

		$this->end_controls_section();
	}

	private function register_social_login_box_style_controls() {
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Form', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'padding_style',
			[
				'label'      => __( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_4',
			[
				'type' => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'     => 'border',
				'label'    => __( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .raven-social-login-wrap',
			]
		);

		$this->add_control(
			'wrap_border_radius',
			[
				'label'      => __( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_5',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'col-spacing',
			[
				'label' => __( 'Column Spacing', 'jupiterx-core' ),
				'type'  => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row-spacing',
			[
				'label'      => __( 'Row Spacing', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 7,
				],
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_6',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'text_align',
			[
				'label'      => __( 'Alignment', 'jupiterx-core' ),
				'type'       => 'choose',
				'options'    => [
					'left'   => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'  => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'    => 'center',
				'toggle'     => true,
				'selectors'  => [
					'{{WRAPPER}} .raven-social-login-wrap' => 'justify-content: {{text_align}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name'     => 'content_social_login_typography',
				'label'    => __( 'Typography', 'jupiterx-core' ),
				'scheme'   => '4',
				'selector' => '{{WRAPPER}} .raven-social-login-wrap , .raven-social-login-wrap span',
			]
		);

		$this->end_controls_section();
	}

	private function register_facebook_controls() {
		$this->start_controls_section(
			'style_section_facebook',
			[
				'label' => __( 'Facebook', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'padding_style_facebook',
			[
				'label'      => __( 'Text Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-facebook-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_7',
			[
				'type' => 'divider',
			]
		);

		$this->start_controls_tabs(
			'style_fb_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab_fb',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_fb',
				'label'     => __( 'Button Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper',
			]
		);

		$this->add_control(
			'hr_8',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_fb',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper',
			]
		);

		$this->add_control(
			'hr_9',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'fb_color_icon',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-facebook-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-facebook-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'fb_color_text',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-facebook-wrapper span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_10',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_fb',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab_fb',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_fb_hover',
				'label'     => __( 'Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_11',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_fb_hover',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_12',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'fb_color_hover_icon',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-facebook-wrapper:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-facebook-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'fb_color_hover_text',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-facebook-wrapper:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_13',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_fb_hover',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-facebook-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_twitter_controls() {
		$this->start_controls_section(
			'style_section_twitter',
			[
				'label' => __( 'Twitter', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'padding_style_twitter_normal',
			[
				'label'      => __( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-twitter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_14',
			[
				'type'      => 'divider',
			]
		);

		$this->start_controls_tabs(
			'style_tw_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab_tw',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_tw',
				'label'     => __( 'Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper',
			]
		);

		$this->add_control(
			'hr_15',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_tw',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper',
			]
		);

		$this->add_control(
			'hr_16',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'tw_color_icon',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-twitter-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-twitter-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tw_color_text',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-twitter-wrapper span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_17',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_tw',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab_tw',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_tw_hover',
				'label'     => __( 'Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_18',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_tw_hover',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_19',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'tw_color_hover',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-twitter-wrapper:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-twitter-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tw_color_text_hover',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-twitter-wrapper:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_20',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_tw_hover',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-twitter-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_google_controls() {
		$this->start_controls_section(
			'style_section_google',
			[
				'label' => __( 'Google', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_control(
			'padding_style_google',
			[
				'label'      => __( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .raven-google-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_21',
			[
				'type'      => 'divider',
			]
		);

		$this->start_controls_tabs(
			'style_google_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab_google',
			[
				'label' => __( 'Normal', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_google',
				'label'     => __( 'Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-google-wrapper',
			]
		);

		$this->add_control(
			'hr_22',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_google',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-google-wrapper',
			]
		);

		$this->add_control(
			'hr_23',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'google_color',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .raven-google-wrapper i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-google-wrapper svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'google_color_text',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-google-wrapper span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_24',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_google',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-google-wrapper',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab_google',
			[
				'label' => __( 'Hover', 'jupiterx-core' ),
			]
		);

		$this->add_group_control(
			'background',
			[
				'name'      => 'background_google_hover',
				'label'     => __( 'Background', 'jupiterx-core' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .raven-google-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_25',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'      => 'border_google_hover',
				'label'     => __( 'Border', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-google-wrapper:hover',
			]
		);

		$this->add_control(
			'hr_26',
			[
				'type'      => 'divider',
			]
		);

		$this->add_control(
			'google_color_hover',
			[
				'label'     => __( 'Icon Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-google-wrapper:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .raven-google-wrapper:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'google_color_text_hover',
			[
				'label'     => __( 'Text Color', 'jupiterx-core' ),
				'type'      => 'color',
				'selectors' => [
					'{{WRAPPER}} .raven-google-wrapper:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hr_27',
			[
				'type'      => 'divider',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name'      => 'box_shadow_google_hover',
				'label'     => __( 'Box Shadow', 'jupiterx-core' ),
				'selector'  => '{{WRAPPER}} .raven-google-wrapper:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_social_login_icon_controls() {
		$this->start_controls_section(
			'style_social_icons',
			[
				'label' => __( 'Icons', 'jupiterx-core' ),
				'tab'   => 'style',
			]
		);

		$this->add_responsive_control(
			'social_media_icon_width',
			[
				'label'      => __( 'Icon Width', 'jupiterx-core' ),
				'type'       => 'slider',
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .icon-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_28',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'icon_style_padding',
			[
				'label'      => __( 'Padding', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_style_spacing',
			[
				'label'      => __( 'Spacing', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .icon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_29',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'social_media_icon_sizing',
			[
				'label'      => __( 'Icon Size', 'jupiterx-core' ),
				'type'       => 'slider',
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .raven-social-single-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .raven-social-single-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr_30',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'      => __( 'Icon Position', 'jupiterx-core' ),
				'type'       => 'choose',
				'options'    => [
					'row'   => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row-reverse' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'eicon-h-align-right',
					],
					'column' => [
						'title' => __( 'Top', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'column-reverse' => [
						'title' => __( 'Bottom', 'jupiterx-core' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default'    => 'row',
				'toggle'     => true,
				'selectors'  => [
					'{{WRAPPER}} .raven-social-single-wrapper' => 'flex-direction: {{icon_position}}',
				],
			]
		);

		$this->add_control(
			'hr_31',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'icon_alignment',
			[
				'label'      => __( 'Alignment', 'jupiterx-core' ),
				'type'       => 'choose',
				'options'    => [
					'left'   => [
						'title' => __( 'Left', 'jupiterx-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jupiterx-core' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'jupiterx-core' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default'    => 'center',
				'toggle'     => true,
				'selectors'  => [
					'{{WRAPPER}} .icon-wrapper' => 'text-align : {{icon_alignment}};',
				],
			]
		);

		$this->add_control(
			'hr_32',
			[
				'type' => 'divider',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name'     => 'border_icons',
				'label'    => __( 'Border', 'jupiterx-core' ),
				'selector' => '{{WRAPPER}} .icon-wrapper',
			]
		);

		$this->add_control(
			'hr_33',
			[
				'type' => 'divider',
			]
		);

		$this->add_control(
			'btn_border_radius',
			[
				'label'      => __( 'Border Radius', 'jupiterx-core' ),
				'type'       => 'dimensions',
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( is_user_logged_in() && ! current_user_can( 'administrator' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		Google::html();
		Facebook::html();
		?>
		<div class="raven-social-login-wrap">
			<?php
				if ( 'yes' === $settings['enable_google'] ) : ?>
					<div class="raven-social-single-wrapper raven-google-wrapper">
						<?php
						if ( $settings['google_icon'] ) {
							echo '<div class="icon-wrapper google">';
							Icons_Manager::render_icon( $settings['google_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
						}
						?>
						<div class="google raven-social-medias btn btn-sm text-center" id="jupiterx-raven-social-login-widget-google">
							<span><?php echo $settings['google_label']; ?></span>
						</div>
					</div>
					<?php
				endif;
				if ( 'yes' === $settings['enable_facebook'] ) : ?>
					<div class="raven-social-single-wrapper raven-facebook-wrapper">
						<?php
						if ( $settings['facebook_icon'] ) {
							echo '<div class="icon-wrapper facebook">';
							Icons_Manager::render_icon( $settings['facebook_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
						}
						?>
						<div class="facebook raven-social-medias btn btn-sm text-center" id="jupiterx-raven-social-login-widget-facebook">
							<span><?php echo esc_html( $settings['facebook_label'] ); ?></span>
						</div>
					</div>
					<?php
				endif;
				if ( 'yes' === $settings['enable_twitter'] ) : ?>
					<div class="raven-social-single-wrapper raven-twitter-wrapper">
						<?php
						if ( $settings['twitter_icon'] ) {
							echo '<div class="icon-wrapper twitter">';
							Icons_Manager::render_icon( $settings['twitter_icon'], [ 'aria-hidden' => 'true' ] );
							echo '</div>';
						}
						?>
						<div class="twitter raven-social-medias btn btn-sm text-center" id="jupiterx-raven-social-login-widget-twitter">
							<span><?php echo esc_html( $settings['twitter_label'] ); ?></span>
						</div>
					</div>
					<?php
				endif;
			?>
			<form class="jx-raven-social-login-form">
				<input type="hidden" id="jx-raven-social-widget-post" value="<?php echo Utils::get_current_post_id(); ?>">
				<input type="hidden" id="jx-raven-social-widget-form" value="<?php echo $this->get_id(); ?>">
			</form>
		</div>
		<p class="jx-social-login-errors-wrapper"></p>
		<?php
			if ( current_user_can( 'administrator' ) ) : ?>
				<?php if ( empty( get_option( 'elementor_raven_facebook_app_id' ) ) && 'yes' === $settings['enable_facebook'] ) : ?>
					<div class="elementor-alert elementor-alert-danger raven-social-login-alert raven-social-login-error-box">
						<?php
							echo sprintf(
								/* translators: %s: setting page link. */
								esc_html__( 'Set Facebook by adding APP ID in the %s', 'jupiterx-core' ),
								'<a href="' . admin_url( '/admin.php?page=elementor#tab-raven' ) . '" target="_blank"> ' .  esc_html__( 'Facebook API settings', 'jupiterx-core' ) . ' </a>' // phpcs:ignore
							);
						?>
					</div>
				<?php endif; ?>
				<?php if ( empty( get_option( 'elementor_raven_google_client_id' ) ) && 'yes' === $settings['enable_google'] ) : ?>
					<div class="elementor-alert elementor-alert-danger raven-social-login-alert raven-social-login-error-box">
						<?php
							echo sprintf(
								/* translators: %s: setting page link. */
								esc_html__( 'Set Google by adding Client ID in the %s', 'jupiterx-core' ),
								'<a href="' . admin_url( '/admin.php?page=elementor#tab-raven' ) . '" target="_blank"> ' .  esc_html__( 'Google API settings', 'jupiterx-core' ) . ' </a>' // phpcs:ignore
							);
						?>
					</div>
				<?php endif; ?>
				<?php
					if ( ( empty( get_option( Twitter::APP_KEY ) ) || empty( get_option( Twitter::APP_SECRET ) ) || empty( get_option( Twitter::ACCESS_TOKEN ) ) || empty( get_option( Twitter::ACCESS_SECRET ) ) ) && 'yes' === $settings['enable_google'] ) : ?>
					<div class="elementor-alert elementor-alert-danger raven-social-login-alert raven-social-login-error-box">
						<?php
							echo sprintf(
								/* translators: %s: setting page link. */
								esc_html__( 'Set Twitter by adding API Key, API Secret Key, Access Token, and Access Token Secret in the %s', 'jupiterx-core' ),
								'<a href="' . admin_url( '/admin.php?page=elementor#tab-raven' ) . '" target="_blank"> ' .  esc_html__( 'Twitter API settings', 'jupiterx-core' ) . ' </a>' // phpcs:ignore
							);
						?>
					</div>
				<?php endif; ?>
				<div class="elementor-alert elementor-alert-danger raven-social-login-alert raven-social-login-error-box">
					<?php echo esc_html__( 'This element is hidden for logged-in users and visible only to logged-out users and also administrator for demo purposes.', 'jupiterx-core' ); ?>
				</div>
		<?php endif;
	}
}
