<?php

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class StmLmsProfileAuthLinks extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {

		parent::__construct( $data, $args );
		wp_enqueue_style( 'profile-auth-links-style' );
	}

	public function get_name() {
		return 'stm_lms_pro_site_authorization_links';
	}

	public function get_title() {
		return esc_html__( 'Site Authorization links', 'masterstudy-lms-learning-management-system' );
	}


	public function get_icon() {
		return 'stmlms-authlinks lms-icon';
	}

	public function get_categories() {
		return array( 'stm_lms' );
	}

	public function get_style_depends() {
		return array( 'profile-auth-links-style' );
	}


	/** Register General Controls */

	protected function register_controls() {
		$this->content_tab_profile_icon();
		$this->content_tab_auth_links();
	}

	protected function content_tab_profile_icon() {
		$this->start_controls_section(
			'profile_general_section',
			array(
				'label' => esc_html__( 'Login/Sign up', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'profile_lms_icon',
			array(
				'name'  => 'profile_lms_icon_selected',
				'label' => esc_html__( 'Icon', 'masterstudy-lms-learning-management-system' ),
				'type'  => Controls_Manager::ICONS,
			)
		);
		$this->add_responsive_control(
			'profile_lms_icon_section_width',
			array(
				'label'          => esc_html__( 'Profile Width', 'masterstudy-lms-learning-management-system' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
					'size' => 30,
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}  span.ms-lms-authorization-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'profile_lms_icon_section_height',
			array(
				'label'          => esc_html__( 'Profile Height', 'masterstudy-lms-learning-management-system' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
					'size' => 30,
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} span.ms-lms-authorization-icon' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'profile_icon_section_border',
				'selector'  => '{{WRAPPER}} span.ms-lms-authorization-icon',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'masterstudy-lms-learning-management-system' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} span.ms-lms-authorization-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'profile_icon_section_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} span.ms-lms-authorization-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);
		$this->add_control(
			'auth_links_btn_text',
			array(
				'label'   => esc_html__( 'Button Text', 'masterstudy-lms-learning-management-system' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'login/sign up', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'auth_links_btn_link',
			array(
				'label'       => esc_html__( 'Button Link', 'masterstudy-lms-learning-management-system' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => site_url() . '/user-account',
				'default'     => array(
					'url' => site_url() . '/user-account',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'auth_links__btn_typography',
				'label'    => __( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} span.ms-lms-authorization-title',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'profile_general_section_logged',
			array(
				'label' => esc_html__( 'Sign In', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_responsive_control(
			'profile_lms_icon_section_width_logged_icon',
			array(
				'label'          => esc_html__( 'Profile icon size', 'masterstudy-lms-learning-management-system' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
					'size' => 12,
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}}  .stm_lms_account_dropdown .dropdown button i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'auth_links__btn_typography_logged_profile',
				'label'    => __( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}}  .stm_lms_account_dropdown .dropdown button .login_name',
			)
		);

		$this->end_controls_section();
	}

	protected function content_tab_auth_links() {
		$this->start_controls_section(
			'auth_style_section',
			array(
				'label' => esc_html__( 'Sign in', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs(
			'general_auth_links_logged_tabs'
		);

		$this->start_controls_tab(
			'general_event_btn_logged_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'general_auth_links_color_logged_text',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button .caret' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button .login_name' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'general_auth_links_color_logged',
			array(
				'label'     => esc_html__( 'Background Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'general_auth_links_tab_focus_logged',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'general_auth_links_color_focus_logged_text',
			array(
				'label'     => esc_html__( 'Text Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button:hover .caret' => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button:hover .login_name' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'general_auth_links_color_focus_logged',
			array(
				'label'     => esc_html__( 'Background Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm_lms_account_dropdown .dropdown button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		$this->start_controls_section(
			'auth_style_section_sing_in',
			array(
				'label' => esc_html__( 'Login/Sign up', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'profile_icon_section_bg_color',
			array(
				'label'     => esc_html__( 'Icon Background', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#195EC8',
				'selectors' => array(
					'{{WRAPPER}} span.ms-lms-authorization-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'profile_icon_section_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} span.ms-lms-authorization-icon i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs(
			'general_auth_links_tabs'
		);

		$this->start_controls_tab(
			'general_event_btn_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'general_auth_links_color',
			array(
				'label'     => esc_html__( 'Button Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} span.ms-lms-authorization-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'general_auth_links_tab_focus',
			array(
				'label' => esc_html__( 'Hover', 'masterstudy-lms-learning-management-system' ),
			)
		);

		$this->add_control(
			'general_auth_links_color_focus',
			array(
				'label'     => esc_html__( 'Button Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} span.ms-lms-authorization-title:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		//** STM_LMS_Templates */

		$settings = $this->get_settings_for_display();
		if ( ! is_user_logged_in() || isset( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) ) {
			$url = '';
			if ( class_exists( 'STM_LMS_User' ) ) {
				$url = \STM_LMS_User::login_page_url();
			}
			?>
			<a href="<?php echo esc_url( $url ); ?>" class="ms-lms-authorization">
				<span class="ms-lms-authorization-icon">
					<i class="<?php echo esc_attr( $settings['profile_lms_icon']['value'] ); ?>" aria-hidden="true"></i>
				</span>
				<a href="<?php echo esc_url( $settings['auth_links_btn_link']['url'] ); ?>">
					<span class="ms-lms-authorization-title">
						<?php echo esc_html( $settings['auth_links_btn_text'] ); ?>
					</span>
				</a>
			</a>

			<?php

		} else {
			\STM_LMS_Templates::show_lms_template( 'global/account-dropdown' );
			\STM_LMS_Templates::show_lms_template( 'global/settings-button' );
		}
	}


	/**
	 * Render the widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 * @since 1.0.0
	 * @access protected
	 */

	protected function content_template() {

	}
}
