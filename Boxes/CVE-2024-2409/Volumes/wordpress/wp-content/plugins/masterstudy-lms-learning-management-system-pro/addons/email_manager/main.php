<?php
require_once STM_LMS_PRO_ADDONS . '/scorm/db.php';

/**
 * @deprecated
 */
class STM_LMS_Email_Manager {

	public function rewrite_email( $data ) {
		if ( ! isset( $data['vars'] ) || empty( $data['filter_name'] ) ) {
			return $data;
		}
		$vars        = $data['vars'];
		$filter_name = $data['filter_name'];

		$settings = self::stm_lms_get_settings();

		if ( isset( $settings[ "{$filter_name}_subject" ] ) ) {
			$data['subject'] = $settings[ "{$filter_name}_subject" ];
		}

		if ( empty( $settings[ $filter_name ] ) ) {
			return $data;
		}

		$data['enabled'] = ( ! empty( $settings[ "{$filter_name}_enable" ] ) && $settings[ "{$filter_name}_enable" ] );

		$message = $settings[ $filter_name ];

		$occurences = self::findReplace( $message );
		if ( empty( $occurences[0] ) || empty( $occurences[1] ) ) {
			$data['message'] = $message;

			return $data;
		}

		$data['message'] = $message;

		foreach ( $occurences[1] as $occurence_index => $occurence ) {
			if ( ! isset( $vars[ $occurence ] ) ) {
				continue;
			}

			$data['message'] =
				str_replace( $occurences[0][ $occurence_index ], $vars[ $occurence ], $data['message'] );
		}

		return $data;
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Email Manager',
				'menu_title'  => 'Email Manager',
				'menu_slug'   => 'email_manager_settings',
			),
			'fields'      => self::stm_lms_settings(),
			'option_name' => 'stm_lms_email_manager_settings',
		);

		return $setups;
	}

	public static function stm_lms_settings() {
		$sections = array(
			'instructors' => esc_html__(
				'Instructors',
				'masterstudy-lms-learning-management-system-pro'
			),
			'lessons'     => esc_html__( 'Lessons', 'masterstudy-lms-learning-management-system-pro' ),
			'account'     => esc_html__( 'Account', 'masterstudy-lms-learning-management-system-pro' ),
			'enterprise'  => esc_html__( 'Enterprise', 'masterstudy-lms-learning-management-system-pro' ),
			'order'       => esc_html__( 'Orders', 'masterstudy-lms-learning-management-system-pro' ),
			'course'      => esc_html__( 'Course', 'masterstudy-lms-learning-management-system-pro' ),
			'assignment'  => esc_html__( 'Assignment', 'masterstudy-lms-learning-management-system-pro' ),
		);

		$emails = include __DIR__ . '/emails.php';

		$emails = apply_filters( 'stm_lms_email_manager_emails', $emails );

		$data = array();

		foreach ( $sections as $section_key => $section ) {
			$data[ $section_key ] = array(
				'name'   => $section,
				'fields' => array(),
			);
		}

		foreach ( $emails as $email_key => $email ) {
			$data[ $email['section'] ]['fields'][ "{$email_key}_enable" ]  = array(
				'group' => 'started',
				'type'  => 'checkbox',
				'label' => $email['notice'],
				'value' => true,
			);
			$data[ $email['section'] ]['fields'][ "{$email_key}_subject" ] = array(
				'type'       => 'text',
				'label'      => esc_html__( 'Subject', 'masterstudy-lms-learning-management-system-pro' ),
				'value'      => $email['subject'],
				'dependency' => array(
					'key'   => "{$email_key}_enable",
					'value' => 'not_empty',
				),
			);
			$email_textarea = 'hint_textarea';
			if ( defined( 'STM_WPCFTO_VERSION' ) && STM_LMS_Helpers::is_pro_plus() ) {
				$email_textarea = 'trumbowyg';
			}
			$data[ $email['section'] ]['fields'][ $email_key ] = array(
				'type'       => $email_textarea,
				'group'      => ( STM_LMS_Helpers::is_pro_plus() ) ? '' : 'ended',
				'label'      => esc_html__( 'Message', 'masterstudy-lms-learning-management-system-pro' ),
				'value'      => $email['message'],
				'hints'      => $email['vars'],
				'dependency' => array(
					'key'   => "{$email_key}_enable",
					'value' => 'not_empty',
				),
			);
			if ( STM_LMS_Helpers::is_pro_plus() ) {
				$data[ $email['section'] ]['fields'][ "{$email_key}_hidden" ] = array(
					'type'       => 'send_email',
					'group'      => 'ended',
					'label'      => esc_html__( 'Hidden', 'masterstudy-lms-learning-management-system-pro' ),
					'value'      => $email_key,
					'dependency' => array(
						'key'   => "{$email_key}_enable",
						'value' => 'not_empty',
					),
				);
			}
		}

		return apply_filters( 'stm_lms_email_manager_settings', $data );
	}

	public static function stm_lms_get_settings() {
		$manager_settings = get_option( 'stm_lms_email_manager_settings', array() );

		if ( empty( $manager_settings ) ) {
			$settings = self::stm_lms_settings();

			foreach ( $settings as $key => $setting ) {
				if ( isset( $setting['fields'] ) ) {
					foreach ( $setting['fields'] as $email_key => $email ) {
						$manager_settings[ $email_key ] = $email['value'] ?? '';
					}
				}
			}
		}

		return $manager_settings;
	}

	public static function findReplace( $string ) {
		preg_match_all( '~\{\{\s*(.*?)\s*\}\}~', $string, $values );

		return $values;
	}

	public static function stm_lms_get_image_by_id( $id ) {
		if ( ! empty( $id ) ) {
			$image_url = wp_get_attachment_image_url( $id, 'full' );
			if ( ! empty( $image_url ) ) {
				return $image_url;
			}
		}

		return '';
	}

}
