<?php
namespace WprAddons\Classes\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Post_Likes setup
 *
 * @since 1.0
 */
class WPR_Post_Likes {

	/**
	** Constructor
	*/
	public function __construct() {
		add_action( 'wp_ajax_nopriv_wpr_likes_init', [ $this, 'wpr_likes_init' ] );
		add_action( 'wp_ajax_wpr_likes_init', [ $this, 'wpr_likes_init' ] );
	}

	/**
	** Likes Init
	*/
	public function wpr_likes_init() {
		// Security
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash($_REQUEST['nonce']) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'wpr-post-likes-nonce' ) ) {
			exit( esc_html__( 'Not permitted', 'wpr-addons' ) );
		}

		// Test if javascript is disabled
		$js_disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;

		// Base variables
		$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? absint($_REQUEST['post_id']) : '';

		$post_users = NULL;
		$like_count = 0;

		// Init
		if ( $post_id != '' ) {
			// Likes Count
			$count = get_post_meta( $post_id, '_post_like_count', true );
			$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;

			// Like the Post
			if ( ! $this->already_liked( $post_id ) ) {
				// Logged-in User
				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();
					$post_users = $this->get_user_likes( $user_id, $post_id );

					// Get Like Count
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;

					// Update Like Count
					update_user_option( $user_id, '_user_like_count', ++$user_like_count );

					// Update Post
					if ( $post_users ) {
						update_post_meta( $post_id, '_user_liked', $post_users );
					}

				// Anonymous User
				} else {
					$post_users = $this->get_IP_likes( $this->get_IP(), $post_id );

					// Update Post
					if ( $post_users ) {
						update_post_meta( $post_id, '_user_IP', $post_users );
					}
				}

				// Send to JS
				$like_count = ++$count;
				$response['status'] = 'liked';

			// Unlike the Post
			} else {
				// Logged-in User
				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();
					$post_users = $this->get_user_likes( $user_id, $post_id );

					// Get Like Count
					$user_like_count = get_user_option( '_user_like_count', $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;

					// Update Like Count
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}

					// Update Post
					if ( $post_users ) {	
						$uid_key = array_search( $user_id, $post_users );
						unset( $post_users[$uid_key] );
						update_post_meta( $post_id, '_user_liked', $post_users );
					}

				// Anonymous User
				} else {
					$post_users = $this->get_IP_likes( $this->get_IP(), $post_id );

					// Update Post
					if ( $post_users ) {

						unset( $post_users[ array_search( $this->get_IP(), $post_users ) ] );
						update_post_meta( $post_id, '_user_IP', $post_users );
					}
				}

				// Send to JS
				$like_count = ( $count > 0 ) ? --$count : 0;
				$response['status'] = 'unliked';
			}

			// Update Post
			update_post_meta( $post_id, '_post_like_count', $like_count );
			update_post_meta( $post_id, '_post_like_modified', date( 'Y-m-d H:i:s' ) );

			// Send to JS
			$response['count'] = $this->get_like_count( $like_count );

			// JavaScript Disabled
			if ( $js_disabled == true ) {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			} else {
				wp_send_json( $response );
			}
		}
	}

	/**
	** Get Button
	*/
	public function get_button( $post_id, $settings ) {
		$nonce = wp_create_nonce( 'wpr-post-likes-nonce' ); // Security
		$like_count = get_post_meta( $post_id, '_post_like_count', true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
		$default_text_class = '';
		$button_text = $settings['element_like_text'];

		// Already Liked
		if ( $this->already_liked( $post_id ) ) {
			$title = esc_html__( 'Like', 'wpr-addons' );
			$liked_class = esc_attr( ' wpr-already-liked' );
			$icon_class = str_replace( 'far', 'fas', $settings['element_like_icon'] );
		} else {
			$title = esc_html__( 'Unlike', 'wpr-addons' );
			$liked_class = '';
			$icon_class = $settings['element_like_icon'];
		}

		// Default Text
		if ( '' === $settings['element_like_text'] ) {
			$default_text_class = ' wpr-likes-no-default';
		}

		// Zero Likes Class
		if ( 0 == $like_count ) {
			$default_text_class .= ' wpr-likes-zero';
		}

		// Button Attributes
		$attributes  = 'href="'. esc_url(admin_url( 'admin-ajax.php?action=wpr_likes_init&post_id='. $post_id .'&nonce='. $nonce )) .'"';
		$attributes .= ' class="wpr-post-like-button'. esc_attr($liked_class . $default_text_class) .'"';
		$attributes .= ' title="'. esc_attr($title) .'"';
		$attributes .= ' data-nonce="'. esc_attr($nonce) .'"';
		$attributes .= ' data-post-id="'. esc_attr($post_id) .'"';
		$attributes .= ' data-ajax="'. esc_url(admin_url( 'admin-ajax.php' )) .'"';
		$attributes .= ' data-icon="'. esc_attr($icon_class) .'"';
		$attributes .= ' data-text="'. esc_attr($button_text) .'"';

		// Output
		$output  = '<a '. $attributes .'>';
		$output .= '<i class="'. esc_attr($icon_class) .'"></i>';
		$output .= $this->get_like_count( $like_count, $button_text );
		$output .= '</a>';

		return $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	** Utility: Already Liked
	*/
	public function already_liked( $post_id ) {
		$post_users = NULL;
		$user_id = NULL;

		// Logged-in User
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$post_meta_users = get_post_meta( $post_id, '_user_liked' );

			if ( count( $post_meta_users ) != 0 ) {
				$post_users = $post_meta_users[0];
			}

		// Anonymous User
		} else {
			$user_id = $this->get_IP();
			$post_meta_users = get_post_meta( $post_id, '_user_IP' ); 

			if ( count( $post_meta_users ) != 0 ) {
				$post_users = $post_meta_users[0];
			}
		}

		if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	** Utility: Get User Likes
	*/
	public function get_user_likes( $user_id, $post_id ) {
		$post_users = '';
		$post_meta_users = get_post_meta( $post_id, '_user_liked' );

		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}

		if ( !is_array( $post_users ) ) {
			$post_users = array();
		}

		if ( !in_array( $user_id, $post_users ) ) {
			$post_users['user-'. $user_id] = $user_id;
		}

		return $post_users;
	}

	/**
	** Utility: Get IP Likes
	*/
	public function get_IP_likes( $user_ip, $post_id ) {
		$post_users = '';
		$post_meta_users = get_post_meta( $post_id, '_user_IP' );

		// Retrieve post information
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}

		if ( !is_array( $post_users ) ) {
			$post_users = array();
		}

		if ( !in_array( $user_ip, $post_users ) ) {
			$post_users['ip-'. $user_ip] = $user_ip;
		}

		return $post_users;
	}

	/**
	** Utility: Get IP
	*/
	public function get_IP() {
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
		} else {
			$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '0.0.0.0';
		}

		$ip = filter_var( $ip, FILTER_VALIDATE_IP );
		$ip = ( $ip === false ) ? '0.0.0.0' : $ip;

		return $ip;
	}

	/**
	** Utility: Format Likes Number
	*/
	public function get_format_count( $number ) {
		$precision = 2;

		if ( $number >= 1000 && $number < 1000000 ) {
			$formatted = number_format( $number/1000, $precision ).'K';
		} elseif ( $number >= 1000000 && $number < 1000000000 ) {
			$formatted = number_format( $number/1000000, $precision ).'M';
		} elseif ( $number >= 1000000000 ) {
			$formatted = number_format( $number/1000000000, $precision ).'B';
		} else {
			$formatted = $number; // Number is less than 1000
		}

		$formatted = str_replace( '.00', '', $formatted );

		return $formatted;
	}

	/**
	** Utility: Get Likes Count
	*/
	public function get_like_count( $like_count, $like_text = '' ) {
		if ( is_numeric( $like_count ) && $like_count > 0 ) { 
			$number = $this->get_format_count( $like_count );
		} else {
			$number = $like_text;
		}

		return '<span class="wpr-post-like-count">'. esc_html($number) .'</span>';
	}
}

new WPR_Post_Likes();