<?php

namespace JupiterX_Core\Raven\Modules\Categories;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function get_widgets() {
		return [ 'categories' ];
	}

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_raven_categories_editor', [ $this, 'handle_editor' ] );
	}

	public static function get_taxonomy( $post_type ) {
		$taxonomy_map = [
			'blog' => 'category',
			'portfolio' => 'portfolio_category',
			'product' => 'product_cat',
		];

		return $taxonomy_map[ $post_type ];
	}

	public function handle_editor() {
		$post_type = filter_input( INPUT_POST, 'post_type' );

		$args = [
			'taxonomy' => self::get_taxonomy( $post_type ),
		];

		wp_send_json_success( get_terms( $args ) );
	}

}
