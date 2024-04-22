<?php

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class StmLmsProTestimonials extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'lms-testimonials-carousel-slider', STM_LMS_URL . 'assets/vendors/swiper-bundle.min.css', array(), STM_LMS_VERSION, false );
		wp_register_style( 'lms-testimonials-carousel', STM_LMS_URL . '/assets/css/elementor-widgets/testimonials-carousel.css', array(), STM_LMS_VERSION, false );
	}

	public function get_name() {
		return 'stm_lms_pro_testimonials';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'masterstudy-lms-learning-management-system' );
	}

	public function get_style_depends() {
		return array( 'lms-testimonials-carousel', 'lms-testimonials-carousel-slider' );
	}

	public function get_icon() {
		return 'stmlms-testimonials lms-icon';
	}

	public function get_categories() {
		return array( 'stm_lms' );
	}

	/** Register General Controls */
	protected function register_controls() {
		$this->register_general_content_controls();
		$this->register_heading_typo_content_controls();
		$this->register_heading_review_content_controls();
		$this->register_description_content_controls();
		$this->register_author_content_controls();
	}

	protected function register_general_content_controls() {
		$this->start_controls_section(
			'section_general_fields',
			array(
				'label' => esc_html__( 'General', 'masterstudy-lms-learning-management-system' ),
			)
		);
		$this->add_control(
			'testimonials_title',
			array(
				'label' => esc_html__( 'Title', 'masterstudy-lms-learning-management-system' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$this->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'masterstudy-lms-learning-management-system' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
				'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
				'return_value'       => true,
				'frontend_available' => true,
			)
		);
		$this->add_control(
			'loop',
			array(
				'label'              => esc_html__( 'Loop', 'masterstudy-lms-learning-management-system' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'On', 'masterstudy-lms-learning-management-system' ),
				'label_off'          => esc_html__( 'Off', 'masterstudy-lms-learning-management-system' ),
				'return_value'       => 'true',
				'default'            => 'true',
				'frontend_available' => true,
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'image',
			array(
				'label' => esc_html__( 'User Logo', 'masterstudy-lms-learning-management-system' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
		$repeater->add_control(
			'author_name',
			array(
				'label' => esc_html__( 'Author name', 'masterstudy-lms-learning-management-system' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'review_rating',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Rating', 'masterstudy-lms-learning-management-system' ),
				'default' => 5,
				'options' => array(
					5 => '5',
					4 => '4',
					3 => '3',
					2 => '2',
					1 => '1',
				),
			)
		);
		$repeater->add_control(
			'content',
			array(
				'label'      => esc_html__( 'Content', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::WYSIWYG,
				'show_label' => false,
			)
		);
		$this->add_control(
			'testimonials_heading',
			array(
				'label'     => esc_html__( 'Testimonials', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'testimonials',
			array(
				'type'        => Controls_Manager::REPEATER,
				'label'       => '',
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ author_name }}}',
			)
		);
		$this->end_controls_section();
	}

	/** Register Typography Controls */
	protected function register_heading_typo_content_controls() {
		$this->start_controls_section(
			'section_heading_typography',
			array(
				'label' => esc_html__( 'Heading', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'name'     => 'testimonials_title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .ms-lms-testimonials-header p',
			)
		);
		$this->add_responsive_control(
			'text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ms-lms-testimonials-header p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs(
			'style_tabs'
		);
		$this->start_controls_tab(
			'heading_text_normal',
			array(
				'label' => esc_html__( 'Normal', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_title_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms-lms-testimonials-header p' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'header_text_hover',
			array(
				'label' => esc_html__( 'Hover', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_title_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ms-lms-testimonials-header p:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function register_heading_review_content_controls() {
		$this->start_controls_section(
			'section_heading_star',
			array(
				'label' => esc_html__( 'Reviews', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'testimonials_icon_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Star Icon', 'masterstudy-lms-learning-management-system' ),
				'default'   => '#ffc321',
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .ms-lms-testimonial-review-rating i' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'reviews_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}  .ms-lms-testimonial-review-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function register_description_content_controls() {
		$this->start_controls_section(
			'section_description',
			array(
				'label' => esc_html__( 'Description', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'testimonials_content_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .content',
				'exclude'  => array(
					'font_style',
					'text_decoration',
				),
			)
		);
		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs(
			'style_tabs_content'
		);
		$this->start_controls_tab(
			'content_text_normal',
			array(
				'label' => esc_html__( 'Normal', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_content_color',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .content' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'content_text_hover',
			array(
				'label' => esc_html__( 'Hover', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_content_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .content:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function register_author_content_controls() {
		$this->start_controls_section(
			'section_author',
			array(
				'label' => esc_html__( 'Author', 'masterstudy-lms-learning-management-system' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'testimonials_author_typography',
				'label'    => esc_html__( 'Typography', 'masterstudy-lms-learning-management-system' ),
				'selector' => '{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .author-name',
			)
		);
		$this->add_responsive_control(
			'author_margin',
			array(
				'label'      => esc_html__( 'Margin', 'masterstudy-lms-learning-management-system' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .author-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs(
			'style_tabs_author'
		);
		$this->start_controls_tab(
			'author_text_normal',
			array(
				'label' => esc_html__( 'Normal', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_author_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .author-name' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'author_text_hover',
			array(
				'label' => esc_html__( 'Hover', 'companion-elementor' ),
			)
		);
		$this->add_control(
			'testimonials_author_color_hover',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'masterstudy-lms-learning-management-system' ),
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .elementor-testimonials-carousel .ms-lms-testimonial-data .author-name:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/** Render the widget output on the frontend */
	protected function render() {
		if ( ! Plugin::$instance->editor->is_edit_mode() ) {
			wp_enqueue_script( 'lms-testimonials-carousel-slider', STM_LMS_URL . 'assets/vendors/swiper-bundle.min.js', array(), STM_LMS_VERSION, true );
			wp_enqueue_script( 'lms-testimonials-carousel', STM_LMS_URL . '/assets/js/elementor-widgets/testimonials_carousel.js', array(), STM_LMS_VERSION, true );
		}
		$settings              = $this->get_settings_for_display();
		$settings['unique_id'] = 'stm_testimonials_carousel-' . $this->get_id();
		if ( empty( $settings['testimonials'] ) ) {
			?>
			<h2><?php echo esc_html__( 'LMS Testimonials Widget', 'masterstudy-lms-learning-management-system' ); ?></h2>
			<p><?php echo esc_html__( 'Add some reviewers to display the content of the widget.', 'masterstudy-lms-learning-management-system' ); ?></p>
			<?php
		}
		extract( $settings );
		if ( ! empty( $testimonials ) ) {
			?>
			<div class="stm-testimonials-carousel-wrapper swiper-container" id="<?php echo esc_attr( $unique_id ); ?>">
				<div class="ms-lms-testimonials-header">
					<i class="ms-lms-testimonials-icon"></i>
					<p><?php echo esc_html( $testimonials_title ); ?></p>
				</div>
				<div class="elementor-testimonials-carousel swiper-wrapper">
					<?php
					foreach ( $testimonials as $testimonial ) {
						$thumbnail_img = '';
						if ( ! empty( $testimonial['image'] ) && ! empty( $testimonial['image']['id'] ) ) {
							$thumbnail_img = wp_get_attachment_image_src( $testimonial['image']['id'], 'thumbnail' );
						}
						?>
						<div class="ms-lms-testimonial-data swiper-slide"
							data-thumbnail="<?php echo isset( $thumbnail_img[0] ) ? esc_attr( $thumbnail_img[0] ) : ''; ?>">
							<div class="ms-lms-testimonial-review-rating">
								<?php for ( $i = 0; $i < $testimonial['review_rating']; $i ++ ) { ?>
									<i class="fa fa-star"></i>
								<?php } ?>
							</div>
							<div class="author-name"><?php echo esc_html( $testimonial['author_name'] ); ?></div>
							<div class="content">
								<?php echo wp_kses_post( $testimonial['content'] ); ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="ms-lms-elementor-testimonials-swiper-pagination"></div>
			</div>
			<?php
		}
	}

	protected function get_html_data( $testimonials_data, $title ) {
		$html = '<div class="ms-lms-testimonials-wrapper simple_carousel_wrapper">';
		$html .= '<div class="ms-lms-testimonials-header"><i class="ms-lms-testimonials-icon"></i>';
		$html .= '<p>' . esc_html( $title ) . '</p>';
		$html .= '</div>';
		$html .= '<div class="ms-lms-starter-theme-testimonials">';
		foreach ( $testimonials_data as $testimonial ) {
			$html .= '<div class="stm_testimonials_single" >
						<div class="stars" ><i class="fa fa-star" ></i ></div>
						<div class="testimonials_title h3" >'
					. sanitize_text_field( $testimonial['title'] ) .
					'</div>
						<div class="testimonials_excerpt" >'
					. wp_kses_post( $testimonial['excerpt'] ) .
					'</div>
					</div>';
		}
		$html .= '</div>';
		$html .= '<div class="navs">';
		$html .= '<ul id="carousel-custom-dots">';
		foreach ( $testimonials_data as $testimonial ) {
			$html .= '<li class="testinomials_dots_image"><img src="' . esc_url( $testimonial['image'] ) . '" /></li>';
		}
		$html .= '</ul></div></div>';

		return $html;
	}

	protected function content_template() {
	}

}
