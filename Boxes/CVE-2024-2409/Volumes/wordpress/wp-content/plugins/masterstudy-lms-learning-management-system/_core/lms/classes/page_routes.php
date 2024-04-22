<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

new STM_LMS_Page_Router();

class STM_LMS_Page_Router {

	private $settings              = array();
	private $pages                 = array();
	private $page_config           = array();
	private $routes                = array();
	private static $regex          = '([^/]+)';
	private $is_wpml               = false;
	private $current_language_code = 'en';

	private static $pages_transient_name        = 'stm_lms_routes_pages_transient';
	private static $pages_config_transient_name = 'stm_lms_routes_pages_config_transient';
	private static $pages_routes_transient_name = 'stm_lms_routes_pages_routes_transient';

	public function __construct() {
		add_action( 'stm_lms_pages_generated', array( $this, 'reset_config' ) );
		add_action( 'wpcfto_settings_saved', array( $this, 'reset_config' ) );
		add_action( 'permalink_structure_changed', array( $this, 'reset_config' ) );

		add_action( 'save_post', array( $this, 'reset_page_config' ) );

		add_action( 'init', array( $this, 'init' ), 99999 );

		add_action( 'template_redirect', array( $this, 'include_template' ) );

		add_filter( 'wpml_active_languages', array( $this, 'modify_switcher' ) );

		add_filter( 'wpml_ls_language_url', array( $this, 'wpml_current_language_url' ) );
	}

	public function init() {
		$has_config = $this->has_config();

		if ( ! $has_config || $this->update_config() ) {
			$this->settings = get_option( 'stm_lms_settings', array() );

			$this->wpml_config();

			$this->set_pages();

			if ( $this->is_wpml ) {
				$this->add_wpml_pages();
			}

			$this->page_config = $this->pages;

			foreach ( $this->pages as &$page ) {
				$this->fill_parent_data( $page );
			}

			$this->generate_pages( $this->pages );

			$this->save_config();

			flush_rewrite_rules( true );
		}

		$this->add_rewrite_rules();

		add_filter( 'the_content', array( $this, 'change_page_content' ), 100 );
	}

	public function modify_switcher( $langs ) {
		if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
			return $langs;
		}

		$lms_path = get_query_var( 'lms_template' );

		$pages        = array();
		$current_page = array();

		foreach ( wp_list_pluck( $this->routes, 'template' ) as $page_index => $page_key ) {
			if ( $page_key !== $lms_path ) {
				continue;
			}

			$page = $this->routes[ $page_index ];

			if ( ! empty( $page['parent'] ) && empty( $page['type'] ) ) {
				if ( empty( $page['lang'] ) ) {
					$page['lang'] = $this->current_language_code;
				}

				if ( ICL_LANGUAGE_CODE === $page['lang'] ) {
					$current_page = $page;
				}

				if ( ! empty( $langs[ $page['lang'] ] ) ) {
					$pages[ $page['lang'] ] = $page;
				}
			}
		}

		/*Now we have pages to change and page where we have current parent*/
		if ( ! empty( $current_page ) && ! empty( $current_page['parent'] ) && ! empty( $current_page['parent'][0] ) ) {
			$current_page_path = $current_page['parent'][0];

			foreach ( $langs as &$lang ) {
				$page_path = $pages[ $lang['code'] ]['parent'][0];

				$lang['url'] = esc_url( str_replace( $current_page_path, "/{$page_path}", $lang['url'] ?? '' ) );
			}
		}

