<?php

namespace MasterStudy\Lms\Libraries;

class Mixpanel_General extends Mixpanel {

	private static $options_array = array(
		'courses_per_row'     => 'Courses Per Row',
		'course_card_style'   => 'Courses Card Style',
		'course_style'        => 'Courses Player Style',
		'courses_view'        => 'Courses Page Layout',
		'wocommerce_checkout' => 'WooCommerce Checkout',
	);

	private static $woocommerce_payments = array(
		'woocommerce_bacs_settings'   => 'WooCommerce Bank Transfer',
		'woocommerce_cheque_settings' => 'WooCommerce Check',
		'woocommerce_cod_settings'    => 'WooCommerce Cash',
	);

	private static $ms_lms_payments = array(
		'paypal'        => 'MS LMS PayPal',
		'stripe'        => 'MS LMS Stripe',
		'wire_transfer' => 'MS LMS Wire Transfer',
		'cash'          => 'MS LMS Cash',
	);

	private static $mime_types = array(
		'image/jpeg'        => 'ML JPEG Files',
		'image/png'         => 'ML PNG Files',
		'image/gif'         => 'ML GIF Files',
		'application/pdf'   => 'ML PDF Files',
		'application/excel' => 'ML Excel Files',
		'audio/mpeg3'       => 'ML MP3 Files',
		'video/mp4'         => 'ML MP4 Files',
		'video/avi'         => 'ML AVI Files',
		'image/svg+xml'     => 'ML SVG Files',
		'video/webm'        => 'ML WEBM Files',
	);

	public static function register_data() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach ( get_option( 'active_plugins', array() ) as $plugin ) {
			self::add_data( strstr( $plugin, '/', true ), true );
		}

		self::add_data( 'Admin Email', get_bloginfo( 'admin_email' ) );
		self::add_data( 'Business type', self::get_business_type() );
		self::add_data( 'Active theme', self::get_active_theme() );
		self::add_data( 'Copyright URL', self::get_copyright_content_info() );
		self::add_data( 'Footer Widget URL', self::get_footer_content_info() );
		self::add_data( 'MS LMS FREE Version', STM_LMS_VERSION );

		if ( defined( 'STM_LMS_PRO_VERSION' ) ) {
			self::add_data( 'MS LMS PRO PLUS Used', defined( 'STM_LMS_PLUS_ENABLED' ) );
			self::add_data( 'MS LMS PRO Version', STM_LMS_PRO_VERSION );
			self::add_data( 'Instructors Count', self::get_instructors_count() );
			if ( 'not-exist' !== get_option( 'fs_accounts', 'not-exist' ) ) {
				self::add_data( 'Freemius Email', self::get_freemius_email() );
			}
		}

		foreach ( self::$options_array as $slug => $label ) {
			self::add_data( $label, self::get_courses_option( $slug ) );
		}

		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			$used_widgets = self::get_lms_widgets_used_on_homepage();

