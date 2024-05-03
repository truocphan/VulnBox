<?php
namespace Frontend_Admin\Classes;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Query_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class LimitSubmit {


	public function limit_form_render( $form ) {
		if ( empty( $form['limiting_rules'] ) || ! $form['display'] ) {
			return $form;
		}

		$limit_rules = $form['limiting_rules'];

		$active_user = wp_get_current_user();

		$message = '';

		foreach ( $limit_rules as $rule ) {
			if ( empty( $rule['allowed_submits'] ) ) {
				continue;
			}

			$roles = $rule['limit_by_role'];
			$users = $rule['limit_by_user'];
			if ( is_string( $users ) ) {
				$users = explode( ',', $users );
			}
			$in_rule = false;

			if ( $rule['limit_to_everyone'] ) {
				$in_rule = true;
			}
			if ( is_array( $roles ) ) {
				if ( count( array_intersect( $roles, (array) $active_user->roles ) ) == 0 ) {
					$in_rule = false;
				} else {
					$in_rule = true;
				}
			}
			if ( is_array( $users ) ) {
				if ( in_array( $active_user->ID, $users ) ) {
					$in_rule = true;
				}
			}

			if ( $in_rule === true ) {
				$submits = (int) $rule['allowed_submits'];
				if ( $form['limit_reached'] == 'show_message' ) {
					$message = '<div class="acf-notice -limit frontend-admin-limit-message"><p>' . $form['limit_submit_message'] . '</p></div>';
				} elseif ( $form['limit_reached'] == 'custom_content' ) {
					$message = $form['limit_submit_content'];
				} else {
					$message = 'NOTHING';
				}
			}
		}

		$submitted = get_user_meta( $active_user->ID, 'submitted::' . $form['id'], true );
		if ( ! empty( $submits ) && (int) $submits - (int) $submitted <= 0 ) {
			$form['display'] = false;
			$form['message'] = $message;
		}

		return $form;

	}

	public function submit_record( $post_id ) {
		if ( get_post_field( 'post_status', $post_id ) == 'publish' ) {
			$post_author = get_post_field( 'post_author', $post_id );
			$post_form   = get_post_meta( $post_id, 'admin_form_source', true );
			$submitted   = get_user_meta( $post_author, 'submitted::' . $post_form, true );

			$submitted++;
			update_user_meta( $post_author, 'submitted::' . $post_form, $submitted );
		}
	}

	public function subtract_record( $post_id ) {
		$post_author = get_post_field( 'post_author', $post_id );
		$post_form   = get_post_meta( $post_id, 'admin_form_source', true );
		$submitted   = get_user_meta( $post_author, 'submitted::' . $post_form, true );

		if ( $submitted && 'trash' !== get_post_status( $post_id ) ) {
			$submitted--;
			update_user_meta( $post_author, 'submitted::' . $post_form, $submitted );
		}

	}

	public function subtract_add_record( $new_status, $old_status, $post ) {
		if ( $old_status == $new_status || did_action( 'frontend_admin/form/on_submit' ) ) {
			return;
		}

		$post_author = get_post_field( 'post_author', $post->ID );
		$post_form   = get_post_meta( $post->ID, 'admin_form_source', true );
		$submitted   = get_user_meta( $post_author, 'submitted::' . $post_form, true );

		if ( ( $old_status == 'publish' || $old_status == 'pending' ) && ( $new_status != 'publish' && $new_status != 'pending' ) ) {
			$submitted--;
			update_user_meta( $post_author, 'submitted::' . $post_form, $submitted );
		}
		if ( ( $old_status != 'publish' && $old_status != 'pending' ) && ( $new_status == 'publish' || $new_status == 'pending' ) ) {
			if ( ! $submitted ) {
				$submitted = 1;
			} else {
				$submitted++;
			}
			update_user_meta( $post_author, 'submitted::' . $post_form, $submitted );
		}

	}

	public function add_submission( $form ) {
		$user_id = get_current_user_id();
		$form_id = $form['id'];

		if ( $user_id ) {
			$user_submitted = get_user_meta( $user_id, 'submitted::' . $form_id, true );
			$user_submitted++;
			update_user_meta( $user_id, 'submitted::' . $form_id, $user_submitted );
		}
	}

	public function __construct() {
		 add_filter( 'frontend_admin/show_form', array( $this, 'limit_form_render' ), 11 );
		add_action( 'frontend_admin/form/on_submit', array( $this, 'add_submission' ) );
		add_action( 'before_delete_post', array( $this, 'subtract_record' ) );
		add_action( 'transition_post_status', array( $this, 'subtract_add_record' ), 10, 3 );
	}

}

new LimitSubmit();
