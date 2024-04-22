<?php

class STM_LMS_Woocommerce_Courses_Admin {

	public $key      = '';
	public $meta_key = '';
	public $label    = '';
	public $args     = array();

	public function __construct( $key, $label, $meta_key, $args = array() ) {

		$this->key      = $key;
		$this->label    = $label;
		$this->meta_key = $meta_key;
		$this->args     = $args;

		if ( is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'only_self' ) );

			add_filter( 'views_edit-product', array( $this, 'view' ) );
		}

	}

	public function is_current() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return ( ! empty( $_GET['stm_lms_product'] ) && $_GET['stm_lms_product'] === $this->key );
	}

	public function only_self( $query ) {
		$meta_query = array();
		$tax_query  = array();

		if ( self::is_current() ) {
			$tax_query = array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'stm_lms_product',
					'operator' => 'IN',
				),
			);

			$meta_query = array(
				array(
					'key'     => $this->meta_key,
					'value'   => '0',
					'compare' => '>',
				),
			);

			if ( ! empty( $this->args['meta_query'] ) ) {
				$meta_query[] = $this->args['meta_query'];
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query->set( 'tax_query', $tax_query );
		}

		if ( ! empty( $meta_query ) ) {
			$query->set( 'meta_query', $meta_query );
		}
	}

	public function view( $views ) {
		$current = ( $this->is_current() ) ? 'current' : '';

		$link = admin_url( "edit.php?post_type=product&product_type=stm_lms_product&stm_lms_product={$this->key}" );

		$views[ "stm_lms_product_{$this->key}" ] = "<a class='{$current}' href='{$link}'>{$this->label}</a>";

		return $views;
	}

}
