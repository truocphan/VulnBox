<?php

namespace Frontend_Admin\Elementor\Widgets;

use  Frontend_Admin\Plugin;
use  Frontend_Admin\FEA_Module;
use  Frontend_Admin\Classes;
use  Elementor\Controls_Manager;
use  Elementor\Controls_Stack;
use  Elementor\Widget_Base;
use  ElementorPro\Modules\QueryControl\Module as Query_Module;
use  Frontend_Admin\Controls;
use  Elementor\Group_Control_Typography;
use  Elementor\Group_Control_Background;
use  Elementor\Group_Control_Border;
use  Elementor\Group_Control_Text_Shadow;
use  Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

/**

 *
 * @since 1.0.0
 */
class ACF_Form extends Widget_Base {

	public $form_defaults;
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
		return 'acf_ele_form';
	}


	/**
	 * Get widget defaults.
	 *
	 * Retrieve acf form widget defaults.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget defaults.
	 */
	public function get_form_defaults() {
		return array(
			'custom_fields_save' => 'all',
			'form_title'         => '',
			'submit'             => __( 'Update', 'acf-frontend-form-element' ),
			'success_message'    => __( 'Your site has been updated successfully.', 'acf-frontend-form-element' ),
			'field_type'         => 'ACF_fields',
			'fields'             => array(
				array( 'ACF_fields' ),
			),
		);
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
		return __( 'ACF Form', 'acf-frontend-form-element' );
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
		return 'eicon-form-horizontal frontend-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since  2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'frontend editing',
			'edit post',
			'add post',
			'add user',
			'edit user',
			'edit site',
			'acf',
			'acf form',
		);
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
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_form_structure_controls();
		$legacy_elementor = true;

		if ( $legacy_elementor ) {
			$this->register_actions_controls();
			$this->action_controls_section();
			// $this->register_progress_controls();
			do_action( 'frontend_admin/multi_step_settings', $this, true );
			do_action( 'frontend_admin/permissions_section', $this, true );

			$this->register_limit_controls();
			do_action( 'frontend_admin/elementor_widget/content_controls', $this );
		}

		$this->register_style_tab_controls();

		do_action( 'frontend_admin/styles_controls', $this );

	}

	protected function register_form_controls() {
		$this->start_controls_section(
			'form_select',
			array(
				'label' => __( 'Form', 'acf-frontend-form-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->end_controls_section();
	}

	protected function register_form_structure_controls() {
		$this->start_controls_section(
			'fields_section',
			array(
				'label' => __( 'Form', 'acf-frontend-form-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$default = array( '' => __( 'Build in Widget', 'acf-frontend-form-element' ) );
		
		$form_choices = feadmin_form_choices( $default );

		if ( $form_choices ) {
			$this->add_control(
				'admin_forms_select',
				array(
					'label'       => __( 'Choose Form...', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT,
					'label_block' => true,
					'options'     => $form_choices,
					'default'     => '',
				)
			);
			$this->add_control(
				'edit_form_button',
				array(
					'show_label' => false,
					'type'       => Controls_Manager::RAW_HTML,
					'raw'        => '<button class="edit-fea-form" type="button" data-link="' . admin_url( 'post.php' ) . '">
                        <span class="eicon-pencil">' . __( 'Edit Form', 'acf-frontend-form-element' ) . '</span>
                    </button>',
					'condition'  => array(
						'admin_forms_select!' => '',
					),
				)
			);
		}

		$this->add_control(
			'create_form_button',
			array(
				'show_label' => false,
				'type'       => Controls_Manager::RAW_HTML,
				'raw'        => '<button class="new-fea-form" type="button" data-link="' . admin_url( 'post-new.php?post_type=admin_form' ) . '">
                    <span class="eicon-plus"></span>' . __( 'Create New Form', 'acf-frontend-form-element' ) . '
                </button>',
			)
		);

			$this->add_control(
				'form_title',
				array(
					'label'       => __( 'Form Title', 'acf-frontend-form-element' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => $this->form_defaults['form_title'],
					'placeholder' => $this->form_defaults['form_title'],
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'admin_forms_select' => '',
					),
				)
			);

			$this->custom_fields_control();
			do_action( 'frontend_admin/fields_controls', $this );
			$this->add_control(
				'submit_button_text',
				array(
					'label'       => __( 'Submit Button Text', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => $this->form_defaults['submit'],
					'placeholder' => $this->form_defaults['submit'],
					'condition'   => array(
						'admin_forms_select' => '',
					),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$this->add_control(
				'allow_unfiltered_html',
				array(
					'label'        => __( 'Allow Unfiltered HTML', 'acf-frontend-form-element' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'condition'    => array(
						'admin_forms_select' => '',
					),
				)
			);

		$this->end_controls_section();

			do_action( 'frontend_admin/sub_fields_controls', $this );
	}

	protected function register_actions_controls() {
		global $fea_instance;
		$this->start_controls_section(
			'actions_section',
			array(
				'label'     => __( 'Actions', 'acf-frontend-form-element' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'admin_forms_select' => '',
				),
			)
		);
		if ( isset( $fea_instance->remote_actions ) ) {
			$remote_actions = array();
			foreach ( $fea_instance->remote_actions as $name => $action ) {
				$remote_actions[ $name ] = $action->get_label();
				unset( $remote_actions['mailchimp'] );
			}
			$this->add_control(
				'more_actions',
				array(
					'label'       => __( 'Submit Actions', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple'    => true,
					'options'     => $remote_actions,
					'render_type' => 'none',
				)
			);
		} else {
			$this->add_control(
				'more_actions_promo',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<p><a target="_blank" href="https://www.dynamiapps.com/"><b>Go pro</b></a> to unlock more actions.</p>', 'acf-frontend-form-element' ),
					'content_classes' => 'acf-fields-note',
				)
			);
		}

		$redirect_options = array(
			'current'     => __( 'Stay on Current Page/Post', 'acf-frontend-form-element' ),
			'custom_url'  => __( 'Custom Url', 'acf-frontend-form-element' ),
			'referer_url' => __( 'Referer', 'acf-frontend-form-element' ),
			'post_url'    => __( 'Post Url', 'acf-frontend-form-element' ),
		);

		$redirect_options = apply_filters( 'frontend_admin/forms/redirect_options', $redirect_options );

		$this->add_control(
			'redirect',
			array(
				'label'       => __( 'Redirect After Submit', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'current',
				'options'     => $redirect_options,
				'render_type' => 'none',
			)
		);
		if ( isset( $fea_instance->pro_features ) ) {
			$this->add_control(
				'no_reload',
				array(
					'label'        => __( 'No Page Reload', 'acf-frontend-form-element' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'true',
					'condition'    => array(
						'redirect' => 'current',
						'multi!'   => 'true',
					),
					'render_type'  => 'none',
				)
			);
		}
		$this->add_control(
			'open_modal',
			array(
				'label'        => __( 'Leave Modal Open After Submit', 'acf-frontend-form-element' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'show_in_modal' => 'true',
					'render_type'   => 'none',
				),
			)
		);
		$this->add_control(
			'redirect_action',
			array(
				'label'       => __( 'After Reload', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'clear',
				'options'     => array(
					'clear' => __( 'Clear Form', 'acf-frontend-form-element' ),
					'edit'  => __( 'Edit Form', 'acf-frontend-form-element' ),
				),
				'condition'   => array(
					'redirect' => 'current',
				),
				'render_type' => 'none',
			)
		);
		$this->add_control(
			'custom_url',
			array(
				'label'       => __( 'Custom Url', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Url Here', 'acf-frontend-form-element' ),
				'options'     => false,
				'show_label'  => false,
				'condition'   => array(
					'redirect' => 'custom_url',
				),
				'dynamic'     => array(
					'active' => true,
				),
				'render_type' => 'none',
			)
		);

		$this->add_control(
			'show_success_message',
			array(
				'label'        => __( 'Show Success Message', 'acf-frontend-form-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off'    => __( 'No', 'acf-frontend-form-element' ),
				'default'      => 'true',
				'return_value' => 'true',
				'render_type'  => 'none',
			)
		);
		$this->add_control(
			'update_message',
			array(
				'label'       => __( 'Submit Message', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => $this->form_defaults['success_message'],
				'placeholder' => $this->form_defaults['success_message'],
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_success_message' => 'true',
				),
			)
		);
		$this->add_control(
			'error_message',
			array(
				'label'       => __( 'Error Message', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => __( 'There shouldn\'t be any problems with the form submission, but if there are, this is what your users will see. If you are expeiencing issues, try and changing your cache settings and reach out to ', 'acf-frontend-form-element' ) . 'support@dynamiapps.com',
				'default'     => __( 'There has been an error. Form has been submitted successfully, but some actions might not have been completed.', 'acf-frontend-form-element' ),
				'dynamic'     => array(
					'active' => true,
				),
				'render_type' => 'none',
			)
		);
		$this->end_controls_section();
	}

	protected function action_controls_section() {
		global $fea_instance;
		$local_actions = $fea_instance->local_actions;
		foreach ( $local_actions as $name => $action ) {
			$object = $this->form_defaults['custom_fields_save'];
			if ( $object == $name || $object == 'all' ) {
				$action->register_settings_section( $this );
			}
		}
		if ( isset( $fea_instance->remote_actions ) ) {
			$remote_actions = $fea_instance->remote_actions;
			foreach ( $remote_actions as $action ) {
				$action->register_settings_section( $this );
			}
		}
	}

	public function custom_fields_control( $repeater = false ) {
		$cf_save = 'post';
		if ( $this->get_name() != 'acf_ele_form' ) {
			$cf_save = str_replace( array( 'new_', 'edit_', 'duplicate_' ), '', $this->get_name() );
		}
		$continue_action   = array();
		$controls_settings = array(
			'label'     => __( 'Save Custom Fields to...', 'acf-frontend-form-element' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => $cf_save,
			'condition' => array(
				'admin_forms_select' => '',
			),

		);

		if ( $this->form_defaults['custom_fields_save'] == 'all' ) {
			$custom_fields_options = array(
				'post' => __( 'Post', 'acf-frontend-form-element' ),
				'user' => __( 'User', 'acf-frontend-form-element' ),
				'term' => __( 'Term', 'acf-frontend-form-element' ),
			);
			if ( isset( fea_instance()->pro_features ) ) {
				$custom_fields_options['options'] = __( 'Site Options', 'acf-frontend-form-element' );
				if ( class_exists( 'woocommerce' ) ) {
					$custom_fields_options['product'] = __( 'Product', 'acf-frontend-form-element' );
				}
			}
			$controls_settings['options'] = $custom_fields_options;
			$this->add_control( 'custom_fields_save', $controls_settings );
		} else {
			$this->add_control(
				'custom_fields_save',
				array(
					'type'      => Controls_Manager::HIDDEN,
					'default'   => $this->form_defaults['custom_fields_save'],
					'condition' => array(
						'admin_forms_select' => '',
					),
				)
			);
		}

	}

	public function register_progress_controls() {
		$this->start_controls_section(
			'submissions_section',
			array(
				'label'     => __( 'Submissions', 'acf-frontend-form-element' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'admin_forms_select' => '',
				),
			)
		);
		$this->add_control(
			'save_progress_button',
			array(
				'label'        => __( 'Save Progress Option', 'acf-frontend-form-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off'    => __( 'No', 'acf-frontend-form-element' ),
				'return_value' => 'true',
			)
		);
		$this->add_control(
			'saved_draft_text',
			array(
				'label'       => __( 'Save Progress Text', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Save Progress', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Save Progress', 'acf-frontend-form-element' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'save_progress_button' => 'true',
				),
			)
		);

		$this->add_control(
			'saved_draft_message',
			array(
				'label'       => __( 'Progress Saved Message', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Progress Saved', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Progress Saved', 'acf-frontend-form-element' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'save_progress_button' => 'true',
				),
			)
		);

		$this->add_control(
			'saved_submissions',
			array(
				'label'        => __( 'Show Saved Drafts Selection', 'acf-frontend-form-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'acf-frontend-form-element' ),
				'label_off'    => __( 'No', 'acf-frontend-form-element' ),
				'return_value' => 'true',
				'condition'    => array(
					'save_progress_button' => 'true',
				),
				'seperator'    => 'before',
			)
		);
		$condition['saved_drafts'] = 'true';
		$this->add_control(
			'saved_drafts_label',
			array(
				'label'       => __( 'Edit Draft Label', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Edit a draft', 'acf-frontend-form-element' ),
				'placeholder' => __( 'Edit a draft', 'acf-frontend-form-element' ),
				'condition'   => array(
					'save_progress_button' => 'true',
					'saved_drafts'         => 'true',
				),
			)
		);
		$this->add_control(
			'saved_drafts_new',
			array(
				'label'       => __( 'New Drafts Text', 'acf-frontend-form-element' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '&mdash; New Post &mdash;', 'acf-frontend-form-element' ),
				'placeholder' => __( '&mdash; New Post &mdash;', 'acf-frontend-form-element' ),
				'condition'   => array(
					'save_progress_button' => 'true',
					'saved_drafts'         => 'true',
				),
			)
		);
		$this->end_controls_section();

	}

	public function register_limit_controls() {
		$this->start_controls_section(
			'limit_submit_section',
			array(
				'label'     => __( 'Limit Submissions', 'acf-frontend-form-element' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'admin_forms_select' => '',
				),
			)
		);
		if ( ! isset( fea_instance()->pro_features ) ) {
			$this->add_control(
				'limit_submit_promo',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<p><a target="_blank" href="https://www.dynamiapps.com/"><b>Go pro</b></a> to unlock limit submissions.</p>', 'acf-frontend-form-element' ),
					'content_classes' => 'acf-fields-note',
				)
			);
		}
		do_action( 'frontend_admin/limit_submit_settings', $this );
		$this->end_controls_section();
	}


	public function register_style_tab_controls() {
		if ( ! isset( fea_instance()->pro_features ) ) {

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

		} else {
			do_action( 'frontend_admin/style_tab_settings', $this );
		}
	}

	public function get_field_type_options() {
		$groups = feadmin_get_field_type_groups();
		$fields = array(
			'acf'    => $groups['acf'],
			'layout' => $groups['layout'],
		);

		switch ( $this->form_defaults['custom_fields_save'] ) {
			case 'post':
				$fields['post'] = $groups['post'];
				break;
			case 'user':
				$fields['user'] = $groups['user'];
				break;
			case 'options':
				$fields['options'] = $groups['options'];
				break;
			case 'term':
				$fields['term'] = $groups['term'];
				break;
			case 'comment':
				$fields['comment'] = $groups['comment'];
				break;
			case 'product':
				$fields = array_merge(
					$fields,
					array(
						'product_type' => $groups['product_type'],
						'product'      => $groups['product'],
						'inventory'    => $groups['product_inventory'],
						'shipping'     => $groups['product_shipping'],
						'downloadable' => $groups['product_downloadable'],
						'external'     => $groups['product_external'],
						'linked'       => $groups['product_linked'],
						'attributes'   => $groups['product_attributes'],
						'advanced'     => $groups['product_advanced'],
					)
				);
				break;
			default:
				$fields = array_merge(
					$fields,
					array(
						'post' => $groups['post'],
						'user' => $groups['user'],
						'term' => $groups['term'],
					)
				);
				if ( isset( fea_instance()->pro_features ) ) {
					$fields['options'] = $groups['options'];
					// $fields['comment'] = $groups['comment'];
					if ( class_exists( 'woocommerce' ) ) {
						$fields['product_type']                  = $groups['product_type'];
						$fields['product']                       = $groups['product'];
						$fields['product_inventory']             = $groups['product_inventory'];
								 $fields['product_downloadable'] = $groups['product_downloadable'];
								 $fields['product_shipping']     = $groups['product_shipping'];
						$fields['product_external']              = $groups['product_external'];
						$fields['product_attributes']            = $groups['product_attributes'];
						$fields['product_advanced']              = $groups['product_advanced'];
					}
					$fields['security'] = $groups['security'];
				}
		}
		if( isset( $groups['security'] ) ){
			$fields['security'] = $groups['security'];
		}
		return $fields;
	}



	/**
	 * Render acf ele form widget output on the frontend.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$wg_id = $this->get_id();
		$settings = $this->get_settings_for_display();

		$form_args = apply_filters( 'frontend_admin/elementor/form/args', $settings, $wg_id );

		$form_args['page_builder'] = 'elementor';

		fea_instance()->form_display->render_form( $form_args );

	}

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			fea_instance()->frontend->enqueue_scripts( 'frontend_admin_form' );
		}
		$this->form_defaults = $this->get_form_defaults();

		fea_instance()->elementor->form_widgets[] = $this->get_name();

	}

}
