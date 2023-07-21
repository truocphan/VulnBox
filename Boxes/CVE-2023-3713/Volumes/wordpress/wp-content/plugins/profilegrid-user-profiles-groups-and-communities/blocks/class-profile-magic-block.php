<?php

/**
 * The Gutenberg Block functionality of the plugin.
 *
 * @link       https://profilegrid.co
 * @since      1.0.0
 *
 * @package    Profile_Magic
 * @subpackage Profile_Magic/block
 */

class Profile_Magic_Block {

	private $profile_magic;
	private $version;

	public function enqueue_scripts() {

		$index_js = 'index.js';
		wp_enqueue_script(
			'profilegrid-blocks-group-registration',
			plugins_url( $index_js, __FILE__ ),
			array(
				'wp-blocks',
				'wp-editor',
				'wp-i18n',
				'wp-element',
				'wp-components',

			),
                        $this->version,
			true
		);

		wp_localize_script( 'profilegrid-blocks-group-registration', 'pm_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

	public function pm_register_rest_route() {
		register_rest_route(
			'profilegrid/v1',
			'/groups',
			array(
				'method'              => 'GET',
				'callback'            => array( $this, 'pm_load_groups' ),
				'permission_callback' => array( $this, 'pg_get_private_data_permissions_check' ),
			)
		);
	}

	public function pg_get_private_data_permissions_check() {
		// Restrict endpoint to only users who have the edit_posts capability.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'OMG you can not view private data.', 'my-text-domain' ), array( 'status' => 401 ) );
		}

		// This is a black-listing approach. You could alternatively do this via white-listing, by returning false here and changing the permissions check.
		return true;
	}

	public function pm_default_group() {
		$dbhandler = new PM_DBhandler();
		$results   = $dbhandler->get_all_result( 'GROUPS', array( 'id' ), 1, 'results', 0, 1, null, false, '', 'ARRAY_A' );
                if($results)
                {
                    return $results;
                }
                else
                {
                    return array();
                }
	}

	public function pm_load_groups() {
		$dbhandler = new PM_DBhandler();
		$results   = $dbhandler->get_all_result( 'GROUPS', array( 'id', 'group_name' ), 1, 'results', 0, false, null, false, '', 'ARRAY_A' );
		foreach ( $results as $res ) {
			if ( $res['id'] ) {
				$res['value'] = $res['id'];
			}
			unset( $res['id'] );

			if ( $res['group_name'] ) {
				$res['label'] = $res['group_name'];
			}
			unset( $res['group_name'] );
			$return[] = $res;
		}
		return rest_ensure_response( $return );
	}

	public function profilegrid_block_register() {
		global $pagenow;

			// Skip block registration if Gutenberg is not enabled/merged.
			$group = $this->pm_default_group();
		if ( isset( $group[0]['id'] ) ) {
			$gid = $group[0]['id'];
		} else {
			$gid = '';
		}
		if ( ! function_exists( 'register_block_type' ) ) {
				return;
		}
			$dir = dirname( __FILE__ );

			$index_js = 'index.js';
		if ( $pagenow !== 'widgets.php' ) {
			wp_register_script(
				'profilegrid-blocks-group-registration',
				plugins_url( $index_js, __FILE__ ),
				array(
					'wp-blocks',
					'wp-editor',
					'wp-i18n',
					'wp-element',
					'wp-components',
				),
				filemtime( "$dir/$index_js" ),false
			);
		} else {
			wp_register_script(
				'profilegrid-blocks-group-registration',
				plugins_url( $index_js, __FILE__ ),
				array(
					'wp-blocks',
					'wp-edit-widgets',
					'wp-i18n',
					'wp-element',
					'wp-components',
				),
				filemtime( "$dir/$index_js" ),false
			);
		}
			wp_localize_script( 'profilegrid-blocks-group-registration', 'pg_groups', $this->pm_default_group() );

			wp_register_style( 'pg-gutenberg', plugins_url( 'profile-magic-gutenberg.css', __FILE__ ),array(), $this->version, 'all' );

			register_block_type(
				'profilegrid-blocks/group-registration',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'editor_style'    => 'pg-gutenberg',
					'render_callback' => array( $this, 'profilegrid_blocks_group_registration_block_handler' ),
					'attributes'      => array(
						'gid'  => array(
							'default' => $gid,
							'type'    => 'string',
						),
						'type' => array(
							'default' => '',
							'type'    => 'string',
						),

					),
				)
			);

			register_block_type(
				'profilegrid-blocks/login-form',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'render_callback' => array( $this, 'profilegrid_blocks_login_form_block_handler' ),
				)
			);

