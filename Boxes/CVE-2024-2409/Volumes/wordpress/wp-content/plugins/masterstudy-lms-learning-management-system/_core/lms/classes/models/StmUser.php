<?php

namespace stmLms\Classes\Models;

use WP_User_Query;
use stmLms\Classes\Vendor\StmBaseModelUser;

class StmUser extends StmBaseModelUser {
	/**
	 * @param $search string
	 *
	 * @return array
	 */
	public static function search( $search ) {
		if ( ! $search && empty( trim( $search ) ) ) {
			return array();
		}

		$data  = array();
		$users = new WP_User_Query(
			array(
				'search'         => '*' . esc_attr( $search ) . '*',
				'number'         => 50,
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
			)
		);

		foreach ( $users->get_results() as $user ) {
			$data[] = array(
				'id'    => $user->data->ID,
				'name'  => $user->data->display_name,
				'email' => $user->data->user_email,
			);
		}

		return $data;
	}

	/**
	 * @return mixed
	 */
	public function getRole() {
		global $wp_roles;

		$all_roles = $wp_roles->roles ?? null;

		return ! empty( $all_roles ) && isset( $this->roles[0] )
			? array_merge( array( 'id' => $this->roles[0] ), $all_roles[ $this->roles[0] ] )
			: false;
	}

	public function get_courses() {
		return StmLmsCourse::query()
			->asTable( 'course' )
			->where_in( 'course.`post_type`', array( 'stm-courses', 'stm-course-bundles' ) )
			->where( 'course.`post_author`', intval( $this->ID ) )
			->find();
	}

	public static function save_paypal_email( $email = '' ) {
		$result = array(
			'success' => false,
			'status'  => 'error',
			'message' => ':(',
		);

		// phpcs:ignore WordPress.Security.NonceVerification
		$email             = $_POST['paypal_email'] ?? $email;
		$validator         = new \Validation();
		$data_for_validate = $validator->sanitize( array( 'email' => $email ) );
		$validator->validation_rules(
			array(
				'email' => 'required|valid_email',
			)
		);

		$validated_data = $validator->run( $data_for_validate );
		if ( false === $validated_data ) {
			$errors            = $validator->get_errors_array();
			$result['message'] = $errors['email'];

			return $result;
		}

		if ( get_current_user_id() && isset( $validated_data['email'] ) ) {
			update_user_meta( get_current_user_id(), 'stm_lms_paypal_email', $validated_data['email'] );

			$result = array(
				'success' => true,
				'status'  => 'success',
				'message' => esc_html__( 'Successfully saved', 'masterstudy-lms-learning-management-system' ),
			);
		}

		return $result;
	}
}
