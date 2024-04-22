<?php

new STM_LMS_WPCFTO_HELPERS();

class STM_LMS_WPCFTO_HELPERS {
	public function __construct() {
		add_action( 'wp_ajax_stm_manage_posts', array( $this, 'manage_posts' ) );

		add_filter( 'wpcfto_search_posts_response', array( $this, 'questions_modify_search' ), 10, 2 );

		add_filter( 'stm_lms_wpcfto_create_question', array( $this, 'questions_modify_search' ), 10, 2 );

		add_filter( 'wpcfto_check_is_pro_field', array( $this, 'is_pro' ) );

		add_filter(
			'wpcfto_field_pro_banner',
			function () {
				return STM_LMS_PATH . '/stm-lms-templates/journey/unlock-banner-component.php';
			}
		);

		add_filter( 'wpcfto_iconpicker_sets', array( $this, 'add_wpcfto_category_icons' ) );

		add_filter( 'stm_wpcfto_single_field_classes', array( $this, 'wpcfto_field_addon_state' ), 10, 3 );

		add_action( 'stm_wpcfto_single_field_before_start', array( $this, 'start_field' ), 10, 6 );

		add_filter(
			'wpcfto_all_users_label',
			function() {
				return esc_html__( 'Choose User 1', 'masterstudy-lms-learning-management-system' );
			}
		);

		add_filter(
			'wpcfto_file_error_label',
			function() {
				return esc_html__( 'Error occurred, please try again', 'masterstudy-lms-learning-management-system' );
			}
		);

		add_filter(
			'wpcfto_empty_file_error_label',
			function() {
				return esc_html__( 'Please, select file', 'masterstudy-lms-learning-management-system' );
			}
		);

		add_filter(
			'wpcfto_file_error_ext_label',
			function() {
				return esc_html__( 'Invalid file extension', 'masterstudy-lms-learning-management-system' );
			}
		);

	}

