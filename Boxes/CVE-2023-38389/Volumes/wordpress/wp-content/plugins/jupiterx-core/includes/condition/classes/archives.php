<?php

/**
 * Check archive conditions if match current WordPress page.
 *
 * @return boolean
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Jupiterx_Archives_Condition {

	public function sub_condition( $condition, $query, $post ) {
		if ( 'all' === $condition[1] && is_archive() ) {
			return true;
		}

		// All author archives or certain author archive.
		if ( 'by_author' === $condition[1] ) {
			if ( 'all' === $condition[2][0] && is_author() ) {
				return true;
			}

			if ( is_author( $condition[2][0] ) ) {
				return true;
			}
		}

		// Date archive.
		if ( 'date' === $condition[1] && is_date() ) {
			return true;
		}

		// Search archive.
		if ( 'search' === $condition[1] && is_search() ) {
			return true;
		}

		if ( 'single_post' === $condition[1] ) {
			$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

			if ( 'post' === $post_type || is_home() ) {
				return true;
			}

			return false;
		}

		// Post tag all.
		if ( 'post_in_post_tag' === $condition[1] && 'all' === $condition[2][0] && is_tag() ) {
			return true;
		}

		// Post tag single.
		if ( 'post_in_post_tag' === $condition[1] && 'all' !== $condition[2][0] && is_tag( $condition[2][0] ) ) {
			return true;
		}

		// Post category all.
		if ( 'post_in_category' === $condition[1] && 'all' === $condition[2][0] && is_category() ) {
			return true;
		}

		// Post category single.
		if ( 'post_in_category' === $condition[1] && 'all' !== $condition[2][0] && is_category( $condition[2][0] ) ) {
			return true;
		}

		if ( 'post_in_category_children' === $condition[1] ) {
			return $this->in_post_category_children( $query, $condition );
		}

		// All of the custom post type archive.
		if ( post_type_exists( $condition[1] ) && is_post_type_archive( $condition[1] ) ) {
			return true;
		}

		// Prevent above to keep checking if not necessary.
		if ( ! strpos( $condition[1], '@' ) ) {
			return false;
		}

		if ( strpos( $condition[1], 'child_of' ) !== false ) {
			return $this->manage_taxonomy_children( $condition, $query );
		}

		/**
		 * IF none of above, condition must be about post type archive.
		 * There are 2 scenario for each post type:
		 * 1. All term taxonomies of the post type is selected.
		 * 2. Certain term of a taxonomy is selected.
		*/
		$type      = explode( '@', $condition[1], 2 );
		$post_type = $type[0];
		$taxonomy  = $type[1];
		$term      = $condition[2][0];

		//1. Certain taxonomy of post type selected and all terms of taxonomy selected.
		if ( is_tax( $taxonomy ) && 'all' === $term ) {
			return true;
		}

		//2. Certain taxonomy and certain term archive selected.
		if ( is_tax( $taxonomy, $term ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks any child of and direct child of condition for a condition array.
	 *
	 * @since 2.5.0
	 * @param array  $condition condition array.
	 * @param object $query current query.
	 * @return boolean
	 * @access private
	 */
	private function manage_taxonomy_children( $condition, $query ) {
		$type      = explode( '@', $condition[1], 2 );
		$post_type = $type[0];
		$term      = $condition[2][0];

		if ( empty( $query->term_id ) ) {
			return false;
		}

		$child          = $query->term_id;
		$queried_parent = $query->parent;

		if ( strpos( $type[1], 'direct_child_of_' ) !== false ) {
			$taxonomy = trim( str_replace( 'direct_child_of_', '', $type[1] ) );

			// If we are in right taxonomy archive - All direct child selected. - current term is direct child of condition term.
			if ( is_tax( $taxonomy ) && 'all' === $term && 0 !== $queried_parent ) {
				return true;
			}

			// If parent is 0 , return and do not continue at all.
			if ( 'all' === $term && 0 === $queried_parent ) {
				return false;
			}

			$term = (int) $term;

			// If certain direct child is selected.
			if ( is_tax( $taxonomy ) && $term === $queried_parent ) {
				return true;
			}

			return false;
		}

		if ( strpos( $type[1], 'any_child_of_' ) !== false ) {
			// First check all : if parent isn't 0
			if ( 'all' === $term && 0 !== $queried_parent ) {
				return true;
			}

			// If parent is 0 , return and do not continue at all.
			if ( 'all' === $term && 0 === $queried_parent ) {
				return false;
			}

			$term      = (int) $term;
			$taxonomy  = trim( str_replace( 'any_child_of_', '', $type[1] ) );
			$args      = [
				'format' => 'slug',
				'link'   => false,
			];
			$ancestors = [];

			if ( is_tax( $taxonomy, $term ) ) {
				return false;
			}

			// Get all parents slug of current term id.
			$ancestors = $this->get_ancestors( $child, $taxonomy, $args );

			if ( empty( $ancestors ) ) {
				return false;
			}

			// If condition term exists in ancestors array.
			return in_array( $term, $ancestors, true );
		}

		return false;
	}

	/**
	 * Manage in post category condition.
	 *
	 * @param object $query current query.
	 * @param array  $condition condition array.
	 * @since 2.5.0
	 * @return boolean
	 */
	private function in_post_category_children( $query, $condition ) {
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! empty( $post_type ) ) {
			return false;
		}

		$queried_parent = $query->parent;
		$term           = $condition[2][0];
		$child          = $query->term_id;
		$args           = [
			'format' => 'slug',
			'link'   => false,
		];

		if ( 'all' === $term && is_category() && 0 !== $queried_parent ) {
			return true;
		}

		$term = (int) $term;

		if ( is_category( $term ) ) {
			return false;
		}

		$ancestors = $this->get_ancestors( $child, 'category', $args );

		if ( empty( $ancestors ) ) {
			return false;
		}

		return in_array( $term, $ancestors, true );
	}

	/**
	 * Get ancestors of a term.
	 *
	 * @param int    $child current query term_id.
	 * @param string $taxonomy taxonomy name.
	 * @param array  $args arguments.
	 * @return array
	 * @access private
	 * @since 2.5.0
	 */
	private function get_ancestors( $child, $taxonomy, $args ) {
		$ancestors = [];
		$parents   = get_term_parents_list( $child, $taxonomy, $args );

		if ( empty( $parents ) ) {
			return [];
		}

		$parents = explode( '/', $parents );

		// Remove last item cause it's empty.
		array_pop( $parents );

		if ( count( $parents ) < 1 ) {
			return [];
		}

		// Put all ancestor id in one array.
		foreach ( $parents as $parent ) {
			$ancestor    = get_term_by( 'slug', $parent, $taxonomy );
			$ancestors[] = $ancestor->term_id;
		}

		return $ancestors;
	}
}
