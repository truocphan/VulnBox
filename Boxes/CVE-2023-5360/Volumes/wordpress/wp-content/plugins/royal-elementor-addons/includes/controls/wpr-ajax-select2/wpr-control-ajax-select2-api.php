<?php
use WprAddons\Classes\Utilities;

class Wpr_Control_Ajax_Select2_Api {

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'rest_api_init', function() {
			register_rest_route(
				'wpraddons/v1/ajaxselect2',
				'/(?P<action>\w+)/',
				[
					'methods' => 'GET',
					'callback' =>  [$this, 'callback'],
					'permission_callback' => '__return_true'
				]
			);
		} );
	}

	public function callback( $request ) {
		return $this->{$request['action']}( $request );
	}

	public function get_elementor_templates( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = [
			'post_type' => 'elementor_library',
			'post_status' => 'publish',
			'meta_key' => '_elementor_template_type',
			'meta_value' => ['page', 'section', 'container'],
			'numberposts' => 15
		];
		
		if ( isset( $request['s'] ) ) {
			$args['s'] = $request['s'];
		}

		$options = [];
		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = [
					'id' => get_the_ID(),
					'text' => html_entity_decode(get_the_title()),
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}

	public function get_posts_by_post_type( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}
		
		$post_type = isset($request['query_slug']) ? $request['query_slug'] : '';

		$args = [
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => 15,
		];

		if ( isset( $request['ids'] ) ) {
			$ids = explode( ',', $request['ids'] );
			$args['post__in'] = $ids;
		}
		
		if ( isset( $request['s'] ) ) {
			$args['s'] = $request['s'];
		}

		if ( 'attachment' === $post_type ) {
			$args['post_status'] = 'any';
		}

		$options = [];
		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[] = [
					'id' => get_the_ID(),
					'text' => html_entity_decode(get_the_title()),
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}

	public function get_taxonomies( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = [
			'orderby' => 'name', 
			'order' => 'DESC',
			'hide_empty' => true,
			'number' => 10,
		];
		
		$tax = isset($request['query_slug']) ? $request['query_slug'] : '';

		if ( isset( $request['ids'] ) ) {
			$request['ids'] = ('' !== $request['ids']) ? $request['ids'] : '99999999'; // Query Hack
			$ids = explode( ',', $request['ids'] );
			$args['include'] = $ids;
		}
		
		if ( isset( $request['s'] ) ) {
			$args['name__like'] = $request['s'];
		}

		$options = [];
		$terms = get_terms( $tax, $args );

		if ( ! empty($terms) ) {
			foreach ( $terms as $term ) {
				$options[] = [
					'id'   => $term->term_id,
					'text' => $term->name,
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}

	public function get_users( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$args = [
			'number' => '15',
			'blog_id' => 0
		];

		if ( isset( $request['ids'] ) ) {
			$ids = array_map('intval', explode(',', $request['ids'] ));
			$args['include'] = $ids;
		}

		if ( isset( $request['s'] ) ) {
			$args['search'] = '*'. $request['s'] .'*';
		}

		$options = [];
		$user_query = new \WP_User_Query( $args );

		if ( ! empty( $user_query->get_results() ) ) {
			foreach ( $user_query->get_results() as $user ) {
				$options[] = [
					'id' => $user->ID,
					'text' => $user->display_name,
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}

	public function get_post_type_taxonomies( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}

		$post_type = isset($request['query_slug']) ? $request['query_slug'] : '';

		$args = [
			'orderby' => 'name', 
			'order' => 'DESC',
			'hide_empty' => true,
			'number' => -1,
		];

		if ( isset( $request['ids'] ) ) {
			$request['ids'] = ('' !== $request['ids']) ? $request['ids'] : '99999999'; // Query Hack
			$ids = explode( ',', $request['ids'] );
			$args['include'] = $ids;
		}
		
		if ( isset( $request['s'] ) ) {
			$args['name__like'] = $request['s'];
		}

		$options = [];
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );

		if ( ! empty($taxonomies) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$options[] = [
					'id'   => $taxonomy->name,
					'text' => $taxonomy->label,
				];
			}
		}

		wp_reset_postdata();

		return [ 'results' => $options ];
	}
	
	/**
	** Get Custom Meta Keys
	*/
	public function get_custom_meta_keys( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}
	
		$data = [];
		$options = [];
		$merged_meta_keys = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );
	
		foreach ( $post_types as $post_type_slug => $post_type_name ) {
			$data[ $post_type_slug ] = [];
			$posts = get_posts( [ 'post_type' => $post_type_slug, 'posts_per_page' => -1 ] );
	
			foreach ( $posts as $key => $post ) {
				$meta_keys = get_post_custom_keys( $post->ID );
	
				if ( ! empty($meta_keys) ) {
					for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
						if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
							array_push( $data[$post_type_slug], $meta_keys[$i] );
						}
					}
				}
			}
	
			$data[ $post_type_slug ] = array_unique( $data[ $post_type_slug ] );
		}
	
		foreach ( $data as $array ) {
			$merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
		}
		
		// Rekey
		$merged_meta_keys = array_values($merged_meta_keys);
	
		for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
			// Add a search condition here
			if ( ! isset( $request['s'] ) || strpos( $merged_meta_keys[$i], $request['s'] ) !== false ) {
				$options[] = [
					'id' => $merged_meta_keys[$i],
					'text' => $merged_meta_keys[$i],
				];
			}
		}
	
		return [ 'results' => $options ];
	}
	
	/**
	** Get Custom Meta Keys Product
	*/
	public function get_custom_meta_keys_product( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}
	
		$data = [];
		$options = [];
		$merged_meta_keys = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );
	
		foreach ( $post_types as $post_type_slug => $post_type_name ) {
			$data[ $post_type_slug ] = [];
			$posts = get_posts( [ 'post_type' => $post_type_slug, 'posts_per_page' => -1 ] );
	
			foreach ( $posts as $key => $post ) {
				$meta_keys = get_post_custom_keys( $post->ID );
	
				if ( ! empty($meta_keys) ) {
					for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
						if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
							array_push( $data[$post_type_slug], $meta_keys[$i] );
						}
					}
				}
			}
	
			$data[ $post_type_slug ] = array_unique( $data[ $post_type_slug ] );
		}
	
		foreach ( $data as $array ) {
			$merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
		}
		
		// Rekey
		$merged_meta_keys = array_values($merged_meta_keys);
	
		for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
			// Add a search condition here
			if ( ! isset( $request['s'] ) || strpos( $merged_meta_keys[$i], $request['s'] ) !== false ) {
				$options[] = [
					'id' => $merged_meta_keys[$i],
					'text' => $merged_meta_keys[$i],
				];
			}
		}

		// Get a list of all product attributes used by products
		$product_attributes = array();

		// Query for all products of the "product" post type
		$args = array(
			'post_type' => 'product', // Change to the correct post type if needed
			'posts_per_page' => -1,  // Retrieve all products
		);

		$products_query = new \WP_Query($args);

		if ($products_query->have_posts()) {
			while ($products_query->have_posts()) {
				$products_query->the_post();

				// Get the product object
				$product = wc_get_product(get_the_ID());

				// Get product attributes
				$attributes = $product->get_attributes();

				// Loop through attributes and add them to the master list
				foreach ($attributes as $attribute) {
					$product_attributes[$attribute->get_name()] = true;
				}
			}

			// Reset post data
			wp_reset_postdata();

			// Extract attribute names from the master list
			$attribute_names = array_keys($product_attributes);

			// Initialize an empty array
			$attributes_array = [];

			// Iterate through the original array and set both key and value to be the same
			foreach ($attribute_names as $value) {
				$attributes_array[] = [
					'id' => $value,
					'text' => $value,
				];
			}

			$options = array_merge($options, $attributes_array);
		}
	
		return [ 'results' => $options ];
	}
	
	
	/**
	** Get Custom Meta Keys Data
	*/
	public function get_custom_meta_keys_data() { // TODO
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;   
		}
		
		$data = [];
		$options = [];
		$merged_meta_keys = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $post_type_slug => $post_type_name ) {
			$data[ $post_type_slug ] = [];
			$posts = get_posts( [ 'post_type' => $post_type_slug, 'posts_per_page' => -1 ] );

			foreach (  $posts as $key => $post ) {
				$meta_keys = get_post_custom_keys( $post->ID );

				if ( ! empty($meta_keys) ) {
					for ( $i = 0; $i < count( $meta_keys ); $i++ ) {
						if ( '_' !== substr( $meta_keys[$i], 0, 1 ) ) {
							array_push( $data[$post_type_slug], $meta_keys[$i] );
						}
					}
				}
			}

			$data[ $post_type_slug ] = array_unique( $data[ $post_type_slug ] );
		}

		foreach ( $data as $array ) {
			$merged_meta_keys = array_unique( array_merge( $merged_meta_keys, $array ) );
		}
		
		// Rekey
		$merged_meta_keys = array_values($merged_meta_keys);

		for ( $i = 0; $i < count( $merged_meta_keys ); $i++ ) {
			$options[ $merged_meta_keys[$i] ] = $merged_meta_keys[$i];
		}

		// return [ $data, $options ];
		return [ 'results' => $data ];
	}

}

new Wpr_Control_Ajax_Select2_Api();
