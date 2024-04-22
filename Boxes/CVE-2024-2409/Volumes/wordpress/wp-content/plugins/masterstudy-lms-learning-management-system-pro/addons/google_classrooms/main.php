<?php

require_once STM_LMS_PRO_ADDONS . '/google_classrooms/admin_ajax.php';
require_once STM_LMS_PRO_ADDONS . '/google_classrooms/auditory.php';
require_once STM_LMS_PRO_ADDONS . '/google_classrooms/module_ajax.php';
require_once STM_LMS_PRO_ADDONS . '/google_classrooms/popup.php';
require_once STM_LMS_PRO_ADDONS . '/google_classrooms/demo.php';

STM_LMS_Google_Classroom::getInstance();

class STM_LMS_Google_Classroom {

	private static $instance             = null;
	public static $course_classroom_name = 'stm_lms_google_classroom_id';
	private $token_name                  = 'stm_lms_google_classroom_token';
	private static $config_name          = 'stm_lms_google_classroom_config';
	private $client;
	private $service;
	public $admin_url;

	public function __construct() {
		/*ACTIONS*/
		add_action( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ) );

		add_action(
			'wpcfto_settings_screen_stm_lms_google_classrooms_settings_before',
			function () {
				require_once dirname( __FILE__ ) . '/admin_view/google_api_classroom.php';
			}
		);

		add_action(
			'init',
			function () {
				if ( isset( $_GET['code'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$this->get_code();
				}

				if ( isset( $_GET['stm_lms_remove_token'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( empty( $this->admin_url ) ) {
						$this->admin_url = get_option( $this::$config_name )['web']['redirect_uris'][0];
					}
					$this->revoke_token();
					$this->redirect_to_admin();
				}

				if ( isset( $_GET['stm_lms_delete_credentials'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( empty( $this->admin_url ) ) {
						$this->admin_url = get_option( $this::$config_name )['web']['redirect_uris'][0];
					}
					$this->revoke_config();
					$this->redirect_to_admin();
				}
			}
		);

		add_action( 'stm_lms_register_custom_fields', array( $this, 'register_select_classroom' ) );

		add_action( 'stm_lms_user_registered', array( $this, 'user_registered' ), 10, 2 );

		add_action( 'vc_after_init', array( $this, 'vc_module' ) );

		add_action( 'wp_ajax_stm_lms_g_c_load_credentials', array( $this, 'load_credentials' ) );

		add_shortcode( 'stm_lms_google_classroom', array( $this, 'add_shortcode' ) );

	}

	public function vc_module() {
		vc_map(
			array(
				'name'          => esc_html__( 'STM Google Classrooms', 'masterstudy' ),
				'base'          => 'stm_lms_google_classroom',
				'icon'          => 'stm_lms_google_classroom',
				'description'   => esc_html__( 'Google Classrooms grid view', 'masterstudy' ),
				'html_template' => STM_LMS_PRO_ADDONS . '/google_classrooms/frontend_view/google_classroom.php',
				'category'      => array(
					esc_html__( 'Content', 'masterstudy' ),
				),
				'params'        => array(
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Title', 'masterstudy' ),
						'param_name' => 'title',
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Number', 'masterstudy' ),
						'param_name' => 'number',
					),
				),
			)
		);
	}

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new STM_LMS_Google_Classroom();
		}

		return self::$instance;
	}

	public function setClient() {
		$config       = self::getAuthConfig();
		$this->client = new Google_Client();

		if ( ! empty( $config ) ) {
			$this->client->setAuthConfig( self::getAuthConfig() );
			$this->client->addScope( Google_Service_Classroom::CLASSROOM_COURSES_READONLY );
			$this->client->addScope( Google_Service_Classroom::CLASSROOM_TOPICS_READONLY );
		}
	}

	public static function add_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'  => '',
				'number' => '',
			),
			$atts
		);

