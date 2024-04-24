<?php
/**
 * Handles the use-elementor-template-for-masteriyo action.
 *
 * @since 1.7.0
 */
namespace Masteriyo\Addons\ElementorIntegration;

use Masteriyo\Addons\ElementorIntegration\DocumentTypes\CourseArchivePageDocumentType;
use Masteriyo\Addons\ElementorIntegration\DocumentTypes\SingleCoursePageDocumentType;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the use-elementor-template-for-masteriyo action.
 *
 * @since 1.7.0
 */
class UseTemplateForMasteriyoAction {

	/**
	 * Initialize.
	 *
	 * @since 1.7.0
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.7.0
	 */
	public function init_hooks() {
		add_filter( 'init', array( $this, 'handle' ) );
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
	}

	/**
	 * Handle the action.
	 *
	 * @since 1.7.0
	 */
	public function handle() {
		if ( ! isset( $_GET['masteriyo-use-elementor-template-for-masteriyo'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET['nonce'], 'masteriyo-use-elementor-template-for-masteriyo' ) ) {
			wp_die( esc_html__( 'Invalid nonce!', 'masteriyo' ) );
			return;
		}

		$template_id = absint( $_GET['template-id'] );
		$document    = \Elementor\Plugin::$instance->documents->get( $template_id );

		if ( empty( $document ) ) {
			wp_die( esc_html__( 'The template does not exist!', 'masteriyo' ) );
		}

		$template_type = sanitize_text_field( $_GET['template-type'] );

		if ( CourseArchivePageDocumentType::TYPE_SLUG === $template_type ) {
			masteriyo_set_setting( 'course_archive.custom_template.enable', true );
			masteriyo_set_setting( 'course_archive.custom_template.template_source', 'elementor' );
			masteriyo_set_setting( 'course_archive.custom_template.template_id', $template_id );

			set_transient( '_masteriyo_updated_template_settings', 1, 10 );
		} elseif ( SingleCoursePageDocumentType::TYPE_SLUG === $template_type ) {
			masteriyo_set_setting( 'single_course.custom_template.enable', true );
			masteriyo_set_setting( 'single_course.custom_template.template_source', 'elementor' );
			masteriyo_set_setting( 'single_course.custom_template.template_id', $template_id );

			set_transient( '_masteriyo_updated_template_settings', 1, 10 );
		} else {
			wp_die( esc_html__( 'Invalid template type!', 'masteriyo' ) );
		}

		wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}

	/**
	 * Show notices related to this action.
	 *
	 * @since 1.7.0
	 */
	public function show_notice() {
		if ( ! get_transient( '_masteriyo_updated_template_settings' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-success"><p>%s</p></div>',
			esc_html__( 'Successfully updated the Masteriyo template settings.', 'masteriyo' )
		);
	}
}