		return $langs;
	}

	public function wpml_current_language_url( $url ) {
		$endpoint = get_query_var( 'lms_template' );

		if ( ! empty( $endpoint ) && ! empty( $this->pages['user_url']['sub_pages'] ) ) {
			foreach ( $this->pages['user_url']['sub_pages'] as $subpage ) {
				if ( $subpage['template'] === $endpoint && false === strpos( $url, $subpage['url'] ) ) {
					$url = rtrim( $url, '/' ) . "/{$this->pages['user_url']['url']}/{$subpage['url']}";
					break;
				}
			}
		}

		return $url;
	}

	public function reset_page_config( $post ) {
		if ( get_post_type( $post ) === 'page' ) {
			$this->reset_config();
		}
	}

	public function has_config() {
		$pages       = get_transient( self::$pages_transient_name );
		$page_config = get_transient( self::$pages_config_transient_name );
		$routes      = get_transient( self::$pages_routes_transient_name );

		if ( false === $pages || false === $page_config || false === $routes ) {
			return false;
		}

		$this->pages       = $pages;
		$this->page_config = $page_config;
		$this->routes      = $routes;

		return true;
	}

	public function update_config() {
		$pages_config = self::pages_config();

		return count( $pages_config['user_url']['sub_pages'] ) !== count( $this->pages['user_url']['sub_pages'] );
	}

	public function save_config() {
		set_transient( self::$pages_transient_name, $this->pages, WEEK_IN_SECONDS );
		set_transient( self::$pages_config_transient_name, $this->page_config, WEEK_IN_SECONDS );
		set_transient( self::$pages_routes_transient_name, $this->routes, WEEK_IN_SECONDS );
	}

	public function reset_config() {
		delete_transient( self::$pages_transient_name );
		delete_transient( self::$pages_config_transient_name );
		delete_transient( self::$pages_routes_transient_name );

		flush_rewrite_rules( true );
	}

	public static function pages_config() {
		$settings = get_option( 'stm_lms_settings', array() );

		$course_player_template = 'old' === ( $settings['course_player_view'] ?? 'new' )
			? 'stm-lms-lesson'
			: 'course-player';
		$courses_page_slug      = ! empty( $settings['courses_page'] )
			? get_post_field( 'post_name', $settings['courses_page'] )
			: STM_LMS_Options::courses_page_slug();

		$page_routes = array(
			'user_url'         => array(
				'title'     => esc_html__( 'Private account', 'masterstudy-lms-learning-management-system' ),
				'logged_in' => 'stm-lms-user',
				'template'  => 'stm-lms-login',
				'sub_pages' => array(
					'chat_url'             => array(
						'template'  => 'stm-lms-user-chats',
						'protected' => true,
						'url'       => 'chat',
					),
					'enrolled_courses_url' => array(
						'template'  => 'stm-lms-user-courses',
						'protected' => true,
						'url'       => 'enrolled-courses',
					),
					'enrolled_quizzes_url' => array(
						'template'  => 'stm-lms-user-quizzes',
						'protected' => true,
						'url'       => 'enrolled-quizzes',
					),
					'my_orders_url'        => array(
						'template'  => 'stm-lms-user-orders',
						'protected' => true,
						'url'       => 'my-orders',
					),
					'settings_url'         => array(
						'template'  => 'stm-lms-user-settings',
						'protected' => true,
						'url'       => 'settings',
					),
					'paid_membership_url'  => array(
						'template'  => 'stm-lms-user-pmp',
						'protected' => true,
						'url'       => 'memberships-pmp',
					),
					'certificate_url'      => array(
						'template'  => 'stm-lms-certificates',
						'protected' => true,
						'url'       => 'certificates',
						'sub_pages' => array(
							'certificate_url_generate' => array(
								'template'  => 'stm-lms-certificates-generator',
								'protected' => true,
								'var'       => 'course_id',
							),
						),
					),
					'add_students'         => array(
						'template'         => 'stm-lms-instructor-add-students',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'add-students',
					),
					'announcement'         => array(
						'template'         => 'stm-lms-instructor-announcement',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'announcement',
					),
					'manage_course'        => array(
						'template'         => 'course-builder',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'edit-course',
						'sub_pages'        => array(
							'edit_course' => array(
								'template'  => 'course-builder',
								'protected' => true,
								'var'       => 'course_id',
							),
						),
					),
					'manage_lesson'        => array(
						'template'         => 'course-builder',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'edit-lesson',
						'sub_pages'        => array(
							'edit_course' => array(
								'template'  => 'course-builder',
								'protected' => true,
								'var'       => 'lesson_id',
							),
						),
					),
					'manage_quiz'          => array(
						'template'         => 'course-builder',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'edit-quiz',
						'sub_pages'        => array(
							'edit_course' => array(
								'template'  => 'course-builder',
								'protected' => true,
								'var'       => 'quiz_id',
							),
						),
					),
					'manage_assignment'    => array(
						'template'         => 'course-builder',
						'protected'        => true,
						'instructors_only' => true,
						'url'              => 'edit-assignment',
						'sub_pages'        => array(
							'edit_course' => array(
								'template'  => 'course-builder',
								'protected' => true,
								'var'       => 'assignment_id',
							),
						),
					),
				),
			),
			'courses_url'      => array(
				'title'     => esc_html__( 'Course', 'masterstudy-lms-learning-management-system' ),
				'post_type' => 'stm-courses',
				'url'       => "$courses_page_slug/" . self::$regex,
				'sub_pages' => array(
					'lesson_url' => array(
						'template' => $course_player_template,
						'var'      => 'lesson_id',
						'match'    => 2,
					),
				),
			),
			'user_url_profile' => array(
				'title'     => esc_html__( 'Account', 'masterstudy-lms-learning-management-system' ),
				'logged_in' => 'account/public/main',
				'template'  => 'stm-lms-login',
				'sub_pages' => array(
					'user_url_profile_single' => array(
						'template' => 'stm-lms-user-public',
						'var'      => 'lms_user_id',
					),
				),
			),
			'wishlist_url'     => array(
				'title'    => esc_html__( 'Wishlist', 'masterstudy-lms-learning-management-system' ),
				'template' => 'stm-lms-wishlist',
			),
			'checkout_url'     => array(
				'title'    => esc_html__( 'Checkout', 'masterstudy-lms-learning-management-system' ),
				'template' => 'stm-lms-checkout',
			),
		);

		self::fill_subpages_url( $page_routes, $settings );

		return apply_filters( 'stm_lms_custom_routes_config', $page_routes );
	}

	public function set_pages() {
		$this->pages = self::pages_config();

		foreach ( $this->pages as $page_slug => &$page_settings ) {
			if ( empty( $page_settings['post_type'] ) ) {
				$page_id = 0;

				if ( ! empty( $this->settings[ $page_slug ] ) ) {
					$page_id = $this->settings[ $page_slug ];
				}

				$page_settings['page_id'] = $page_id;

				$permalink = get_the_permalink( $page_id );

				if ( $this->is_wpml ) {
					$permalink = apply_filters( 'wpml_permalink', get_permalink( $page_settings['page_id'] ), $this->current_language_code, true );
				}

				$page_settings['url'] = ( ! empty( $page_settings['page_id'] ) ) ? basename( $permalink ) : '';
			} elseif ( empty( $page_settings['url'] ) ) {
				$page_settings['url'] = get_post_type_archive_link( $page_settings['post_type'] );
			}
		}
	}

	public static function fill_subpages_url( &$page_routes, $settings ) {
		foreach ( $page_routes as &$page_route ) {
			if ( ! empty( $page_route['sub_pages'] ) ) {
				foreach ( $page_route['sub_pages'] as $sub_page_key => &$sub_page ) {
					if ( ! empty( $settings[ $sub_page_key ] ) ) {
						$sub_page['url'] = $settings[ $sub_page_key ];
					}
				}
			}
		}
	}

	public function change_page_content( $content ) {
		foreach ( $this->pages as $page_slug => $page_settings ) {
			if ( ! empty( $page_settings['page_id'] ) && is_page( $page_settings['page_id'] ) ) {
				remove_filter( 'the_content', array( $this, 'change_page_content' ), 100 );

				$template = ( ! empty( $page_settings['logged_in'] ) && is_user_logged_in() ) ? $page_settings['logged_in'] : $page_settings['template'];

				$page_content = STM_LMS_Templates::load_lms_template( 'body_class' );

				$page_content .= STM_LMS_Templates::load_lms_template( $template );

				add_filter( 'the_content', array( $this, 'change_page_content' ), 100 );

				return $content . $page_content;
			}
		}

		return $content;
	}

	public function fill_parent_data( &$page ) {
		if ( ! empty( $page['sub_pages'] ) ) {
			foreach ( $page['sub_pages'] as &$page_settings ) {
				$page_settings['parent']   = ( ! empty( $page['parent'] ) ) ? $page['parent'] : array();
				$page_settings['parent'][] = $page['url'];

				if ( ! empty( $page['post_type'] ) ) {
					$page_settings['type'] = $page['post_type'];
				}

				$this->fill_parent_data( $page_settings );
			}
		}
	}

	public function generate_pages( $pages ) {
		if ( ! empty( $pages ) ) {
			$this->generate_routes( $pages );

			if ( ! empty( $pages ) ) {
				$this->generate_pages( $pages );
			}
		}
	}

	public function generate_routes( &$pages ) {
		foreach ( $pages as $page_key => &$page ) {
			if ( ! empty( $page['sub_pages'] ) ) {
				$this->generate_routes( $page['sub_pages'] );
			} else {
				if ( ! isset( $page['page_id'] ) && empty( $page['post_type'] ) ) {
					$this->routes[] = $page;
				}

				unset( $pages[ $page_key ] );
			}
		}
	}

	public function add_rewrite_rules() {
		foreach ( $this->routes as $route ) {
			if ( empty( $route['parent'] ) ) {
				continue;
			}

			$base = implode( '/', $route['parent'] );

			$lang = $route['lang'] ?? $this->current_language_code;

			/*We have dynamic url*/
			if ( ! empty( $route['var'] ) ) {
				add_rewrite_tag( "%{$route['var']}%", '([^/]+)' );

				$match = $route['match'] ?? 1;

				$query = "index.php?{$route['var']}" . '=$matches[' . $match . ']&lms_template=' . $route['template'] . '&lms_page_path=$matches[1]';

				$query .= "&lang={$lang}";

				if ( ! empty( $route['type'] ) ) {
					$query = self::modify_post_type_query( $query );
				}

				add_rewrite_rule(
					"{$base}/([^/]+)/?",
					$query,
					'top'
				);
			} else {
				add_rewrite_tag( '%lms_template%', '([^/]+)' );

				$regex = "{$base}/({$route['url']})/?";

				$query = 'index.php?lms_template=' . $route['template'];

				$query .= "&lang={$lang}";

				add_rewrite_rule(
					$regex,
					$query,
					'top'
				);
			}
		}
	}

	public function include_template() {
		$lms_path = get_query_var( 'lms_template' );

		if ( empty( $lms_path ) ) {
			return false;
		}

		$this->template( $this->page_config, $lms_path );
	}

	public function template( $pages, $lms_path ) {
		if ( ! empty( $pages ) ) {
			foreach ( $pages as $page ) {
				$template_path = ( ! empty( $page['template'] ) ) ? $page['template'] : '';

				if ( ! empty( $template_path ) && $lms_path === $template_path ) {
					add_filter(
						'template_include',
						function () use ( $page ) {
							$template = $page['template'];

							if ( ! empty( $page['protected'] ) && $page['protected'] && ! is_user_logged_in() ) {
								wp_safe_redirect( STM_LMS_User::login_page_url() );
							}

							if ( ! empty( $page['instructors_only'] ) && $page['instructors_only'] && ! STM_LMS_Instructor::is_instructor() ) {
								wp_safe_redirect( STM_LMS_User::login_page_url() );
							}

							return STM_LMS_Templates::locate_template( $template );
						},
						20
					);
				}

				if ( ! empty( $page['sub_pages'] ) ) {
					$this->template( $page['sub_pages'], $lms_path );
				}
			}
		}
	}

	public function add_wpml_pages() {
		global $sitepress;

		$languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );

		$page_ids = array_filter( array_column( $this->pages, 'page_id' ) );

		foreach ( $languages as $language ) {
			foreach ( $this->pages as $page_key => $page ) {
				if ( empty( $page['page_id'] ) ) {
					continue;
				}

				$page_id = apply_filters( 'wpml_object_id', $page['page_id'], get_post_type( $page['page_id'] ), false, $language['code'] );

				if ( in_array( $page_id, $page_ids ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					continue;
				}

				$page_language            = $page;
				$page_language['page_id'] = $page_id;
				$page_language['title']   = get_the_title( $page_id );
				$page_language['url']     = basename( apply_filters( 'wpml_permalink', get_permalink( $page['page_id'] ), $language['code'], true ) );
				$page_language['lang']    = $language['code'];

				$this->insert_language_code( $page_language, $language['code'] );

				$this->pages[ "{$page_key}_{$language['code']}" ] = $page_language;
			}

			// Add Course Player page for each language
			if ( $language['code'] !== $sitepress->get_default_language() ) {
				$courses_page_id = apply_filters( 'wpml_object_id', STM_LMS_Options::courses_page(), 'page', false, $language['code'] );

				if ( ! empty( $courses_page_id ) ) {
					$this->pages[ "courses_url_{$language['code']}" ] = array_merge(
						$this->pages['courses_url'],
						array(
							'url'  => get_post_field( 'post_name', $courses_page_id ) . '/' . self::$regex,
							'lang' => $language['code'],
						)
					);
				}
			}
		}
	}

	public function insert_language_code( &$arr, $code ) {
		if ( ! empty( $arr ) && ! empty( $arr['sub_pages'] ) ) {
			foreach ( $arr['sub_pages'] as &$sub_page ) {
				$sub_page['lang'] = $code;
				$this->insert_language_code( $sub_page, $code );
			}
		}
	}

	public function wpml_config() {
		$this->is_wpml = class_exists( 'SitePress' );

		if ( $this->is_wpml ) {
			global $sitepress;
			$this->current_language_code = $sitepress->get_default_language();
		}
	}

	public static function modify_post_type_query( $query ) {
		add_rewrite_tag( '%lms_page_path%', '([^/]+)' );

		return $query . '&lms_page_path=$matches[1]';
	}
}
