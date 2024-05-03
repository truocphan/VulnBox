<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'acf_add_local_field' ) ) :

	acf_add_local_field(
		array(
			'key'      => 'frontend_admin_title',
			'label'    => __( 'Title', 'acf-frontend-form-element' ),
			'required' => true,
			'name'     => 'frontend_admin_title',
			'type'     => 'post_title',
		)
	);
	acf_add_local_field(
		array(
			'key'      => 'frontend_admin_term_name',
			'label'    => __( 'Name', 'acf-frontend-form-element' ),
			'required' => true,
			'name'     => 'frontend_admin_term_name',
			'type'     => 'term_name',
		)
	);

	acf_add_local_field(
		array(
			'key'      => 'frontend_admin_custom_term',
			'label'    => __( 'Value', 'acf-frontend-form-element' ),
			'required' => true,
			'name'     => 'frontend_admin_custom_term',
			'type'     => 'text',
		)
	);


endif;
