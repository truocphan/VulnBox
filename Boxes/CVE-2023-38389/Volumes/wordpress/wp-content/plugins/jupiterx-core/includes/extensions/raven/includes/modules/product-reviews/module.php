<?php
namespace JupiterX_Core\Raven\Modules\Product_Reviews;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_jupiterx_product_review_submitter', [ $this, 'submit' ] );
		add_action( 'wp_ajax_nopriv_jupiterx_product_review_submitter', [ $this, 'submit' ] );
	}

	public function get_widgets() {
		return [ 'product-reviews' ];
	}

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function submit() {
		check_ajax_referer( 'jupiterx-core-raven', 'nonce' );

		$score   = filter_input( INPUT_POST, 'score', FILTER_SANITIZE_NUMBER_INT );
		$content = filter_input( INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$name    = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$email   = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$post    = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );

		if ( empty( $score ) || empty( $content ) || empty( $name ) || empty( $email ) ) {
			wp_send_json_error();
		}

		$args = [
			'comment_post_ID'      => $post,
			'comment_type'         => 'review',
			'comment_content'      => $content,
			'user_id'              => get_current_user_id(),
			'comment_author'       => $name,
			'comment_author_email' => $email,
			'comment_meta'         => [
				'rating' => $score,
			],
		];

		$comment_id = wp_insert_comment( $args );

		if ( is_wp_error( $comment_id ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}
}
