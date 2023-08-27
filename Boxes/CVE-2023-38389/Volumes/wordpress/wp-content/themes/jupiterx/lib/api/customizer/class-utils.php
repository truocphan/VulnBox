<?php
/**
 * This class handles customizer utils function.
 *
 * @package JupiterX\Framework\API\Customizer
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define all customizer utils.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 *
 * @package JupiterX\Framework\API\Customizer
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
final class JupiterX_Customizer_Utils {

	/**
	 * Assets url getter.
	 *
	 * @since 1.0.0
	 *
	 * @return string Url of the assets.
	 */
	public static function get_assets_url() {
		return JUPITERX_ASSETS_URL . 'customizer';
	}

	/**
	 * Pro badge url getter.
	 *
	 * @since 2.0.0
	 *
	 * @return string Url cutomizer images.
	 */
	public static function get_pro_badge_url() {
		$icon = 'pro-badge';

		if ( jupiterx_is_premium() ) {
			$icon = 'lock-badge';
		}

		return esc_url( JUPITERX_ASSETS_URL . 'customizer/img/customizer-icons.svg#' . $icon );
	}

	/**
	 * Icon file url getter.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name File name.
	 *
	 * @return string The formatted url of the icon.
	 */
	public static function get_icon_url( $file_name ) {
		return ( ! empty( $file_name ) ) ? esc_url( JUPITERX_ASSETS_URL . 'customizer/img/' . $file_name . '.svg' ) : '';
	}

	/**
	 * Get and print svg icon.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name File name.
	 *
	 * @return string The formatted url of the icon.
	 */
	public static function print_svg_icon( $file_name ) {
		if ( empty( $file_name ) ) {
			return;
		}

		$file_path = JUPITERX_ASSETS_URL . 'customizer/img/' . $file_name . '.svg';

		if ( file_exists( $file_path ) ) {
			ob_start();
			include $file_path;
			$contents = ob_get_clean();
			echo $contents; // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Assets url getter.
	 *
	 * @since 1.0.0
	 *
	 * @return string Url of the assets.
	 */
	public static function get_text_decoration_choices() {
		return [
			'none'         => __( 'None', 'jupiterx' ),
			'underline'    => __( 'Underline', 'jupiterx' ),
			'overline'     => __( 'Overline', 'jupiterx' ),
			'line-through' => __( 'Line Through', 'jupiterx' ),
		];
	}

	/**
	 * Get all registered menus and format as a valid choices for select control.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of all registered menus.
	 */
	public static function get_select_menus() {
		$choices = [];

		$menus = get_terms( 'nav_menu', [ 'hide_empty' => false ] );

		foreach ( $menus as $menu ) {
			$choices[ $menu->slug ] = $menu->name;
		}

		return $choices;
	}

	/**
	 * Get all pages.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of published pages.
	 */
	public static function get_select_pages() {
		$choices = [];

		$pages = get_pages( [
			'sort_column'    => 'post_title',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		] );

		foreach ( $pages as $page ) {
			$post_title = $page->post_title;

			if ( '' === $post_title ) {
				/* translators: %d: page ID */
				$post_title = sprintf( __( '#%d (no title)', 'jupiterx' ), $page->ID );
			}

			$choices[ $page->ID ] = $post_title;
		}

		return $choices;
	}

	/**
	 * Get layouts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $choices The initial option.
	 *
	 * @return array List of layouts.
	 */
	public static function get_layouts( $choices = [] ) {
		$right   = is_rtl() ? esc_html__( 'Left', 'jupiterx' ) : esc_html__( 'Right', 'jupiterx' );
		$left    = is_rtl() ? esc_html__( 'Right', 'jupiterx' ) : esc_html__( 'Left', 'jupiterx' );
		$choices = $choices;

		$choices = array_merge( $choices, [
			'c'       => esc_html__( 'No sidebar', 'jupiterx' ),
			/* translators: The sidebar position */
			'sp_c'    => sprintf( esc_html__( 'Single Sidebar %s', 'jupiterx' ), $left ),
			/* translators: The sidebar position */
			'c_sp'    => sprintf( esc_html__( 'Single Sidebar %s', 'jupiterx' ), $right ),
			/* translators: The sidebar position */
			'sp_ss_c' => sprintf( esc_html__( 'Double Sidebar %s', 'jupiterx' ), $left ),
			/* translators: The sidebar position */
			'c_sp_ss' => sprintf( esc_html__( 'Double Sidebar %s', 'jupiterx' ), $right ),
			'sp_c_ss' => esc_html__( 'Opposing Sidebars', 'jupiterx' ),
		] );

		return $choices;
	}

	/**
	 * Get templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type    The template type.
	 * @param string $choices The initial option.
	 *
	 * @return array List of templates.
	 */
	public static function get_templates( $type, $choices = [] ) {
		$choices = $choices;

		$args = [
			'sort_column'    => 'post_title',
			'post_type'      => 'elementor_library',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => [ // @codingStandardsIgnoreLine
				[
					'key'   => '_elementor_template_type',
					'value' => $type,
				],
			],
		];

		$query = new \WP_Query( $args );

		foreach ( $query->posts as $post ) {
			$choices[ $post->ID ] = $post->post_title;
		}

		return $choices;
	}

	/**
	 * Get all possible terms in a taxonomy as an array.
	 * Used in custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $taxonomy    Taxonomy slug.
	 * @param  boolean $add_default Prepend All {$taxonomy} to array.
	 *
	 * @return array   $terms       Array of existing terms.
	 */
	public static function get_terms( $taxonomy, $add_default = true ) {

		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'fields'     => 'id=>name',
		] );

		if ( is_wp_error( $terms ) ) {
			return [];
		}

		if ( $add_default ) {
			$terms = [ '0' => esc_html__( 'All Categories', 'jupiterx' ) ] + $terms;
		}

		return $terms;
	}

	/**
	 * Get all registered widgets.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of widgets area.
	 */
	public static function get_select_widgets_area() {
		global $wp_registered_sidebars;

		$choices = [];

		foreach ( $wp_registered_sidebars as $sidebar ) {
			$choices[ $sidebar['id'] ] = $sidebar['name'];
		}

		return $choices;
	}

	/**
	 * Get align choices.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $type    The type 'justify-content', 'flex-direction'.
	 * @param  array  $exclude The items to be excluded.
	 *
	 * @return array The align choices.
	 */
	public static function get_align( $type = '', $exclude = [] ) {
		$first = is_rtl() ? 'right' : 'left';
		$last  = is_rtl() ? 'left' : 'right';

		if ( 'justify-content' === $type ) {
			$first = 'flex-start';
			$last  = 'flex-end';
		}

		if ( 'flex-direction' === $type ) {
			$first = 'row';
			$last  = 'row-reverse';
		}

		$choices = [
			$first   => [
				'icon'  => is_rtl() ? 'alignment-right' : 'alignment-left',
			],
			'center' => [
				'icon'  => 'alignment-center',
			],
			$last    => [
				'icon'  => is_rtl() ? 'alignment-left' : 'alignment-right',
			],
		];

		foreach ( $exclude as $item ) {
			unset( $choices[ $item ] );
		}

		return $choices;
	}

	/**
	 * Get a random post.
	 *
	 * @param string $post_type Post type to get a post from.
	 *
	 * @since 1.0.0
	 *
	 * @return int Post ID.
	 * @SuppressWarnings(PHPMD.ElseExpression)
	 */
	public static function get_random_post( $post_type = '' ) {
		if ( empty( $post_type ) ) {
			return false;
		}

		if ( 'product' === $post_type && class_exists( 'WooCommerce' ) ) {
			// It is safer than get_posts for getting products regarding to DB changes from WooCommerce.
			$product = wc_get_products( [
				'limit'   => 1,
				'orderby' => 'rand',
				'return' => 'ids,',
			] );

			$post_id = ! empty( $product[0] ) ? $product[0]->get_id() : 0;

		} else {
			$args = [
				'post_type'           => $post_type,
				'posts_per_page'      => 1,
				'orderby'             => 'rand',
				'post_status'         => 'any',
				'has_password'        => false,
				'ignore_sticky_posts' => true,
			];

			$post = get_posts( $args );

			wp_reset_postdata();

			$post_id = ! empty( $post[0] ) ? $post[0]->ID : 0;
		}

		if ( $post_id ) {

			if ( function_exists( 'pll_get_post' ) ) {
				return pll_get_post( $post_id );
			}

			return $post_id;
		}

		return false;
	}

	/**
	 * Get random term based on taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy Taxonomy to get random term from.
	 *
	 * @return int term ID.
	 */
	public static function get_random_term( $taxonomy = '' ) {
		if ( empty( $taxonomy ) ) {
			return false;
		}

		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		] );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return false;
		}

		shuffle( $terms );

		return $terms[0];
	}

	/**
	 * Filter permalink based on current language if WPML is active.
	 *
	 * @param string $post_id Post ID to get permalink.
	 *
	 * @since 1.0.0
	 *
	 * @return string Translated URL or main if WPML is not active.
	 */
	public static function get_permalink( $post_id ) {
		if ( ! $post_id ) {
			return false;
		}

		$url = esc_url( get_permalink( intval( $post_id ) ) );

		if ( class_exists( 'SitePress' ) ) {
			return esc_url( apply_filters( 'wpml_permalink', $url, ICL_LANGUAGE_CODE, true ) );
		}

		return $url;
	}

	/**
	 * Get a proper preview URL for sections.
	 * Random valid URL for search, single post, single portfolio, single product.
	 *
	 * @param string $section Section to get preview URL for it.
	 *
	 * @since 1.0.0
	 *
	 * @return string A URL related to current editing section in customizer.
	 */
	public static function get_preview_url( $section = 'home' ) {
		switch ( $section ) {
			// Theme pages.
			case 'search':
				$title = get_the_title( self::get_random_post( 'post' ) );
				return get_search_link( $title );

			// Single pages.
			case 'portfolio_single':
				return self::get_permalink( self::get_random_post( 'portfolio' ) );

			case 'blog_single':
				return self::get_permalink( self::get_random_post( 'post' ) );

			case 'product_single':
				return self::get_permalink( self::get_random_post( 'product' ) );

			case 'single_page':
				return self::get_permalink( self::get_random_post( 'page' ) );

			// Archive pages.
			case 'blog_archive':
				$term_link = get_term_link( self::get_random_term( 'category' ) );
				return is_wp_error( $term_link ) ? get_home_url() : $term_link;

			case 'product_archive':
				$term_link = get_term_link( self::get_random_term( 'product_cat' ) );
				return is_wp_error( $term_link ) ? get_home_url() : $term_link;

			case 'portfolio_archive':
				$term_link = get_term_link( self::get_random_term( 'portfolio_category' ) );
				return is_wp_error( $term_link ) ? get_home_url() : $term_link;

			default:
				return get_home_url();
		}
	}
}
