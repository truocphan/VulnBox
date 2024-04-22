<?php

/** @var \MasterStudy\Lms\Plugin $plugin */

use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;
use MasterStudy\Lms\Plugin\PostType;

add_action( 'init', array( $plugin, 'init' ) );
add_action( 'rest_api_init', array( $plugin, 'register_api' ) );

add_action(
	'plugins_loaded',
	function () use ( $plugin ) {
		$plugin->register_addons( apply_filters( 'masterstudy_lms_plugin_addons', array() ) );
	}
);

add_action(
	'delete_post',
	function ( int $post_id, \WP_Post $post ) {
		if ( PostType::COURSE === $post->post_type ) {
			( new CurriculumSectionRepository() )->delete_course_sections( $post_id );
		}
	},
	10,
	2
);

add_action(
	'dp_duplicate_post',
	function ( $post_id, $post ) {
		if ( PostType::COURSE === $post->post_type ) {
			( new CurriculumRepository() )->duplicate_curriculum( $post->ID, $post_id );
		}
	},
	10,
	2
);

function masterstudy_lms_duplicate_wpml_curriculum( $master_post_id, $post_id, $language_code ) {
	if ( PostType::COURSE === get_post_type( $post_id ) ) {
		$sections = ( new CurriculumSectionRepository() )->get_course_section_ids( $post_id );

		if ( empty( $sections ) ) {
			( new CurriculumRepository() )->duplicate_curriculum( $master_post_id, $post_id, $language_code );
		}
	}
}

add_action(
	'wpml_after_save_post',
	function ( $post_id, $trid, $language_code ) {
		if ( 'publish' === get_post_status( $post_id ) ) {
			masterstudy_lms_duplicate_wpml_curriculum( $trid, $post_id, $language_code );
		}
	},
	10,
	3
);

add_action(
	'icl_make_duplicate',
	function ( $master_post_id, $target_lang, $post_array, $target_post_id ) {
		masterstudy_lms_duplicate_wpml_curriculum( $master_post_id, $target_post_id, $target_lang );
	},
	10,
	4
);

add_action(
	'icl_pro_translation_completed',
	function ( $post_id, $fields, $job ) {
		if ( ! empty( $job->original_doc_id ) ) {
			masterstudy_lms_duplicate_wpml_curriculum( $job->original_doc_id, $post_id, $job->language_code ?? '' );
		}
	},
	10,
	3
);

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 */
function masterstudy_lms_gutenberg_blocks_init() {
	$blocks = array(
		'cta',
		'icon',
		'button',
		'testimonials',
		'iconbox',
		'adaptive-box',
		'advanced-text',
	);

	foreach ( $blocks as $block ) {
		register_block_type( MS_LMS_PATH . '/assets/gutenberg/blocks/' . $block );
	}
}
add_action( 'init', 'masterstudy_lms_gutenberg_blocks_init' );
