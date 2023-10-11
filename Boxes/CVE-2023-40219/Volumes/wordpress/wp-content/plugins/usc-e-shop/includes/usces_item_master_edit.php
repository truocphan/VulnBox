<?php
/**
 * Edit item administration panel.
 *
 * Manage Item actions: post, edit, delete, etc.
 *
 * @package Welcart
 */

global $wp_version;

wp_reset_vars(
	array(
		'action',
		'safe_mode',
		'withcomments',
		'posts',
		'content',
		'edited_post_title',
		'comment_error',
		'profile',
		'trackback_url',
		'excerpt',
		'showcomments',
		'commentstart',
		'commentend',
		'commentorder',
	)
);

/**
 * Redirect to previous page.
 *
 * @param int $post_ID Optional. Post ID.
 */
if ( ! function_exists( 'redirect_post' ) ) {

	function redirect_post( $post_ID = '' ) {
		global $action;

		$referredby = '';
		if ( ! empty( $_POST['referredby'] ) ) {
			$referredby = preg_replace( '|https?://[^/]+|i', '', $_POST['referredby'] );
			$referredby = remove_query_arg( '_wp_original_http_referer', $referredby );
		}
		$referer = preg_replace( '|https?://[^/]+|i', '', wp_get_referer() );

		if ( ! empty( $_POST['mode'] ) && 'bookmarklet' == $_POST['mode'] ) {
			$location = $_POST['referredby'];
		} elseif ( ! empty( $_POST['mode'] ) && 'sidebar' == $_POST['mode'] ) {
			if ( isset( $_POST['saveasdraft'] ) ) {
				$location = 'sidebar.php?a=c';
			} elseif ( isset( $_POST['publish'] ) ) {
				$location = 'sidebar.php?a=b';
			}
		} elseif ( ( isset( $_POST['save'] ) || isset( $_POST['publish'] ) ) && ( empty( $referredby ) || $referredby == $referer || 'redo' != $referredby ) ) {
			if ( isset( $_POST['_wp_original_http_referer'] ) && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post.php' ) === false && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post-new.php') === false ) {
				$location = add_query_arg(
					array(
						'_wp_original_http_referer' => urlencode( stripslashes( $_POST['_wp_original_http_referer'] ) ),
						'message'                   => 1,
					),
					usces_link_replace( get_edit_post_link( $post_ID, 'url' ) )
				);
			} else {
				if ( isset( $_POST['publish'] ) ) {
					if ( 'pending' == get_post_status( $post_ID ) ) {
						$location = add_query_arg( 'message', 8, usces_link_replace( get_edit_post_link( $post_ID, 'url' ) ) );
					} else {
						$location = add_query_arg( 'message', 6, usces_link_replace( get_edit_post_link( $post_ID, 'url' ) ) );
					}
				} else {
					$location = add_query_arg( 'message', 7, usces_link_replace( get_edit_post_link( $post_ID, 'url' ) ) );
				}
			}
		} elseif ( isset( $_POST['addmeta']) && $_POST['addmeta'] ) {
			$location = add_query_arg( 'message', 2, wp_get_referer() );
			$location = explode( '#', $location );
			$location = $location[0] . '#postcustom';
		} elseif ( isset( $_POST['deletemeta'] ) && $_POST['deletemeta'] ) {
			$location = add_query_arg( 'message', 3, wp_get_referer() );
			$location = explode( '#', $location );
			$location = $location[0] . '#postcustom';
		} elseif ( ! empty( $referredby ) && $referredby != $referer ) {
			$location = $_POST['referredby'];
			$location = remove_query_arg( '_wp_original_http_referer', $location );
			if ( false !== strpos( $location, 'edit.php' ) || false !== strpos( $location, 'edit-post-drafts.php' ) ) {
				$location = add_query_arg('posted', $post_ID, $location);
			} elseif ( false !== strpos( $location, 'wp-admin' ) ) {
				$location = "post-new.php?posted=$post_ID";
			}
		} elseif ( isset( $_POST['publish'] ) ) {
			$location = "post-new.php?posted=$post_ID";
		} elseif ( 'editattachment' === $action ) {
			$location = 'attachments.php';
		} elseif ( 'post-quickpress-save-cont' == $_POST['action'] ) {
			$location = "post.php?action=edit&post=$post_ID&message=7";
		} else {
			$location = add_query_arg( 'message', 4, usces_link_replace( get_edit_post_link( $post_ID, 'url' ) ) );
		}
		wp_redirect( $location );
	}
}

