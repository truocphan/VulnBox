<?php
namespace JupiterX_Core\Raven\Modules\Post_Terms;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'post-terms' ];
	}

	public static function taxonomy_list() {
		$default_values = [
			'category'           => esc_html__( 'Post Category', 'jupiterx-core' ),
			'post_tag'           => esc_html__( 'Post Tags', 'jupiterx-core' ),
			'product_cat'        => esc_html__( 'Product Categories', 'jupiterx-core' ),
			'product_tag'        => esc_html__( 'Product Tags', 'jupiterx-core' ),
			'portfolio_category' => esc_html__( 'Portfolio Categories', 'jupiterx-core' ),
			'portfolio_tag'      => esc_html__( 'Portfolio Tags', 'jupiterx-core' ),
		];

		$post_types    = self::get_post_types();
		$final_options = self::merge( $default_values, $post_types );

		return $final_options;
	}

	private static function get_post_types() {
		$post_types = [];
		$args       = [
			'public'   => true,
			'_builtin' => false,
		];

		$post_types = get_post_types( $args, 'object', 'and' );

		return $post_types;
	}

	private static function merge( $default_values, $post_types ) {
		$excluded_post_types = [ 'post', 'product', 'portfolio', 'page', 'sellkit_step' ];

		foreach ( $post_types as $post ) {
			// Escape post without archive.
			if ( false === $post->has_archive || in_array( $post->name, $excluded_post_types, true ) ) {
				continue;
			}

			$taxonomies = get_object_taxonomies( $post->name, 'object' );

			if ( empty( $taxonomies ) ) {
				continue;
			}

			foreach ( $taxonomies as $taxonomy ) {
				$default_values[ $taxonomy->name ] = $taxonomy->label;
			}
		}

		return $default_values;
	}
}
