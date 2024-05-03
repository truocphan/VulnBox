<?php
namespace Frontend_Admin\Field_Types;

if ( ! class_exists( 'delete_user' ) ) :

	class delete_user extends delete_object {



		/*
		*  __construct
		*
		*  This function will setup the field type data
		*
		*  @type    function
		*  @date    5/03/2014
		*  @since    5.0.0
		*
		*  @param    n/a
		*  @return    n/a
		*/

		function initialize() {
			// vars
			$this->name     = 'delete_user';
			$this->label    = __( 'Delete User', 'acf-frontend-form-element' );
			$this->category = __( 'User', 'acf-frontend-form-element' );
			$this->object   = 'user';
			$this->defaults = array(
				'button_text'       => __( 'Delete', 'acf-frontend-form-element' ),
				'confirmation_text' => __( 'Are you sure you want to delete this user?', 'acf-frontend-form-element' ),
				'field_label_hide'  => 1,
				'reassign_posts'    => 0,
				'redirect'          => 'current',
				'show_delete_message' => 1,
				'delete_message'    => __( 'Your profile has been deleted' ),
			);

			add_action( 'wp_ajax_acf_frontend/fields/reassign_posts/query', array( $this, 'ajax_query' ) );

		}

		/*
		*  ajax_query
		*
		*  description
		*
		*  @type    function
		*  @date    17/06/2016
		*  @since   5.3.8
		*
		*  @param   $post_id (int)
		*  @return  $post_id (int)
		*/

		function ajax_query() {
			// validate
			if ( ! acf_verify_ajax() ) {
				die();
			}

			$results = array();

			$all_roles = wp_roles()->get_names();

			// Load all roles if none provided.
			if ( empty( $roles ) ) {
				$roles = array_keys( $all_roles );
			}

			// Loop over roles and populare labels.
			$lables = array();
			foreach ( $roles as $role ) {
				$users = acf_get_users(
					array(
						'include' => array(),
						'role'    => $role,
					)
				);

				// bail early if no field groups
				if ( empty( $users ) ) {
					continue;
				}

				$data = array( 'text' => translate_user_role( $all_roles[ $role ] ) );

				foreach ( $users as $user ) {
					$text = $user->user_login;

					// Add name.
					if ( $user->first_name && $user->last_name ) {
						  $text .= " ({$user->first_name} {$user->last_name})";
					} elseif ( $user->first_name ) {
							$text .= " ({$user->first_name})";
					}
					$data['children'][] = array(
						'id'   => $user->ID,
						'text' => $text,
					);
				}

				$results[] = $data;
			}

			$limit = 20;

			// return
			acf_send_ajax_results(
				array(
					'results' => $results,
					'limit'   => $limit,
				)
			);

		}

	}




endif; // class_exists check


