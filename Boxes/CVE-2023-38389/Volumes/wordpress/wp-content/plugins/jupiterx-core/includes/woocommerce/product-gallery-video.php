<?php
/**
 * Class for video attachment media.
 *
 * @package JupiterX_Core\Woocommerce
 *
 * @since 2.5.0
 */

/**
 * Product gallery compatibility with video.
 *
 * @since 2.5.0
 */
class JupiterX_Core_Product_Gallery_Video {
	/**
	 * Construct class.
	 *
	 * @since 2.5.0
	 */
	public function __construct() {
		if ( ! $this->validate() ) {
			return;
		}

		add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'image_thumbnail_html' ], 10, 2 );
		add_filter( 'woocommerce_available_variation', [ $this, 'add_custom_meta_fields' ], 10, 1 );
	}

	/**
	 * It's for validating WC is activated
	 * and option is enabled.
	 *
	 * @since 2.5.0
	 * @return boolean|null
	 */
	private function validate() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		if ( empty( jupiterx_core_get_option( 'enable_media_controls' ) ) ) {
			return;
		}

		return true;
	}

	/**
	 * Get image thumbnail html.
	 *
	 * @param string $html
	 * @param int    $attachment_id
	 * @since 2.5.0
	 * @return string
	 */
	public function image_thumbnail_html( $html, $attachment_id ) {
		if ( empty( $attachment_id ) ) {
			return $html;
		}

		$data = get_post_meta( $attachment_id, '_jupiterx_attachment_meta', true );

		if ( empty( $data ) ) {
			return $this->get_gallery_image_html( $attachment_id, true );
		}

		$html = $this->get_video_html( $data, $html, $attachment_id );

		return $html;
	}

	/**
	 * Get video by it's type.
	 *
	 * @param array  $data attachment meta data.
	 * @param string $html attachment html.
	 * @param int    $id attachment id.
	 * @since 2.5.0
	 * @return string
	 */
	private function get_video_html( $data, $html, $id ) {
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$thumbnail_src     = wp_get_attachment_image_src( $id, $thumbnail_size );

		if ( 'embed' === $data['jupiterx_video_type'] && ! empty( $data['jupiterx_embed_url'] ) ) {
			$embed = sprintf(
				'<div class="jupiterx-attachment-media-iframe iframe-on-load" style="aspect-ratio: %1$s;">%2$s %3$s</div>',
				esc_attr( $this->aspect_ratio( $data ) ),
				wp_oembed_get( $data['jupiterx_embed_url'] ),
				$this->preloader()
			);

			$default = [
				'content' => $embed,
				'video_type' => 'embed',
				'poster' => $id,
				'enabled' => true,
			];

			return sprintf(
				'<div class="woocommerce-product-gallery__image" data-video-type="embed" data-poster="%1$s" data-thumb="%2$s" data-default="%3$s">%4$s</div>',
				esc_attr( $id ),
				! empty( $thumbnail_src[0] ) ? esc_attr( $thumbnail_src[0] ) : '',
				esc_attr( wp_json_encode( $default ) ),
				$embed
			);
		}

		if ( 'custom' === $data['jupiterx_video_type'] && ! empty( $data['jupiterx_media_url'] ) ) {
			$custom_video = sprintf(
				'<div class="jupiterx-attachment-media-iframe" style="aspect-ratio: %1$s;">%2$s</div>',
				esc_attr( $this->aspect_ratio( $data ) ),
				$this->custom_video_html( $data['jupiterx_media_url'], $id )
			);

			$default = [
				'content' => $custom_video,
				'video_type' => 'custom',
				'poster' => $id,
				'enabled' => true,
			];

			return sprintf(
				'<div class="woocommerce-product-gallery__image" data-video-type="custom" data-poster="%1$s" data-thumb="%2$s" data-default="%3$s">%4$s</div>',
				esc_attr( $id ),
				! empty( $thumbnail_src[0] ) ? esc_attr( $thumbnail_src[0] ) : '',
				esc_attr( wp_json_encode( $default ) ),
				$custom_video
			);
		}

		return $html;
	}

	/**
	 * Get aspect ratio.
	 *
	 * @param array $data attachment meta data.
	 * @since 2.5.0
	 * @return string
	 */
	private function aspect_ratio( $data ) {
		if ( empty( $data['jupiterx_aspect_ratio'] ) ) {
			return;
		}

		$aspect_ratio = explode( ':', $data['jupiterx_aspect_ratio'] );

		return "$aspect_ratio[0] / $aspect_ratio[1]";
	}

	/**
	 * Get custom video and it's setting from control panel.
	 *
	 * @param string $url cutom video url.
	 * @param int    $id attachment id.
	 * @since 2.5.0
	 * @return string
	 */
	private function custom_video_html( $url, $id ) {
		if ( empty( $url ) ) {
			return;
		}

		$settings = [];

		$settings['autoplay'] = jupiterx_core_get_option( 'enable_autoplay_video' ) ? 'autoplay' : '';
		$settings['inline']   = jupiterx_core_get_option( 'play_video_inline' ) ? 'playsinline' : '';
		$settings['loop']     = jupiterx_core_get_option( 'play_video_loop' ) ? 'loop' : '';
		$settings['mute']     = jupiterx_core_get_option( 'mute_video_sound' ) ? 'muted' : '';
		$poster               = is_numeric( $id ) ? wp_get_attachment_url( $id ) : '';

		$video = sprintf(
			'<video class="jupiterx-attachment-media-custom-video" %1$s poster="%2$s"><source src="%3$s" type="video/mp4" preload="metadata"></source></video>',
			esc_attr( implode( ' ', $settings ) ),
			esc_attr( $poster ),
			esc_url( $url )
		);

		$video_icon = ! empty( $settings['autoplay'] ) ? '<i class="circle-pause"></i>' : '<i class="circle-play"></i>';

		$video .= '<span class="jupiterx-attachment-media-custom-video-icons">' . $video_icon . '</span>';

		return $video;
	}

	/**
	 * Add parameters to add to cart form variations.
	 *
	 * @param mixed $variation_data.
	 * @since 2.5.0
	 * @return array
	 */
	public function add_custom_meta_fields( $variation_data ) {
		if ( empty( $variation_data ) ) {
			return $variation_data;
		}

		$product_id = absint( $variation_data['variation_id'] );
		$product    = new WC_Product_Variation( $product_id );

		$variable_image = $product->get_image_id();

		$variable_content = '';

		if ( empty( $variable_image ) ) {
			return $variation_data;
		}

		$data = get_post_meta( $variable_image, '_jupiterx_attachment_meta', true );

		if ( empty( $data ) ) {
			$variable_content = $this->get_gallery_image_html( $variable_image, false );

			$variation_data['jupiterx_attached_media_enabled'] = false;
			$variation_data['jupiterx_attached_media']         = $variable_content;
			$variation_data['jupiterx_attached_media_type']    = '';
			$variation_data['jupiterx_attached_media_poster']  = '';

			return $variation_data;
		}

		$meta_data = $this->get_meta_data( $data, $variable_image );

		$variation_data['jupiterx_attached_media_enabled'] = true;
		$variation_data['jupiterx_attached_media_type']    = $meta_data['video-type'];
		$variation_data['jupiterx_attached_media_poster']  = $meta_data['poster'];
		$variation_data['jupiterx_attached_media']         = $meta_data['content'];

		return $variation_data;
	}

	/**
	 * Render video base on type.
	 *
	 * @param array $data array of jupiterx meta data.
	 * @param int   $id attachment id.
	 * @since 2.5.0
	 * @return array
	 */
	public function get_meta_data( $data, $id ) {
		if ( 'embed' === $data['jupiterx_video_type'] && ! empty( $data['jupiterx_embed_url'] ) ) {
			$embed = sprintf(
				'<div class="jupiterx-attachment-media-iframe iframe-on-load" style="aspect-ratio: %1$s;">%2$s %3$s</div>',
				esc_attr( $this->aspect_ratio( $data ) ),
				wp_oembed_get( $data['jupiterx_embed_url'] ),
				$this->preloader()
			);

			return [
				'content' => $embed,
				'video-type' => 'embed',
				'poster' => $id,
			];
		}

		if ( 'custom' === $data['jupiterx_video_type'] && ! empty( $data['jupiterx_media_url'] ) ) {
			$custom_video = sprintf(
				'<div class="jupiterx-attachment-media-iframe " style="aspect-ratio: %1$s;">%2$s</div>',
				esc_attr( $this->aspect_ratio( $data ) ),
				$this->custom_video_html( $data['jupiterx_media_url'], $id )
			);

			return [
				'content' => $custom_video,
				'video-type' => 'custom',
				'poster' => $id,
			];
		}

		$normal_variation = $this->get_gallery_image_html( $id, false );

		return [
			'content' => $normal_variation,
			'video-type' => '',
			'poster' => '',
		];
	}

	/**
	 * Override wc_get_gallery_image_html function of woocommerce.
	 *
	 * @param int  $attachment_id attachment meta data.
	 * @since 2.5.0
	 * @return string
	 */
	private function get_gallery_image_html( $attachment_id, $main_image ) {
		$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text          = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
		$image             = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			apply_filters(
				'woocommerce_gallery_image_html_attachment_image_params',
				array(
					'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-src'                => esc_url( $full_src[0] ),
					'data-large_image'        => esc_url( $full_src[0] ),
					'data-large_image_width'  => esc_attr( $full_src[1] ),
					'data-large_image_height' => esc_attr( $full_src[2] ),
					'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
				),
				$attachment_id,
				$image_size,
				$main_image
			)
		);

		$default = [
			'content' => '<a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a>',
			'video_type' => '',
			'poster' => '',
			'enabled' => false,
		];

		if ( $main_image ) {
			return '<div data-default="' . esc_attr( wp_json_encode( $default ) ) . '" data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
		}

		return '<a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a>';
	}

	/**
	 * It will add preloader when on load.
	 *
	 * @since 2.5.0
	 * @return string
	 */
	private function preloader() {
		return '<div class="jupiterx-attachment-media-preloader"></div>';
	}
}

new JupiterX_Core_Product_Gallery_Video();
