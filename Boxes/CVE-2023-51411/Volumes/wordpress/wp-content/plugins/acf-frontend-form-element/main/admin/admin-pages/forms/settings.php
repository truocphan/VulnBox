<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Handles the admin part of the forms
 *
 * @since 1.0.0
 */
class Frontend_Forms_UI {

	/**
	 * Adds a form key to a form if one doesn't exist
	 *
	 * @since 1.0.0
	 */
	function save_post( $form_id, $post ) {
		// do not save if this is an auto save routine
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $form_id;
		}

		// bail early if not acff form
		if ( 'admin_form' !== $post->post_type ) {
			return $form_id;
		}
		// only save once! WordPress save's a revision as well.
		if ( wp_is_post_revision( $form_id ) ) {
			return $form_id;
		}

			// verify nonce
		if ( ! acf_verify_nonce( 'post' ) ) {
			return $form_id;
		}

		// disable filters to ensure ACF loads raw data from DB
		acf_disable_filters();

		if( empty( $_POST['form'] ) ) return $form_id;
		$form = feadmin_sanitize_array( $_POST['form'] );

		if ( isset( $form['admin_form_type'] ) ) {
			update_post_meta( $form_id, 'admin_form_type', $form['admin_form_type'] );
			return $form_id;
		}

		// save fields
		if ( ! empty( $_POST['acf_fields'] ) ) {

			$form_fields = wp_kses_post_deep( $_POST['acf_fields'] );
			// loop
			foreach ( $form_fields as $field ) {

				// vars
				$specific = false;
				$save     = acf_extract_var( $field, 'save' );

				// only saved field if has changed
				if ( $save == 'meta' ) {
					$specific = array(
						'menu_order',
						'post_parent',
					);
				}

				// set parent
				if ( ! $field['parent'] ) {
					$field['parent'] = $form_id;
				}

				// save field
				$field = acf_update_field( $field, $specific );

			}
		}

