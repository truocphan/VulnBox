<?php
namespace JupiterX_Core\Raven\Modules\Media_Gallery\Submodules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Spotify extends Base {
	public static function render_item( $data, $settings ) {
		$lightbox_html               = self::render_lightbox_html( $data );
		$elementor_lightbox_settings = self::render_json_lightbox_data( $lightbox_html, $data['lightbox_id'] );
		$meta_data                   = self::get_meta_data( $data, 'spotify_poster' );

		ob_start();
		?>
		<div class="gallery-item"
			<?php if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
				data-elementor-lightbox='<?php echo esc_attr( $elementor_lightbox_settings ); ?>'
			<?php endif; ?>
		>
			<div class="type-audio spotify">
				<?php echo self::poster_image( $data, $settings ); ?>
			</div>
			<?php echo self::render_overlay( $meta_data, $settings ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function render_lightbox_html( $data ) {
		$embed_data = self::get_embed_data( $data );

		ob_start();
		?>
		<div class="raven-media-gallery-lightbox-item type-audio spotify">
			<div class="iframe-container">
				<?php
				if ( ! empty( $embed_data ) ) {
					echo $embed_data->html;
				} else {
					esc_html_e( 'Failed to load resources.', 'jupiterx-core' );
				}
				?>
				<div class="raven-iframe-loader"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function get_embed_data( $data ) {
		$encoded_url    = rawurlencode( $data['spotify_url']['url'] );
		$embed_url      = 'https://open.spotify.com/oembed?url=' . $encoded_url;
		$embed_response = wp_safe_remote_get( $embed_url, [ 'timeout' => 30 ] );
		$embed_body     = wp_remote_retrieve_body( $embed_response );

		if ( ! empty( $embed_body ) ) {
			return json_decode( $embed_body );
		}

		return false;
	}

	private static function poster_image( $data, $settings ) {
		$lazy       = self::is_lazy_load( $settings ) ? 'loading="lazy"' : '';
		$poster_url = Group_Control_Image_Size::get_attachment_image_src( $data['spotify_poster']['id'], 'thumbnail_image', $settings );
		$play_icon  = self::render_play_icon( $settings );
		$zoom_img   = '';

		if ( 'zoom' === $settings['image_hover_animation'] && ! empty( $data['spotify_poster']['id'] ) ) {
			$full_poster = wp_get_attachment_image_url( $data['spotify_poster']['id'], 'full' );
			$zoom_img    = sprintf( '<img alt="zoomImg" class="zoom-animation-image" src="%s">', $full_poster );
		}

		if ( empty( $poster_url ) ) {
			$poster_url = Utils::get_placeholder_image_src();
		}

		return sprintf(
			'<div class="poster">%1$s%2$s<img src="%3$s" alt="%4$s" %5$s></div>',
			$play_icon,
			$zoom_img,
			esc_url( $poster_url ),
			! empty( $data['spotify_poster']['alt'] ) ? esc_html( $data['spotify_poster']['alt'] ) : '',
			$lazy
		);
	}
}