	public function manage_posts() {
		check_ajax_referer( 'stm_manage_posts', 'nonce' );

		$r = array(
			'posts' => array(),
		);

		$args = array(
			'posts_per_page' => 10,
		);

		if ( ! empty( $_GET['post_types'] ) ) {
			$args['post_type'] = explode( ',', sanitize_text_field( $_GET['post_types'] ) );
		}

		$args['post_status'] = ( ! empty( $_GET['post_status'] ) ) ? sanitize_text_field( $_GET['post_status'] ) : 'all';
		$offset              = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] - 1 ) : 0;
		if ( ! empty( $offset ) ) {
			$args['offset'] = $offset * $args['posts_per_page'];
		}

		if ( ! empty( $_GET['meta'] ) ) {
			$args['meta_query'] = array(
				array(
					'key'     => sanitize_text_field( $_GET['meta'] ),
					'compare' => 'EXISTS',
				),
			);
		}

		$r['args'] = $args;

		$q             = new WP_Query( $args );
		$r['total']    = intval( $q->found_posts );
		$r['per_page'] = $args['posts_per_page'];
		$r['offset']   = $args['offset'] ?? 0;

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$response = array(
					'id'           => get_the_ID(),
					'title'        => get_the_title(),
					'url'          => get_the_permalink(),
					'status'       => get_post_status(),
					'edit_link'    => ms_plugin_manage_course_url( get_the_ID() ),
					'loading'      => false,
					'loading_text' => '',
				);

				$r['posts'][] = $response;
			}

			wp_reset_postdata();
		}

		wp_send_json( $r );
	}


	public function questions_modify_search( $response, $post_type ) {
		if ( in_array( 'stm-questions', $post_type, true ) ) {
			$response = array_merge( $response, $this->question_fields( $response['id'] ) );
		}

		return $response;
	}

	public static function get_question_fields() {
		return array(
			'type'                 => array(
				'default' => 'single_choice',
			),
			'answers'              => array(
				'default' => array(),
			),
			'question'             => array(),
			'question_explanation' => array(),
			'question_hint'        => array(),
			'question_view_type'   => '',
			'image'                => '',
		);
	}

	public function question_fields( $post_id ) {
		$fields = $this->get_question_fields();
		$meta   = array();

		foreach ( $fields as $field_key => $field ) {
			$meta[ $field_key ] = get_post_meta( $post_id, $field_key, true );
			$default            = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$meta[ $field_key ] = ( ! empty( $meta[ $field_key ] ) ) ? $meta[ $field_key ] : $default;
		}

		$meta['opened'] = true;

		if ( ! empty( $meta['answers'] ) && ! empty( $meta['answers'][0] && ! empty( $meta['answers'][0]['categories'] ) ) ) {
			$categories         = $meta['answers'][0]['categories'];
			$checked_categories = array();
			foreach ( $categories as $category ) {
				if ( term_exists( $category['term_id'] ) ) {
					$checked_categories[] = $category;
				}
			}

			$meta['answers'][0]['categories'] = $checked_categories;
		}

		return $meta;
	}

	public function is_pro( $pro ) {
		return defined( 'STM_LMS_PRO_PATH' );
	}

	public function stm_wpcfto_add_vc_icons( $fonts ) {
		if ( empty( $fonts ) ) {
			$fonts = array();
		}

		$icons = json_decode( file_get_contents( STM_LMS_PATH . '/assets/icons/selection.json', true ), true );
		$icons = $icons['icons'];

		$fonts['STM LMS Icons'] = array();

		foreach ( $icons as $icon ) {
			$icon_name                = $icon['properties']['name'];
			$fonts['STM LMS Icons'][] = array(
				"stmlms-{$icon_name}" => $icon_name,
			);
		}

		return $fonts;
	}

	public function add_wpcfto_category_icons( $sets ) {
		$ms_icons   = $this->stm_wpcfto_add_vc_icons( array() );
		$ms_icons   = $ms_icons['STM LMS Icons'];
		$ms_icons_i = array();
		foreach ( $ms_icons as $icon ) {
			$icons        = array_keys( $icon );
			$ms_icons_i[] = $icons[0];
		}
		$ms_icons_i = apply_filters( 'stm_wpcfto_ms_icons', $ms_icons_i );

		$sets['MasterStudy'] = $ms_icons_i;

		return $sets;
	}

	public function wpcfto_field_addon_state( $classes, $field_name, $field ) {
		$is_addon = ( ! empty( $field['pro'] ) && empty( $is_pro ) );

		if ( 'addons' === $field['type'] ) {
			$is_addon = false;
		}

		$addon_state = apply_filters( "wpcfto_addon_option_{$field_name}", '' );

		if ( empty( $addon_state ) ) {
			$is_addon = false;
		}

		/*CHECK IF ADDON IS ENABLED*/
		if ( $this->stm_lms_check_addon_enabled( $addon_state ) ) {
			$is_addon = false;
		}

		if ( $is_addon ) {
			$classes[] = 'is_pro is_pro_in_addon';
		}

		if ( ! empty( $addon_state ) ) {
			$classes[] = "stm_lms_addon_group_settings_{$addon_state}";
		}

		return $classes;
	}

	public function is_addon( $classes, $field_name, $field ) {
		return in_array( 'is_pro is_pro_in_addon', $classes, true );
	}

	public function addon_state( $field_name ) {
		$addon_state = apply_filters( "wpcfto_addon_option_{$field_name}", '' );

		return $addon_state;
	}

	public function stm_lms_check_addon_enabled( $addon_name ) {
		if ( empty( $addon_name ) ) {
			return false;
		}

		$addons = get_option( 'stm_lms_addons' );

		return ( ! empty( $addons[ $addon_name ] ) && 'on' === $addons[ $addon_name ] );
	}

	public function start_field( $classes, $field_name, $field, $is_pro, $pro_url, $disable ) {
		$is_addon    = $this->is_addon( $classes, $field_name, $field );
		$addon_state = $this->addon_state( $field_name );

		if ( isset( $field['label'] ) && ! empty( $field['label'] ) ) {
			$converted_label = preg_replace( '/[^\p{L}\p{N}_]+/u', '_', $field['label'] );
		} else {
			$converted_label = '';
		}

		$field_label = rtrim( strtolower( $converted_label ), '_' );

		if ( empty( $pro_url ) ) {
			$pro_url = admin_url( 'admin.php?page=stm-lms-go-pro' );
		} else {
			$pro_url = admin_url( 'admin.php?page=stm-lms-go-pro&source=' . $field_label );
		}

		if ( 'is_pro' === $is_pro ) { ?>
			<div class="field_overlay"></div>
			<!--We have no pro plugin active-->
			<span class="pro-notice">
				<?php esc_html_e( 'Available in ', 'masterstudy-lms-learning-management-system' ); ?>
				<a href="<?php echo esc_url( $pro_url ); ?>" target="_blank"><?php esc_html_e( 'Pro Version', 'masterstudy-lms-learning-management-system' ); ?></a>
			</span>
			<?php
		}

		if ( $is_addon ) {
			/*We have pro plugin but addon seems to be disabled*/
			?>
			<div class="field_overlay"></div>
			<span class="pro-notice">
				<a href="#" @click.prevent="enableAddon($event, '<?php echo esc_attr( $addon_state ); ?>')">
					<i class="fa fa-power-off"></i>
				<?php esc_html_e( 'Enable addon', 'masterstudy-lms-learning-management-system' ); ?>
				</a>
			</span>
			<?php
		}

		if ( 'is_disabled' === $disable ) {
			$no_quota = STM_LMS_Subscriptions::get_featured_quota() < 1;
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id  = ( isset( $_GET['post'] ) ) ? intval( $_GET['post'] ) : false;
			$featured = get_post_meta( $post_id, 'featured', true );

			if ( $no_quota && 'on' !== $featured ) {
				?>
				<div class="field_overlay"></div>
				<span class="is_disabled_notice">
					<?php esc_html_e( 'You have reached your featured courses quota limit!', 'masterstudy-lms-learning-management-system' ); ?>
				</span>
				<?php
			}
		}
	}

}
