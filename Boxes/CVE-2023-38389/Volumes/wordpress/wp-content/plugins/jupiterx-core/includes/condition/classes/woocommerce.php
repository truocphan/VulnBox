<?php

/**
 * Check woocommerce conditions if match current WordPress page.
 *
 * @return boolean
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class Jupiterx_Woocommerce_Condition {

	public function sub_condition( $condition, $query, $post ) {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}

		// Entire Shop
		if ( 'entire-shop' === $condition[1] && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
			return true;
		}

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
			add_filter( 'jupiterx_determines_main_checkout_using_layout_builder', '__return_true' );
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

		// Woocommerce Archive Section.
		if ( 'all_product_archive' === $condition[1] && ( is_product_category() || is_shop() || is_product_tag() ) ) {
			return true;
		}

		// Shop page.
		if ( 'shop_archive' === $condition[1] && is_shop() ) {
			return true;
		}

		// TODO : add search result !! is woo search result is as same as wp search ?

		// Product category archive , child cats not included.
		if ( 'product_cat_archive' === $condition[1] ) {
			if ( 'all' === $condition[2][0] && is_product_category() ) {
				return true;
			}

			if ( is_product_category( $condition[2][0] ) ) {
				return true;
			}
		}

		// Product tag archive.
		if ( 'product_tag_archive' === $condition[1] ) {
			if ( 'all' === $condition[2][0] && is_product_tag() ) {
				return true;
			}

			if ( is_product_tag( $condition[2][0] ) ) {
				return true;
			}
		}

		// Woocommerce Single Product Section.
		if ( ! is_product() ) {
			return false;
		}

		if ( 'single_product' === $condition[1] ) {
			// All single Products.
			if ( 'all' === $condition[2][0] ) {
				return true;
			}

			// By ID.
			if ( is_single( $condition[2][0] ) ) {
				return true;
			}
		}

		// Checks if a product belongs to a term( category ).
		if ( 'in_product_cat' === $condition[1] ) {
			if ( 'all' === $condition[2][0] && has_term( '', 'product_cat', $query->ID ) ) {
				return true;
			}

			if ( has_term( $condition[2][0], 'product_cat', $query->ID ) ) {
				return true;
			}
		}

		// Checks if a product belongs to a child of a term ( we need parent id cmd[3] = parent id ).
		if ( 'in_product_cat_children' === $condition[1] ) {
			$parent   = $condition[2][0];
			$children = get_term_children( $parent, 'product_cat' );
			foreach ( $children as $child ) {
				if ( has_term( $child, 'product_cat', $query->ID ) ) {
					return true;
				}
			}
		}

		// Product Tags.
		if ( 'in_product_tag' === $condition[1] ) {
			// All tags selected.
			if ( 'all' === $condition[2][0] && has_term( '', 'product_tag', $query->ID ) ) {
				return true;
			}

			// Certain tag selected.
			if ( has_term( $condition[2][0], 'product_tag', $query->ID ) ) {
				return true;
			}
		}

		if ( 'product_by_author' === $condition[1] ) {
			if ( 'all' === $condition[2][0] ) {
				return true;
			}

			if ( $query->post_author === $condition[2][0] ) {
				return true;
			}
		}

		return false;
	}
}
