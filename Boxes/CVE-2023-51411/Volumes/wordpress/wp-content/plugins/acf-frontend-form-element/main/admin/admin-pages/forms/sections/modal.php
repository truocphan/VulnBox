<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

return array(
	array(
		'key'          => 'show_in_modal',
		'label'        => __( 'Show in Modal', 'acf-frontend-form-element' ),
		'type'         => 'true_false',
		'instructions' => '',
		'required'     => 0,
		'ui'           => 1,
	),
	array(
		'key'               => 'modal_button_text',
		'label'             => __( 'Modal Button Text', 'acf-frontend-form-element' ),
		'type'              => 'text',
		'instructions'      => '',
		'required'          => 0,
		'conditional_logic' => array(
			array(
				array(
					'field'    => 'show_in_modal',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
	),

);
