<?php
/**
 * The Jupiter Image component contains a set of functions to edit images on the fly.
 *
 * Edited images are duplicates of the originals. All modified images are stored in a shared folder,
 * which makes it easy to delete them without impacting the originals.
 *
 * @package JupiterX\Framework\API\Image
 */

/**
 * Edit image size and/or quality.
 *
 * Edited images are duplicates of the originals. All modified images are stored in a shared folder,
 * which makes it easy to delete them without impacting the originals.
 *
 * @since 1.0.0
 *
 * @param int    $attachment_id Image attachment ID.
 * @param string $src           The image source.
 * @param array  $args          An array of editor arguments, where the key is the {@see WP_Image_Editor} method name
 *                              and the value is a numeric array of arguments for the method. Make sure to specify
 *                              all of the arguments the WordPress editor's method requires. Refer to
 *                              {@link https://codex.wordpress.org/Class_Reference/WP_Image_Editor#Methods} for more
 *                              information on the available methods and each method's arguments.
 * @param string $output        Optional. Returned format. Accepts STRING, OBJECT, ARRAY_A, or ARRAY_N.
 *                              Default is STRING.
 *
 * @return string|array Image source if output set the STRING, image data otherwise.
 */
function jupiterx_edit_image( $attachment_id, $src, array $args, $output = 'STRING' ) {
	require_once JUPITERX_API_PATH . 'image/class-image-editor.php';
	$editor = new _JupiterX_Image_Editor( $attachment_id, $src, $args, $output );
	return $editor->run();
}

/**
 * Get attachment data.
 *
 * This function regroups all necessary data about a post attachment into an object.
 *
 * @since 1.0.0
 *
 * @param string $post_id The post id.
 * @param string $size    Optional. The desired attachment size. Accepts 'thumbnail', 'medium', 'large'
 *                        or 'full'.
 *
 * @return object Post attachment data.
 */
function jupiterx_get_post_attachment( $post_id, $size = 'full' ) {
	$id   = get_post_thumbnail_id( $post_id );
	$post = get_post( $id );
	$src  = wp_get_attachment_image_src( $id, $size );

	if ( empty( $post ) ) {
		return false;
	}

	$obj              = new stdClass();
	$obj->id          = $id;
	$obj->src         = $src[0];
	$obj->width       = $src[1];
	$obj->height      = $src[2];
	$obj->alt         = trim( strip_tags( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) ); // @codingStandardsIgnoreLine
	$obj->title       = $post->post_title;
	$obj->caption     = $post->post_excerpt;
	$obj->description = $post->post_content;

	return $obj;
}

/**
 * Edit post attachment.
 *
 * This function is shortcut of {@see jupiterx_edit_image()}. It should be used to edit a post attachment.
 *
 * @since 1.0.0
 *
 * @param string $post_id     The post id.
 * @param array  $args        An array of editor arguments, where the key is the {@see WP_Image_Editor} method name
 *                            and the value is a numeric array of arguments for the method. Make sure to specify
 *                            all of the arguments the WordPress editor's method requires. Refer to
 *                            {@link https://codex.wordpress.org/Class_Reference/WP_Image_Editor#Methods} for more
 *                            information on the available methods and each method's arguments.
 *
 * @return object Edited post attachment data.
 */
function jupiterx_edit_post_attachment( $post_id, $args = array() ) {

	if ( ! has_post_thumbnail( $post_id ) ) {
		return false;
	}

	// Get full size image.
	$attachment = jupiterx_get_post_attachment( $post_id, 'full' );

	if ( ! $attachment ) {
		return false;
	}

	$attachment_id = get_post_thumbnail_id( $post_id );

	$edited = jupiterx_edit_image( $attachment_id, $attachment->src, $args, 'ARRAY_A' );

	if ( ! $edited ) {
		return $attachment;
	}

	return (object) array_merge( (array) $attachment, $edited );
}

/**
 * Get the "edited images" storage directory, i.e. where the "edited images" are/will be stored.
 *
 * @since 1.0.0
 *
 * @return string
 */
function jupiterx_get_images_dir() {
	$wp_upload_dir = wp_upload_dir();

	/**
	 * Filter the edited images directory.
	 *
	 * @since 1.0.0
	 *
	 * @param string Default path to the Jupiter' edited images storage directory.
	 */
	$dir = apply_filters( 'jupiterx_images_dir', trailingslashit( $wp_upload_dir['basedir'] ) . 'jupiterx/images/' );

	return wp_normalize_path( trailingslashit( $dir ) );
}

/**
 * Register custom image sizes.
 *
 * @since 1.13.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jupiterx_register_image_sizes() {
	if ( ! function_exists( 'jupiterx_core' ) ) {
		return;
	}

	jupiterx_core()->load_files( [
		'control-panel-2/includes/class-image-sizes',
	] );

	if ( ! class_exists( 'JupiterX_Core_Control_Panel_Image_Sizes' ) ) {
		return;
	}

	$available_image_method = new ReflectionMethod( 'JupiterX_Core_Control_Panel_Image_Sizes', 'get_available_image_sizes' );

	if ( ! $available_image_method->isStatic() ) {
		return;
	}

	$image_sizes = JupiterX_Core_Control_Panel_Image_Sizes::get_available_image_sizes();
	$image_names = [];

	if ( ! empty( $image_sizes ) ) {
		foreach ( $image_sizes as $size ) {

			$width  = absint( $size['size_w'] );
			$height = absint( $size['size_h'] );

			$is_valid_width  = ( ! empty( $width ) && $width > 0 ) ? true : false;
			$is_valid_height = ( ! empty( $height ) && $height > 0 ) ? true : false;

			if ( ! $is_valid_width || ! $is_valid_height ) {
				continue;
			}

			$crop = ( isset( $size['size_c'] ) && 'on' === strtolower( $size['size_c'] ) ) ? true : false;

			add_image_size( $size['size_n'], $width, $height, $crop );

			$image_names[ $size['size_n'] ] = $size['size_n'];
		}
	}

	add_filter( 'image_size_names_choose', function ( $sizes ) use ( $image_names ) {
		return array_merge( $sizes, $image_names );
	} );
}
