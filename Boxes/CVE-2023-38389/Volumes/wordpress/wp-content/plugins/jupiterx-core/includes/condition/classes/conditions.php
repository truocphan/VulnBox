<?php
/**
 * Jupiterx single condition manager.
 *
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Jupiterx_Conditions_Check {

	public function __construct() {
		require_once __DIR__ . '/singulars.php';
		require_once __DIR__ . '/archives.php';
		require_once __DIR__ . '/woocommerce.php';
		require_once __DIR__ . '/users.php';

		$this->singulars   = new Jupiterx_Singular_Condition();
		$this->archives    = new Jupiterx_Archives_Condition();
		$this->woocommerce = new Jupiterx_Woocommerce_Condition();
		$this->users       = new Jupiterx_Users_Condition();
	}

	/**
	 * Gets template layout type.
	 *
	 * @since 2.5.0
	 * @param int $post_id post id.
	 */
	private function get_layout( $post_id ) {
		return get_post_meta( $post_id, 'jx-layout-type', true );
	}

	/**
	 * Handle each condition.
	 *
	 * @param array $condition
	 * @param array $query get_queried_object()
	 * @return boolean
	 * @since 2.0.0
	 */
	public function conditions( $condition, $query, $post ) {
		$result = false;
		$layout = $this->get_layout( $post );

		if ( empty( $condition[0] ) || '' === $condition[0] ) {
			return false;
		}

		if ( 'entire' === $condition[0] ) {
			$result = true;
		}

		if ( 'maintenance' === $condition[0] ) {
			$this->maintenance();
		}

		// Singular and non WooCommerce related pages.
		if ( 'singular' === $condition[0] && true === $this->is_singular() ) {
			$result = $this->singulars->sub_condition( $condition, $query, $post );
		}

		// 404 page.
		if ( 'singular' === $condition[0] && true !== $this->is_singular() && is_404() ) {
			$result = $this->singulars->sub_condition( $condition, $query, $post );
		}

		// Archive and it is not WooCommerce pages.
		if ( 'archive' === $condition[0] && true === $this->is_archive() ) {
			$result = $this->archives->sub_condition( $condition, $query, $post );
		}

		if ( 'woocommerce' === $condition[0] ) {
			$result = $this->woocommerce->sub_condition( $condition, $query, $post );
		}

		if ( 'users' === $condition[0] ) {
			$result = $this->users( $condition, $layout, $query );
		}

		return $result;
	}

	/**
	 * Proceed to maintenance.
	 *
	 * @since 2.5.0
	 * @return boolean
	 */
	private function maintenance() {
		if ( true === get_theme_mod( 'jupiterx_maintenance' ) && ! is_user_logged_in() ) {
			$this->maintenance_mode_hooks();
			return true;
		}

		return false;
	}

	/**
	 * Proceed to users.
	 *
	 * @since 2.5.0
	 * @param array  $condition condition array.
	 * @param string $layout template type.
	 * @param object $query current query.
	 * @return boolean
	 */
	private function users( $condition, $layout, $query ) {
		if ( empty( $layout ) ) {
			return $this->users->sub_condition( $condition );
		}

		$general = [ 'header', 'footer', 'page-title-bar' ];

		if ( 'archive' === $layout && true === $this->is_archive() ) {
			return $this->users->sub_condition( $condition );
		}

		if ( 'single' === $layout && true === $this->is_singular() ) {
			return $this->users->sub_condition( $condition );
		}

		if ( in_array( $layout, $general, true ) ) {
			return $this->users->sub_condition( $condition );
		}

		if ( 'product' === $layout && is_singular( 'product' ) ) {
			return $this->users->sub_condition( $condition );
		}

		if ( ! function_exists( 'is_shop' ) ) {
			return false;
		}

		if ( 'product-archive' === $layout && ( $this->is_product_category( $query ) || is_shop() ) ) {
			return $this->users->sub_condition( $condition );
		}

		return false;
	}

	/**
	 * Checks if we are in any taxonomy of product or not.
	 *
	 * @since 2.5.0
	 * @param object $query current query.
	 * @return boolean
	 */
	private function is_product_category( $query ) {
		if ( ! is_archive() || empty( $query->taxonomy ) ) {
			return false;
		}

		$taxonomy   = $query->taxonomy;
		$taxonomies = get_object_taxonomies( 'product' );

		return in_array( $taxonomy, $taxonomies, true );
	}

	/**
	 * Checks if singular page, except single products.
	 *
	 * @since 2.5.0
	 * @return boolean
	 */
	private function is_singular() {
		if ( is_singular() || is_single() || is_home() || is_front_page() ) {

			// Woocommerce related conditions must be managed by woocommerce class itself.
			if ( true === $this->is_woo_single_product() ) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Checks if single product.
	 *
	 * @since 2.5.0
	 * @return boolean
	 */
	private function is_woo_single_product() {
		if ( function_exists( 'is_woocommerce' ) && is_product() ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if we are in woocommerce pages.
	 *
	 * @since 2.5.0
	 * @return boolean
	 */
	private function is_archive() {
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( is_archive() || is_search() || 'post' === $post_type || $this->is_blog_page() ) {

			// Let WooCommerce related pages get handled by WooCommerce class.
			if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Checks if we are at blog page.
	 *
	 * @since 2.5.0
	 */
	private function is_blog_page() {
		$blog_page = get_option( 'page_for_posts' );
		$page_id   = get_queried_object_id();

		if ( empty( $blog_page ) || empty( $page_id ) ) {
			return false;
		}

		if ( (int) $blog_page === (int) $page_id ) {
			return true;
		}

		return false;
	}

	/**
	 * Used hooks when maintenance mode is on.
	 *
	 * @since 2.0.0
	 */
	private function maintenance_mode_hooks() {
		jupiterx_remove_action( 'jupiterx_main_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_main_footer_partial_template' );
		jupiterx_remove_action( 'jupiterx_header_partial_template' );
		jupiterx_remove_action( 'jupiterx_footer_partial_template' );
	}
}
