<?php

namespace MasterStudy\Lms\Pro\addons\email_manager;

use STM_LMS_Helpers;

class EmailManagerSettingsPage {
	/**
	 * @param array $pages
	 */
	public static function setup( $pages ): array {
		$pages[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Email Manager',
				'menu_title'  => 'Email Manager',
				'menu_slug'   => 'email_manager_settings',
			),
			'fields'      => self::fields(),
			'option_name' => 'stm_lms_email_manager_settings',
		);

		return $pages;
	}

	private static function fields(): array {
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

		$emails = require __DIR__ . '/emails.php';
		$emails = apply_filters( 'stm_lms_email_manager_emails', $emails );
		$data   = array();

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
}