function usces_get_message( $post_ID ) {
	global $usces;

	if ( ( isset( $_POST['save'] ) || isset( $_POST['publish'] ) ) ) {
		if ( isset( $_POST['_wp_original_http_referer'] ) && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post.php' ) === false && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post-new.php' ) === false ) {
			$usces->action_message = sprintf( __( 'Post updated. <a href="%s">View post</a>' ), get_permalink( $post_ID ) );
		} else {
			if ( isset( $_POST['publish'] ) ) {
				if ( 'pending' == get_post_status( $post_ID ) ) {
					$usces->action_message = sprintf( __( 'Post submitted. <a href="%s">Preview post</a>' ), add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) );
				} else {
					$usces->action_message = sprintf( __( 'Post published. <a href="%s">View post</a>'), get_permalink( $post_ID ) );
				}
			} else {
				$usces->action_message = __( 'Post saved.' );
			}
		}
	} elseif ( isset( $_POST['addmeta'] ) && $_POST['addmeta'] ) {
		$usces->action_message = __( 'Custom field updated.' );
	} elseif ( isset( $_POST['deletemeta'] ) && $_POST['deletemeta'] ) {
		$usces->action_message = __( 'Custom field deleted.' );
	} elseif ( 'post-quickpress-save-cont' == $_POST['action'] ) {
		$usces->action_message = __( 'Post saved.' );
	} else {
		$usces->action_message = __( 'Post updated.' );
	}

	$usces->action_status = 'none';
}

if ( isset( $_POST['deletepost'] ) ) {
	$action = 'delete';
}

