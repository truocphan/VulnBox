<?php
/**
 * Class for parameter-based Section querying
 *
 * @package  Masteriyo\Query
 * @version 1.0.0
 * @since   1.0.0
 */

namespace Masteriyo\Query;

use Masteriyo\Enums\PostStatus;
use Masteriyo\Abstracts\ObjectQuery;

defined( 'ABSPATH' ) || exit;

/**
 * Section query class.
 */
class SectionQuery extends ObjectQuery {

	/**
	 * Valid query vars for sections.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_default_query_vars() {
		return array_merge(
			parent::get_default_query_vars(),
			array(
				'description'   => '',
				'menu_order'    => '',
				'parent_id'     => '',
				'course_id'     => '',
				'date_created'  => '',
				'date_modified' => '',
				'status'        => array( PostStatus::DRAFT, PostStatus::PENDING, PostStatus::PVT, PostStatus::PUBLISH ),
			)
		);
	}

	/**
	 * Get sections matching the current query vars.
	 *
	 * @since 1.0.0
	 *
	 * @return Masteriyo\Models\Section[] Section objects
	 */
	public function get_sections() {
		/**
		 * Filters section object query args.
		 *
		 * @since 1.0.0
		 *
		 * @param array $query_args The object query args.
		 */
		$args    = apply_filters( 'masteriyo_section_object_query_args', $this->get_query_vars() );
		$results = masteriyo( 'section.store' )->query( $args );

		/**
		 * Filters section object query results.
		 *
		 * @since 1.0.0
		 *
		 * @param Masteriyo\Models\Section[] $results The query results.
		 * @param array $query_args The object query args.
		 */
		return apply_filters( 'masteriyo_section_object_query', $results, $args );
	}
}
