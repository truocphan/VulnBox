<?php
namespace JupiterX_Core\Raven\Modules\Product_Gallery;

use JupiterX_Core\Raven\Base\Module_base;
use Elementor\Plugin as Elementor;
use JupiterX_Core\Raven\Modules\Forms\Module as FormModule;
use JupiterX_Core\Raven\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;

defined( 'ABSPATH' ) || die();
/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Module extends Module_Base {
	public function __construct() {
		parent::__construct();

		add_action( 'woocommerce_variation_options', [ $this, 'add_variation_gallery_button' ], 10, 3 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation_gallery_data' ], 10, 2 );
		add_action( 'wp_ajax_jupiterx_product_gallery_get_gallery_items', [ $this, 'get_gallery_items' ] );
		add_action( 'wp_ajax_nopriv_jupiterx_product_gallery_get_gallery_items', [ $this, 'get_gallery_items' ] );

	}
	public function get_widgets() {
		return [ 'product-gallery' ];
	}

	public static function is_active() {
		return function_exists( 'WC' );
	}

	/**
	 * Add a hidden field and gallery upload button to the product variations.
	 *
	 * @param int $loop loop number.
	 * @param array $variation_data variation data.
	 * @param object $variation post object.
	 * @since 3.1.0
	 */
	public function add_variation_gallery_button( $loop, $variation_data, $variation ) {
		$image_id    = get_post_meta( $variation->ID, 'jupiterx_variation_gallery_image_id', true );
		$image_array = explode( ',', $image_id );

		?>
			<p class="form-field jupiterx-variation-gallery-wrap">
				<span class="jupiterx-gallery-images-preview">
					<?php foreach ( $image_array as $key => $id ) : ?>
						<?php
							if ( empty( $id ) ) {
								continue;
							}
						?>
						<span class="jupiterx-product-variation-gallery-single-image-wrapper" data-id="<?php echo esc_attr( $id ); ?>">
							<a class="jx-remove-gallery-item" title="<?php echo esc_attr__( 'Remove', 'jupiterx-core' ); ?>"><?php echo esc_html__( 'Remove', 'jupiterx-core' ); ?></a>
							<img src="<?php echo esc_url( wp_get_attachment_url( $id ) ); ?>" >
						</span>
					<?php endforeach; ?>
				</span>
				<input type="button" class="jupiterx-variation-gallery-button button" value="<?php echo ( $image_id ) ? esc_html_e( 'Change Image', 'jupiterx-core' ) : esc_html_e( 'Upload Gallery Images', 'jupiterx-core' ); ?>" />
				<?php
					woocommerce_wp_hidden_input(
						[
							'id'    => 'jupiterx_variation_gallery_image_id[' . $loop . ']',
							'value' => '',
							'class' => 'variation-gallery-images-id',
							'value' => $image_id,
						]
					);
				?>
			</p>
		<?php
	}

	/**
	 * Save variation gallery data as a meta box.
	 *
	 * @param int $variation_id variation ID.
	 * @param int $i loop id.
	 * @since 3.1.0
	 */
	public function save_variation_gallery_data( $variation_id, $i ) {
		$gallery_images = filter_input( INPUT_POST, 'jupiterx_variation_gallery_image_id', FILTER_DEFAULT, FILTER_FORCE_ARRAY );
		$gallery_images = $gallery_images[ $i ];

		if ( substr( $gallery_images, -1 ) === ',' ) {
			$gallery_images = substr( $gallery_images, 0, -1 );
		}

		update_post_meta( $variation_id, 'jupiterx_variation_gallery_image_id', $gallery_images );
	}

	/**
	 * Gets gallery pictures HTML an prepare it for frontend.
	 *
	 * @since 3.1.0
	 */
	public function get_gallery_items() {
		check_ajax_referer( 'jupiterx-core-raven', 'nonce' );

		$variation_id = filter_input( INPUT_POST, 'variation', FILTER_SANITIZE_NUMBER_INT );
		$post_id      = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$form_id      = filter_input( INPUT_POST, 'form_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$product_id   = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );

		$form_meta   = Elementor::$instance->documents->get( $post_id )->get_elements_data();
		$widget_data = FormModule::find_element_recursive( $form_meta, $form_id );
		$settings    = Elementor::$instance->elements_manager->create_element_instance( $widget_data )->get_settings_for_display();

		$this->replace = true;

		$data = [
			'variation_id' => $variation_id,
			'post_id'      => $post_id,
			'form_id'      => $form_id,
			'product_id'   => $product_id,
		];

		if ( 'standard' === $settings['gallery_layout'] ) {
			$content = $this->render_standard( $settings, $data );
		}

		if ( 'stack' === $settings['gallery_layout'] ) {
			$content = $this->render_stack( $settings, $data );
		}

		wp_send_json_success( [
			'layout'  => $settings['gallery_layout'],
			'content' => $content,
			'replace' => $this->replace,
		] );
	}

	/**
	 * Render gallery items by ajax.
	 *
	 * @param array $settings widget settings.
	 * @param array $data gallery data.
	 * @since 3.1.0
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function render_standard( $settings, $data ) {
		$product = wc_get_product( (int) $data['product_id'] );

		if ( empty( $product ) ) {
			return;
		}

		$required_options = [];

		if ( ! empty( $settings['zoom'] ) && empty( current_theme_supports( 'wc-product-gallery-zoom' ) ) ) {
			$required_options['data-wc-disable-zoom'] = 1;
		}

		if ( ! empty( $settings['lightbox'] ) && empty( current_theme_supports( 'wc-product-gallery-lightbox' ) ) ) {
			$required_options['data-wc-disable-lightbox'] = 1;

			add_action( 'wp_footer', function() {
				wc_get_template( 'single-product/photoswipe.php' );
			} );
		}

		$columns           = 1;
		$post_thumbnail_id = get_post_thumbnail_id( $data['variation_id'] );
		$gallery_string    = get_post_meta( $data['variation_id'], 'jupiterx_variation_gallery_image_id', true );
		$attachment_ids    = explode( ',', $gallery_string );
		$gallery_ids       = [];
		$attachment_ids    = array_filter( $attachment_ids, function( $value ) {
			// Remove empty and false values.
			return ! empty( $value ) || 0 === $value || false === $value;
		} );

		if ( 0 === $post_thumbnail_id && empty( $attachment_ids ) ) {
			$post_thumbnail_id = $product->get_image_id();
			$gallery_ids       = $product->get_gallery_image_ids();
		}

		if ( 0 === $post_thumbnail_id && ! empty( $attachment_ids ) ) {
			$post_thumbnail_id = $product->get_image_id();
			$gallery_ids       = $attachment_ids;
		}

		if ( 0 !== $post_thumbnail_id && empty( $attachment_ids ) ) {
			$gallery_ids = $product->get_gallery_image_ids();
		}

		if ( ! empty( $attachment_ids ) && 0 !== $post_thumbnail_id ) {
			$images[]    = $post_thumbnail_id;
			$gallery_ids = $attachment_ids;
		}

		if ( empty( $data['variation_id'] ) || 0 === $data['variation_id'] ) {
			$product           = wc_get_product( (int) $data['product_id'] );
			$post_thumbnail_id = $product->get_image_id();
			$gallery_ids       = $product->get_gallery_image_ids();
		}

		$wrapper_classes = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			[
				'raven-product-gallery-slider-wrapper',
				'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
				'woocommerce-product-gallery--columns-1',
				'images',
			]
		);

		add_filter( 'woocommerce_gallery_thumbnail_size', function() use ( $settings ) {
			return $settings['product_thumbnail_size'];
		} );

		add_filter( 'woocommerce_gallery_image_size', function() use ( $settings ) {
			return $settings['image_size'];
		} );

		?>
		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
			<figure class="woocommerce-product-gallery__wrapper">
				<?php
				if ( $post_thumbnail_id ) {
					$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
				} else {
					$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
					$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'noks-core' ) );
					$html .= '</div>';
				}

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

				if ( $gallery_ids && ! empty( $post_thumbnail_id ) ) {
					foreach ( $gallery_ids as $attachment_id ) {
						if ( empty( $attachment_id ) ) {
							continue;
						}

						echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
					}
				}
				?>
			</figure>
			<input type="hidden" name="post_id" value="<?php echo esc_attr( $data['post_id'] ); ?>" />
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $data['form_id'] ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $data['product_id'] ); ?>" />
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Render stack mode.
	 *
	 * @param array $setting widget settings.
	 * @param array $data variation data.
	 * @since 3.1.0
	 */
	private function render_stack( $settings, $data ) {
		ob_start();

		$images = $this->get_images( $settings, $data );

		if ( empty( $images ) ) {
			return;
		}

		$html = '<ul class="raven-product-gallery-stack-wrapper">';
		foreach ( $images as $image ) {
			$media = $this->render_media( $image, $settings );
			$html .= sprintf(
				'<li class="jupiterx-product-gallery-stack-item %1$s %2$s">%3$s</li>',
				esc_attr( 'jupiterx-product-gallery-stack-' . $media['type'] ),
				! empty( $settings['enable_aspect_ratio'] ) && 'image' === $media['type'] ? 'raven-image-fit' : '',
				$media['content']
			);
		}

		$html .= '</ul>';
		$html .= '<input type="hidden" name="post_id" value="' . esc_attr( $data['post_id'] ) . '" />';
		$html .= '<input type="hidden" name="form_id" value="' . esc_attr( $data['form_id'] ) . '" />';
		$html .= '<input type="hidden" name="product_id" value="' . esc_attr( $data['product_id'] ) . '" />';

		echo $html;

		return ob_get_clean();
	}

	/**
	 * Get images for stack mode.
	 *
	 * @param array $settings widget settings.
	 * @param array $data variation data.
	 * @since 3.1.0
	 */
	private function get_images( $settings, $data ) {
		$product = wc_get_product( (int) $data['product_id'] );

		$images      = [];
		$gallery_ids = [];

		if ( empty( $product ) ) {
			return;
		}

		$post_thumbnail_id = get_post_thumbnail_id( $data['variation_id'] );
		$gallery_string    = get_post_meta( $data['variation_id'], 'jupiterx_variation_gallery_image_id', true );
		$attachment_ids    = explode( ',', $gallery_string );
		$parent            = wp_get_post_parent_id( $data['variation_id'] );
		$attachment_ids    = array_filter( $attachment_ids, function( $value ) {
			// Remove empty and false values.
			return ! empty( $value ) || 0 === $value || false === $value;
		} );

		if ( 0 === $post_thumbnail_id && empty( $attachment_ids ) ) {
			$images[]    = $product->get_image_id();
			$gallery_ids = $product->get_gallery_image_ids();
		}

		if ( 0 === $post_thumbnail_id && ! empty( $attachment_ids ) ) {
			$images[]    = $product->get_image_id();
			$gallery_ids = $attachment_ids;
		}

		if ( 0 !== $post_thumbnail_id && empty( $attachment_ids ) ) {
			$images[]    = $post_thumbnail_id;
			$gallery_ids = $product->get_gallery_image_ids();

			if ( empty( $gallery_ids ) ) {
				$gallery_ids[] = $product->get_image_id();
			}
		}

		if ( ! empty( $attachment_ids ) && 0 !== $post_thumbnail_id ) {
			$images[]    = $post_thumbnail_id;
			$gallery_ids = $attachment_ids;
		}

		if ( empty( $data['variation_id'] ) || 0 === $data['variation_id'] ) {
			$images      = [];
			$images[]    = $product->get_image_id();
			$gallery_ids = $product->get_gallery_image_ids();
		}

		foreach ( $gallery_ids as $id ) {
			if ( empty( $id ) ) {
				continue;
			}

			$images[] = $id;
		}

		return array_unique( $images );
	}

	/**
	 * Render media.
	 *
	 * @param int   $image_id image id.
	 * @param array $settings widget settings
	 * @since 3.1.0
	 */
	private function render_media( $image_id, $settings ) {
		if ( empty( jupiterx_get_option( 'enable_media_controls', 0 ) ) ) {
			return [
				'content' => $this->get_thumbnail( $image_id, $settings ),
				'type' => 'image',
			];
		}

		$data = get_post_meta( $image_id, '_jupiterx_attachment_meta', true );

		if ( empty( $data ) ) {
			return [
				'content' => $this->get_thumbnail( $image_id, $settings ),
				'type' => 'image',
			];
		}

		return [
			'content' => $this->get_video( $image_id, $data, $settings ),
			'type' => 'video',
		];
	}

	/**
	 * Get thumbnail image.
	 *
	 * @param int   $image image id.
	 * @param array $settings widget settings.
	 * @since 3.1.0
	 */
	private function get_thumbnail( $image, $settings ) {
		$image_src = Group_Control_Image_Size::get_attachment_image_src( $image, 'image', $settings );

		$image_tag = sprintf(
			'<img class="%4$s" src="%1$s" title="%2$s" alt="%3$s" %5$s />',
			esc_attr( $image_src ),
			Control_Media::get_image_title( $image ),
			Control_Media::get_image_alt( $image ),
			'raven-product-gallery-stack-image',
			$this->get_image_size( $image, $settings )
		);

		if ( ! empty( $settings['lightbox'] ) ) {
			$result = sprintf(
				'<a %1$s href="%2$s">%3$s</a>',
				'class="elementor-clickable" data-elementor-open-lightbox="' . esc_attr( $settings['lightbox'] ) . '"',
				$this->get_thumbnail_src( $image ),
				$image_tag
			);

			return $result;
		}

		return $image_tag;
	}

	/**
	 * Get video
	 *
	 * @param int   $image image id.
	 * @param array $data image meta.
	 * @param array $settings widget settings.
	 * @since 3.1.0
	 */
	private function get_video( $image, $data, $settings ) {
		if ( ! class_exists( 'JupiterX_Core_Product_Gallery_Video' ) ) {
			return $this->get_thumbnail( $image, $settings );
		}

		$video_gallery = new \JupiterX_Core_Product_Gallery_Video();
		$video_content = $video_gallery->get_meta_data( $data, $image );

		if ( empty( $video_content ) ) {
			return $this->get_thumbnail( $image, $settings );
		}

		return $video_content['content'];
	}

	/**
	 * Get thumbnail src.
	 *
	 * @param int $image image id.
	 * @since 3.1.0
	 */
	private function get_thumbnail_src( $image ) {
		if ( empty( $image ) ) {
			return;
		}

		$image = wp_get_attachment_image_src( $image, 'full' );

		return $image[0];
	}

	/**
	 * Get image size.
	 *
	 * @param int   $image image id.
	 * @param array $settings widget settings.
	 * @since 3.1.0
	 */
	private function get_image_size( $image, $settings ) {
		$size      = $settings['image_size'];
		$dimension = [];

		$original_image = [
			'data-src' => wp_get_attachment_image_src( $image, 'full' )[0],
			'data-large_image_width' => wp_get_attachment_image_src( $image, 'full' )[1],
			'data-large_image_height' => wp_get_attachment_image_src( $image, 'full' )[2],
		];

		if ( 'custom' === $size ) {
			$image_size = $settings['image_custom_dimension'];

			$dimension = [
				'width' => ! empty( $image_size['width'] ) ? $image_size['width'] : 0,
				'height' => ! empty( $image_size['height'] ) ? $image_size['width'] : 0,
			];
		} else {
			$image_size = image_downsize( $image, $size );

			$dimension = [
				'width' => ! empty( $image_size[1] ) ? $image_size[1] : 0,
				'height' => ! empty( $image_size[2] ) ? $image_size[2] : 0,
			];
		}

		$dimension = array_merge( $dimension, $original_image );

		if ( empty( $dimension ) ) {
			return;
		}

		$attribute = '';

		foreach ( $dimension as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}

			$attribute .= $key . '="' . esc_attr( $value ) . '" ';
		}

		return $attribute;
	}
}
