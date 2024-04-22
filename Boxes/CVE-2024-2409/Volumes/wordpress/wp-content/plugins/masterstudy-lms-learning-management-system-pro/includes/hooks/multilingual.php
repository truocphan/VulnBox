<?php

add_filter( 'stm_lms_courses_page', 'stm_lms_courses_page_pro' );

function stm_lms_courses_page_pro( $page_id ) {
	return stm_lms_wpml_binding( $page_id, 'page' );
}

add_filter( 'stm_lms_instructors_page', 'stm_lms_instructors_page_pro' );

function stm_lms_instructors_page_pro( $page_id ) {
	return stm_lms_wpml_binding( $page_id, 'page' );
}

function stm_lms_wpml_binding( $id, $type ) {
	return apply_filters( 'wpml_object_id', $id, $type );
}
