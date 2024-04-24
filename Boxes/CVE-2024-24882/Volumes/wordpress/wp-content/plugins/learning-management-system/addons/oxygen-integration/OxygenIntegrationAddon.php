<?php
/**
 * Masteriyo oxygen integration addon setup.
 *
 * @package Masteriyo\Addons\OxygenIntegration
 *
 * @since 1.6.16
 */
namespace Masteriyo\Addons\OxygenIntegration;

use Masteriyo\Addons\OxygenIntegration\Elements\CourseCategoriesElement;
use Masteriyo\Addons\OxygenIntegration\Elements\CourseListElement;

defined( 'ABSPATH' ) || exit;

/**
 * Main Masteriyo oxygen integration class.
 *
 * @class Masteriyo\Addons\OxygenIntegration\OxygenIntegrationAddon
 */
class OxygenIntegrationAddon {

	/**
	 * Initialize module.
	 *
	 * @since 1.6.16
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.16
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_elements' ) );
		add_action( 'oxygen_add_plus_sections', array( $this, 'add_masteriyo_elements_group' ) );
		add_action( 'oxygen_add_plus_masteriyo_section_content', array( $this, 'add_element_sub_groups' ) );
	}

	/**
	 * Initialize oxygen elements.
	 *
	 * @since 1.6.16
	 */
	public function init_elements() {
		if ( ! class_exists( 'OxygenElement' ) ) {
			return;
		}

		new CourseListElement();
		new CourseCategoriesElement();
	}

	/**
	 * Add Masteriyo elements category/group.
	 *
	 * @since 1.6.16
	 */
	public function add_masteriyo_elements_group() {
		\CT_Toolbar::oxygen_add_plus_accordion_section( 'masteriyo', __( 'Masteriyo', 'masteriyo' ) );
	}

	/**
	 * Add sub-groups in the Masteriyo elements category/group.
	 *
	 * @since 1.6.16
	 */
	public function add_element_sub_groups() {
		printf( '<h2>%s</h2>', esc_html__( 'General', 'masteriyo' ) );
		do_action( 'oxygen_add_plus_masteriyo_general' );
	}
}
