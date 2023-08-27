<?php

namespace JupiterX_Core\Condition;

defined( 'ABSPATH' ) || die();

/**
 * Handle conditions arrays and translate them to user readable strings.
 *
 * @since 2.5.0
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Conditions_Logic {
	private static $instance = null;

	/**
	 * Instance of class
	 *
	 * @return object
	 * @since 2.5.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hold user roles of WordPress.
	 *
	 * @since 2.5.0
	 */
	public $user_roles = [];

	/**
	 * Hold post types of WordPress.
	 *
	 * @since 2.5.0
	 */
	public $post_types = [];

	/**
	 * Hold archives of WordPress.
	 *
	 * @since 2.5.0
	 */
	public $archives = [];

	/**
	 * Hold array of single conditions.
	 *
	 * @since 2.5.0
	 */
	public $conditions = [];

	/**
	 * Hold single condition array.
	 *
	 * @since 2.5.0
	 */
	public $condition = [];

	/**
	 * Hold final string of conditions.
	 *
	 * @since 2.5.0
	 */
	public $final_string = '';

	/**
	 * Construct
	 *
	 * @since 2.5.0
	 */
	public function __construct() {
		$this->users_role = self::get_user_roles();
		$this->post_types = self::get_post_types();
		$this->archives   = self::get_post_archive( $this->post_types );
	}

	/**
	 * Get user roles array.
	 *
	 * @since 2.5.0
	 * @return array
	 */
	public static function get_user_roles() {
		global $wp_roles;

		$all_roles      = $wp_roles->roles;
		$editable_roles = apply_filters( 'editable_roles', $all_roles );
		$roles          = [];

		foreach ( $editable_roles as $key => $details ) {
			$roles[ $key ] = $details['name'];
		}

		return $roles;
	}

	/**
	 * Get WordPress post types.
	 *
	 * @since 2.5.0
	 * @return array
	 */
	public static function get_post_types() {
		$post_types = [];
		$args       = [
			'public'   => true,
			'_builtin' => false,
		];

		$types = get_post_types( $args, 'object', 'and' );

		foreach ( $types as $type ) {
			$post_types[ $type->name ] = $type->label;
		}

		// Add default WordPress post types.
		$post_types['post'] = get_post_type_object( 'post' )->label;
		$post_types['page'] = get_post_type_object( 'page' )->label;

		return $post_types;
	}

	/**
	 * Get WordPress archives.
	 *
	 * @since 2.5.0
	 * @return array
	 */
	public static function get_post_archive( $post_types = [] ) {
		$archives   = [];
		$args       = [
			'public'   => true,
			'_builtin' => false,
		];
		$post_types = get_post_types( $args, 'object', 'and' );

		foreach ( $post_types as $post ) {
			// Escape post without archive.
			if ( false === $post->has_archive ) {
				continue;
			}

			$taxonomies = get_object_taxonomies( $post->name, 'object' );
			if ( empty( $taxonomies ) ) {
				continue;
			}

			foreach ( $taxonomies as $taxonomy ) {
				$archives[ $post->name ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		return $archives;
	}

	public function manage_conditions_array( $conditions ) {
		$this->conditions = $conditions;

		foreach ( $this->conditions as $condition ) {
			$this->manage_single_condition_array( $condition );
		}

		$string = trim( $this->final_string, ", \t\n" );

		return $string;
	}

	/**
	 * Get a single condition array and return human readable string for it.
	 *
	 * @param array $condition single condition array. each array has 4 element.
	 * @since 2.5.0
	 */
	public function manage_single_condition_array( $condition ) {
		if ( empty( $condition ) ) {
			return '-';
		}

		if ( 'exclude' === $condition['conditionA'] ) {
			return '';
		}

		$this->condition = $condition;

		$type = $condition['conditionB'];

		return call_user_func( [ $this, $type ] );
	}

	/**
	 * Entire website condition to string.
	 *
	 * @since 2.5.0
	 * @return string
	 */
	private function entire() {
		$this->attach_comma_separator( esc_html__( 'Entire website', 'jupiterx-core' ) );
	}

	/**
	 * Entire website condition to string.
	 *
	 * @since 2.5.0
	 * @return string
	 */
	private function maintenance() {
		$this->attach_comma_separator( esc_html__( 'Maintenance page', 'jupiterx-core' ) );
	}

	/**
	 * Handle related conditions to singular section.
	 *
	 * @since 2.5.0
	 */
	private function singular() {
		$case = $this->condition['conditionC'];

		switch ( $case ) {
			case 'all':
				$this->attach_comma_separator( esc_html__( 'All singulars', 'jupiterx-core' ) );
				break;
			case 'front_page':
				$this->attach_comma_separator( esc_html__( 'Front page', 'jupiterx-core' ) );
				break;
			case 'error_404':
				$this->attach_comma_separator( esc_html__( '404 page', 'jupiterx-core' ) );
				break;
			case 'by_author':
				$this->singular_by_author();
				break;
			case 'any_child_of':
				$this->singular_any_child_of();
				break;
			case 'child_of':
				$this->singular_child_of();
				break;
			case 'post_in_category':
				$this->singular_post_in_category();
				break;
			case 'post_in_category_children':
				$this->singular_post_in_category_children();
				break;
			case 'post_in_post_tag':
				$this->singular_post_in_post_tag();
				break;
			default:
				$this->manage_remaining_singulars();
		}
	}

	/**
	 * Convert singular by author condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_by_author() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Singular by all authors', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Singular belong to author', 'jupiterx-core' ) . ':' . get_the_author_meta( 'display_name', $this->condition['conditionD'][0] );

		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert singular any child of condition to proper string
	 *
	 * @since 2.5.0
	 */
	private function singular_any_child_of() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All singulars child', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Any child of', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];

		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert singular child of condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_child_of() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All singulars direct child', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'singulars direct child of', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];

		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert singular post in category conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_post_in_category() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All singulars posts', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Posts by category id', 'jupiterx-core' ) . ':' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert singular post that belong to a child category condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_post_in_category_children() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Posts by child category', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Posts by child category id', 'jupiterx-core' ) . ':' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert singular post by tag condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_post_in_post_tag() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Posts by tag', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Posts by tag id', 'jupiterx-core' ) . ':' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert and manage rest of remaining singular conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function manage_remaining_singulars() {
		// Handle if user going to select post type itself.
		if ( strpos( $this->condition['conditionC'], 'single' ) !== false ) {
			$post_type = explode( 'single_', $this->condition['conditionC'] )[1];

			if ( 'all' === $this->condition['conditionD'][0] ) {
				$string = esc_html__( 'All', 'jupiterx-core' ) . " {$this->post_types[ $post_type ]}";
				$this->attach_comma_separator( $string );
				return;
			}

			$string = "{$this->post_types[ $post_type ]} " . esc_html__( 'by ID', 'jupiterx-core' ) . " #{$this->condition['conditionD'][0]}";
			$this->attach_comma_separator( $string );
			return;
		}

		// Handle if user going to select post type posts by author.
		if ( strpos( $this->condition['conditionC'], 'author' ) !== false ) {
			$this->singular_post_type_by_author();
			return;
		}

		// Handle if user going to select post type posts based on taxonomies.
		if ( strpos( $this->condition['conditionC'], '@' ) !== false ) {
			$case      = explode( '@', $this->condition['conditionC'] );
			$post_type = $case[0];
			$taxonomy  = $case[1];

			if ( 'all' === $this->condition['conditionD'][0] ) {
				$string = esc_html__( 'All ', 'jupiterx-core' ) . " {$this->archive[ $post_type ][ $taxonomy ] } " . esc_html__( 'posts', 'jupiterx-core' );
				$this->attach_comma_separator( $string );
				return;
			}

			$string = esc_html__( 'All ', 'jupiterx-core' ) . " {$this->archive[ $post_type ][ $taxonomy ] } " . esc_html__( 'by taxonomy id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
			$this->attach_comma_separator( $string );
		}
	}

	/**
	 * Convert singular post type posts by author conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function singular_post_type_by_author() {
		$case      = $this->condition['conditionC'];
		$child     = $this->condition['conditionD'][0];
		$post_type = str_replace( '_by_author', '', $case );

		if ( strpos( $this->condition['conditionC'], '@by_author' ) !== false ) {
			$post_type = explode( '@', $this->condition['conditionC'] )[0];
		}

		if ( 'all' === $child ) {
			$string = esc_html__( 'All ', 'jupiterx-core' ) . " {$this->post_types[ $post_type ]}";
			$this->attach_comma_separator( $string );
			return;
		}

		$string = esc_html__( 'All ', 'jupiterx-core' ) . " {$this->post_types[ $post_type ]} " . esc_html__( 'by author' ) . ':' . get_the_author_meta( 'display_name', $this->condition['conditionD'][0] );
		$this->attach_comma_separator( $string );
	}

	/**
	 * Handle archive related condition and convert them to proper string.
	 *
	 * @since 2.5.0
	 */
	private function archive() {
		switch ( $this->condition['conditionC'] ) {
			case 'all':
				$this->attach_comma_separator( esc_html__( 'All archives', 'jupiterx-core' ) );
				break;
			case 'by_author':
				$this->archive_by_author();
				break;
			case 'date':
				$this->attach_comma_separator( esc_html__( 'Date archive', 'jupiterx-core' ) );
				break;
			case 'search':
				$this->attach_comma_separator( esc_html__( 'Search result', 'jupiterx-core' ) );
				break;
			case 'single_post':
				$this->archive_single_post();
				break;
			case 'post_in_category':
				$this->archive_post_in_category();
				break;
			case 'post_in_category_children':
				$this->archive_post_in_category_children();
				break;
			case 'post_in_post_tag':
				$this->archive_post_in_post_tag();
				break;
			default:
				$this->manage_remaining_archive_condition();

		}
	}

	/**
	 * Convert archive by author conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function archive_by_author() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All archives', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Archives by author', 'jupiterx-core' ) . ':' . get_the_author_meta( 'display_name', $this->condition['conditionD'][0] );
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert post archive condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function archive_single_post() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Post archives', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Post archive by id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert post archive of categories condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function archive_post_in_category() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All category archive', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Category archive by id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert post archive in children category to proper string.
	 *
	 * @since 2.5.0
	 */
	private function archive_post_in_category_children() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Children category archive', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Child archives of category', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert post archive of tags to peroper string
	 *
	 * @since 2.5.0
	 */
	private function archive_post_in_post_tag() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All post tag archives', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Post tag archive id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert remaining archive conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function manage_remaining_archive_condition() {
		$case  = $this->condition['conditionC'];
		$child = $this->condition['conditionD'][0];

		if ( strpos( $case, 'direct_child_of_' ) !== false ) {
			$case = explode( '@', $case );
			$tax  = str_replace( 'direct_child_of_', '', $case[1] );

			if ( 'all' === $child ) {
				$string = esc_html__( 'All direct child in the', 'jupiterx-core' ) . ' ' . $this->archives[ $case[0] ][ $tax ];
				$this->attach_comma_separator( $string );
				return;
			}

			$string = esc_html__( 'Direct child of the archive id', 'jupiterx-core' ) . ' #' . $child;
			$this->attach_comma_separator( $string );
			return;
		}

		if ( strpos( $case, 'any_child_of_' ) !== false ) {
			$case = explode( '@', $case );
			$tax  = str_replace( 'any_child_of_', '', $case[1] );

			if ( 'all' === $child ) {
				$string = esc_html__( 'Any child archive in the', 'jupiterx-core' ) . ' ' . $this->archives[ $case[0] ][ $tax ];
				$this->attach_comma_separator( $string );
				return;
			}

			$string = esc_html__( 'Child of the archive id', 'jupiterx-core' ) . ' #' . $child;
			$this->attach_comma_separator( $string );
			return;
		}

		if ( strpos( $case, '@' ) !== false ) {
			$case = explode( '@', $case );

			if ( 'all' === $child ) {
				$string = esc_html__( 'All archive of the ', 'jupiterx-core' ) . $this->archives[ $case[0] ][ $case[1] ];
				$this->attach_comma_separator( $string );
				return;
			}

			$string = $this->archives[ $case[0] ][ $case[1] ] . ' ' . esc_html__( 'archive id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
			$this->attach_comma_separator( $string );
			return;
		}

		if ( post_type_exists( $case ) ) {
			$this->attach_comma_separator( $this->post_types[ $case ] . ' ' . esc_html__( 'Archive', 'jupiterx-core' ) );
			return;
		}

		$string = $this->post_types[ $case ] . ' ' . esc_html__( 'archive id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Users conditions to string.
	 *
	 * @since 2.5.0
	 */
	private function users() {
		$case = $this->condition['conditionC'];

		switch ( $case ) {
			case 'all':
				$this->attach_comma_separator( esc_html__( 'All users', 'jupiterx-core' ) );
				break;
			case 'guests-users':
				$this->attach_comma_separator( esc_html__( 'Guest users', 'jupiterx-core' ) );
				break;
			case 'all-users':
				$this->attach_comma_separator( esc_html__( 'Logged in users', 'jupiterx-core' ) );
				break;
			default:
				$string = esc_html__( 'Users by', 'jupiterx-core' ) . ' ' . $this->users_role[ $case ] . ' ' . esc_html__( 'role', 'jupiterx-core' );
				$this->attach_comma_separator( $string );
		}
	}

	private function woocommerce() {
		$case = $this->condition['conditionC'];

		switch ( $case ) {
			case 'entire-shop':
				$this->attach_comma_separator( esc_html__( 'Entire shop', 'jupiterx-core' ) );
				break;
			case 'checkout-page':
				$this->attach_comma_separator( esc_html__( 'Checkout page', 'jupiterx-core' ) );
				break;
			case 'cart-page':
				$this->attach_comma_separator( esc_html__( 'Cart page', 'jupiterx-core' ) );
				break;
			case 'empty-cart-page':
				$this->attach_comma_separator( esc_html__( 'Empty cart page', 'jupiterx-core' ) );
				break;
			case 'thankyou-page':
				$this->attach_comma_separator( esc_html__( 'Order received page', 'jupiterx-core' ) );
				break;
			case 'my-account-user':
				$this->attach_comma_separator( esc_html__( 'User my account page', 'jupiterx-core' ) );
				break;
			case 'my-account-guest':
				$this->attach_comma_separator( esc_html__( 'Guest my account page', 'jupiterx-core' ) );
				break;
			case 'all_product_archive':
				$this->attach_comma_separator( esc_html__( 'All product archive', 'jupiterx-core' ) );
				break;
			case 'shop_archive':
				$this->attach_comma_separator( esc_html__( 'Shop page', 'jupiterx-core' ) );
				break;
			case 'woo_search':
				$this->attach_comma_separator( esc_html__( 'Search result', 'jupiterx-core' ) );
				break;
			case 'product_cat_archive':
				$this->woocommerce_product_cat_archive();
				break;
			case 'product_tag_archive':
				$this->woocommerce_product_tag_archive();
				break;
			case 'single_product':
				$this->woocommerce_single_product();
				break;
			case 'in_product_cat':
				$this->woocommerce_in_product_cat();
				break;
			case 'in_product_cat_children':
				$this->woocommerce_in_product_cat_children();
				break;
			case 'in_product_tag':
				$this->woocommerce_in_product_tag();
				break;
			case 'product_by_author':
				$this->woocommerce_product_by_author();
				break;
		}
	}

	/**
	 * Convert product cat archive condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_product_cat_archive() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Product category archive', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Product category archive id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert product tag archive condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_product_tag_archive() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'Product tag archive', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Product tag archive id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert woocommerce single product conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_single_product() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All products', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Single product id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert woocommerce products by cat conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_in_product_cat() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All products', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Products by category id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert product in sub categories conditions to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_in_product_cat_children() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All products of sub categories', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Sub categories of', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert products in tags condition to proper string.
	 *
	 * @since 2.5.0
	 */
	private function woocommerce_in_product_tag() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All products by tag', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Products by tag id', 'jupiterx-core' ) . ' #' . $this->condition['conditionD'][0];
		$this->attach_comma_separator( $string );
	}

	/**
	 * Convert product by author condition to proper string.
	 *
	 * @since next.
	 */
	private function woocommerce_product_by_author() {
		if ( 'all' === $this->condition['conditionD'][0] ) {
			$this->attach_comma_separator( esc_html__( 'All products', 'jupiterx-core' ) );
			return;
		}

		$string = esc_html__( 'Products by author', 'jupiterx-core' ) . ':' . get_the_author_meta( 'display_name', $this->condition['conditionD'][0] );
		$this->attach_comma_separator( $string );
	}

	/**
	 * Attach a comma as separator at end of each string to make it clear string.
	 *
	 * @param string $string condition human readable string.
	 * @since 2.5.0
	 * @return string
	 */
	private function attach_comma_separator( $string ) {
		$this->final_string .= $string . ', ';
	}
}
