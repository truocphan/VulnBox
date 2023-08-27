<?php
/**
 * Add WPML Compatibility Module.
 *
 * @package JupiterX_Core\Raven
 * @since 1.0.4
 */

namespace JupiterX_Core\Raven\Core\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

/**
 * Raven WPML compatibility module.
 *
 * Raven compatibility module handler class is responsible for registering and
 * managing translatable fields with WPML plugin.
 *
 * @since 1.0.4
 */
class Module {

	/**
	 * Constructor.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'register_widgets_fields' ] );
	}

	/**
	 * Register widgets fields for translation.
	 *
	 * @since 1.0.4
	 *
	 * @param array $fields Fields to translate.
	 *
	 * @return array
	 */
	public function register_widgets_fields( $fields ) {

		// Alert.
		$fields['raven-alert'] = [
			'conditions' => [ 'widgetType' => 'raven-alert' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Raven Alert: Title', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Raven Alert: Content', 'jupiterx-core' ),
					'editor_type' => 'VISUAL',
				],
			],
		];

		// Button.
		$fields['raven-button'] = [
			'conditions' => [ 'widgetType' => 'raven-button' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Raven Button: Text', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Button: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Categories.
		$fields['raven-categories'] = [
			'conditions' => [ 'widgetType' => 'raven-categories' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Raven Categories: Text', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Categories: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Counter.
		$fields['raven-counter'] = [
			'conditions' => [ 'widgetType' => 'raven-counter' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Counter',
		];

		// Form.
		$fields['raven-form'] = [
			'conditions' => [ 'widgetType' => 'raven-form' ],
			'fields'     => [
				[
					'field'       => 'form_name',
					'type'        => esc_html__( 'Raven Form: Form name', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'submit_button_text',
					'type'        => esc_html__( 'Raven Form: Submit button Text', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_success',
					'type'        => esc_html__( 'Raven Form: Success message', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_error',
					'type'        => esc_html__( 'Raven Form: Error message', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_required',
					'type'        => esc_html__( 'Raven Form: Required message', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messages_subscriber',
					'type'        => esc_html__( 'Raven Form: Subscriber already exists message', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\Modules\Form',
		];

		// Heading.
		$fields['raven-heading'] = [
			'conditions' => [ 'widgetType' => 'raven-heading' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Raven Heading: Title', 'jupiterx-core' ),
					'editor_type' => 'AREA',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Heading: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Icon.
		$fields['raven-icon'] = [
			'conditions' => [ 'widgetType' => 'raven-icon' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Icon: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Image.
		$fields['raven-image'] = [
			'conditions' => [ 'widgetType' => 'raven-image' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Raven Image: Caption', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Image: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Photo Album.
		$fields['raven-photo-album'] = [
			'conditions' => [ 'widgetType' => 'raven-photo-album' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Photo_Album',
		];

		// Search Form.
		$fields['raven-search-form'] = [
			'conditions' => [ 'widgetType' => 'raven-search-form' ],
			'fields'     => [
				[
					'field'       => 'placeholder',
					'type'        => esc_html__( 'Raven Search Form: Placeholder', 'jupiterx-core' ),
					'editor_type' => 'LINE',
				],
			],
		];

		// Site Logo.
		$fields['raven-site-logo'] = [
			'conditions' => [ 'widgetType' => 'raven-site-logo' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Raven Site Logo: Link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		// Tabs.
		$fields['raven-tabs'] = [
			'conditions' => [ 'widgetType' => 'raven-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\Modules\Tabs',
		];

		// Video.
		$fields['raven-video'] = [
			'conditions' => [ 'widgetType' => 'raven-video' ],
			'fields'     => [
				[
					'field'       => 'youtube_link',
					'type'        => esc_html__( 'Raven Video: YouTube link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'vimeo_link',
					'type'        => esc_html__( 'Raven Video: Vimeo link', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'hosted_link',
					'type'        => esc_html__( 'Raven Video: Hosted video - MP4', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'hosted_link_webm',
					'type'        => esc_html__( 'Raven Video: Hosted video - WebM', 'jupiterx-core' ),
					'editor_type' => 'LINK',
				],
			],
		];

		return $fields;
	}
}
