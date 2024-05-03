<?php
namespace Frontend_Admin\Actions;

use Frontend_Admin\Plugin;
use Frontend_Admin\Classes\ActionBase;
use Frontend_Admin\Forms\Actions;
use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Query_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'ActionComment' ) ) :

	class ActionComment extends ActionBase {


		public function get_name() {
			return 'comment';
		}

		public function get_label() {
			return __( 'Comment', 'acf-frontend-form-element' );
		}

		public function get_fields_display( $form_field, $local_field ) {
			switch ( $form_field['field_type'] ) {
				case 'comment':
					$local_field['type']           = 'textarea';
					$local_field['custom_comment'] = true;
					break;
				case 'author':
					$local_field['type']          = 'text';
					$local_field['custom_author'] = true;
					break;
				case 'author_email':
					$local_field['type']                = 'email';
					$local_field['custom_author_email'] = true;
					break;
			}
			return $local_field;
		}

		public function register_settings_section( $widget ) {

			$widget->start_controls_section(
				'section_edit_comment',
				array(
					'label' => $this->get_label(),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->action_controls( $widget );

			$widget->end_controls_section();
		}

		public function action_controls( $widget, $step = false ) {
			 $condition = array(
				 'custom_fields_save' => 'comment',
			 );
			 if ( $step ) {
				 $condition['field_type']         = 'step';
				 $condition['overwrite_settings'] = 'true';
			 }

            $widget->add_control(
				'comment_parent_post',
				array(
					'label'     => __( 'Post to Comment On', 'acf-frontend-form-element' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'current_post',
					'options'   => array(
						'current_post' => __( 'Current Post', 'acf-frontend-form-element' ),
						'select_post'  => __( 'Specific Post', 'acf-frontend-form-element' ),
					),
					'condition' => $condition,
				)
			);
			$condition['comment_parent_post'] = 'select_post';

			$widget->add_control(
				'select_parent_post',
				array(
					'label'       => __( 'Specific Post', 'acf-frontend-form-element' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( '18', 'acf-frontend-form-element' ),
					'description' => __( 'Enter the post ID', 'acf-frontend-form-element' ) . ' ' . __( 'Commenting must be turned on for that post', 'acf-frontend-form-element' ),
					'condition'   => $condition,
				)
			);
		}

		public function run( $post_id, $settings ) {
			global $current_user;

			

			$comment_to_insert = array(
				'comment_post_ID' => absint( $_POST['parent_post'] ),
				'comment_parent'  => absint( $_POST['parent_comment'] ),
			);

			$comment_id = wp_insert_comment( $comment_to_insert );

			return 'comment_' . $comment_id;
		}

	}
	// fea_instance()->local_actions['comment'] = new ActionComment();

endif;
