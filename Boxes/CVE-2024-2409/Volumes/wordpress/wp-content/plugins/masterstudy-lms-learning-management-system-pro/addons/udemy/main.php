<?php

require_once STM_LMS_PRO_ADDONS . '/udemy/import.php';
require_once STM_LMS_PRO_ADDONS . '/udemy/udemy_importer/main.php';

new STM_LMS_Udemy();

class STM_LMS_Udemy {

	public function __construct() {
		add_action( 'wp_ajax_stm_lms_pro_search_courses', 'STM_LMS_Udemy::search_courses' );
		add_action( 'stm-lms-content-stm-courses', 'STM_LMS_Udemy::single_course', 10 );
		add_action( 'wp_ajax_stm_lms_pro_udemy_publish_course', 'STM_LMS_Udemy::publish_course' );
		add_action( 'stm-lms-buy-button', 'STM_LMS_Udemy::buy-button' );

		add_filter( 'stm_lms_wrapper_classes', 'STM_LMS_Udemy::udemy_classes' );
		add_filter( 'stm_lms_template_name', 'STM_LMS_Udemy::replace_templates', 100, 2 );

		$addons_enabled = get_option( 'stm_lms_addons', array() );

		if ( array_key_exists( 'udemy', $addons_enabled ) && 'on' === $addons_enabled['udemy'] ) {
			add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ) );
		}
	}

	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'option_name' => 'stm_lms_udemy_settings',
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'Udemy Importer',
				'menu_title'  => 'Udemy Importer',
				'menu_slug'   => 'stm-lms-udemy-settings',
			),
			'fields'      => $this->stm_lms_settings(),
		);

		return $setups;
	}

	public function stm_lms_settings() {
		return apply_filters(
			'stm_lms_udemy_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Credentials', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'udemy_client_id'          => array(
							'type'        => 'text',
							'label'       => esc_html__( 'Client ID', 'masterstudy-lms-learning-management-system-pro' ),
							'description' => wp_kses_post( 'You need <a href="https://www.udemy.com/user/edit-api-clients/" target="_blank">Udemy API</a> credentials.' ),
						),
						'udemy_client_secret'      => array(
							'type'        => 'text',
							'label'       => esc_html__( 'Client Secret', 'masterstudy-lms-learning-management-system-pro' ),
							'description' => wp_kses_post( 'You need <a href="https://www.udemy.com/user/edit-api-clients/" target="_blank">Udemy API</a> credentials.' ),
						),
						'udemy_affiliate_automate' => array(
							'type'        => 'textarea',
							'label'       => esc_html__( 'Udemy Rakuten Affiliate script', 'masterstudy-lms-learning-management-system-pro' ),
							'description' => wp_kses_post( 'Get Your <a href="http://cli.linksynergy.com/cli/publisher/portfolio/automate/automate.php" target="_blank">Rakuten Automate script</a> and paste it here.' ),
						),

					),
				),
				'search'      => array(
					'name'   => esc_html__( 'Search', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'search_udemy' => array(
							'type'  => 'udemy/search',
							'label' => esc_html__( 'Search Courses', 'masterstudy-lms-learning-management-system-pro' ),
						),
					),
				),
				'courses'     => array(
					'name'   => esc_html__( 'Imported Courses', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'manage_udemy_courses' => array(
							'type'      => 'manage_udemy_posts',
							'meta_key'  => 'udemy_course_id',
							'post_type' => 'stm-courses',
						),
					),
				),
			)
		);
	}

	public static function search_courses() {
		check_ajax_referer( 'stm_lms_pro_search_courses', 'nonce' );

		$s                 = ( ! empty( $_GET['s'] ) ) ? sanitize_text_field( $_GET['s'] ) : '';
		$transient_name    = "stm_lms_search_courses_{$s}";
		$disable_transient = true;
		$courses           = get_transient( $transient_name );

		if ( false === $courses || $disable_transient ) {
			require_once STM_LMS_PRO_INCLUDES . '/libraries/Udemy/autoload.php';

			$client = new Udemy_Client();
			$apis   = get_option( 'stm_lms_udemy_settings', array() );

			if ( empty( $apis['udemy_client_id'] ) || empty( $apis['udemy_client_secret'] ) ) {
				wp_send_json( esc_html__( 'Please, enter Udemy API Credentials', 'masterstudy-lms-learning-management-system-pro' ) );
				die;
			}

			$client_id     = $apis['udemy_client_id'];
			$client_secret = $apis['udemy_client_secret'];
			$client->setClientId( $client_id );
			$client->setClientSecret( $client_secret );

			$service = new Udemy_Service_Courses( $client );

			$opt_params = array(
				'search'    => $s,
				'page_size' => 50,
			);

			try {
				$results = $service->courses->listCourses( $opt_params );
			} catch ( \Udemy_Exception $e ) {
				wp_send_json( $e->getMessage(), 500 );
			}

			$courses = array();

			foreach ( $results as $item ) {
				$courses[] = $item;
			}

			set_transient( $transient_name, $courses, 60 * 60 );
		}

		if ( empty( $courses ) ) {
			$courses = array(
				array(
					'title' => esc_html__( 'Nothing Found', 'masterstudy-lms-learning-management-system-pro' ),
				),
			);
		}

		if ( $disable_transient ) {
			delete_transient( $transient_name );
		}

		wp_send_json( $courses );
	}

	public static function is_udemy_course( $id = '' ) {
		if ( empty( $id ) ) {
			global $post;
			$id = $post->ID;
		}

		return apply_filters( 'stm_lms_is_udemy_course', get_post_meta( $id, 'udemy_course_id', true ) );
	}

	public static function udemy_classes( $classes ) {
		if ( is_singular( 'stm-courses' ) ) {
			$is_udemy = self::is_udemy_course();
			if ( $is_udemy ) {
				$classes .= ' stm_lms_udemy_course';
			}
		}

		return $classes;
	}

	public static function single_course() {
		$is_udemy = self::is_udemy_course();
		if ( $is_udemy ) {
			remove_all_actions( 'stm-lms-content-stm-courses' );
			STM_LMS_Templates::show_lms_template( 'course/udemy/single' );
		}
	}

	public static function publish_course() {
		check_ajax_referer( 'stm_lms_pro_udemy_publish_course', 'nonce' );

		$udemy_course_id = intval( $_GET['id'] );

		$course_id = STM_LMS_Udemy_Import::is_course_exist( $udemy_course_id );

		if ( empty( $course_id ) ) {
			die;
		}

		$course = array(
			'ID'          => $course_id,
			'post_status' => 'publish',
		);

		wp_update_post( $course );

		wp_send_json( esc_html__( 'Published', 'masterstudy-lms-learning-management-system-pro' ) );
	}

	public static function affiliate_automate_links() {
		$settings = get_option( 'stm_lms_udemy_settings', array() );
		$script   = '';

		if ( ! empty( $settings['udemy_affiliate_automate'] ) ) {
			$script = str_replace(
				array(
					'<!-- Rakuten Automate starts here -->',
					'<!-- Rakuten Automate ends here -->',
					'<script type="text/javascript">',
					'</script>',
				),
				array( '' ),
				$settings['udemy_affiliate_automate']
			);
		}

		stm_lms_register_script( 'buy-button', array(), true, $script );
	}

	public static function replace_templates( $name, $stm_lms_vars ) {
		switch ( $name ) {
			case ( '/stm-lms-templates/courses/parts/rating.php' ):
				if ( self::is_udemy_course( $stm_lms_vars['id'] ) ) {
					$name = '/stm-lms-templates/courses/udemy/parts/rating.php';
				}
				break;
			case ( '/stm-lms-templates/courses/parts/course_info.php' ):
				if ( self::is_udemy_course( $stm_lms_vars['post_id'] ) ) {
					$name = '/stm-lms-templates/courses/udemy/parts/course_info.php';
				}
				break;
		}

		return $name;
	}
}
