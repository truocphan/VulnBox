<?php
require_once STM_LMS_PRO_ADDONS . '/scorm/db.php';

new STM_LMS_Scorm_Packages();

class STM_LMS_Scorm_Packages {

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'course_progress_endpoint' ) );

		add_filter( 'stm_wpcfto_fields', array( $this, 'admin_zip_upload' ) );

		add_filter(
			'wpcfto_modify_file_scorm_package',
			function () {
				return true;
			}
		);

		add_filter( 'wpcfto_modified_scorm_package', array( self::class, 'unzip_scorm_file' ), 10, 2 );

		add_action( 'stm_lms_before_item_lesson_start', array( $this, 'redirect_to_scorm' ), 10, 2 );

		add_filter(
			'stm_lms_stop_item_output',
			function ( $r, $course_id ) {

				if ( self::is_scorm_course( $course_id ) ) {
					$r = true;
				}

				return $r;

			},
			10,
			2
		);

		add_action( 'stm_lms_manage_course_before_curriculum', array( $this, 'add_scorm_package_uploader' ) );

		add_action( 'stm_lms_manage_course_curriculum_atts', array( $this, 'curriculum_atts' ) );

		add_filter( 'stm_lms_manage_course_required_fields', array( $this, 'required_fields' ) );

		add_filter( 'stm_lms_pro_course_added', array( $this, 'frontend_course_add_package' ), 10, 2 );

		add_filter( 'wpcfto_options_page_setup', array( $this, 'stm_lms_settings_page' ) );
	}

	public function course_progress_endpoint() {
		register_rest_route(
			'stm-lms/v1',
			'/scorm_course_progress/(?P<course_id>[\d-]+)/',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'stm_lms_scorm_get_course_progress' ),
			)
		);

		register_rest_route(
			'stm-lms/v1',
			'/scorm_course_progress/(?P<course_id>[\d-]+)/',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'stm_lms_scorm_update_course_progress' ),
			)
		);
	}

	public function stm_lms_scorm_get_course_progress( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			global $wpdb;
			$table       = stm_lms_scorm_name( $wpdb );
			$user_course = stm_lms_get_user_course( get_current_user_id(), intval( $request['course_id'] ), array( 'user_course_id' ) );
			$user_course = STM_LMS_Helpers::simplify_db_array( $user_course );
			$results     = $wpdb->get_results(
				$wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE user_course_id=%d', array( $user_course['user_course_id'] ) ), // phpcs:ignore
				ARRAY_A
			);

			$parameters = array();

			foreach ( $results as $row ) {
				$parameters[ $row['parameter'] ] = $row['value'];
			}

			return $parameters;
		}

		return false;
	}

	public function stm_lms_scorm_update_course_progress( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		if ( $user_id > 0 ) {
			global $wpdb;
			$table              = stm_lms_scorm_name( $wpdb );
			$user_courses_table = stm_lms_user_courses_name( $wpdb );
			$user_course        = stm_lms_get_user_course( get_current_user_id(), intval( $request['course_id'] ), array( 'user_course_id' ) );
			$user_course        = STM_LMS_Helpers::simplify_db_array( $user_course );
			$scorm_data         = $request->get_json_params();
			$scorm_url          = $request->get_url_params();

			if ( ! empty( $scorm_url['course_id'] ) && $scorm_url['course_id'] > 0 ) {
				$last_progress_time = get_user_meta( $user_id, 'last_progress_time', true );
				$last_progress_time = empty( $last_progress_time ) ? array() : $last_progress_time;

				$last_progress_time[ $scorm_url['course_id'] ] = time();
				update_user_meta( $user_id, 'last_progress_time', $last_progress_time );
			}

			if ( ! empty( $scorm_data ) ) {
				//remove previous attempts
				$wpdb->query(
					$wpdb->prepare( 'DELETE FROM ' . $table . ' WHERE user_course_id=%d', array( $user_course['user_course_id'] ) ) // phpcs:ignore
				);
				//add new attempts
				foreach ( $scorm_data as $parameter => $value ) {
					$wpdb->query(
						$wpdb->prepare(
							'INSERT INTO ' . $table . ' SET user_course_id = %d, parameter = %s, value = %s ON DUPLICATE KEY UPDATE value = %s', // phpcs:ignore
							array(
								$user_course['user_course_id'],
								$parameter,
								$value,
								$value,
							)
						)
					);
					if ( 'cmi.core.score.raw' === $parameter || 'cmi.score.raw' === $parameter ) {
						stm_lms_update_user_course_progress( $user_course['user_course_id'], $value );
					} elseif ( 'cmi.core.lesson_status' === $parameter || 'cmi.lesson_status' === $parameter ) {
						$wpdb->query(
							$wpdb->prepare(
								'UPDATE ' . $user_courses_table . ' SET status=%s WHERE user_course_id=%d', // phpcs:ignore
								array(
									$value,
									$user_course['user_course_id'],
								)
							)
						);
					}
				}
			}
		}

		return false;
	}

	public static function package_data() {
		return array(
			'label'       => esc_html__( 'Upload SCORM Package', 'masterstudy-lms-learning-management-system-pro' ),
			'type'        => 'file',
			'description' => esc_html__( 'Course will have one lesson with SCORM package content.', 'masterstudy-lms-learning-management-system-pro' ),
			'load_labels' => array(
				'label'   => esc_html__( 'Choose SCORM package (.zip)', 'masterstudy-lms-learning-management-system-pro' ),
				'loading' => esc_html__( 'Uploading SCORM package', 'masterstudy-lms-learning-management-system-pro' ),
				'loaded'  => esc_html__( 'View SCORM package', 'masterstudy-lms-learning-management-system-pro' ),
				'delete'  => esc_html__( 'Delete package', 'masterstudy-lms-learning-management-system-pro' ),
			),
			'accept'      => array(
				'.zip',
			),
			'mimes'       => array(
				'zip',
			),
			'extract'     => true,
		);
	}

	public function admin_zip_upload( $fields ) {
		$fields['stm_courses_curriculum']['section_curriculum']['fields'] = array(
			'scorm_package' => self::package_data(),
			'curriculum'    => array(
				'type'       => 'curriculum',
				'post_type'  => apply_filters(
					'stm_lms_curriculum_post_types',
					array(
						'stm-lessons',
						'stm-quizzes',
						'stm-assignments',
					)
				),
				'sanitize'   => 'wpcfto_sanitize_curriculum',
				'dependency' => array(
					'key'   => 'scorm_package',
					'value' => 'empty',
				),
			),
		);

		return $fields;
	}

	/**
	 * @param array{path: string} $r
	 * @param string $archive_name filepath
	 *
	 * @return array{path: string, url: string, scorm_version: string, error?: string}
	 */
	public static function unzip_scorm_file( $r, $archive_name ) {
		$archive = $r['path'];

		$upload_dir = STM_WPCFTO_FILE_UPLOAD::upload_dir();
		$upload_url = STM_WPCFTO_FILE_UPLOAD::upload_url();

		$archive_path = pathinfo( $archive_name );
		$archive_name = $archive_path['filename'];

		$zip = new ZipArchive();
		$res = $zip->open( $archive );

		$allowed = array(
			'',
			'css',
			'js',
			'woff',
			'ttf',
			'otf',
			'jpg',
			'jpeg',
			'png',
			'gif',
			'html',
			'json',
			'xml',
			'pdf',
			'mp3',
			'mp4',
			'xsd',
			'dtd',
			'ico',
			'swf',
		);

		$settings = self::stm_lms_get_settings();

		$admin_ext = array();
		if ( ! empty( $settings['allowed_extensions'] ) ) {
			$admin_ext = explode( ',', $settings['allowed_extensions'] );
		}

		$allowed = array_merge( $allowed, $admin_ext );

		$allowed_extensions = apply_filters( 'stm_lms_scorm_allowed_files_ext', $allowed );

		$inappropriate_files = array();
		$manifest_exists     = false;
		$archive_folder      = '';
		$macosx              = array();

		if ( true === $res ) {
			// extract it to the path we determined above

			for ( $i = 0; $i < $zip->numFiles; $i ++ ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$file = $zip->statIndex( $i );

				$item_name = $file['name'];
				$item_ext  = pathinfo( "{$item_name}", PATHINFO_EXTENSION );

				/*skipping macosx folder*/
				if ( substr( $item_name, 0, 9 ) === '__MACOSX/' ) {

					if ( substr( $item_name, - 9 ) === '__MACOSX/' ) {
						$macosx[] = $item_name;
					}

					continue;
				}

				if ( substr( $item_name, - 1 ) === '/' && substr_count( $item_name, '/' ) === 1 && empty( $archive_folder ) ) {
					$manifest_here = "{$upload_dir}/{$archive_name}/{$item_name}/imsmanifest.xml";
					if ( file_exists( $manifest_here ) ) {
						$archive_folder = $item_name;
					}
				}

				if ( 'imsmanifest.xml' === $item_name ) {
					$manifest_exists = true;
					$archive_folder  = '';
				}

				if ( "{$archive_folder}imsmanifest.xml" === $item_name ) {
					$manifest_exists = true;
				}

				if ( ! in_array( $item_ext, $allowed_extensions ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$inappropriate_files[] = $item_name;
				}
			}

			if ( ! empty( $archive_folder ) ) {
				$archive_folder = "/{$archive_folder}";
			}

			if ( ! empty( $inappropriate_files ) ) {
				$inappropriate_files = implode( ', ', $inappropriate_files );

				unlink( $archive );

				return array(
					/* translators: %s Files */
					'error' => sprintf( esc_html__( 'Unacceptable files in package: %s', 'masterstudy-lms-learning-management-system-pro' ), $inappropriate_files ),
				);
			}

			if ( ! $manifest_exists ) {

				unlink( $archive );

				return array(
					'error' => sprintf( esc_html__( 'SCORM Package should contain file imsmanifest.xml', 'masterstudy-lms-learning-management-system-pro' ), $inappropriate_files ),
				);
			}

			$zip->extractTo( "{$upload_dir}/{$archive_name}" );
			$zip->close();
			unlink( $archive );

			$r['path']          = "{$upload_dir}/{$archive_name}{$archive_folder}";
			$r['url']           = "{$upload_url}/{$archive_name}{$archive_folder}";
			$r['scorm_version'] = self::get_manifest_scorm_version( $r );

			/*And remove MACOSX Files*/
			if ( ! empty( $macosx ) ) {
				foreach ( $macosx as $macosx_folder ) {
					self::deleteDir( "{$upload_dir}/{$archive_name}/{$macosx_folder}" );
				}
			}
		} else {
			$r['error'] = esc_html__( 'Could not extract zip contents. Try another SCORM package', 'masterstudy-lms-learning-management-system-pro' );
		}

		return $r;
	}

	public function redirect_to_scorm( $post_id, $item_id ) {
		if ( self::is_scorm_course( $post_id ) ) {

			/*redirect to 0 lesson*/
			if ( '0' != $item_id ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$scorm_url = str_replace( 'stm_lms_scorm_lesson_id', '0', STM_LMS_Lesson::get_lesson_url( $post_id, 'stm_lms_scorm_lesson_id' ) );

				wp_safe_redirect( $scorm_url );
			}

			STM_LMS_Templates::show_lms_template( 'lesson/scorm', compact( 'post_id', 'item_id' ) );
		}
	}

	public static function get_scorm_meta( $post_id ) {
		$meta_value = get_post_meta( $post_id, 'scorm_package', true );

		if ( empty( $meta_value ) ) {
			return array();
		}

		// update_post_meta() unslash value before save
		// double backslash removed from json string
		// we could try to add slashes back, if we gettting json error
		try {
			return json_decode( $meta_value, true, 512, JSON_THROW_ON_ERROR );
		} catch ( \JsonException $e ) {
			$meta_value = preg_replace( '/(?<!\\\\)\\\\(?!\\\\)/', '\\\\\\\\', $meta_value );
			return json_decode( $meta_value, true );
		}
	}

	public static function is_scorm_course( $post_id ) {
		$scorm_package = self::get_scorm_meta( $post_id );

		if ( empty( $scorm_package ) ) {
			return false;
		}

		return empty( $scorm_package['error'] ) && ! empty( $scorm_package['path'] ) && ! empty( $scorm_package['url'] );
	}

	public static function get_iframe_url( $course_id ) {

		$scorm    = self::get_scorm_meta( $course_id );
		$manifest = simplexml_load_file( self::get_course_scorm_manifest( $scorm ) );

		if ( ! empty( $manifest )
			&& ! empty( $manifest->resources )
			&& ! empty( $manifest->resources->resource )
			&& ! empty( $manifest->resources->resource->attributes() )
		) {
			$atts = $manifest->resources->resource->attributes();
			if ( ! empty( $atts->href ) ) {
				return (string) "{$scorm['url']}/" . $atts->href;
			}
		}

		return false;
	}

	public static function get_course_scorm_manifest( $scorm ) {
		$path          = $scorm['path'];
		$manifest_path = "{$path}/imsmanifest.xml";

		return ( file_exists( $manifest_path ) ) ? $manifest_path : false;
	}

	public static function get_manifest_scorm_version( $scorm ) {
		$xml_file      = simplexml_load_file( self::get_course_scorm_manifest( $scorm ) );
		$scorm_version = '1.2';
		if ( ! empty( $xml_file->metadata ) && count( $xml_file->metadata ) >= 1 ) {
			$schema_version = $xml_file->metadata[0]->schemaversion;
			if ( ! empty( $schema_version ) && '1.2' != $schema_version ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$scorm_version = '2004';
			}
		} elseif ( ! empty( $xml_file['version'] ) ) {
			$scorm_version = (string) $xml_file['version'];
		}

		return $scorm_version;
	}

	public function add_scorm_package_uploader() {
		$field_data = self::package_data();
		?>

		<wpcfto_file :fields='<?php echo wp_json_encode( $field_data ); ?>'
					:field_label="'<?php echo esc_attr( $field_data['label'] ); ?>'"
					:field_name="'scorm_package'"
					:field_id="'section_curriculum-scorm_package'"
					:field_value="fields['scorm_package']"
					:field_data='<?php echo wp_json_encode( $field_data ); ?>'
					@wpcfto-get-value="fields['scorm_package'] = $event">
		</wpcfto_file>
		<hr/>
		<?php
	}

	public function curriculum_atts() {
		echo 'v-if="!fields[\'scorm_package\']"';
	}

	public function required_fields( $required_fields ) {
		if ( ! empty( $required_fields['curriculum'] ) && isset( $_POST['scorm_package'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$scorm_package = json_decode( wp_unslash( $_POST['scorm_package'] ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( ! empty( $scorm_package ) && ! empty( $scorm_package['path'] ) && ! empty( $scorm_package['url'] ) ) {
				unset( $required_fields['curriculum'] );
			}
		}

		return $required_fields;
	}

	public function frontend_course_add_package( $data, $course_id ) {
		if ( isset( $data['scorm_package'] ) ) {
			$scorm_package = json_decode( wp_unslash( $data['scorm_package'] ), true );
			if ( ! empty( $scorm_package ) && ! empty( $scorm_package['path'] ) && ! empty( $scorm_package['url'] ) ) {

				$scorm_package = array(
					'error'         => '',
					'path'          => sanitize_text_field( $scorm_package['path'] ),
					'url'           => sanitize_text_field( $scorm_package['url'] ),
					'scorm_version' => sanitize_text_field( $scorm_package['scorm_version'] ),
				);

				update_post_meta( $course_id, 'scorm_package', wp_json_encode( $scorm_package ) );

			} else {
				delete_post_meta( $course_id, 'scorm_package' );
			}
		}
	}

	/*Settings*/
	public function stm_lms_settings_page( $setups ) {
		$setups[] = array(
			'page'        => array(
				'parent_slug' => 'stm-lms-settings',
				'page_title'  => 'SCORM Settings',
				'menu_title'  => 'SCORM Settings',
				'menu_slug'   => 'scorm_settings',
			),
			'fields'      => $this->stm_lms_settings(),
			'option_name' => 'stm_lms_scorm_settings',
		);

		return $setups;
	}

	public function stm_lms_settings() {
		return apply_filters(
			'stm_lms_scorm_settings',
			array(
				'credentials' => array(
					'name'   => esc_html__( 'Credentials', 'masterstudy-lms-learning-management-system-pro' ),
					'fields' => array(
						'allowed_extensions' => array(
							'type'        => 'textarea',
							'label'       => esc_html__( 'Allowed extensions inside SCORM zip', 'masterstudy-lms-learning-management-system-pro' ),
							'value'       => '',
							'description' => esc_html__(
								'Add extensions, divided by comma (Ex.: psd,txt). Some extensions already added - css,js,woff,ttf,otf,jpg,jpeg,png,gif,html,json,xml,pdf,mp3,mp4,xsd,dtd,ico,swf',
								'masterstudy-lms-learning-management-system-pro'
							),
						),
					),
				),
			)
		);
	}

	public static function stm_lms_get_settings() {
		return get_option( 'stm_lms_scorm_settings', array() );
	}

	// phpcs:disable
	public static function deleteDir( $dirPath ) {
		if ( ! is_dir( $dirPath ) ) {
			throw new InvalidArgumentException( "$dirPath must be a directory" );
		}

		if ( substr( $dirPath, strlen( $dirPath ) - 1, 1 ) !== '/' ) {
			$dirPath .= '/';
		}

		$files = glob( $dirPath . '*', GLOB_MARK );

		foreach ( $files as $file ) {
			if ( is_dir( $file ) ) {
				self::deleteDir( $file );
			} else {
				unlink( $file );
			}
		}

		rmdir( $dirPath );
	}
	// phpcs:enable
}