		// delete fields.
		if ( ! empty( $_POST['_acf_delete_fields'] ) ) { 
			// clean.
			$ids = array_map( 'intval', explode( '|', $_POST['_acf_delete_fields'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- We validate each id and make sure it's an int.

			// loop.
			foreach ( $ids as $id ) {

				// bai early if no id.
				if ( ! $id ) {
					continue;
				}

				// delete.
				acf_delete_field( $id );

			}
		}	

		$form_settings = maybe_serialize( $form );

		$save = [ 'ID' => $form_id, 'post_content' => $form_settings ];

		$save = wp_slash( $save );

		remove_action( 'save_post', array( $this, 'save_post' ), 9, 2 );
		wp_update_post( $save );
		add_action( 'save_post', array( $this, 'save_post' ), 9, 2 );


		$form_key = get_post_meta( $form_id, 'form_key', 1 );
		if ( ! $form_key ) {
			$form_key = uniqid( 'form_' );
			update_post_meta( $form_id, 'form_key', $form_key );
		}
}


	/**
	 * Displays the form key after the title
	 *
	 * @since 1.0.0
	 *
	 */
	function display_shortcode() {
		global $post;

		if( empty( $post->post_type ) ) return;

		$form_id = $post->ID;

		if ( 'admin_form' == $post->post_type ) {
			acf_hidden_input(
				array(
					'id'    => '_acf_delete_fields',
					'name'  => '_acf_delete_fields',
					'value' => '',
				)
			);

			$form_type = get_post_meta( $form_id, 'admin_form_type', true );
			if ( ! $form_type ) {
				acf_render_field_wrap(
					array(
						'label'   => __( 'Select Type', 'acf-frontend-form-element' ),
						'name'    => 'admin_form_type',
						'key'     => 'admin_form_type',
						// 'required'            => true,
						'prefix'  => 'form',
						'type'    => 'select',
						'choices' => feadmin_form_types(),
						'wrapper' => array(
							'width' => 25,
						),
					)
				);
				return;
			}
			echo '<div class="copy-shortcode-box">';

				echo '<div class="shortcode-copy">';
				// Show shortcode
				echo '<p>'. esc_html( __( 'Form Shortcode' ) ) .': ';
				printf( '<code>[frontend_admin form="%d"]</code>', $form_id );
				printf(
					'<button type="button" class="copy-shortcode" data-prefix="frontend_admin form" data-value="%1$s">%2$s %3$s</button>',
					absint( $form_id ),
					'<span class="dashicons dashicons-admin-page"></span>',
					esc_html__( 'Copy Code', 'acf-frontend-form-element' )
				);
				echo '</p></div>';
			echo '</div>';

			$this->get_dynamic_options();
			
		}
	}
		

	/**
	 * Displays the form key after the title
	 *
	 * @since 1.0.0
	 */
	function admin_form_settings() {
		global $post, $form;
		$screen = get_current_screen();

		$screen = get_current_screen();
		if ( empty( $post->post_type ) || 'admin_form' != $post->post_type ) {
			return;
		}

		$form_type = get_post_meta( $post->ID, 'admin_form_type', true );
		if ( ! $form_type ) {
			return;
		}

		add_meta_box( 'acf-field-group-fields', esc_html( 'Fields', 'acf-frontend-form-element' ), array( $this, 'render_fields' ), $screen->id, 'normal', 'default' );
		add_meta_box( 'acf-field-group-options', esc_html( 'Settings', 'acf-frontend-form-element' ), array( $this, 'render_options' ), $screen->id, 'normal', 'default' );

	}

	function get_dynamic_options() {
		$dynamic_data = array(
			'' => __( 'Dynamic', 'acf-frontend-form-element' ),
			__( 'Form', 'acf-frontend-form-element' ) => array(
				'all_fields' => __( 'All Fields', 'acf-frontend-form-element' ),
				/* 'page_url'   => __( 'Current Page URL', 'acf-frontend-form-element' ),
				'form_name'  => __( 'Form Name', 'acf-frontend-form-element' ),
				'date'       => __( 'Current Date', 'acf-frontend-form-element' ),
				'time'       => __( 'Current Time', 'acf-frontend-form-element' ),
				'user_agent' => __( 'User Agent', 'acf-frontend-form-element' ),
				'remote_ip'  => __( 'Remote IP', 'acf-frontend-form-element' ), */
			),
			__( 'Post', 'acf-frontend-form-element' ) => array(
				'post:id'             => __( 'Post ID', 'acf-frontend-form-element' ),
				'post:title'          => __( 'Title', 'acf-frontend-form-element' ),
				'post:content'        => __( 'Content', 'acf-frontend-form-element' ),
				'post:featured_image' => __( 'Featured Image', 'acf-frontend-form-element' ),
				'post:field_name'     => __( 'Custom Field', 'acf-frontend-form-element' ),
			),
			__( 'User', 'acf-frontend-form-element' ) => array(
				'user:id'         => __( 'User ID', 'acf-frontend-form-element' ),
				'user:username'   => __( 'Username', 'acf-frontend-form-element' ),
				'user:email'      => __( 'Email', 'acf-frontend-form-element' ),
				'user:first_name' => __( 'First Name', 'acf-frontend-form-element' ),
				'user:last_name'  => __( 'Last Name', 'acf-frontend-form-element' ),
				'user:role'       => __( 'Role', 'acf-frontend-form-element' ),
				'user:field_name' => __( 'Custom Field', 'acf-frontend-form-element' ),
			),
		);

		echo '<div style="display:none;" class="dynamic-values">';
		acf_select_input(
			array(
				'choices'          => $dynamic_data,
				'allow_null'       => 1,
			)
		);
		echo '</div>';

	}

	/**
	 * Adds custom columns to the listings page
	 *
	 * @since 1.0.0
	 */
	function manage_columns( $columns ) {
		$new_columns = array(
			'shortcode' => __( 'Shortcode', 'acf-frontend-form-element' ),
		// 'fields'     => __( 'Fields', 'acf-frontend-form-element' ),
		);

		// Remove date column
		unset( $columns['date'] );

		return array_merge( array_splice( $columns, 0, 2 ), $new_columns, $columns );

	}


	/**
	 * Outputs the content for the custom columns
	 *
	 * @since 1.0.0
	 */
	function columns_content( $column, $form_id ) {
		$content = str_replace( 'admin_', '', get_post_type( $form_id ) );
		if ( 'shortcode' == $column ) {

			// Show shortcode
			printf( '<code>[frontend_admin form="%d"]</code>', absint( $form_id ) );

			// Save icon location
			printf(
				'<button type="button" class="copy-shortcode" data-prefix="frontend_admin form" data-value="%1$s">%2$s %3$s</button>',
				absint( $form_id ),
				'<span class="dashicons dashicons-admin-page"></span>',
				esc_html__( 'Copy Code', 'acf-frontend-form-element' )
			);

		}

	}



	/**
	 * Hides the months filter on the forms listing page.
	 *
	 * @since 1.6.5
	 */
	function disable_months_dropdown( $disabled, $post_type ) {
		if ( 'admin_form' != $post_type ) {
			return $disabled;
		}

		return true;
	}

	function render_fields( $post, $data ) {
		global $form;
		global $fea_instance;
		$form_fields = array();

		$args = array(
			'post_type'      => 'acf-field',
			'posts_per_page' => '-1',
			'post_parent'    => $post->ID,
			'fields'         => 'ids',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);

		$fields_query = get_posts( $args );

		if ( $fields_query ) {

			foreach ( $fields_query as $field ) {
				$form_fields[] = acf_get_field( $field );
			}
		} else {
			$form_type = get_post_meta( $post->ID, 'admin_form_type', true );
			if ( $form_type != 'general' ) {
				$create_fields = explode( '_', $form_type );
				if ( ! empty( $create_fields[1] ) ) {
					$action    = $create_fields[0];
					$data_type = $create_fields[1];
				}
			}
			if ( ! empty( $data_type ) ) {
				$form_fields = $fea_instance->local_actions[ $data_type ]->get_default_fields( $post->ID, $action );
			}
		}
		$view = array(
			'fields' => $form_fields,
			'parent' => 0,
		);
		
		acf_get_view( 'acf-field-group/fields', $view );

	}

	function render_options( $post, $data ) {
		global $form;
		$sub_tabs = array(
			'submissions' => __( 'Submissions', 'acf-frontend-form-element' ),
			'actions'     => __( 'Actions', 'acf-frontend-form-element' ),
			'permissions' => __( 'Permissions', 'acf-frontend-form-element' ),
			'modal'       => __( 'Modal Window', 'acf-frontend-form-element' ),
			'post'        => __( 'Post', 'acf-frontend-form-element' ),
			'user'        => __( 'User', 'acf-frontend-form-element' ),
			'term'        => __( 'Term', 'acf-frontend-form-element' ),
		);

		$sub_tabs = apply_filters( 'frontend_admin/forms/settings_tabs', $sub_tabs );

		echo '<div class="acf-fields">';
		foreach ( $sub_tabs as $type => $label ) {
			if ( isset( fea_instance()->local_actions[ $type ] ) ) {
				$fields = fea_instance()->local_actions[ $type ]->get_form_builder_options( $form );
			} else {
				$fields = include_once __DIR__ . "/sections/$type.php";
			}
			$fields = apply_filters( 'frontend_admin/forms/settings_tabs/tab=' . $type, $fields );

			acf_render_field_wrap(
				array(
					'type'  => 'tab',
					'label' => $label,
					'key'   => 'acf_field_group_settings_tabs',
				)
			);
			echo '<div class="field-group-'.esc_attr($type).' field-group-settings-tab">';
			
			foreach ( $fields as $field ) {
				$field['prefix'] = 'form';
				$field['name']   = $field['key'];
				if ( empty( $field['conditional_logic'] ) ) {
					$field['conditional_logic'] = 0;
				}
				$field['wrapper']['data-form-tab'] = $type;

				if ( isset( $form[ $field['key'] ] ) ) {
					if ( empty( $field['value'] ) ) {
						$field['value'] = $form[ $field['key'] ];
					}
				} elseif ( isset( $field['default_value'] ) ) {
					$field['value'] = $field['default_value'];
				}
				acf_render_field_wrap( $field, 'div', 'label', true );
			}

			echo '</div>';
		}
		echo '</div>';
	}

	function admin_head() {
		// global
		global $post, $form;

		if ( empty( $post->ID ) ) {
			return;
		}

		$form = maybe_unserialize( $post->post_content );
		$form = wp_unslash( $form );

		if ( ! $form ) {
			$form = array();
		}

		$form_type = get_post_meta( $post->ID, 'admin_form_type', true );

		if ( ! $form_type || $form_type == 'general' ) {
			$custom_fields_save = 'post';
		} else {
			$custom_fields_save = str_replace( array( 'status_', 'delete_', 'new_', 'edit_', 'duplicate_' ), '', $form_type );
		}

		$form = feadmin_parse_args(
			$form,
			array(
				'redirect'              => 'current',
				'custom_url'            => '',
				'show_update_message'   => 1,
				'update_message'        => __( 'The form has been submitted successfully.', 'acf-frontend-form-element' ),
				'custom_fields_save'    => $custom_fields_save,
				'by_role'               => array( 'administrator' ),
				'admin_form_type'       => $form_type,
				'modal_button_text'     => __( 'Open Form', 'acf-frontend-form-element' ),
				'steps_display'         => array( 'tabs' ),
				'steps_tabs_display'    => array( 'desktop', 'tablet' ),
				'steps_counter_display' => array( 'desktop', 'tablet' ),
				'counter_text'          => sprintf( __( 'Step %1$s/%2$s', 'acf-frontend-form-element' ), '[current_step]', '[total_steps]' ),
			)
		);

		foreach ( array( 'post', 'user', 'term', 'product' ) as $type ) {
			if ( empty( $form[ 'save_to_' . $type ] ) ) {
				if ( $form['admin_form_type'] != 'general' && $type == $custom_fields_save ) {
					$form[ 'save_to_' . $type ] = $form['admin_form_type'];
				} else {
					$form[ 'save_to_' . $type ] = 'edit_' . $type;
				}
			}
		}
	}

	/**
	 *  This action is run after post query but before any admin script / head actions.
	 *  It is a good place to register all actions.
	 *
	 *  @since   5.0.0
	 *
	 *  @return  void
	 */
	public function admin_enqueue_scripts() {

		// custom scripts.
		wp_enqueue_script( 'acf-internal-post-type' );

		wp_enqueue_style( 'acf-global' );
		wp_dequeue_script( 'autosave' );
		wp_enqueue_style( 'acf-field-group' );
		wp_enqueue_script( 'acf-field-group' );
		wp_enqueue_script( 'fea-form-builder' );

		// localize text.
		acf_localize_text(
			array(
				'The string "field_" may not be used at the start of a field name' => __( 'The string "field_" may not be used at the start of a field name', 'acf' ),
				'This field cannot be moved until its changes have been saved' => __( 'This field cannot be moved until its changes have been saved', 'acf' ),
				'Field group title is required' => __( 'Field group title is required', 'acf' ),
				'Move field group to trash?'    => __( 'Move field group to trash?', 'acf' ),
				'No toggle fields available'    => __( 'No toggle fields available', 'acf' ),
				'Move Custom Field'             => __( 'Move Custom Field', 'acf' ),
				'Close modal'                   => __( 'Close modal', 'acf' ),
				'Field moved to other group'    => __( 'Field moved to other group', 'acf' ),
				'Checked'                       => __( 'Checked', 'acf' ),
				'(no label)'                    => __( '(no label)', 'acf' ),
				'(this field)'                  => __( '(this field)', 'acf' ),
				'copy'                          => __( 'copy', 'acf' ),
				'or'                            => __( 'or', 'acf' ),
				'Show this field group if'      => __( 'Show this field group if', 'acf' ),
				'Null'                          => __( 'Null', 'acf' ),

				// Conditions.
				'Has any value'                 => __( 'Has any value', 'acf' ),
				'Has no value'                  => __( 'Has no value', 'acf' ),
				'Value is equal to'             => __( 'Value is equal to', 'acf' ),
				'Value is not equal to'         => __( 'Value is not equal to', 'acf' ),
				'Value matches pattern'         => __( 'Value matches pattern', 'acf' ),
				'Value contains'                => __( 'Value contains', 'acf' ),
				'Value is greater than'         => __( 'Value is greater than', 'acf' ),
				'Value is less than'            => __( 'Value is less than', 'acf' ),
				'Selection is greater than'     => __( 'Selection is greater than', 'acf' ),
				'Selection is less than'        => __( 'Selection is less than', 'acf' ),

				// Pro-only fields.
				'Repeater (Pro only)'           => __( 'Repeater (Pro only)', 'acf' ),
				'Flexibly Content (Pro only)'   => __( 'Flexible Content (Pro only)', 'acf' ),
				'Clone (Pro only)'              => __( 'Clone (Pro only)', 'acf' ),
				'Gallery (Pro only)'            => __( 'Gallery (Pro only)', 'acf' ),
			)
		);

		// localize data.
		acf_localize_data(
			array(
				'fieldTypes' => acf_get_field_types_info(),
				'is_pro' => true,
				'PROFieldTypes'       => acf_get_pro_field_types(),
			)
		);

		// 3rd party hook.
		do_action( 'acf/field_group/admin_enqueue_scripts' );

	}
	
	/**
	 * Renders the admin navigation element.
	 *
	 * @date    27/3/20
	 * @since   5.9.0
	 *
	 * @param   void
	 * @return  void
	 */
	function in_admin_header() {
		$screen = get_current_screen();

		if ( isset( $screen->base ) && 'post' === $screen->base ) {
			global $title, $post;
			$form_type = get_post_meta( $post->ID, 'admin_form_type', true );
			$title_placeholder = apply_filters( 'enter_title_here', __( 'Add title' ), $post );
			if( $post->post_title ){
				$form_title = $post->post_title;
			}else{
				$form_title = __( 'Frontend Form', 'acf-frontend-form-element' );
			}
			?>
			<div class="acf-headerbar acf-headerbar-field-editor">
				<div class="acf-headerbar-inner">

					<div class="acf-headerbar-content">
						<h1 class="acf-page-title">
						<?php
						echo esc_html( $title );
						?>
						</h1>
						<div class="acf-title-wrap">
							<label class="screen-reader-text" id="title-prompt-text" for="title"><?php esc_html_e( $title_placeholder ); ?></label>
							<input form="post" type="text" name="post_title" size="30" value="<?php echo esc_attr( $form_title ); ?>" id="title" class="acf-headerbar-title-field" spellcheck="true" autocomplete="off" placeholder="<?php esc_attr_e( 'Form Title', 'acf-frontend-form-element' ); ?>" />
						</div>
					</div>

					<div class="acf-headerbar-actions" id="submitpost">
						<?php if( $form_type ){ ?>						
							<a href="#" class="acf-btn acf-btn-secondary add-field"><i class="acf-icon acf-icon-plus"></i><?php esc_html_e( 'Add Field', 'acf' ); ?></a>
						<?php } ?>
						<button form="post" class="acf-btn acf-publish" type="submit"><?php esc_html_e( 'Save Changes', 'acf' ); ?></button>
					</div>

				</div>
			</div>
			<?php
		}
	}

	function current_screen() {
		 // validate screen
		$current_screen = get_current_screen();

		if ( 'admin_form' != $current_screen->post_type ) {
			return;
		}

		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'admin_notices' );

		add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );

		// enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}
	/**
	 * Modifies the admin body class.
	 *
	 * @since   6.0.0
	 *
	 * @param   string $classes Space-separated list of CSS classes.
	 * @return  string
	 */
	public function admin_body_class( $classes ) {
		global $post;

		if ( isset( $post->post_type ) && 'admin_form' == $post->post_type && isset($_GET['action'])  && $_GET['action'] === 'edit' ) {
			$classes .= ' acf-admin-page acf-admin-single-field-group post-type-acf-field-group';
		}
		return $classes;
	}

	function admin_form_display( $content ) {
		global $post;
		global $form_preview;

		if ( ! empty( $post->post_type ) && 'admin_form' == $post->post_type ) {
			
				$form_preview = true;
				$content      = '[frontend_admin form="' . $post->ID . '"]';
		}

		return $content;
	}

	public function deleted_post( $form_id ) {
		if ( 'admin_form' === get_post_type( $form_id ) ) {
			// Delete fields.
			$fields_args = array(
				'post_type'      => 'acf-field',
				'posts_per_page' => '-1',
				'post_parent'    => $form_id,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			);
			$multi       = false;

			$fields = get_posts( $fields_args );
			if ( $fields ) {
				foreach ( $fields as $index => $field ) {
					$object = acf_get_field( $field );
					if ( $object ) {
						acf_delete_field( $object['ID'] );
					}
				}
			}

			// Delete post.
			wp_delete_post( $form_id, true );

			/**
			 * Fires immediately after a field group has been deleted.
			 *
			 * @date  12/02/2014
			 * @since 5.0.0
			 *
			 * @param array $field_group The field group array.
			 */
			do_action( 'fea/delete_form', $form_id );

			// Return true.
			return true;
		}
	}


	function __construct() {
		include_once __DIR__ . '/post-types.php';
		include_once __DIR__ . '/tools/tool.php';
		include_once __DIR__ . '/tools/export.php';
		include_once __DIR__ . '/tools/import.php';

		add_action( 'edit_form_top', array( $this, 'display_shortcode' ), 12, 0 );
	
		add_filter( 'the_content', array( $this, 'admin_form_display' ) );

		add_action( 'add_meta_boxes', array( $this, 'admin_form_settings' ), 11, 0 );

		add_action( 'save_post', array( $this, 'save_post' ), 9, 2 );
		add_action( 'deleted_post', array( $this, 'deleted_post' ) );

		add_action( 'current_screen', array( $this, 'current_screen' ) );

		add_filter( 'manage_admin_form_posts_columns', array( $this, 'manage_columns' ), 10, 1 );
		add_action( 'manage_admin_form_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );

		add_filter( 'disable_months_dropdown', array( $this, 'disable_months_dropdown' ), 10, 2 );

		add_action( 'admin_body_class', array( $this, 'admin_body_class' ) );

		add_action( 'acf/prepare_field', array( $this, 'dynamic_value_insert' ), 15, 1 );
		add_action( 'media_buttons', array( $this, 'add_dynamic_value_button' ), 15, 1 );
	}

	
	function dynamic_value_insert( $field ) {
		if ( empty( $field['dynamic_value_choices'] ) ) {
			return $field;
		}
		$field['wrapper']['data-dynamic_values'] = '1';
		if ( $field['type'] == 'text' ) {
			$field['type'] = 'text';
			$field['no_autocomplete'] = 1;
		}
		return $field;
	}

	function add_dynamic_value_button( $editor ) {
		global $post;

		if ( empty( $post->post_type ) || ( 'admin_form' !== $post->post_type ) ) {
			return;
		}
		if ( is_admin() && is_string( $editor ) && 'acf-editor' == substr( $editor, 0, 10 ) ) {
			echo '<a class="dynamic-value-options button">' . esc_html__( 'Dynamic Value', 'acf-frontend-form-element' ) . '</a>';
		}

	}

}

fea_instance()->form_builder = new Frontend_Forms_UI();
