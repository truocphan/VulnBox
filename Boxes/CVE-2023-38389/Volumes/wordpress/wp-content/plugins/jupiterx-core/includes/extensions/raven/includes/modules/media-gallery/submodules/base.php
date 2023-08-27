<?php

namespace JupiterX_Core\Raven\Modules\Media_Gallery\Submodules;

use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Base class of the item types.
 *
 * @since 3.0.0
 */
abstract class Base {
	/**
	 *  Returns the HTML of the item content for content under image and overlay.
	 *
	 * @param $data
	 *
	 * @return false|string
	 * @since 3.0.0
	 */
	public static function render_overlay( $meta_data, $settings ) {
		ob_start();
		?>
		<div class="overlay">
			<?php if ( 'yes' === $settings['title'] ) : ?>
				<p class="title">
					<?php
					if ( ! empty( $meta_data['title'] ) ) {
						echo esc_html( $meta_data['title'] );
					}
					?>
				</p>
			<?php endif;

			if ( 'yes' === $settings['caption'] ) : ?>
				<p class="caption">
					<?php
					if ( ! empty( $meta_data['caption'] ) ) {
						echo esc_html( $meta_data['caption'] );
					}
					?>
				</p>
			<?php endif;

			if ( 'yes' === $settings['description'] ) : ?>
				<p class="description">
					<?php
					if ( ! empty( $meta_data['description'] ) ) {
						echo esc_html( $meta_data['description'] );
					}
					?>
				</p>
			<?php endif;

			if ( 'yes' === $settings['alt'] ) : ?>
				<p class="alt">
					<?php
					if ( ! empty( $meta_data['alt'] ) ) {
						echo esc_html( $meta_data['alt'] );
					}
					?>
				</p>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function render_json_lightbox_data( $html, $lightbox_id ) {
		// Remove \r, \n, \t from html.
		$html       = preg_replace( '/(\v|\s)+/', ' ', $html );
		$attr_value = [
			'type'         => 'html',
			'html'         => $html,
			'modalOptions' => [
				'id' => $lightbox_id,
			],
		];

		return wp_json_encode( $attr_value );
	}

	public static function get_meta_data( $data, $poster_key ) {
		$media_id = $data[ $poster_key ]['id'];

		if ( empty( $media_id ) ) {
			return [];
		}

		$media_data = get_post( $media_id );
		$alt        = $data[ $poster_key ]['alt'];

		if ( empty( $alt ) ) {
			$alt = get_post_meta( $media_id, '_wp_attachment_image_alt', true );
		}

		return [
			'title'       => $media_data->post_title,
			'caption'     => $media_data->post_excerpt,
			'description' => $media_data->post_content,
			'alt'         => $alt,
		];
	}

	public static function is_lazy_load( $settings ) {
		return ! empty( $settings['lazy_load'] ) && 'yes' === $settings['lazy_load'];
	}

	public static function render_play_icon( $settings ) {
		ob_start();
		Icons_Manager::render_icon(
			$settings['play_icon'],
			[
				'aria-hidden' => 'true',
			]
		);

		return wp_kses_post( '<span class="play-icon">' . ob_get_clean() . '</span>' );
	}
}