			register_block_type(
				'profilegrid-blocks/all-groups',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'render_callback' => array( $this, 'profilegrid_blocks_all_groups_block_handler' ),
					'attributes'      => array(

						'view'             => array(
							'default' => 'grid',
							'type'    => 'string',
						),
						'sortby'           => array(
							'default' => 'newest',
							'type'    => 'string',
						),
						'sorting_dropdown' => array(
							'default' => true,
							'type'    => 'boolean',
						),
						'view_icon'        => array(
							'default' => true,
							'type'    => 'boolean',
						),
						'search_box'       => array(
							'default' => true,
							'type'    => 'boolean',
						),

					),
				)
			);
                        
                        register_block_type(
				'profilegrid-blocks/all-users',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'render_callback' => array( $this, 'profilegrid_blocks_all_users_block_handler' )
                                    )
			);

			register_block_type(
				'profilegrid-blocks/group-page',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'editor_style'    => 'pg-gutenberg',
					'render_callback' => array( $this, 'profilegrid_blocks_group_page_block_handler' ),
					'attributes'      => array(
						'gid' => array(
							'default' => $gid,
							'type'    => 'string',
						),

					),
				)
			);

			register_block_type(
				'profilegrid-blocks/user-blogs',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'editor_style'    => 'pg-gutenberg',
					'render_callback' => array( $this, 'profilegrid_blocks_user_blogs_block_handler' ),
					'attributes'      => array(
						'wpblog' => array(
							'default' => true,
							'type'    => 'boolean',
						),

					),
				)
			);

			register_block_type(
				'profilegrid-blocks/blog-submission',
				array(
					'editor_script'   => 'profilegrid-blocks-group-registration',
					'editor_style'    => 'pg-gutenberg',
					'render_callback' => array( $this, 'profilegrid_blocks_blog_submission_block_handler' ),
					'attributes'      => array(
						'wpblog' => array(
							'default' => true,
							'type'    => 'boolean',
						),

					),
				)
			);

	}


	public function profilegrid_blocks_blog_submission_block_handler( $atts ) {
		 $public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		return $public->profile_magic_add_blog( $atts );
	}
	public function profilegrid_blocks_user_blogs_block_handler( $atts ) {
		$public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		return $public->profile_magic_get_template_html( 'profile-magic-user-blogs', $atts );

	}

	public function profilegrid_blocks_group_registration_block_handler( $atts ) {
		$public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		return $public->profile_magic_get_template_html( 'profile-magic-registration-form', $atts );
	}
        
        public function profilegrid_blocks_all_users_block_handler()
        {
            $public = new Profile_Magic_Public( $this->profile_magic, $this->version );
            $atts    = array();
            return $public->profile_magic_user_search( $atts );
        }

	public function profilegrid_blocks_login_form_block_handler() {
		 $public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		$atts    = array();
		return $public->profile_magic_login_form( $atts );
	}

	public function profilegrid_blocks_all_groups_block_handler( $atts ) {
		$public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		return $public->profile_magic_get_template_html( 'profile-magic-groups', $atts );

	}

	public function profilegrid_blocks_group_page_block_handler( $atts ) {
		$public = new Profile_Magic_Public( $this->profile_magic, $this->version );
		return $public->profile_magic_get_template_html( 'profile-magic-group', $atts );
	}
}
