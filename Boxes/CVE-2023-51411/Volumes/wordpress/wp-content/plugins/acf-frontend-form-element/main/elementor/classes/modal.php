<?php
namespace Frontend_Admin\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class ModalWindow {


	public function get_icon( $icon, $attributes = array(), $tag = 'i' ) {
		if ( empty( $icon['library'] ) ) {
			return false;
		}
		$output = '';
		// handler SVG Icon
		if ( 'svg' === $icon['library'] ) {
			$output = \Elementor\Icons_Manager::render_svg_icon( $icon['value'] );
		} else {
			$output = $this->render_icon_html( $icon, $attributes, $tag );
		}

		return $output;
	}

	public function render_icon_html( $icon, $attributes = array(), $tag = 'i' ) {
		$icon_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
		if ( isset( $icon_types[ $icon['library'] ]['render_callback'] ) && is_callable( $icon_types[ $icon['library'] ]['render_callback'] ) ) {
			return call_user_func_array( $icon_types[ $icon['library'] ]['render_callback'], array( $icon, $attributes, $tag ) );
		}

		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = $icon['value'];
		} else {
			if ( is_array( $attributes['class'] ) ) {
				$attributes['class'][] = $icon['value'];
			} else {
				$attributes['class'] .= ' ' . $icon['value'];
			}
		}
		return '<' . $tag . ' ' . \Elementor\Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
	}

	public function before_render( $settings ) {
		if ( empty( $settings['show_in_modal'] ) ) {
			return;
		}

		global $hide_modal;
		if ( ! $hide_modal ) {
			echo '<style>
				.modal{display:none}.show{display:block}
			</style>';
			wp_enqueue_style( 'fea-modal' );
			wp_enqueue_style( 'acf-global' );
			wp_enqueue_script( 'fea-modal' );
			$hide_modal = true;
		}
		$show_modal = 'hide';

		$modal_num = feadmin_get_random_string();

		?>
		<div class="modal-button-container"><button class="modal-button fea-open-modal" data-modal="<?php esc_attr_e( $modal_num ); ?>" >

		<?php
		if ( ! empty( $settings['modal_button_icon']['value'] ) ) {
			echo wp_kses_post( $this->get_icon( $settings['modal_button_icon'], array( 'aria-hidden' => 'true' ) ) );
		}
		$current_id = fea_instance()->elementor->get_current_post_id();
		esc_html_e( $settings['modal_button_text'] );
		?>
		</button></div>
		<div id="modal_<?php esc_attr_e( $modal_num ); ?>" class="fea-modal edit-modal elementor-<?php esc_attr_e( $current_id ); ?>">
			<div class="fea-modal-content elementor-element elementor-element-<?php esc_attr( $settings['id'] ); ?>"> 
				<div class="fea-modal-inner"> 
					<span data-modal="<?php esc_attr_e( $modal_num ); ?>" class="acf-icon -cancel fea-close-modal"></span>
						<div class="content-container">
		<?php
	}
	public function after_render( $settings ) {
		if ( empty( $settings['show_in_modal'] ) ) {
			return;
		}
		?>
		</div>
			</div>
		</div>
		</div>

		<?php
	}

	public function modal_controls( $element ) {
		$element->start_controls_section(
			'modal_section',
			array(
				'label'     => __( 'Modal Window', 'acf-frontend-form-element' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'admin_forms_select' => '',
				),
			)
		);

		$element->add_control(
			'show_in_modal',
			array(
				'label'        => __( 'Show in Modal', 'acf-frontend-form-element' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off'    => __( 'No', 'acf-frontend-form-element' ),
				'return_value' => 'true',
			)
		);

		$default_text = __( 'Open Modal', 'acf-frontend-form-element' );

		$element->add_control(
			'modal_button_text',
			array(
				'label'       => __( 'Modal Button Text', 'acf-frontend-form-element' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $default_text,
				'placeholder' => $default_text,
				'condition'   => array(
					'show_in_modal' => 'true',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$element->add_control(
			'modal_button_icon',
			array(
				'label'     => __( 'Modal Button Icon', 'acf-frontend-form-element' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'condition' => array(
					'show_in_modal' => 'true',
				),
			)
		);

		$element->end_controls_section();

	}

	public function __construct() {
		 add_action( 'frontend_admin/elementor_widget/content_controls', array( $this, 'modal_controls' ), 10 );
		add_action( 'frontend_admin/elementor/before_render', array( $this, 'before_render' ), 10 );
		add_action( 'frontend_admin/elementor/after_render', array( $this, 'after_render' ), 10 );
	}

}

new ModalWindow();

