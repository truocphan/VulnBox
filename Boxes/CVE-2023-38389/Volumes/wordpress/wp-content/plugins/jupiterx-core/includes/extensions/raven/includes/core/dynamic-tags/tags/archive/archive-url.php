<?php
namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags\Archive;

use Elementor\Core\DynamicTags\Data_Tag;

defined( 'ABSPATH' ) || die();

class Archive_URL extends Data_Tag {

	public function get_name() {
		return 'archive-url';
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Archive URL', 'jupiterx-core' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function get_value( array $options = [] ) {
		if ( is_category() || is_tag() || is_tax() ) {
			return get_term_link( get_queried_object() );
		}

		if ( is_author() ) {
			return get_author_posts_url( get_queried_object_id() );
		}

		if ( is_year() ) {
			return get_year_link( get_query_var( 'year' ) );
		}

		if ( is_month() ) {
			return get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
		}

		if ( is_day() ) {
			return get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
		}

		return get_post_type_archive_link( get_post_type() );
	}
}