		return STM_LMS_Templates::load_lms_template( 'shortcodes/google_classroom', $atts );
	}

	public function load_credentials() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die;
		}

		$file = $_FILES['file'];
		$path = $file['name'];

		$path_info = pathinfo( $path );

		if ( 'json' !== $path_info['extension'] ) {
			wp_send_json( array( 'error' => esc_html__( 'Not a JSON file', 'masterstudy-lms-learning-management-system-pro' ) ) );
		}

		$file_content = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		$file_content = json_decode( $file_content, true );

		if ( empty( $file_content['web'] ) ) {
			wp_send_json( array( 'error' => esc_html__( 'Wrong JSON file', 'masterstudy-lms-learning-management-system-pro' ) ) );
		}

		if ( empty( $file_content['web']['client_id'] ) ||
			empty( $file_content['web']['project_id'] ) ||
			empty( $file_content['web']['auth_uri'] ) ||
			empty( $file_content['web']['token_uri'] ) ||
			empty( $file_content['web']['client_secret'] ) ||
			empty( $file_content['web']['auth_provider_x509_cert_url'] ) ) {
			wp_send_json( array( 'error' => esc_html__( 'Wrong JSON file', 'masterstudy-lms-learning-management-system-pro' ) ) );
		}
		$this->admin_url = $file_content['web']['redirect_uris'];

		update_option( self::$config_name, $file_content );

		wp_send_json( array( 'success' => esc_html__( 'JSON Loaded, reloading...', 'masterstudy-lms-learning-management-system-pro' ) ) );
	}

	public static function getAuthConfig() {
		return get_option( self::$config_name, array() );
	}

	public function setAuthConfig() {}

	public function setService() {
		$this->client->setAccessToken( $this->get_auth_token() );
		$this->service = new Google_Service_Classroom( $this->client );
	}

	public function get_code() {
		$this->setClient();
		$this->save_auth_token( $this->client->fetchAccessTokenWithAuthCode( $_GET['code'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->redirect_to_admin();
	}

	public function redirect_to_admin() {
		wp_safe_redirect( $this->admin_url );
	}

	public function save_auth_token( $token ) {
		if ( ! isset( $token['error'] ) ) {
			update_option( $this->token_name, $token );
		}
	}

	public function get_auth_token() {
		return get_option( $this->token_name, '' );
	}

	public function revoke_token() {
		delete_option( $this->token_name );
	}

	public function revoke_config() {
		delete_option( self::$config_name );
	}

	public function get_auth_url() {
		$this->setClient();

		return $this->client->createAuthUrl();
	}

	public function revoke_token_url() {
		return add_query_arg( 'stm_lms_remove_token', '1', $this->admin_url );
	}

	public function delete_credentials() {
		return add_query_arg( 'stm_lms_delete_credentials', '1', $this->admin_url );
	}

	public function courses() {
		$token = $this->get_auth_token();

		$this->setClient();
		$this->setService();

		$options = array(
			'pageSize' => 100,
		);

		$data = array( 'courses' => array() );

		if ( empty( $token ) ) {
			wp_send_json( $data );
		}

		try {
			$results = $this->service->courses->listCourses( $options );

			if ( count( $results->getCourses() ) ) {

				foreach ( $results->getCourses() as $item ) {
					$course_id = $item->id;

					$course_data = array(
						'course_id'          => $item->id,
						'name'               => $item->name,
						'section'            => $item->section,
						'descriptionHeading' => $item->descriptionHeading,
						'description'        => $item->description,
						'auditory'           => $item->room,
						'ownerId'            => $item->ownerId,
						'creationTime'       => $item->creationTime,
						'updateTime'         => $item->updateTime,
						'code'               => $item->enrollmentCode,
						'alternateLink'      => $item->alternateLink,
						'courseState'        => $item->courseState,
						'teacherGroupEmail'  => $item->teacherGroupEmail,
						'courseGroupEmail'   => $item->courseGroupEmail,
						'calendarId'         => $item->calendarId,
						'courseMaterialSets' => $item->courseMaterialSets,
						'teacherFolder'      => $item->teacherFolder,
					);

					$lms_course_id = $this->get_post_by_google_id( $course_id );

					if ( ! empty( $lms_course_id ) ) {
						$course_data['action_links'] = array(
							'status'          => get_post_status( $lms_course_id ),
							'course_url'      => get_permalink( $lms_course_id ),
							'course_url_edit' => get_edit_post_link( $lms_course_id, 'normal' ),
						);
					}

					/*Save to transient*/
					set_transient( "stm_lms_google_classroom_course_{$course_id}", $course_data );

					$data['courses'][] = $course_data;
				}
			}
		} catch ( Exception $e ) {
			$this->revoke_token();
			$data['error'] = $e->getMessage();
		}

		return $data;
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {

		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'edit.php?post_type=stm-g-classrooms',
				'page_title'  => 'Import Classrooms',
				'menu_title'  => 'Import Classrooms',
				'menu_slug'   => 'google_classrooms',
			),
			'fields'      => $this->stm_lms_settings(),
			'option_name' => 'stm_lms_google_classrooms_settings',
		);

		return $setups;

	}

	public function stm_lms_settings() {
		return apply_filters(
			'stm_lms_google_classrooms_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Credentials', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'locked'       => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Only signed-in students will see the code', 'masterstudy-lms-learning-management-system-pro' ),
							'value' => false,
						),
						'enable_popup' => array(
							'type'  => 'checkbox',
							'label' => esc_html__( 'Enable popup', 'masterstudy-lms-learning-management-system-pro' ),
						),
						'popup_title'  => array(
							'type'       => 'text',
							'label'      => esc_html__( 'Popup Title', 'masterstudy-lms-learning-management-system-pro' ),
							'dependency' => array(
								'key'   => 'enable_popup',
								'value' => 'not_empty',
							),
						),
						'popup_editor' => array(
							'type'       => 'editor',
							'label'      => esc_html__( 'Popup Editor', 'masterstudy-lms-learning-management-system-pro' ),
							'dependency' => array(
								'key'   => 'enable_popup',
								'value' => 'not_empty',
							),
						),
						'popup_image'  => array(
							'type'       => 'image',
							'label'      => esc_html__( 'Popup Image', 'masterstudy-lms-learning-management-system-pro' ),
							'dependency' => array(
								'key'   => 'enable_popup',
								'value' => 'not_empty',
							),
						),
						'popup_link'   => array(
							'type'       => 'text',
							'label'      => esc_html__( 'Popup Auditory base URL', 'masterstudy-lms-learning-management-system-pro' ),
							'dependency' => array(
								'key'   => 'enable_popup',
								'value' => 'not_empty',
							),
						),
					),
				),
			)
		);
	}

	public static function stm_lms_get_settings() {
		return get_option( 'stm_lms_google_classrooms_settings', array() );
	}

	public static function imported_course_id( $course ) {

		$course_id = '';

		$args = array(
			'post_type'      => 'stm-g-classrooms',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'     => self::$course_classroom_name,
					'value'   => $course['course_id'],
					'compare' => '=',
				),
			),
		);

		$q = new WP_Query( $args );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();

				$course_id = get_the_ID();
			}
		}

		$post_content = ! empty( $course['description'] ) ? $course['description'] : '';

		if ( empty( $post_content ) && ! empty( $course['descriptionHeading'] ) ) {
			$post_content = $course['descriptionHeading'];
		}

		$course_data = array(
			'post_title'        => $course['name'],
			'post_date'         => $course['creationTime'],
			'post_date_gmt'     => $course['creationTime'],
			'post_content'      => $post_content,
			'post_status'       => 'draft',
			'post_type'         => 'stm-g-classrooms',
			'post_modified'     => $course['updateTime'],
			'post_modified_gmt' => $course['updateTime'],
		);

		if ( ! empty( $course_id ) ) {
			$course_data['ID'] = $course_id;
		}

		$course_id = wp_insert_post( $course_data );

		update_post_meta( $course_id, self::$course_classroom_name, $course['course_id'] );

		return $course_id;
	}

	public function get_post_by_google_id( $google_id ) {

		global $wpdb;
		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$course = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'stm_lms_google_classroom_id' AND  meta_value = {$google_id} LIMIT 1", ARRAY_A );

		if ( empty( $course ) ) {
			return null;
		}
		$course = STM_LMS_Helpers::simplify_db_array( $course );

		return $course['post_id'];
	}

	public function register_select_classroom() {
		$auditories = STM_LMS_Helpers::get_posts( 'stm-auditory' );
		STM_LMS_Templates::show_lms_template( 'account/public/select_google_classroom_auditory', compact( 'auditories' ) );
	}

	public function user_registered( $user_id, $data ) {
		if ( ! empty( $data['auditory'] ) ) {
			update_user_meta( $user_id, 'google_classroom_auditory', intval( $data['auditory'] ) );
		}
	}

	public function google_classroom_view( $fields ) {
		if ( ! empty( $fields['stm_courses_settings'] ) &&
			! empty( $fields['stm_courses_settings']['section_settings'] ) &&
			! empty( $fields['stm_courses_settings']['section_settings']['fields'] )
		) {

			$google_classroom_view = array(
				'google_classroom_view' => array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Google Classroom view', 'masterstudy-lms-learning-management-system-pro' ),
				),
			);

			$fields['stm_courses_settings']['section_settings']['fields'] = array_merge(
				$google_classroom_view,
				$fields['stm_courses_settings']['section_settings']['fields']
			);

		}

		return $fields;
	}
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
	class WPBakeryShortCode_Stm_Lms_Google_Classroom extends WPBakeryShortCode {
	}
}
