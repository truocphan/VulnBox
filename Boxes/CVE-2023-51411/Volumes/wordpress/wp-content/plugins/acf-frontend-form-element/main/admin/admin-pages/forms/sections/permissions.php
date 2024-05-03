<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$default_row = array(
	array(
		'rule_name'   => __( 'Administrators', 'acf-frontend-form-element' ),
		'who_can_see' => 'logged_in',
		'by_role'     => array( 'administrator' ),
	),
);

$values = array(
	'who_can_see',
	'not_allowed',
	'not_allowed_message',
	'not_allowed_content',
	'email_verification',
	'by_role',
	'by_user_id',
	'dynamic',
);

foreach ( $values as $value ) {
	if ( isset( $form[ $value ] ) ) {
		$default_row[0][ $value ] = $form[ $value ];
	}
}

$fields = array(
	array(
		'key'          => 'no_kses',
		'label'        => __( 'Allow Unfiltered HTML', 'acf-frontend-form-element' ),
		'type'         => 'true_false',
		'instructions' => '',
		'required'     => 0,
		'ui'           => 1,
		'wrapper'      => array(
			'width' => '50',
			'class' => '',
			'id'    => '',
		),
	),
	array(
		'key'           => 'wp_uploader',
		'label'         => __( 'WP Media Library', 'acf-frontend-form-element' ),
		'type'          => 'true_false',
		'instructions'  => __( 'Whether to use the WordPress media library for file fields or just a basic upload button', 'acf-frontend-form-element' ),
		'required'      => 0,
		'ui'            => 1,
		'default_value' => 1,
		'wrapper'       => array(
			'width' => '50',
			'class' => '',
			'id'    => '',
		),
	),
	array(
		'key'           => 'form_conditions',
		'label'         => __( 'Conditions', 'acf-frontend-form-element' ),
		'type'          => 'list_items',
		'instructions'  => __( 'The form will show if any of these conditions are met.', 'acf-frontend-form-element' ),
		'collapsed'     => 'rule_name',
		'collapsable'   => true,
		'min'           => 1,
		'max'           => '',
		'layout'        => 'block',
		'button_label'  => __( 'Add Rule', 'acf-frontend-form-element' ),
		'remove_label'  => __( 'Remove Rule', 'acf-frontend-form-element' ),
		'default_value' => $default_row,
		'sub_fields'    => array(
			array(
				'key'               => 'rule_name',
				'label'             => __( 'Rule Name', 'acf-frontend-form-element' ),
				'name'              => 'name',
				'type'              => 'text',
				'instructions'      => __( 'Give this rule an identifier', 'acf-frontend-form-element' ),
				'required'          => 1,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '70',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => __( 'Administrators', 'acf-frontend-form-element' ),
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
			),
			array(
				'key'           => 'applies_to',
				'label'         => __( 'Permissions given...', 'acf-frontend-form-element' ),
				'type'          => 'checkbox',
				'instructions'  => __( 'Logged in users will always be able to view and edit their own submissions if they as long as they can submit forms.', 'acf-frontend-form-element' ),
				'required'      => 1,
				'default_value' => array( 'form', 'submissions' ),
				'toggle'        => 1,
				'choices'       => array(
					'form'        => __( 'Submit Form', 'acf-frontend-form-element' ),
					'edit'        => __( 'Edit Submissions', 'acf-frontend-form-element' ),
					'view'        => __( 'View Submissions', 'acf-frontend-form-element' ),
					// 'delete'   => __( 'Delete Submissions', 'acf-frontend-form-element' ),
					'submissions' => __( 'View Submissions List', 'acf-frontend-form-element' ),
				),
			),
			array(
				'key'          => 'not_allowed',
				'label'        => __( 'No Permissions Message', 'acf-frontend-form-element' ),
				'type'         => 'select',
				'instructions' => '',
				'required'     => 0,
				'choices'      => array(
					'show_nothing'   => __( 'None', 'acf-frontend-form-element' ),
					'show_message'   => __( 'Message', 'acf-frontend-form-element' ),
					'custom_content' => __( 'Custom Content', 'acf-frontend-form-element' ),
				),
			),
			array(
				'key'               => 'not_allowed_message',
				'label'             => __( 'Message', 'acf-frontend-form-element' ),
				'type'              => 'textarea',
				'instructions'      => '',
				'required'          => 0,
				'rows'              => 3,
				'placeholder'       => __( 'You do not have the proper permissions to view this form', 'acf-frontend-form-element' ),
				'default_value'     => __( 'You do not have the proper permissions to view this form', 'acf-frontend-form-element' ),
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'not_allowed',
							'operator' => '==',
							'value'    => 'show_message',
						),
					),
				),
			),
			array(
				'key'               => 'not_allowed_content',
				'label'             => __( 'Content', 'acf-frontend-form-element' ),
				'type'              => 'wysiwyg',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'not_allowed',
							'operator' => '==',
							'value'    => 'custom_content',
						),
					),
				),
			),
			array(
				'key'          => 'who_can_see',
				'label'        => __( 'Who Can See This...', 'acf-frontend-form-element' ),
				'type'         => 'select',
				'instructions' => '',
				'required'     => 0,
				'choices'      => array(
					'logged_in'  => __( 'Only Logged In Users', 'acf-frontend-form-element' ),
					'logged_out' => __( 'Only Logged Out', 'acf-frontend-form-element' ),
					'all'        => __( 'All Users', 'acf-frontend-form-element' ),
				),
			),
			array(
				'key'               => 'email_verification',
				'label'             => __( 'Email Address', 'acf-frontend-form-element' ),
				'type'              => 'select',
				'required'          => 0,
				'choices'           => array(
					'all'        => __( 'All', 'acf-frontend-form-element' ),
					'verified'   => __( 'Verified', 'acf-frontend-form-element' ),
					'unverified' => __( 'Unverified', 'acf-frontend-form-element' ),
				),
				'instructions'      => 'Only show to users who verified their email address or only to those who haven\'t.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'who_can_see',
							'operator' => '==',
							'value'    => 'logged_in',
						),
					),
				),
			),
			array(
				'key'               => 'by_role',
				'label'             => __( 'Select By Role', 'acf-frontend-form-element' ),
				'type'              => 'select',
				'instructions'      => '',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'who_can_see',
							'operator' => '==',
							'value'    => 'logged_in',
						),
					),
				),
				'default_value'     => array( 'administrator' ),
				'multiple'          => 1,
				'ui'                => 1,
				'choices'           => feadmin_get_user_roles( array(), true ),
			),
			array(
				'key'               => 'by_user_id',
				'label'             => __( 'Select By User', 'acf-frontend-form-element' ),
				'type'              => 'user',
				'instructions'      => '',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'who_can_see',
							'operator' => '==',
							'value'    => 'logged_in',
						),
					),
				),
				'allow_null'        => 0,
				'multiple'          => 1,
				'return_format'     => 'id',
			),
			array(
				'key'               => 'dynamic',
				'label'             => __( 'Dynamic Permissions', 'acf-frontend-form-element' ),
				'type'              => 'select',
				'instructions'      => '',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'who_can_see',
							'operator' => '==',
							'value'    => 'logged_in',
						),
					),
				),
				'choices'           => feadmin_user_id_fields(),
				'allow_null'        => 1,
			),
		),
	),
);

$fields = apply_filters( 'frontend_admin/forms/settings/permissions', $fields );

return $fields;
