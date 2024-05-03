<?php
namespace Frontend_Admin\Elementor\Widgets;

use Frontend_Admin\Plugin;

use Frontend_Admin\Classes;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use ElementorPro\Modules\QueryControl\Module as Query_Module;

/**

 *
 * @since 1.0.0
 */
class Delete_Product_Widget extends Widget_Base {


	/**
	 * Get widget name.
	 *
	 * Retrieve acf ele form widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'delete_product';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve acf ele form widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Trash/Delete Product', 'acf-frontend-form-element' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve acf ele form widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-trash frontend-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the acf ele form widget belongs to.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'frontend-admin-general' );
	}

	/**
	 * Register acf ele form widget controls.
	 *
	 * Adds different input fields to allow the post to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'delete_button_section',
			array(
				'label' => __( 'Trash Button', 'acf-frontend-form-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'delete_button_text',
			array(
				'label'       => __( 'Delete Button Text', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Delete', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Delete', 'acf-frontend-form-element' ),
			)
		);
		$this->add_control(
			'delete_button_icon',
			array(
				'label' => __( 'Delete Button Icon', 'acf-frontend-form-element' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'confirm_delete_message',
			array(
				'label'       => __( 'Confirm Delete Message', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'The product will be deleted. Are you sure?', 'acf-frontend-form-element' ),
				'placeholder' => __( 'The product will be deleted. Are you sure?', 'acf-frontend-form-element' ),
			)
		);

		$this->add_control(
			'show_delete_message',
			array(
				'label'        => __( 'Show Success Message', 'acf-frontend-form-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off'    => __( 'No', 'acf-frontend-form-element' ),
				'default'      => 'true',
				'return_value' => 'true',
			)
		);
		$this->add_control(
			'delete_message',
			array(
				'label'       => __( 'Success Message', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'You have deleted this product', 'acf-frontend-form-element' ),
				'placeholder' => __( 'You have deleted this product', 'acf-frontend-form-element' ),
				'dynamic'     => array(
					'active'    => true,
					'condition' => array(
						'show_delete_message' => 'true',
					),
				),
			)
		);
		$this->add_control(
			'force_delete',
			array(
				'label'        => __( 'Force Delete', 'acf-frontend-form-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
				'description'  => __( 'Whether or not to completely delete the products right away.' ),
			)
		);

		$this->add_control(
			'delete_redirect',
			array(
				'label'   => __( 'Redirect After Delete', 'acf-frontend-form-element' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom_url',
				'options' => array(
					'current'     => __( 'Reload Current Url', 'acf-frontend-form-element' ),
					'custom_url'  => __( 'Custom Url', 'acf-frontend-form-element' ),
					'referer_url' => __( 'Referer', 'acf-frontend-form-element' ),
				),
			)
		);

		$this->add_control(
			'redirect_after_delete',
			array(
				'label'         => __( 'Custom URL', 'acf-frontend-form-element' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'Enter Url Here', 'acf-frontend-form-element' ),
				'show_external' => false,
				'dynamic'       => array(
					'active' => true,
				),
				'condition'     => array(
					'delete_redirect' => 'custom_url',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'product_section',
			array(
				'label' => __( 'Product', 'acf-frontend-form-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		fea_instance()->local_actions['product']->action_controls( $this, false, 'delete_product' );

		$this->end_controls_section();

		do_action( 'frontend_admin/permissions_section', $this );

		$this->start_controls_section(
			'style_promo_section',
			array(
				'label' => __( 'Styles', 'acf-frontend-form-element' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'styles_promo',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<p><a target="_blank" href="https://www.dynamiapps.com/"><b>Go Pro</b></a> to unlock styles.</p>', 'acf-frontend-form-element' ),
				'content_classes' => 'acf-fields-note',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render acf ele form widget output on the frontend.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$wg_id           = $this->get_id();
		$current_post_id = fea_instance()->elementor->get_current_post_id();
		global $post;
		$settings = $this->get_settings_for_display();

		$local_field = array(
			'key'                 => 'button_' . $wg_id,
			'name'                => 'button_' . $wg_id,
			'type'                => 'delete_product',
			'label'               => '',
			'field_label_hide'    => 1,
			'button_text'         => $settings['delete_button_text'],
			'button_icon'         => $settings['delete_button_icon']['value'],
			'confirmation_text'   => $settings['confirm_delete_message'],
			'show_delete_message' => $settings['show_delete_message'],
			'delete_message'      => $settings['delete_message'],
			'force_delete'        => $settings['force_delete'],
			'wrapper'             => array(
				'class' => '',
				'id'    => '',
				'width' => '',
			),
			'instructions'        => '',
			'required'            => '',
			'redirect'            => $settings['delete_redirect'],
			'custom_url'          => $settings['redirect_after_delete']['url'],
		);

		
		acf_add_local_field( $local_field );
		

		$form_args = array( 'fields' => [ 'button_' . $wg_id => $local_field] );

		$form_args = $this->get_settings_to_pass( $form_args, $settings );

		fea_instance()->form_display->render_form( $form_args );

	}

	public function get_settings_to_pass( $form_args, $settings ) {
		 $settings_to_pass = array( 'who_can_see', 'by_role', 'by_user_id', 'dynamic', 'dynamic_manager', 'not_allowed', 'not_allowed_message', 'not_allowed_content', 'save_all_data' );

		$types = array( 'post', 'user', 'term', 'product' );
		foreach ( $types as $type ) {
			$settings_to_pass[] = "save_to_{$type}";
			$settings_to_pass[] = "{$type}_to_edit";
			$settings_to_pass[] = "url_query_{$type}";
			$settings_to_pass[] = "{$type}_select";
		}

		foreach ( $settings_to_pass as $setting ) {
			if ( isset( $settings[ $setting ] ) ) {
				$form_args[ $setting ] = $settings[ $setting ];
			}
		}

		return $form_args;
	}


}