			if ( ! empty( $used_widgets ) && false !== $used_widgets ) {
				foreach ( $used_widgets as $label => $value ) {
					self::add_data( $label, $value );
				}
			}
		}

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			foreach ( self::$woocommerce_payments as $slug => $label ) {
				self::add_data( $label, self::get_wc_payment_methods( $slug ) );
			}
		}

		foreach ( self::$ms_lms_payments as $slug => $label ) {
			self::add_data( $label, self::get_ms_lms_payment_methods( $slug ) );
		}

		foreach ( self::$mime_types as $type => $label ) {
			self::add_data( $label, intval( ( (array) wp_count_attachments( $type ) )[ $type ] ?? 0 ) );
		}
	}

	public static function get_business_type() {
		return get_option( 'stm_lms_business_type' );
	}

	public static function get_active_theme() {
		$theme_obj = wp_get_theme();

		if ( ! empty( $theme_obj->parent() ) ) {
			$theme_obj = $theme_obj->parent();

			return $theme_obj->name;
		}

		return wp_get_theme()->name;
	}

	public static function is_dev( $domain ) {
		$devs = array( '.loc', '.local', 'stylemix', 'localhost', 'stm' );
		foreach ( $devs as $dev ) {
			if ( false !== stripos( $domain, $dev ) ) {
				return true;
			}
		}

		return false;
	}

	public static function get_instructors_count() {
		$args  = array(
			'role'    => 'stm_lms_instructor',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',
		);
		$users = new \WP_User_Query( $args );

		return $users->get_total();
	}

	public static function get_footer_content_info() {
		if ( 'MasterStudy' === self::get_active_theme() ) {
			$footer_content       = get_option( 'widget_stm_text' );
			$dummy_widget_content = 'Created by <a target="_blank" href="https://wordpress.org/plugins/masterstudy-lms-learning-management-system/">MasterStudy</a> 2023. Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel, rutrum eu ipsum. Mauris accumsan eros eget libero posuere vulputate.';

			foreach ( $footer_content as $item ) {
				if ( isset( $item['text'] ) && $item['text'] === $dummy_widget_content ) {
					return true;
				}
			}
			return false;
		}
	}

	public static function get_copyright_content_info() {
		$active_theme = self::get_active_theme();

		if ( 'MasterStudy' === $active_theme ) {
			$footer_content = get_option( 'stm_option', false );
			$linked_text    = 'Created by <a target="_blank" href="https://wordpress.org/plugins/masterstudy-lms-learning-management-system/" style="text-decoration: none !important">MasterStudy</a> 2023. ';

			return isset( $footer_content ) && $footer_content['footer_copyright_text'] === $linked_text;
		} elseif ( 'Masterstudy LMS Starter' === $active_theme ) {
			if ( get_option( 'hfe_plugin_is_activated' ) ) {
				add_filter( 'hfe_footer_enabled', array( __CLASS__, 'check_hfe_footer_status' ) );
			}

			return ! (bool) get_option( 'ms-starter-hfe-footer-status' );
		}

		return false;
	}

	public static function check_hfe_footer_status( $status ) {
		update_option( 'ms-starter-hfe-footer-status', $status );

		return $status;
	}
	public static function get_courses_option( $option ) {
		$options = get_option( 'stm_lms_settings' );

		return ! empty( $options[ $option ] ) ? $options[ $option ] : false;
	}

	public static function get_ms_lms_payment_methods( $method ) {
		$payments = self::get_courses_option( 'payment_methods' );

		return isset( $payments[ $method ]['enabled'] ) && true === $payments[ $method ]['enabled'];
	}

	public static function get_lms_widgets_used_on_homepage() {
		$widget_names = array(
			'stm_courses_searchbox'                => 'Widget Courses Search box',
			'ms_lms_courses_searchbox'             => 'Widget New Courses Search box',
			'stm_lms_single_course_carousel'       => 'Widget Single Course Carousel',
			'stm_lms_courses_carousel'             => 'Widget Courses Carousel',
			'stm_lms_courses_categories'           => 'Widget Courses Categories',
			'stm_lms_courses_grid'                 => 'Widget Courses Grid',
			'stm_lms_featured_teacher'             => 'Widget Featured Teacher',
			'stm_lms_instructors_carousel'         => 'Widget Instructors Carousel',
			'ms_lms_instructors_carousel'          => 'Widget New Instructors Carousel',
			'stm_lms_recent_courses'               => 'Widget Recent Courses',
			'stm_lms_pro_testimonials'             => 'Widget New Testimonials',
			'stm_lms_pro_site_authorization_links' => 'Widget New Site Authorization links',
			'stm_call_to_action'                   => 'Widget New Call to action',
			'stm_lms_certificate_checker'          => 'Widget Certificate Checker',
			'stm_lms_course_bundles'               => 'Widget Course Bundles',
			'stm_lms_google_classroom'             => 'Widget Google Classrooms grid view',
			'stm_membership_levels'                => 'Widget New Membership Plans',
		);

		$page_id      = get_option( 'page_on_front' );
		$is_used      = false;
		$used_widgets = array();

		if ( ! empty( get_post_meta( $page_id, '_elementor_edit_mode' ) ) ) {
			$page_content = implode( ',', get_post_meta( $page_id, '_elementor_data' ) );

			foreach ( $widget_names as $slug => $label ) {
				$is_used                = stripos( $page_content, $slug );
				$used_widgets[ $label ] = ( ! empty( $is_used ) && false !== $is_used );
			}
		}

		return ! empty( $used_widgets ) ? $used_widgets : false;
	}

	public static function get_wc_payment_methods( $method ) {
		$wc_gateway = get_option( $method );

		return ! empty( $wc_gateway['enabled'] ) && 'yes' === $wc_gateway['enabled'];
	}

	public static function get_freemius_email() {
		$fs_user_object = get_option( 'fs_accounts' )['users'];
		return ! empty( $fs_user_object ) ? current( $fs_user_object )->email : null;
	}
}
