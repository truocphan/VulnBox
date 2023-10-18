<?php
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Particles {

	private static $_instance = null;

	public $default_particles = '{"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#000000","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}' ;

	public function __construct() {
		add_action( 'elementor/element/section/section_background/after_section_end', [ $this, 'register_controls' ], 10 );
		add_action( 'elementor/frontend/section/before_render', [ $this, '_before_render' ], 10, 1 );
		add_action( 'elementor/section/print_template', [ $this, '_print_template' ], 10, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // FLEXBOX
        add_action( 'elementor/element/container/section_layout/after_section_end', [$this, 'register_controls'], 10 );
        add_action( 'elementor/frontend/container/before_render', [$this, '_before_render'], 10, 1 );
        add_action( 'elementor/container/print_template', [ $this, '_print_template' ], 10, 2 );
	}

	public function register_controls( $element ) {

		if ( ( 'section' === $element->get_name() || 'container' === $element->get_name() ) ) {

		$element->start_controls_section (
			'wpr_section_particles',
			[
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' =>  sprintf(esc_html__('Particles - %s', 'wpr-addons'), Utilities::get_plugin_name()),
			]
		);

		$element->add_control(
			'wpr_particles_apply_changes',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button>',
				'separator' => 'after'
			]
		);

        $element->add_control(
            'particles_video_tutorial',
            [
                'raw' => '<br><a href="https://www.youtube.com/watch?v=8OdnaoFSj94" target="_blank">Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></span></a>',
                'type' => Controls_Manager::RAW_HTML,
            ]
        );

		$element->add_control (
			'wpr_enable_particles',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Particles Background', 'wpr-addons' ),
				'default' => 'no',
				'return_value' => 'yes',
				'prefix_class' => 'wpr-particle-',
				'render_type' => 'template',
			]
		);

        if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
            \WprAddonsPro\Extensions\Wpr_Particles_Pro::add_control_which_particle($element);
        } else {
			$element->add_control (
				'which_particle',
				[
					'label' => __( 'Select Style', 'plugin-domain' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'wpr_particle_json_custom',
					'options' => [
						'wpr_particle_json_custom'  => __( 'Custom', 'plugin-domain' ),
						'pro-pjs' => __( 'Predefined (Pro)', 'plugin-domain' ),
					],
					'condition' => [
						'wpr_enable_particles' => 'yes'
					]
				]
			);

			// Upgrade to Pro Notice
			Utilities::upgrade_pro_notice( $element, Controls_Manager::RAW_HTML, 'particles', 'which_particle', ['pro-pjs'] );
        }

		$this->custom_json_particles( $this->default_particles, $element );

		if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
            \WprAddonsPro\Extensions\Wpr_Particles_Pro::add_control_group_predefined_particles($element);
		}

        $element->end_controls_section();

        } // end if()

    }

	public function custom_json_particles($array, $element) {
		$element->add_control(
			'wpr_particle_json_custom_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-control-field-description',
				'raw' => __('<a href="https://vincentgarreau.com/particles.js/" target="_blank">Click here</a> to generate JSON for the below field.', 'wpr-addons'),
				'condition'   => [
					'which_particle' => 'wpr_particle_json_custom',
					'wpr_enable_particles' => 'yes'
				],
			]
		);

		$element->add_control(
			'wpr_particle_json_custom',
			[
				'type'        => Controls_Manager::CODE,
				'label'       => esc_html__( 'Enter Custom JSON', 'wpr-addons' ),
				'default'     => $array,
				'render_type' => 'template',
				'condition'   => [
					'which_particle' => 'wpr_particle_json_custom',
					'wpr_enable_particles' => 'yes'
				],
			]
		);
	}

	public function _print_template( $template, $widget ) {
		if ( $widget->get_name() !== 'section' && $widget->get_name() !== 'container' ) {
			return $template;
		}
	
		ob_start();

		echo '<div class="wpr-particle-wrapper" id="wpr-particle-{{ view.getID() }}" data-wpr-particles-editor="{{ settings[settings.which_particle] }}" particle-source="{{settings.which_particle}}" wpr-quantity="{{settings.quantity}}" wpr-color="{{settings.particles_color}}" wpr-speed="{{settings.particles_speed}}" wpr-shape="{{settings.particles_shape}}" wpr-size="{{settings.particles_size}}"></div>';

		$particles_content = ob_get_contents();

		ob_end_clean();

		return $template . $particles_content;
	}

	public function _before_render( $element ) {
		if ( $element->get_name() !== 'section' && $element->get_name() !== 'container' ) {
			return;
		}

		$settings = $element->get_settings();

		if ( $settings['wpr_enable_particles'] === 'yes' ) {
			$settings['which_particle'] = 'pro-pjs' === $settings['which_particle'] ? 'wpr_particle_json_custom' : $settings['which_particle'];
			
			if ( ! wpr_fs()->can_use_premium_code() ) {
				$element->add_render_attribute( '_wrapper', [
					'data-wpr-particles' => $settings[$settings['which_particle']],
					'particle-source' => $settings['which_particle'],
				] );
			} else {
				$element->add_render_attribute( '_wrapper', [
					'data-wpr-particles' => $settings[$settings['which_particle']],
					'particle-source' => $settings['which_particle'],
					'wpr-quantity' => $settings['quantity'],
					'wpr-color' => $settings['particles_color'],
					'wpr-speed' => $settings['particles_speed'],
					'wpr-shape' => $settings['particles_shape'],
					'wpr-size' => $settings['particles_size']
				] );
			}
		}
	}

    public function enqueue_scripts() {
		wp_enqueue_script( 'wpr-particles', WPR_ADDONS_URL . 'assets/js/lib/particles/particles.js', [ 'jquery' ], '3.0.6', true );
	}

}

new Wpr_Particles();
