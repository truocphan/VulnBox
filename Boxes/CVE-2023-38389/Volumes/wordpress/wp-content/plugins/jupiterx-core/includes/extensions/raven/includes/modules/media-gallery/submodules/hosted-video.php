<?php

namespace JupiterX_Core\Raven\Modules\Media_Gallery\Submodules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || die();

class Hosted_Video extends Base {
	public static function render_item( $data, $settings ) {
		$video_url = $data['video_hosted_url']['url'];
		$poster    = self::poster_image( $data, $settings );
		$lazy      = self::is_lazy_load( $settings ) ? 'loading=lazy' : '';

		if ( 'yes' === $data['video_insert_url'] ) {
			$video_url = $data['video_external_url']['url'];
		}

		$lightbox_html               = self::render_lighbox_html( $poster, $video_url );
		$elementor_lightbox_settings = self::render_json_lightbox_data( $lightbox_html, $data['lightbox_id'] );
		$meta_data                   = self::get_meta_data( $data, 'video_external_url_poster' );

		ob_start();
		?>
		<div class="gallery-item"
			<?php if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
				data-elementor-lightbox='<?php echo esc_attr( $elementor_lightbox_settings ); ?>'
			<?php endif; ?>
		>
			<div class="type-video self-hosted">
				<?php
				if ( 'player' !== $settings['video_preview'] ) {
					echo self::poster_image( $data, $settings );
				} else {
					?>
					<video
						<?php echo esc_attr( $lazy ); ?>
						controls
						<?php echo 'poster=" ' . esc_url( $poster ) . '"'; ?>
					>
						<source src="<?php echo esc_url( $video_url ); ?>">
					</video>
				<?php } ?>
			</div>
			<?php echo self::render_overlay( $meta_data, $settings ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function render_lighbox_html( $poster, $video_url ) {
		return sprintf(
			'<div class="raven-media-gallery-lightbox-item type-video hosted-video"><video controls poster="%1$s"><source src="%2$s"></video></div>',
			esc_url( $poster ),
			esc_url( $video_url )
		);
	}

	private static function poster_image( $data, $settings ) {
		$lazy       = self::is_lazy_load( $settings ) ? 'loading="lazy"' : '';
		$poster_id  = $data['video_external_url_poster']['id'];
		$play_icon  = self::render_play_icon( $settings );
		$poster_url = Group_Control_Image_Size::get_attachment_image_src( $poster_id, 'thumbnail_image', $settings );
		$zoom_img   = '';

		if ( 'zoom' === $settings['image_hover_animation'] && ! empty( $poster_id ) ) {
			$full_poster = wp_get_attachment_image_url( $poster_id, 'full' );
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
			! empty( $data['video_external_url_poster']['alt'] ) ? esc_html( $data['video_external_url_poster']['alt'] ) : '',
			$lazy
		);
	}
}