switch ( $action ) {

	case 'post':
	case 'edit':
		$editing = true;

		if ( empty( $_GET['post'] ) ) {
			wp_redirect( 'post.php' );
			exit();
		}

		$title = 'Welcart Shop ' . __( 'Edit item', 'usces' );

		$this->action_status = 'none';

		global $post;
		if ( $post ) {
			$post_type_object = get_post_type_object( $post->post_type );
			if ( $post_type_object ) {
				$post_type = $post->post_type;
				if ( ! isset( $current_screen ) ) {
					$current_screen = new stdClass();
				}
				$current_screen->post_type = $post->post_type;
				$current_screen->id        = $current_screen->post_type;
			}
			$post_id = $post->ID;
			$post_ID = $post->ID;
		}

		$p = $post_id;

		if ( empty( $post->ID ) ) {
			wp_die( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' ) );
		}

		if ( ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) {
			wp_die( __( 'You are not allowed to edit this item.' ) );
		}

		if ( 'trash' == $post->post_status ) {
			wp_die( __( 'You can&#8217;t edit this item because it is in the Trash. Please restore it and try again.' ) );
		}

		if ( null == $post_type_object ) {
			wp_die( __( 'Unknown post type.' ) );
		}

		$post_type = $post->post_type;

		include USCES_PLUGIN_DIR . '/includes/edit-form-advanced34.php';
		break;

	case 'editpost':

		global $post;
		$title = 'Welcart Shop ' . __( 'Edit item', 'usces' );

		if ( $post ) {
			$post_type_object = get_post_type_object( $post->post_type );
			if ( $post_type_object ) {
				$post_type = $post->post_type;
				if ( ! isset( $current_screen ) ) {
					$current_screen = new stdClass();
				}
				$current_screen->post_type = $post->post_type;
				$current_screen->id        = $current_screen->post_type;
			}
			$post_id = $post->ID;
			$post_ID = $post->ID;
		}
		add_action( 'check_admin_referer', 'usces_update_check_admin' );

		check_admin_referer( 'update-' . $post_type . '_' . $post_id );
		$post_id = edit_post();
		$post_ID = $post_id;

		$post = get_post( $post_id, OBJECT, 'edit' );

		include USCES_PLUGIN_DIR . '/includes/edit-form-advanced34.php';

		if ( ( isset( $_POST['save'] ) || isset( $_POST['publish'] ) ) ) {
			if ( isset( $_POST['_wp_original_http_referer'] ) && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post.php' ) === false && strpos( $_POST['_wp_original_http_referer'], '/wp-admin/post-new.php' ) === false ) {
				$this->action_message = sprintf( __( 'Post updated. <a href="%s">View post</a>' ), get_permalink( $post_ID ) );
			} else {
				if ( isset( $_POST['publish'] ) ) {
					if ( 'pending' == get_post_status( $post_ID ) ) {
						$this->action_message = sprintf( __( 'Post submitted. <a href="%s">Preview post</a>' ), add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) );
					} else {
						$this->action_message = sprintf( __( 'Post published. <a href="%s">View post</a>' ), get_permalink( $post_ID ) );
					}
				} else {
					$this->action_message = __( 'Post saved.' );
				}
			}
		} elseif ( isset( $_POST['addmeta'] ) && $_POST['addmeta'] ) {
			$this->action_message = __( 'Custom field updated.' );
		} elseif ( isset( $_POST['deletemeta'] ) && $_POST['deletemeta'] ) {
			$this->action_message = __( 'Custom field deleted.' );
		} elseif ( 'post-quickpress-save-cont' == $_POST['action'] ) {
			$this->action_message = __( 'Post saved.' );
		} else {
			$this->action_message = __( 'Post updated.' );
		}
		$this->action_status = 'success';
		break;

	case 'new':

		$title = 'Welcart Shop ' . __( 'Add New Item', 'usces' );
		global $post;

		global $post_ID, $current_screen;
		if ( ! isset( $_GET['post_type'] ) ) {
			$post_type = 'post';
		} elseif ( in_array( $_GET['post_type'], get_post_types( array( 'public' => true ) ) ) ) {
			$post_type = $_GET['post_type'];
		} else {
			wp_die( __( 'Invalid post type' ) );
		}
		$action  = 'post';
		$post    = get_default_post_to_edit( $post_type, true );
		$post_ID = $post->ID;

		include USCES_PLUGIN_DIR . '/includes/edit-form-advanced34.php';
		break;

	case 'delete':
		$post_id = ( isset( $_GET['post'] ) )  ? intval( $_GET['post'] ) : intval( $_POST['post_ID'] );
		check_admin_referer( 'delete-post_' . $post_id );

		$post = & get_post( $post_id );

		if ( ! current_user_can( 'delete_post', $post_id ) ) {
			wp_die( __( 'You are not allowed to delete this post.' ) );
		}

		if ( $post->post_type == 'attachment' ) {
			if ( ! wp_delete_attachment( $post_id ) ) {
				wp_die( __( 'Error in deleting...' ) );
			}
		} else {
			if ( ! wp_delete_post( $post_id ) ) {
				wp_die( __( 'Error in deleting...' ) );
			}
		}

		$sendback = wp_get_referer();
		if ( strpos( $sendback, 'admin.php' ) !== false ) {
			$sendback = admin_url( 'admin.php?page=usces_itemedit&deleted=1' );
		} elseif ( strpos( $sendback, 'attachments.php') !== false ) {
			$sendback = admin_url( 'attachments.php' );
		} else {
			$sendback = admin_url( 'admin.php?page=usces_itemedit&deleted=1' );
		}
		exit;

	default:
		exit;
} // end switch
