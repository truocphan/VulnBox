<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post;
$form_shortcode = '[frontend_admin submissions="' . $post->ID . '"]';
$icon_path      = '<span class="dashicons dashicons-admin-page"></span>';

$save_submissions = array(
	array(
		array(
			'field'    => 'save_form_submissions',
			'operator' => '==',
			'value'    => '1',
		),
	),
);

$data_types = array(
	'none'    => __( 'Submission Only', 'acf-frontend-form-element' ),
	'post'    => __( 'Post', 'acf-frontend-form-element' ),
	'user'    => __( 'User', 'acf-frontend-form-element' ),
	'term'    => __( 'Term', 'acf-frontend-form-element' ),
	'options' => __( 'Site Options', 'acf-frontend-form-element' ),
);
if ( class_exists( 'woocommerce' ) ) {
	$data_types['product'] = __( 'Product', 'acf-frontend-form-element' );
}

$fields = array(
	array(
		'key'              => 'custom_fields_save',
		'label'            => __( 'Save Custom Fields to...', 'acf-frontend-form-element' ),
		'field_label_hide' => 0,
		'type'             => 'select',
		'instructions'     => '',
		'required'         => 0,
		'choices'          => $data_types,
		'allow_null'       => 0,
		'multiple'         => 0,
		'ui'               => 0,
		'return_format'    => 'value',
		'ajax'             => 0,
		'placeholder'      => '',
	),
	array(
		'key'               => 'save_form_submissions',
		'label'             => __( 'Save Form Submissions', 'acf-frontend-form-element' ),
		'type'              => 'true_false',
		'instructions'      => '',
		'required'          => 0,
		'conditional_logic' => 0,
		'wrapper'           => array(
			'width' => '',
			'class' => '',
			'id'    => '',
		),
		'default_value'     => get_option( 'frontend_admin_save_submissions' ),
		'message'           => '',
		'ui'                => 1,
		'ui_on_text'        => '',
		'ui_off_text'       => '',
	),
	array(
		'key'                   => 'submission_title',
		'label'                 => __( 'Submission Title', 'acf-frontend-form-element' ),
		'type'                  => 'text',
		'instructions'          => __( 'By default, the submission title will be the first string value in the form. Dynamically set this to something more descriptive.', 'acf-frontend-form-element' ),
		'required'              => 0,
		'placeholder'           => __( 'New Post Submitted: [post:title]', 'acf-frontend-form-element' ),
		'conditional_logic'     => $save_submissions,
		'dynamic_value_choices' => 1,
	),
	array(
		'key'               => 'save_all_data',
		'label'             => __( 'Submission Requirements', 'acf-frontend-form-element' ),
		'type'              => 'select',
		'instructions'      => __( 'Data will not be saved until these requirements are met.', 'acf-frontend-form-element' ),
		'required'          => 0,
		'conditional_logic' => $save_submissions,
		'choices'           => array(
			'require_approval' => __( 'Admin Approval', 'acf-frontend-form-element' ),
			'verify_email'     => __( 'Email is Verified', 'acf-frontend-form-element' ),
		),
		'allow_null'        => 1,
		'multiple'          => 1,
		'ui'                => 1,
		'return_format'     => 'value',
		'ajax'              => 0,
		'placeholder'       => __( 'None', 'acf-frontend-form-element' ),
	),
	array(
		'key'               => 'submissions_list_shortcode',
		'label'             => __( 'Submissions Approval Shortcode', 'acf-frontend-form-element' ),
		'type'              => 'message',
		'instructions'      => __( 'Use this shortcode to show a list of this form\'s submissions.', 'acf-frontend-form-element' ),
		'message'           => sprintf( '<code>%s</code> ', $form_shortcode ) . '<button type="button" data-prefix="frontend_admin submissions" data-value="' . $post->ID . '" class="copy-shortcode"> ' . $icon_path .
		' ' . __( 'Copy Code', 'acf-frontend-form-element' ) . '</button>',
		'conditional_logic' => $save_submissions,
	),
	array(
		'key'               => 'no_submissions_message',
		'label'             => __( 'No Submissions Message', 'acf-frontend-form-element' ),
		'type'              => 'textarea',
		'instructions'      => __( 'Show a message if no submissions have been received yet. Leave blank for no message.', 'acf-frontend-form-element' ),
		'required'          => 0,
		'rows'              => 3,
		'placeholder'       => __( 'There are no submissions for this form.', 'acf-frontend-form-element' ),
		'conditional_logic' => $save_submissions,
	),
	array(
		'key'               => 'total_submissions',
		'label'             => __( 'Total Submissions', 'acf-frontend-form-element' ),
		'type'              => 'number',
		'instructions'      => __( 'Limit the amount of shown in total.', 'acf-frontend-form-element' ),
		'conditional_logic' => $save_submissions,
		'placeholder'       => __( 'All', 'acf-frontend-form-element' ),
		'min'               => 1,
	),
	array(
		'key'               => 'submissions_per_page',
		'label'             => __( 'Number of Submissions Per Load', 'acf-frontend-form-element' ),
		'type'              => 'number',
		'instructions'      => __( 'Limit the amount of submissions loaded each time. Default is 10', 'acf-frontend-form-element' ),
		'conditional_logic' => $save_submissions,
		'placeholder'       => 10,
		'min'               => 1,
	),
);


return $fields;
