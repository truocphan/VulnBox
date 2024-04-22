<?php

namespace MasterStudy\Lms\Plugin;

use MasterStudy\Lms\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; //Exit if accessed directly
}

class Taxonomy {
	public const COURSE_CATEGORY   = 'stm_lms_course_taxonomy';
	public const QUESTION_CATEGORY = 'stm_lms_question_taxonomy';

	public const COURSE_CATEGORY_DEFAULT_SLUG = 'stm_lms_course_category';

	/**
	 * @return array[]
	 */
	public static function defaults( $course_category_slug ): array {
		// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralDomain
		return array(
			self::COURSE_CATEGORY   => array(
				'post_type' => PostType::COURSE,
				'args'      => array(
					'hierarchical'      => true,
					'labels'            => array(
						'name'              => _x( 'Courses category', 'taxonomy general name', Plugin::TRANSLATION_DOMAIN ),
						'singular_name'     => _x( 'Course category', 'taxonomy singular name', Plugin::TRANSLATION_DOMAIN ),
						'search_items'      => __( 'Search Courses category', Plugin::TRANSLATION_DOMAIN ),
						'all_items'         => __( 'All Courses category', Plugin::TRANSLATION_DOMAIN ),
						'parent_item'       => __( 'Parent Course category', Plugin::TRANSLATION_DOMAIN ),
						'parent_item_colon' => __( 'Parent Course category:', Plugin::TRANSLATION_DOMAIN ),
						'edit_item'         => __( 'Edit Course category', Plugin::TRANSLATION_DOMAIN ),
						'update_item'       => __( 'Update Course category', Plugin::TRANSLATION_DOMAIN ),
						'add_new_item'      => __( 'Add New Course category', Plugin::TRANSLATION_DOMAIN ),
						'new_item_name'     => __( 'New Course category Name', Plugin::TRANSLATION_DOMAIN ),
						'menu_name'         => __( 'Course category', Plugin::TRANSLATION_DOMAIN ),
					),
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => $course_category_slug ),
				),
			),
			self::QUESTION_CATEGORY => array(
				'post_type' => PostType::QUESTION,
				'args'      => array(
					'public'            => false,
					'labels'            => array(
						'name'              => _x( 'Questions category', 'taxonomy general name', Plugin::TRANSLATION_DOMAIN ),
						'singular_name'     => _x( 'Question category', 'taxonomy singular name', Plugin::TRANSLATION_DOMAIN ),
						'search_items'      => __( 'Search Questions category', Plugin::TRANSLATION_DOMAIN ),
						'all_items'         => __( 'All Questions category', Plugin::TRANSLATION_DOMAIN ),
						'parent_item'       => __( 'Parent Question category', Plugin::TRANSLATION_DOMAIN ),
						'parent_item_colon' => __( 'Parent Question category:', Plugin::TRANSLATION_DOMAIN ),
						'edit_item'         => __( 'Edit Question category', Plugin::TRANSLATION_DOMAIN ),
						'update_item'       => __( 'Update Question category', Plugin::TRANSLATION_DOMAIN ),
						'add_new_item'      => __( 'Add New Question category', Plugin::TRANSLATION_DOMAIN ),
						'new_item_name'     => __( 'New Question category Name', Plugin::TRANSLATION_DOMAIN ),
						'menu_name'         => __( 'Question category', Plugin::TRANSLATION_DOMAIN ),
					),
					'show_ui'           => true,
					'show_admin_column' => true,
					'query_var'         => true,
				),
			),
		);
		// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralDomain
	}

	/**
	 * @return array<\WP_Term>
	 */
	public static function all_categories(): array {
		return get_terms(
			array(
				'hide_empty' => false,
				'taxonomy'   => self::COURSE_CATEGORY,
			)
		);
	}
}
