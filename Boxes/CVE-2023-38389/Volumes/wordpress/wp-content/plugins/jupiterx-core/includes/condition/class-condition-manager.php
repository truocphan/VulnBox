<?php
/**
 * The file class that handles condition manager component.
 *
 * @package JupiterX_Core\Condition\
 *
 * @since 2.0.0
*/

use JupiterX_Core\Condition\Conditions_Logic;
use JupiterX_Core\Condition\Documents\{ Products, Page_Title_Bar, Product_Archive };
use Elementor\Plugin;

/**
 * Conditions manager class.
 *
 * @since 2.0.0
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.NPathComplexity)
*/
class JupiterX_Core_Condition_Manager {

	// Posts meta to save conditions of post.
	const JUPITERX_CONDITIONS_COMPONENT_META_NAME = 'jupiterx-condition-rules';

	// Post meta to save conditions as string.
	const JUPITERX_CONDITIONS_COMPONENT_META_STRING = 'jupiterx-condition-rules-string';

	// Option to save posts IDs that admin defined some conditions for them.
	const JUPITERX_POSTS_WITH_CONDITIONS = 'jupiterx-posts-with-conditions';

	/**
	 * Type of input
	 *
	 * @since 3.2.0
	 */
	private $string_type = null;

	/**
	 * Class instance.
	 *
	 * @since 2.0.0
	 * @var JupiterX_Core_Condition_Manager Class instance.
	*/
	private static $instance = null;

	/**
	 * Get a class instance.
	 *
	 * @since 2.0.0
	 * @return JupiterX_Core_Condition_Manager Class instance.
	*/
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class construct.
	 *
	 * @since 2.0.0
	*/
	public function __construct() {
		add_action( 'wp_ajax_jupiterx_conditional_manager', [ $this, 'ajax_handle' ] );

		jupiterx_core()->load_files(
			[
				'condition/classes/apply-condition',
			]
		);

		add_action( 'elementor/documents/register', [ $this, 'register_documents' ], 0, 1 );
		add_action( 'elementor/editor/footer', [ $this, 'add_editor_conditions_template' ] );
		add_action( 'wp_ajax_jupiterx_editor_save_conditions', [ $this, 'save_editor_conditions' ] );
	}

	/**
	 * Register documents.
	 *
	 * @since 2.5.0
	 * @param object $documents_manager document manager.
	 */
	public function register_documents( $documents_manager ) {
		jupiterx_core()->load_files(
			[
				'condition/documents/page-title-bar',
				'condition/documents/products',
				'condition/documents/product-archive',
			]
		);

		$types = [
			'page-title-bar'  => Page_Title_Bar::get_class_full_name(),
		];

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$types['product']         = Products::get_class_full_name();
			$types['product-archive'] = Product_Archive::get_class_full_name();
		}

