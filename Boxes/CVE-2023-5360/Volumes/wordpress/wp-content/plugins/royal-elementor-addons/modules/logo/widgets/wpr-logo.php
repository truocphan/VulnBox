<?php
namespace WprAddons\Modules\Logo\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
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

class Wpr_Logo extends Widget_Base {
		
	public function get_name() {
		return 'wpr-logo';
	}

	public function get_title() {
		return esc_html__( 'Logo', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-logo';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'site logo', 'image' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-logo-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	protected function register_controls() {
		
		// Section: Logo -------------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'image',
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
			'retina_image',
			[
				'label' => esc_html__( 'Retina Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'mobile_image',
			[
				'label' => esc_html__( 'Mobile Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title_type',
			[
				'label' => esc_html__( 'Site Title', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],			
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_title',
			[
				'label' => esc_html__( 'Title Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'My Custom Logo',
				'condition' => [
					'title_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'description_type',
			[
				'label' => esc_html__( 'Tagline', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],			
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_description',
			[
				'label' => esc_html__( 'Tagline Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Tagline',
				'condition' => [
					'description_type' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => esc_html__( 'Logo URL', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'custom_url',
			[
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'condition' => [
					'url_type' => 'custom',
				],
			]
		);

		$this->add_control(
			'remove_front_page_url',
			[
				'label' => esc_html__( 'Disable Link on Front Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'url_type!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		// Section: Request New Feature
		Utilities::wpr_add_section_request_feature( $this, Controls_Manager::RAW_HTML, '' );

		// Styles
		// Section: General ----------
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-logo' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_section',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'position',
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
				'prefix_class'	=> 'wpr-logo-position-',
            ]
        );

        $this->add_responsive_control(
			'image_distance',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-logo-position-left .wpr-logo-image' => 'margin-right:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-logo-position-right .wpr-logo-image' => 'margin-left:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-logo-position-center .wpr-logo-image' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				],	
			]
		);
        
		$this->start_controls_tabs( 'logo_img_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .wpr-logo-image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'opacity_hv',
			[
				'label' => esc_html__( 'Opacity', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-logo:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hv',
				'selector' => '{{WRAPPER}} .wpr-logo:hover img',
			]
		);

		$this->add_control(
			'bg_hv_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.7,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-image img' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				
			]
		);

		$this->add_control(
			'hv_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_section',
			[
				'label' => esc_html__( 'Site Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

       $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-logo-title',
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
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-title' => 'margin: 0 0 {{SIZE}}{{UNIT}};',
				],	
			]
		);

		$this->add_control(
			'description_section',
			[
				'label' => esc_html__( 'Tagline', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

       $this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#888888',
				'selectors' => [
					'{{WRAPPER}} .wpr-logo-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .wpr-logo-description',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'border' => [
						'default' => 'solid',
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
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-logo',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
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
					'{{WRAPPER}} .wpr-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .wpr-logo'
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function logo_is_linked() {

		$settings = $this->get_settings();

		if ( 'none' === $settings['url_type'] ) {
			return false;
		}

		if ( 'yes' === $settings['remove_front_page_url'] && is_front_page() ) {
			return false;
		}

		return true;
	}
		

	protected function render() {

	$settings = $this->get_settings(); 

	$image_src = esc_url( $settings['image']['url'] );  
	$mobile_image_src = esc_url( $settings['mobile_image']['url'] );
	
	// Title
	$title = '';
	if ( 'default' === $settings['title_type'] ) {
		$title = get_bloginfo( 'name' );
	} elseif ( 'custom' === $settings['title_type'] ) {
		$title = $settings['custom_title'];
	}

	// Description
	$description = '';
	if ( 'default' === $settings['description_type'] ) {
		$description =  get_bloginfo( 'description' );
	} elseif ( 'custom' === $settings['description_type'] ) {
		$description = $settings['custom_description'];
	}

	// Image hover animation
	$this->add_render_attribute( 'image_attr', 'class', 'wpr-logo-image' );
	if ( $settings['hv_animation'] ) {
		$this->add_render_attribute( 'image_attr', 'class', 'elementor-animation-'. $settings['hv_animation'] );
	}

	// Logo URL
	$this->add_render_attribute( 'url_attr', 'class', 'wpr-logo-url' );
	$this->add_render_attribute( 'url_attr', 'rel', 'home' );
	
	if ( 'default' === $settings['url_type'] ) {
		$this->add_render_attribute( 'url_attr', 'href',  home_url( '/' ) );
	} elseif ( 'custom' === $settings['url_type'] ) {

		if ( $settings['custom_url']['is_external'] ) {
			$this->add_render_attribute( 'url_attr', 'target', '_blank' );
		}

		if ( $settings['custom_url']['nofollow'] ) {
			$this->add_render_attribute( 'url_attr', 'nofollow', '' );
		}

		$this->add_render_attribute( 'url_attr', 'href',  $settings['custom_url']['url'] );
	}

	?>
	
	<div class="wpr-logo elementor-clearfix">

		<?php if ( !empty( $image_src ) ) : ?>
		<picture <?php echo $this->get_render_attribute_string( 'image_attr' ); ?>>
			<?php if ( ! empty( $mobile_image_src ) ) : ?>
			<source media="(max-width: 767px)" srcset="<?php echo esc_attr( $mobile_image_src ); ?>">	
			<?php endif; ?>

			<?php if ( ! empty( $settings['retina_image']['url'] ) ) : ?>
			<source srcset="<?php echo esc_attr( $image_src ); ?> 1x, <?php echo esc_attr( $settings['retina_image']['url'] ); ?> 2x">	
			<?php endif; ?>
			
			<img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr( $title ); ?>">

			<?php if ( $this->logo_is_linked() ) : ?>
				<a <?php echo $this->get_render_attribute_string( 'url_attr' ); ?>></a>
			<?php endif; ?>
		</picture>
		<?php endif; ?>

		<?php if ( ! empty( $title ) || ! empty( $description ) ) : ?>
		<div class="wpr-logo-text">
			<?php if ( ! empty( $title ) ) : ?>
				<?php if ( is_home() || is_front_page() ) : ?>
					<h1 class="wpr-logo-title"><?php echo esc_html__( $title ); ?></h1>
				<?php else : ?>
					<p class="wpr-logo-title"><?php echo esc_html__( $title ); ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<?php
			if ( ! empty( $description ) ) : ?>
				<p class="wpr-logo-description"><?php echo esc_html__( $description ); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $this->logo_is_linked() ) : ?>
			<a <?php echo $this->get_render_attribute_string( 'url_attr' ); ?>></a>
		<?php endif; ?>

	</div>
		
<?php
	}
}