<?php
/**
 * Class for attachment media.
 *
 * @package JupiterX_Core\Admin
 *
 * @since 2.5.0
 */

if ( ! class_exists( 'JupiterX_Core_Attachment_Media' ) ) {
	/**
	 * Attachment Media class.
	 *
	 * @since 2.5.0
	 */
	class JupiterX_Core_Attachment_Media {
		/**
		 * Video type key.
		 *
		 * @access private
		 * @var array Video type meta.
		 */
		const VIDEO_TYPE = 'jupiterx_video_type';

		/**
		 * Media URL key.
		 *
		 * @access private
		 * @static
		 *
		 * @var array Media url meta.
		 */
		const MEDIA_URL = 'jupiterx_media_url';

		/**
		 * Embed URL.
		 *
		 * @access private
		 * @var array Media url meta.
		 */
		const EMBED_URL = 'jupiterx_embed_url';

		/**
		 * Aspect ratio.
		 *
		 * @access private
		 * @var array Aspect ratio meta.
		 */
		const ASPECT_RATIO = 'jupiterx_aspect_ratio';

		/**
		 * Construct class.
		 *
		 * @since 2.5.0
		 */
		public function __construct() {
			if ( empty( jupiterx_core_get_option( 'enable_media_controls' ) || ! function_exists( 'WC' ) ) ) {
				return;
			}

			add_filter( 'attachment_fields_to_edit', [ $this, 'add_new_video_attachment_fields' ], 10, 2 );
			add_filter( 'attachment_fields_to_save', [ $this, 'save_new_video_attachment_fields' ], 10, 2 );
		}

		/**
		 * Add fields to the form fields.
		 *
		 * @param array  $form_fields
		 * @param object $post
		 * @since 2.5.0
		 * @return array
		 */
		public function add_new_video_attachment_fields( $form_fields, $post ) {
			if ( strpos( $post->post_mime_type, 'image/' ) !== 0 || empty( $this->validate_is_edit_product() ) ) {
				return $form_fields;
			}

			$video_types = [
				'custom' => esc_html__( 'Custom Video (MP4)', 'jupiterx-core' ),
				'embed' => esc_html__( 'Embed URL', 'jupiterx-core' ),
			];

			$aspect_ratios = [
				'1:1',
				'2:1',
				'3:2',
				'4:3',
				'5:4',
				'5:3',
				'8:5',
				'9:5',
				'9:16',
				'10:7',
				'16:9',
				'20:9',
				'21:9',
				'25:16',
			];

			$video_type_select  = $this->render_select( $video_types, $post->ID, self::VIDEO_TYPE, 'custom' );
			$video_aspect_ratio = $this->render_select( $aspect_ratios, $post->ID, self::ASPECT_RATIO, '16:9' );

			$form_fields['jupiterx_media_details'] = [
				'tr' => sprintf( '<h2 class="jupiterx-media-details-title">%s</h2>', esc_html__( 'Jupiterx Media Details', 'jupiterx-core' ) ),
			];

			$form_fields[ self::VIDEO_TYPE ] = [
				'label' => esc_html__( 'Video Type', 'jupiterx-core' ),
				'input' => 'html',
				'html' => $video_type_select,
			];

			$selected_video_type = $this->get_value_on_change( $post->ID, self::VIDEO_TYPE );

			$form_fields[ self::MEDIA_URL ] = [
				'label' => esc_html__( 'Media URL', 'jupiterx-core' ),
				'input' => 'text',
				'value' => $this->get_attachment_data( $post->ID, self::MEDIA_URL ),
			];

			$form_fields['jupiterx_attached_video'] = [
				'tr' => sprintf(
					'<th scope="row" class="label">&nbsp;</th><td class="field jupiterx-attach-video">
						<span class="setting has-description"><a href="#" class="button-secondary" data-media-id="%1$s">%2$s</a></span>
						<p class="description" style="%3$s">%4$s</p>
					</td>',
					esc_attr( $post->ID ),
					esc_html__( 'Attach MP4', 'jupiterx-core' ),
					esc_attr( 'width: 100%; padding-top: 4px;' ),
					esc_html__( 'Enter an external MP4 URL or click "Attach MP4" to upload your MP4 video into the media library.', 'jupiterx-core' )
				),
			];

			$form_fields[ self::EMBED_URL ] = [
				'label' => esc_html__( 'Embed URL', 'jupiterx-core' ),
				'input' => 'text',
				'value' => $this->get_attachment_data( $post->ID, self::EMBED_URL ),
			];

			$form_fields['jupiterx_embed_url_text'] = [
				'label' => '',
				'input' => 'html',
				'html' => sprintf(
					'<th scope="row" class="label">&nbsp;</th><td class="field jupiterx-embed-video">
						<p class="description" style="%1$s">
						%2$s
						<a href="%3$s" target="_blank">%4$s</a>
						</p>
					</td>',
					esc_attr( 'width: 100%; padding-top: 4px;' ),
					esc_html__( 'Enter a valid', 'jupiterx-core' ),
					'https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from',
					esc_html__( 'media URL', 'jupiterx-core' )
				),
			];

			$form_fields[ self::ASPECT_RATIO ] = [
				'label' => esc_html__( 'Aspect ratio', 'jupiterx-core' ),
				'input' => 'html',
				'html' => $video_aspect_ratio,
			];

			$form_fields['jupiterx_attached_video'] = [
				'label' => '',
				'input' => 'html',
				'html' => sprintf(
					'<th scope="row" class="label">&nbsp;</th><td class="field jupiterx-attach-video">
						<span class="setting has-description"><a href="#" class="jupiterx-attach-mp4 button-secondary" data-media-id="%1$s">%2$s</a></span>
						<p class="description" style="%3$s">%4$s</p>
					</td>',
					esc_attr( $post->ID ),
					esc_html__( 'Attach MP4', 'jupiterx-core' ),
					esc_attr( 'width: 100%; padding-top: 4px;' ),
					esc_html__( 'Enter an external MP4 URL or click "Attach MP4" to upload your MP4 video into the media library.', 'jupiterx-core' )
				),
			];

			$form_fields['jupiterx_media_note'] = [
				'tr' => sprintf(
					'<td colspan="2" style="display: block; padding-top: 8px;">
						<p class="description">
						<strong>%1$s: </strong>
						%2$s
						<a href="%3$s" target="_blank">%4$s</a></p>
					</td>',
					esc_html__( 'Note', 'jupiterx-core' ),
					esc_html__( 'To access Product Gallery video settings', 'jupiterx-core' ),
					esc_url( admin_url( 'admin.php?page=jupiterx#/settings#woocommerce' ) ),
					esc_html__( 'click here', 'jupiterx-core' )
				),
			];

			if ( 'embed' === $selected_video_type ) {
				unset( $form_fields[ self::MEDIA_URL ] );
				unset( $form_fields['jupiterx_attached_video'] );
			}

			if ( 'custom' === $selected_video_type ) {
				unset( $form_fields[ self::EMBED_URL ] );
				unset( $form_fields['jupiterx_embed_url_text'] );
			}

			return $form_fields;
		}

		/**
		 * This method will render select option html code.
		 *
		 * @param array  $options
		 * @param int    $id
		 * @param string $type
		 * @param string $default
		 * @since 2.5.0
		 * @return string
		 */
		private function render_select( $options, $id, $type, $default ) {
			if ( empty( $options ) || empty( $id ) || empty( $type ) ) {
				return;
			}

			$options_html = '';
			$default      = ! empty( $this->get_attachment_data( $id, $type ) ) ? $this->get_attachment_data( $id, $type ) : $default;

			foreach ( $options as $key => $option ) {
				$value = $key;

				if ( self::ASPECT_RATIO === $type ) {
					$value = $option;
				}

				$options_html .= sprintf(
					'<option value="%1$s" %2$s>%3$s</option>',
					$value,
					selected( $default, $value, false ),
					$option
				);
			}

			$video_type_select = sprintf(
				'<select class="jupiterx-attachment-form-select %1$s" name="attachments[%2$s][%3$s]">%4$s</select>',
				str_replace( '_', '-', $type ) . '-select',
				$id,
				$type,
				$options_html
			);

			return $video_type_select;
		}

		/**
		 * Save form fields.
		 *
		 * @param array $post
		 * @param array $attachment
		 * @since 2.5.0
		 * @return array
		 */
		public function save_new_video_attachment_fields( $post, $attachment ) {
			$meta = [];

			foreach ( $attachment as $key => $value ) {
				if ( ! str_starts_with( $key, 'jupiterx_' ) ) {
					continue;
				}

				$meta[ $key ] = sanitize_text_field( $value );
			}

			update_post_meta( $post['ID'], '_jupiterx_attachment_meta', $meta );

			return $post;
		}

		/**
		 * Get meta data.
		 *
		 * @param integer $id
		 * @param string  $key
		 * @since 2.5.0
		 * @return mixed Value set for the option.
		 */
		private function get_attachment_data( $id, $key, $default = false ) {
			$options = get_post_meta( $id, '_jupiterx_attachment_meta', true );

			if ( ! isset( $options[ $key ] ) ) {
				return $default;
			}

			if ( 'jupiterx_embed_url' === $key && empty( wp_oembed_get( $options[ $key ] ) ) ) {
				$options[ $key ] = null;
			}

			return $options[ $key ];
		}

		/**
		 * Get changed meta field.
		 *
		 * @param integer $id
		 * @param string  $meta
		 * @since 2.5.0
		 * @return string
		 */
		private function get_value_on_change( $id, $meta ) {
			$default = $this->get_attachment_data( $id, $meta );

			if ( empty( $default ) ) {
				$default = 'custom';
			}

			if ( empty( $_POST['attachments'][ $id ][ $meta ] ) ) {  // phpcs:ignore
				return $default;
			}

			return htmlspecialchars( $_POST['attachments'][ $id ][ $meta ] );  // phpcs:ignore
		}

		/**
		 * Check is product edit page.
		 *
		 * @since 2.5.0
		 * @return boolean|null
		 */
		private function validate_is_edit_product() {
			$post_id = ! empty( $_POST['post_id'] ) ? htmlspecialchars( $_POST['post_id'] ) : null; // phpcs:ignore.

			if ( empty( $post_id ) ) {
				$params = [];

				$url        = ! empty( $_SERVER['HTTP_REFERER'] ) ? htmlspecialchars( $_SERVER['HTTP_REFERER'] ) : null; // phpcs:ignore.
				$url_params = wp_parse_url( $url );

				if ( empty( $url_params['query'] ) ) {
					return;
				}

				wp_parse_str( $url_params['query'], $params );

				$post_id = ! empty( $params['post'] ) ? htmlspecialchars( $params['post'] ) : null; // phpcs:ignore.
			}

			$post_type = get_post_type( $post_id );

			if ( ! in_array( $post_type, [ 'product', 'product_variation' ], true ) || empty( $post_id ) ) {
				return;
			}

			return true;
		}
	}
}

new JupiterX_Core_Attachment_Media();