		foreach ( $types as $type => $class_name ) {
			$documents_manager->register_document_type( $type, $class_name );
		}
	}

	/**
	 * Add required template to editor to handle conditions in editor.
	 *
	 * @since 2.5.0
	 */
	public function add_editor_conditions_template() {
		Plugin::$instance->common->add_template( jupiterx_core()->plugin_dir() . '/includes/condition/templates/condition-templates.php' );
	}

	/**
	 * Handle ajax call from Elementor editor to save conditions.
	 *
	 * @since 2.5.0
	 */
	public function save_editor_conditions() {
		check_ajax_referer( 'elementor_ajax', 'nonce' );
		$this->save_post_conditions();
	}

	public function ajax_handle() {
		check_ajax_referer( 'jupiterx_control_panel', 'nonce' );

		if ( ! current_user_can( 'edit_others_posts' ) || ! current_user_can( 'edit_others_pages' ) ) {
			wp_send_json_error( 'You do not have access to this section', 'jupiterx-core' );
		}

		$action = filter_input( INPUT_POST, 'sub_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		call_user_func( [ $this, $action ] );
	}

	/**
	 * Gets singular and archive list.
	 *
	 * @return array
	 * @since 2.5.0
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	*/
	private function get_list_singular() {
		$list    = filter_input( INPUT_POST, 'list', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$section = filter_input( INPUT_POST, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$data    = [];

		foreach ( $list as $type ) {
			$data[] = $this->get_data( $type, $section );
		}

		wp_send_json_success( $data );
	}

	/**
	 * Get conditions data.
	 *
	 * @param string $type condition type.
	 * @param string $section section name.
	 * @return array
	 * @since 2.5.0
	 */
	public function get_data( $type, $section ) {
		if ( 'woocommerce' === $type ) {
			$data = [
				'list' => $this->get_woocommerce_options( $section ),
				'type' => 'woocommerce',
			];

			return $data;
		}

		if ( 'users' === $type ) {
			$data = [
				'list' => $this->get_user_status_options(),
				'type' => $type,
			];

			return $data;
		}

		// First we get default WordPress singulars.
		$list = [
			''             => esc_html__( 'Select One', 'jupiterx-core' ),
			'all'          => esc_html__( 'All Singulars', 'jupiterx-core' ),
			'front_page'   => esc_html__( 'Front Page', 'jupiterx-core' ),
			'error_404'    => esc_html__( 'Not Found 404', 'jupiterx-core' ),
			'by_author'    => ( 'archive' === $type ) ? esc_html__( 'Author Archive', 'jupiterx-core' ) : esc_html__( 'By Author', 'jupiterx-core' ),
			'any_child_of' => esc_html__( 'Any Child Of', 'jupiterx-core' ),
			'child_of'     => esc_html__( 'Direct Child Of', 'jupiterx-core' ),
			'date'         => esc_html__( 'Date Archive', 'jupiterx-core' ),
			'search'       => esc_html__( 'Search Results', 'jupiterx-core' ),
			'post'         => [
				'single_post'               => ( 'archive' === $type ) ? esc_html__( 'Post Archive( Blog )', 'jupiterx-core' ) : esc_html__( 'Post', 'jupiterx-core' ),
				'post_in_category'          => esc_html__( 'In Category', 'jupiterx-core' ),
				'post_in_category_children' => esc_html__( 'In Category Children', 'jupiterx-core' ),
				'post_in_post_tag'          => esc_html__( 'In Post Tag', 'jupiterx-core' ),
				'post_by_author'            => esc_html__( 'Post By Author', 'jupiterx-core' ),
			],
			'page'         => [
				'single_page'    => esc_html__( 'Pages', 'jupiterx-core' ),
				'page_by_author' => esc_html__( 'Page By Author', 'jupiterx-core' ),
			],
			'attachment'        => [
				'single_attachment'    => esc_html__( 'Media', 'jupiterx-core' ),
				'attachment_by_author' => esc_html__( 'Media By Author', 'jupiterx-core' ),
			],
		];

		// Unset unnecessary values for each type.
		$list = $this->unset_unnecessary_values_of_options( $list, $type );

		// Now we find custom post types.
		$args = array(
			'public'            => true,
			'_builtin'          => false,
			'show_in_nav_menus' => true,
		);

		$post_types = get_post_types( $args, 'objects', 'and' );

		// If there is no post type, return default list.
		if ( ! $post_types ) {
			$data = [
				'list' => $list,
				'type' => $type,
			];

			return $data;
		}

		// If there is some post types, attach them and their taxonomies to list.
		$list = $this->attach_post_types_to_list( $post_types, $type, $list );

		$data = [
			'list' => $list,
			'type' => $type,
		];

		return $data;
	}

	/**
	 * Woocommerce options for frontend UI selection.
	 *
	 * @param string $section layout builder section.
	 * @return array
	 * @since 2.0.0
	 */
	private function get_woocommerce_options( $section ) {
		if ( 'single' === $section ) {
			return [
				''                   => esc_html__( 'Select One', 'jupiterx-core' ),
				'checkout-page'      => esc_html__( 'Checkout Page', 'jupiterx-core' ),
				'cart-page'          => esc_html__( 'Cart Page', 'jupiterx-core' ),
				'empty-cart-page'    => esc_html__( 'Empty Cart Page', 'jupiterx-core' ),
				'thankyou-page'      => esc_html__( 'Order Received Page', 'jupiterx-core' ),
				'my-account-user'    => esc_html__( 'My Account Page', 'jupiterx-core' ),
				'my-account-guest'   => esc_html__( 'My Account Login Page', 'jupiterx-core' ),
			];
		}

		if ( 'product' === $section ) {
			return [
				''                        => esc_html__( 'Select One', 'jupiterx-core' ),
				'single_product'          => esc_html__( 'Products', 'jupiterx-core' ),
				'in_product_cat'          => esc_html__( 'In Product Category', 'jupiterx-core' ),
				'in_product_cat_children' => esc_html__( 'In Child Product categories', 'jupiterx-core' ),
				'in_product_tag'          => esc_html__( 'In Product Tags', 'jupiterx-core' ),
				'product_by_author'       => esc_html__( 'Products By Author', 'jupiterx-core' ),
			];
		}

		if ( 'product-archive' === $section ) {
			return [
				''                    => esc_html__( 'Select One', 'jupiterx-core' ),
				'all_product_archive' => esc_html__( 'All Products Archive', 'jupiterx-core' ),
				'shop_archive'        => esc_html__( 'Shop Page', 'jupiterx-core' ),
				'woo_search'          => esc_html__( 'Search Results', 'jupiterx-core' ),
				'product_cat_archive' => esc_html__( 'Products Categories', 'jupiterx-core' ),
				'product_tag_archive' => esc_html__( 'Products Tags', 'jupiterx-core' ),
			];
		}

		$global_types = [ 'header', 'footer', 'page-title-bar', 'custom-snippet' ];

		if ( in_array( $section, $global_types, true ) ) {
			return [
				''                 => esc_html__( 'Select One', 'jupiterx-core' ),
				'entire-shop'      => esc_html__( 'Entire Shop', 'jupiterx-core' ),
				'checkout-page'    => esc_html__( 'Checkout Page', 'jupiterx-core' ),
				'cart-page'        => esc_html__( 'Cart Page', 'jupiterx-core' ),
				'empty-cart-page'  => esc_html__( 'Empty Cart Page', 'jupiterx-core' ),
				'thankyou-page'    => esc_html__( 'Order Received Page', 'jupiterx-core' ),
				'my-account-user'  => esc_html__( 'My Account Page', 'jupiterx-core' ),
				'my-account-guest' => esc_html__( 'My Account Login Page', 'jupiterx-core' ),
				'Products Archive' => [
					'all_product_archive' => esc_html__( 'All Products Archive', 'jupiterx-core' ),
					'shop_archive'        => esc_html__( 'Shop Page', 'jupiterx-core' ),
					'woo_search'          => esc_html__( 'Search Results', 'jupiterx-core' ),
					'product_cat_archive' => esc_html__( 'Products Categories', 'jupiterx-core' ),
					'product_tag_archive' => esc_html__( 'Products Tags', 'jupiterx-core' ),
				],
				'Products'        => [
					'single_product'          => esc_html__( 'Products', 'jupiterx-core' ),
					'in_product_cat'          => esc_html__( 'In Product Category', 'jupiterx-core' ),
					'in_product_cat_children' => esc_html__( 'In Child Product categories', 'jupiterx-core' ),
					'in_product_tag'          => esc_html__( 'In Product Tags', 'jupiterx-core' ),
					'product_by_author'       => esc_html__( 'Products By Author', 'jupiterx-core' ),
				],
			];
		}
	}

	/**
	 * User related options for frontend UI selection.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	private function get_user_status_options() {
		return [
			''                                                 => esc_html__( 'Select One', 'jupiterx-core' ),
			'all'                                              => esc_html__( 'All users', 'jupiterx-core' ),
			'guests-users'                                     => esc_html__( 'Not logged in as user', 'jupiterx-core' ),
			esc_html__( 'Logged in as user', 'jupiterx-core' ) => $this->list_user_role(),
		];
	}

	/**
	 * Retrieve users roles.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	private function list_user_role() {
		global $wp_roles;

		$all_roles      = $wp_roles->roles;
		$editable_roles = apply_filters( 'editable_roles', $all_roles );
		$roles          = [];

		$roles['all-users'] = esc_html__( 'All logged in users', 'jupiterx-core' );

		foreach ( $editable_roles as $key => $details ) {
			$roles[ $key ] = $details['name'];
		}

		return $roles;
	}

	/**
	 * Remove unnecessary array values for each type.
	 * also reduce class complexity.
	 *
	 * @param array $list
	 * @param string $type
	 * @return array
	 * @since 2.0.0
	 */
	private function unset_unnecessary_values_of_options( $list, $type ) {
		// Remove singular specific when type = archive.
		if ( 'archive' === $type ) {
			$list['all'] = esc_html__( 'All Archives', 'jupiterx-core' );

			unset( $list['error_404'] );
			unset( $list['front_page'] );
			unset( $list['any_child_of'] );
			unset( $list['child_of'] );
			unset( $list['page'] );
			unset( $list['attachment'] );
			unset( $list['post']['post_by_author'] );
			unset( $list['wooCommerce'] );
		}

		// Remove date & search archive from singular list.
		if ( 'singular' === $type ) {
			unset( $list['date'] );
			unset( $list['search'] );
		}

		return $list;
	}

	/**
	 * Attach post types and their taxonomies to list for frontend uses.
	 *
	 * @param array $post_types
	 * @param string $type
	 * @param array $list
	 * @return array
	 * @since 2.0.0
	 */
	private function attach_post_types_to_list( $post_types, $type, $list ) {
		$excluded = [ 'product', 'jupiterx-codes', 'jupiterx-fonts', 'jupiterx-icons' ];

		foreach ( $post_types as $post ) {
			// Escape post without archive.
			if ( false === $post->has_archive && 'archive' === $type ) {
				continue;
			}

			// Escape woocommerce product post type also, it will be managed by woocommerce section.
			if ( in_array( $post->name, $excluded, true ) ) {
				continue;
			}

			if ( 'singular' === $type ) {
				$list[ $post->label ][ "single_$post->name" ] = $post->label;
			} else {
				/* translators: 1: post type label 2:postfix */
				$list[ $post->label ][ $post->name ] = sprintf(
					'%1$s %2$s',
					$post->label,
					esc_html__( 'Archive', 'jupiterx-core' )
				);
			}

			// Attach taxonomies as options.
			$list = $this->add_taxonomies( $list, $post->name, $post->label, $type );

			// Attach by author as option to each custom post type for singulars.
			if ( 'archive' !== $type ) {
				/* translators: 1: post type label 2:postfix */
				$list[ $post->label ][ "$post->name@by_author" ] = sprintf(
					'%1$s %2$s',
					$post->label,
					esc_html( 'By Author', 'jupiterx-core' )
				);
			}
		}

		return $list;
	}

	/**
	 * Add taxonomies to list of singular array.
	 *
	 * @param array  $taxonomies.
	 * @param string $name post type slug.
	 * @param string $label post type label.
	 * @param string $type archive|single.
	 * @return array
	 * @since 2.0.0
	*/
	private function add_taxonomies( $list, $name, $label, $type ) {
		$taxonomies = get_object_taxonomies( $name, 'object' );

		if ( empty( $taxonomies ) ) {
			return $list;
		}

		// Attach post type's taxonomies to array.
		// Add a @ sign between post type and its taxonomy to split and use them later.
		foreach ( $taxonomies as $taxonomy ) {
			$list[ $label ][ $name . '@' . $taxonomy->name ] = $taxonomy->label;

			if ( true === $taxonomy->hierarchical && 'archive' === $type ) {
				/* translators: 1: prefix 2:tax name 3:postfix */
				$list[ $label ][ "$name@direct_child_of_$taxonomy->name" ] = sprintf(
					'%1$s %2$s  %3$s',
					esc_html__( 'Direct child', 'jupiterx-core' ),
					$taxonomy->label,
					esc_html__( 'of', 'jupiterx-core' )
				);

				/* translators: 1: prefix 2:tax name 3:postfix */
				$list[ $label ][ "$name@any_child_of_$taxonomy->name" ] = sprintf(
					'%1$s %2$s  %3$s',
					esc_html__( 'Any child', 'jupiterx-core' ),
					$taxonomy->label,
					esc_html__( 'of', 'jupiterx-core' )
				);
			}
		}

		return $list;
	}

	/**
	 * Save conditions as meta for post.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	private function save_post_conditions() {
		$post       = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );
		$conditions = filter_input( INPUT_POST, 'conditions', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$conditions = $this->filter_condition_before_saving( $conditions );

		$result = update_post_meta( $post, self::JUPITERX_CONDITIONS_COMPONENT_META_NAME, $conditions );

		jupiterx_core()->load_files(
			[
				'condition/classes/conditions-logic',
			]
		);

		$converter         = new Conditions_Logic();
		$conditions_string = $converter->manage_conditions_array( $conditions );
		update_post_meta( $post, self::JUPITERX_CONDITIONS_COMPONENT_META_STRING, $conditions_string );

		if ( ! $result ) {
			wp_send_json_error( esc_html__( 'Conditions has been set successfully.', 'jupiterx-core' ) );
		}

		$this->add_posts_id_with_conditions( $post, $conditions );

		wp_send_json_success( esc_html__( 'Conditions has been set successfully.', 'jupiterx-core' ) );
	}

	/**
	 * Filter conditions.
	 *
	 * @since 2.5.0
	 * @param array $conditions condition array.
	 * @return array
	 */
	private function filter_condition_before_saving( $conditions ) {
		$filtered = [];
		$to_check = [ 'entire', 'maintenance' ];

		foreach ( $conditions as $condition ) {
			$condition_b = $condition['conditionB'];
			$condition_c = $condition['conditionC'];

			if ( ! in_array( $condition_b, $to_check, true ) && empty( $condition_c ) ) {
				continue;
			}

			$filtered[] = $condition;
		}

		return $filtered;
	}

	/**
	 * Load user saved conditions.
	 *
	 * @return array
	 * @since 2.0.0
	 */
	private function load_post_conditions() {
		$post = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );

		$result = get_post_meta( $post, self::JUPITERX_CONDITIONS_COMPONENT_META_NAME, true );

		if ( empty( $result ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( $result );
	}

	/**
	 * Load user saved conditions string.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	private function load_post_conditions_string() {
		$post = filter_input( INPUT_POST, 'post', FILTER_SANITIZE_NUMBER_INT );

		$string = get_post_meta( $post, self::JUPITERX_CONDITIONS_COMPONENT_META_STRING, true );
		$array  = get_post_meta( $post, self::JUPITERX_CONDITIONS_COMPONENT_META_NAME, true );

		if ( empty( $string ) ) {
			wp_send_json_error();
		}

		$response = [
			'string' => $string,
			'array'  => $array,
		];

		wp_send_json_success( $response );
	}

	/**
	 * Save posts that has conditions as an array to be used later.
	 *
	 * @param int $id
	 * @return void
	 * @since 2.0.0
	 */
	public function add_posts_id_with_conditions( $id, $conditions ) {
		$option = get_option( self::JUPITERX_POSTS_WITH_CONDITIONS );

		if ( ! is_array( $option ) ) {
			$option = [];
		}

		// IF user deleted all conditions for post and/or empty condition array.
		if ( empty( $conditions ) ) {
			$new_options = array_diff( $option, array( $id ) );

			update_option( self::JUPITERX_POSTS_WITH_CONDITIONS, $new_options );

			return;
		}

		// Post already added.
		if ( in_array( $id, $option, true ) ) {
			return;
		}

		array_push( $option, $id );

		update_option( self::JUPITERX_POSTS_WITH_CONDITIONS, $option );
	}

	/**
	 * Determines type of string for the query.
	 *
	 * @param string $string user input.
	 * @since 3.2.0
	 */
	private function determine_string_type( $string ) {
		// Check if the string is a number.
		if ( is_numeric( $string ) ) {
			return 'number';
		}

		// Check if the string is a URL.
		$site_url = site_url();
		$site_url = str_replace( 'https://', '', $site_url );
		$site_url = str_replace( 'http://', '', $site_url );

		if ( filter_var( $string, FILTER_VALIDATE_URL ) || strpos( $string, $site_url ) !== false ) {
			return 'url';
		}

		// Check if the string is a slug.
		if ( preg_match( '/^[a-z0-9]+(?:[_-][a-z0-9]+)*$/i', $string ) ) {
			return 'slug';
		}

		// Otherwise, assume the string is a plain string.
		return 'string';
	}

	/**
	 * Add protocol to a string that already is determined as URL.
	 *
	 * @param string $url url.
	 * @since 3.2.0
	 */
	private function add_proper_protocol_to_url( $url ) {
		$site_protocol = is_ssl() ? 'https://' : 'http://';
		$url_protocol  = preg_match( '/^https?:\/\//i', $url ) ? '' : $site_protocol;

		return $url_protocol . $url;
	}

	/**
	 * Main Ajax handler.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function retrieve_select_options() {
		$type  = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$sub   = filter_input( INPUT_POST, 'sub', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$value = filter_input( INPUT_POST, 'value', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		$this->string_type = $this->determine_string_type( $value );

		if ( 'singular' === $type ) {
			$sub = str_replace( 'single_', '', $sub );

			$this->manage_singulars( $sub, $value );
		}

		if ( 'archive' === $type ) {
			$this->manage_archives( $sub, $value );
		}

		if ( 'woocommerce' === $type ) {
			$this->manage_woocommerce( $sub, $value );
		}
	}

	/**
	 * We know some of WordPress post type. And usually user has some custom post type in his site.
	 * Known WordPress post type managed by methods. And custom Post types will be managed by following function.
	 * And in manage_unknown_wp_post_types method.
	 *
	 * @since 2.0.0
	 */
	private function manage_singulars( $sub, $value ) {
		// Manage unknown WordPress post types
		if ( ! method_exists( $this, "singular_$sub" ) ) {
			$this->manage_unknown_wp_post_types( $sub, $value );
		}

		// Manage known WordPress post types.
		call_user_func_array( [ $this, "singular_$sub" ], [ $value ] );
	}

	/**
	 * Manage unknown WordPress post types return.
	 *
	 * @param string $sub
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function manage_unknown_wp_post_types( $sub, $value ) {
		// 1. User selected post type taxonomies or by_author.
		if ( strpos( $sub, '@' ) !== false ) {
			$sub       = explode( '@', $sub, 2 );
			$post_type = $sub[0];
			$rest      = $sub[1];

			if ( strpos( $rest, 'author' ) !== false ) {
				// User looking for authors.
				$this->get_authors( $value );
			} else {
				// User looking for terms.
				$this->get_terms( $rest, $value );
			}
		}

		//2. User selected Post type.
		$args = [
			'post_type' => $sub,
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Manage archives response when user is typing 4th parameter.
	 *
	 * @param string $sub
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	 */
	private function manage_archives( $sub, $value ) {
		// Manage unknown WordPress post types.
		if ( ! method_exists( $this, "archive_$sub" ) ) {
			$this->manage_unknown_archives( $sub, $value );
		}

		// Manage known WordPress post types.
		call_user_func_array( [ $this, "archive_$sub" ], [ $value ] );
	}

	/**
	 * Manage archive for unknowns post types.
	 *
	 * @return array
	 * @since 2.0.0
	*/
	private function manage_unknown_archives( $sub, $value ) {
		$sub       = explode( '@', $sub, 2 );
		$post_type = $sub[0];
		$taxonomy  = $sub[1];

		// Get get exact taxonomy slug by removing extra text.
		$taxonomy = str_replace( 'direct_child_of_', '', $taxonomy );
		$taxonomy = str_replace( 'any_child_of_', '', $taxonomy );

		// $taxonomy is now taxonomy slug. we can retrieve requested terms of taxonomy based on user search.
		$this->get_terms( $taxonomy, $value );
	}

	/**
	 * Manage woocommerce response when user is typing 4th parameter.
	 *
	 * @param string $sub
	 * @param string $value
	 * @since 2.0.0
	 */
	private function manage_woocommerce( $sub, $value ) {
		call_user_func_array( [ $this, "woocommerce_$sub" ], [ $value ] );
	}

	/**
	 * Get and return terms of taxonomy based on user selection and search.
	 *
	 * @param string $tax -> taxonomy.
	 * @param string $value -> user input.
	 * @return array
	 * @since 2.0.0
	 */
	private function get_terms( $tax, $value ) {
		$items  = [];
		$string = $this->string_type;

		if ( 'number' === $string ) {
			$terms = get_term_by( 'id', $value, $tax );
		}

		if ( 'url' === $string ) {
			$value = $this->add_proper_protocol_to_url( $string );
			$terms = get_term_by( 'slug', basename( untrailingslashit( wp_parse_url( $value, PHP_URL_PATH ) ) ), $tax );
		}

		if ( 'slug' === $string ) {
			$terms = get_term_by( 'slug', $value, $tax );
		}

		if ( 'string' === $string ) {
			$terms = get_term_by( 'name', $value, $tax );
		}

		if ( empty( $terms ) ) {
			$terms = get_terms(
				$tax,
				[
					'hide_empty' => false,
					'name__like' => $value,
				]
			);
		}

		if ( empty( $terms ) ) {
			wp_send_json_success( [] );
		}

		if ( is_object( $terms ) ) {
			$items[] = [
				'value' => $terms->term_id,
				'label' => $terms->name,
				'link'  => get_term_link( $terms->term_id, $tax ),
			];
		}

		if ( ! is_object( $terms ) ) {
			foreach ( $terms as $term ) {
				$items[] = [
					'value' => $term->term_id,
					'label' => $term->name,
					'link'  => get_term_link( $term->term_id, $tax ),
				];
			}
		}

		wp_send_json_success( $items );
	}

	/**
	 * Return posts of a post type based on arguments.
	 *
	 * @param array $args
	 * @return array
	 * @since 2.0.0
	 */
	private function get_posts( $args ) {
		$post_type = $args['post_type'];
		$input     = $args['s'];
		$items     = [];
		$string    = $this->string_type;

		global $wpdb;

		if ( 'any' === $args['post_type'] ) {
			if ( 'number' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_status` NOT IN( 'auto-draft', 'inherit' ) AND ID = %s",
						$input
					)
				);
			}

			if ( 'url' === $string ) {
				$input   = $this->add_proper_protocol_to_url( $input );
				$post_id = url_to_postid( $input );

				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_status` NOT IN( 'auto-draft', 'inherit' ) AND ID = %s",
						$post_id
					)
				);
			}

			if ( 'slug' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_status` NOT IN( 'auto-draft', 'inherit' ) AND post_name LIKE %s",
						'%' . $wpdb->esc_like( $input ) . '%'
					)
				);
			}

			if ( 'string' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_status` NOT IN( 'auto-draft', 'inherit' ) AND post_title LIKE %s",
						'%' . $wpdb->esc_like( $input ) . '%'
					)
				);
			}

			if ( empty( $posts ) ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_status` NOT IN( 'auto-draft', 'inherit' ) AND post_title LIKE %s",
						'%' . $wpdb->esc_like( $input ) . '%'
					)
				);
			}
		}

		if ( 'any' !== $args['post_type'] ) {
			if ( 'number' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_type` = %s AND ID = %s",
						$post_type,
						$input
					)
				);
			}

			if ( 'url' === $string ) {
				$input   = $this->add_proper_protocol_to_url( $input );
				$post_id = url_to_postid( $input );

				if ( 'e-landing-page' === $args['post_type'] ) {
					$site_url = site_url();
					$input    = str_replace( $site_url, '', $input );
					$input    = trim( $input, '/' );
					$post     = get_page_by_path( $input, 'OBJECT', 'e-landing-page' );
					$post_id  = $post->ID;
				}

				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_type` = %s AND ID = %s",
						$post_type,
						$post_id
					)
				);
			}

			if ( 'slug' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_type` = %s AND post_name = %s",
						$post_type,
						$input
					)
				);
			}

			if ( 'string' === $string ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_type` = %s AND post_title LIKE %s",
						$post_type,
						'%' . $wpdb->esc_like( $input ) . '%'
					)
				);
			}

			if ( empty( $posts ) ) {
				$posts = $wpdb->get_results( // phpcs:ignore
					$wpdb->prepare(
						"SELECT * FROM $wpdb->posts WHERE `post_type` = %s AND post_title LIKE %s",
						$post_type,
						'%' . $wpdb->esc_like( $input ) . '%'
					)
				);
			}
		}

		if ( empty( $posts ) ) {
			wp_send_json_success( [] );
		}

		foreach ( $posts as $post ) {
			$items[] = [
				'value' => $post->ID,
				'label' => $post->post_title,
				'link'  => get_permalink( $post->ID ),
			];
		}

		wp_send_json_success( $items );
	}

	/**
	 * Get and return authors based on user input.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function get_authors( $value ) {
		$users = get_users(
			[
				'search'   => '*' . $value . '*',
				'role__in' => [ 'author', 'administrator' ],
			]
		);
		$items = [];

		if ( empty( $users ) ) {
			wp_send_json_success( $items );
		}

		foreach ( $users as $user ) {
			$items[] = [
				'value' => $user->ID,
				'label' => $user->display_name,
				'link'  => get_author_posts_url( $user->ID ),
			];
		}

		wp_send_json_success( $items );
	}

	/**
	 * Return post.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_post( $value ) {
		$args = [
			'post_type' => 'post',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Return authors for all singulars.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Return authors for posts.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_post_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Return terms of category.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_post_in_category( $value ) {
		$this->get_terms( 'category', $value );
	}

	/**
	 * Return terms of category.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_post_in_category_children( $value ) {
		$this->get_terms( 'category', $value );
	}

	/**
	 * Return terms of tags.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_post_in_post_tag( $value ) {
		$this->get_terms( 'post_tag', $value );
	}

	/**
	 * Return list of pages.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_page( $value ) {
		$args = [
			'post_type' => 'page',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Return authors.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_page_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Return attachments list.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_attachment( $value ) {
		$args = [
			'post_type' => 'attachment',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Return authors.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_attachment_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Search for posts.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_any_child_of( $value ) {
		$args = [
			'post_type' => 'any',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Search for posts.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function singular_child_of( $value ) {
		$args = [
			'post_type' => 'any',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Return authors for archive.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	 */
	private function archive_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Return terms of category for archive in_category.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function archive_post_in_category( $value ) {
		$this->get_terms( 'category', $value );
	}

	/**
	 * Return terms of category for archive in_category_children.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function archive_post_in_category_children( $value ) {
		$this->get_terms( 'category', $value );
	}

	/**
	 * Return terms of category for archive in_post_tag.
	 *
	 * @param string $value
	 * @return array
	 * @since 2.0.0
	 */
	private function archive_post_in_post_tag( $value ) {
		$this->get_terms( 'post_tag', $value );
	}

	/**
	 * Return authors for woocommerce.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_product_by_author( $value ) {
		$this->get_authors( $value );
	}

	/**
	 * Return tags of products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_in_product_tag( $value ) {
		$this->get_terms( 'product_tag', $value );
	}

	/**
	 * Return categories of products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_in_product_cat_children( $value ) {
		$this->get_terms( 'product_cat', $value );
	}

	/**
	 * Return categories of products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_in_product_cat( $value ) {
		$this->get_terms( 'product_cat', $value );
	}

	/**
	 * Return Products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_single_product( $value ) {
		$args = [
			'post_type' => 'product',
			's'         => $value,
		];

		$this->get_posts( $args );
	}

	/**
	 * Return categories of products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_product_cat_archive( $value ) {
		$this->get_terms( 'product_cat', $value );
	}

	/**
	 * Return tags of products.
	 *
	 * @param string $value
	 * @return void
	 * @since 2.0.0
	*/
	private function woocommerce_product_tag_archive( $value ) {
		$this->get_terms( 'product_tag', $value );
	}
}

JupiterX_Core_Condition_Manager::get_instance();
