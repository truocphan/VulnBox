<?php
/**
 * Single course page document type for Elementor.
 *
 * @since 1.6.12
 */

namespace Masteriyo\Addons\ElementorIntegration\DocumentTypes;

use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Modules\PageTemplates\Module as PageTemplatesModule;
use Masteriyo\PostType\PostType;

defined( 'ABSPATH' ) || exit;

/**
 * Single course page document type for Elementor.
 *
 * @since 1.6.12
 */
class SingleCoursePageDocumentType extends PageBase {

	use \Elementor\Modules\Library\Traits\Library;

	/**
	 * Document type slug.
	 *
	 * @since 1.6.12
	 *
	 * @var string
	 */
	const TYPE_SLUG = 'masteriyo-single-course-page';

	/**
	 * Get document type properties.
	 *
	 * @since 1.6.12
	 *
	 * @return array
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = self::TYPE_SLUG;
		$properties['support_kit']     = true;
		$properties['show_in_library'] = true;
		$properties['register_type']   = true;
		$properties['cpt']             = array( PostType::COURSE );

		return $properties;
	}

	/**
	 * Get document type.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public static function get_type() {
		return self::TYPE_SLUG;
	}

	/**
	 * Get document type name.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_name() {
		return self::TYPE_SLUG;
	}

	/**
	 * Get document type title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public static function get_title() {
		return esc_html__( 'Masteriyo Single Course Page', 'masteriyo' );
	}

	/**
	 * Get document type plural title.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public static function get_plural_title() {
		return esc_html__( 'Masteriyo Single Course Pages', 'masteriyo' );
	}

	/**
	 * Save Document.
	 *
	 * @since 1.6.12
	 *
	 * @param array $data
	 *
	 * @return boolean
	 */
	public function save( $data ) {
		if ( empty( $data['settings']['template'] ) ) {
			$data['settings']['template'] = PageTemplatesModule::TEMPLATE_HEADER_FOOTER;
		}
		return parent::save( $data );
	}

	/**
	 * Get container attributes to use in editor.
	 *
	 * @since 1.6.12
	 *
	 * @return array
	 */
	public function get_container_attributes() {
		$attributes           = parent::get_container_attributes();
		$attributes['class'] .= ' masteriyo-single-course ';
		return $attributes;
	}

	/**
	 * Get document preview URL.
	 *
	 * @since 1.6.12
	 *
	 * @return string
	 */
	public function get_preview_url() {
		$url = parent::get_preview_url();
		$url = add_query_arg( 'masteriyo-load-single-course-js', true, $url );
		return $url;
	}

	/**
	 * Get document configuration.
	 *
	 * @since 1.6.12
	 *
	 * @return array
	 */
	public static function get_editor_panel_config() {
		$config                                     = parent::get_editor_panel_config();
		$config['messages']['publish_notification'] = __( 'Congrats! You can now use this template in your Single Course page.', 'masteriyo' );

		return $config;
	}
}
