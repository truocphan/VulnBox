<?php

/**
 * Check singular conditions if match current WordPress page.
 *
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Jupiterx_Singular_Condition {

	/**
	 * WooCommerce pages getting handled by singular section.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_supported_page() {
		return [
			'checkout-page',
			'cart-page',
			'empty-cart-page',
			'thankyou-page',
			'my-account-user',
			'my-account-guest',
		];
	}

	/**
	 * Gets a condition array and check if it's match with current WordPress singular page.
	 *
	 * @param array $condition
	 * @param object $query @get_queried_object().
	 * @return boolean
	 */
	public function sub_condition( $condition, $query, $post ) {
		// Disable customizer blog elements.
		$this->disable_customizer_blog_post_elements( $post );

		if ( 'all' === $condition[1] ) {
			return true;
		}

		if ( 'front_page' === $condition[1] && ( is_front_page() || is_home() ) ) {
			return true;
		}

		if ( 'error_404' === $condition[1] && is_404() ) {
			return true;
		}

		// Handle woocommerce pages.
		if ( function_exists( 'is_woocommerce' ) && in_array( $condition[1], $this->woocommerce_supported_page(), true ) ) {
			// Checkout page.
			if ( 'checkout-page' === $condition[1] && ( is_checkout() ) ) {
				return true;
			}

			// Cart page.
			if ( 'cart-page' === $condition[1] && ( is_cart() ) ) {
				return true;
			}

			// Empty cart page.
			if ( 'empty-cart-page' === $condition[1] && is_cart() && WC()->cart->is_empty() ) {
				return true;
			}

			// Thank you page.
			if ( 'thankyou-page' === $condition[1] && is_wc_endpoint_url( 'order-received' ) ) {
				return true;
			}

			// My account page, user logged in.
			if ( 'my-account-user' === $condition[1] && is_account_page() && is_user_logged_in() ) {
				return true;
			}

			// My account page, user is not logged in.
			if ( 'my-account-guest' === $condition[1] && is_account_page() && ! is_user_logged_in() ) {
				return true;
			}
		}

		if ( 'any_child_of' === $condition[1] ) {
			$parent  = (int) $condition[2][0];
			$child   = $query->ID;
			$parents = get_post_ancestors( $child );

			if ( empty( $parents ) || empty( $parent ) ) {
				return false;
			}

			if ( 'all' === $parent ) {
				return true;
			}

			if ( in_array( $parent, $parents, true ) ) {
				return true;
			}

			return false;
		}

		if ( 'child_of' === $condition[1] ) {
			$parent   = (int) $condition[2][0];
			$args     = [
				'numberposts' => -1,
				'post_type'   => 'any',
				'post_status' => 'any',
				'post_parent' => (int) $parent,
				'fields'      => 'ids',
			];
			$child    = $query->ID;
			$children = get_children( $args );
			$parents  = get_post_ancestors( $child );

			if ( 'all' === $parent && ! empty( $parents ) ) {
				return true;
			}

			if ( in_array( $child, $children, true ) ) {
				return true;
			}

			return false;
		}

		// All posts of a singular and or certain id of an singular. [singular, post, all] || [singular, post, 10].
		if ( strpos( $condition[1], 'single' ) !== false ) {
			$split     = explode( '_', $condition[1], 2 );
			$post_type = $split[1];

			if ( is_singular( $post_type ) ) {
				if ( 'all' === $condition[2][0] && ! is_front_page() ) {
					return true;
				}

				if ( (int) $condition[2][0] === $query->ID ) {
					return true;
				}
			}
		}

		// Exceptional for some types : post & page
		$exceptional = [ 'post_in_category', 'post_in_category_children', 'post_in_post_tag' ];

		if ( in_array( $condition[1], $exceptional, true ) ) {
			return $this->check_exceptional( $condition, $query );
		}

		// Check if current post belongs author by id cmd[2] : post_by_author || page_by_author || product_by_author || portfolio_by_author.
		if ( false !== strpos( $condition[1], 'author' ) && $query->post_author === $condition[2][0] ) {
			$type = explode( '@by_author', $condition[1] );
			if ( is_singular( $type[0] ) ) {
				return true;
			}
		}

		if ( false === strpos( $condition[1], '@' ) ) {
			return false;
		}

		// Check if current singular is belongs to a taxonomy term of certain post type : [singular, post_category, all] || [singular, post_category, uncategorized].
		$tax       = explode( '@', $condition[1], 2 );
		$term      = $condition[2][0];
		$taxonomy  = $tax[1];
		$post_type = $tax[0];

		if ( is_singular( $post_type ) && 'all' === $term && has_term( '', $taxonomy, $query->ID ) ) {
			return true;
		}

		if ( is_singular( $post_type ) && has_term( $term, $taxonomy, $query->ID ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Disable customizer blog elements when layout builder template is applied.
	 *
	 * @param int $post post id.
	 * @since 2.0.0
	 */
	private function disable_customizer_blog_post_elements( $post ) {
		if ( 'single' !== get_post_meta( $post, '_elementor_template_type', true ) ) {
			return;
		}

		add_filter( 'jupiterx_apply_single_blog_customizer_elements', function() {
			return false;
		} );
	}

	/**
	 * Checking some of post post-type in standalone method.
	 *
	 * @since 2.5.0
	 * @param array  $condition condition array.
	 * @param object $query global query.
	 */
	private function check_exceptional( $condition, $query ) {
		switch ( $condition[1] ) {
			case 'post_in_category':
				$post_type = 'post';
				$taxonomy  = 'category';
				break;
			case 'post_in_category_children':
				$post_type = 'post';
				$taxonomy  = 'category';
				break;
			case 'post_in_post_tag':
				$post_type = 'post';
				$taxonomy  = 'post_tag';
				break;
		}

		$term = $condition[2][0];

		if ( is_singular( $post_type ) && 'all' === $term && has_term( '', $taxonomy, $query->ID ) ) {
			return true;
		}

		if ( is_singular( $post_type ) && has_term( $term, $taxonomy, $query->ID ) ) {
			return true;
		}

		return false;
	}
}
