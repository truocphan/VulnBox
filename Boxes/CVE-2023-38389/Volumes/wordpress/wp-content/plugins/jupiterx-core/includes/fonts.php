<?php

use Elementor\Plugin;

/**
 * Handle the JupiterX custom fonts functionality.
 *
 * @since 2.5.0
 */
class JupiterX_Custom_Fonts {

	private static $instance = null;

	const POST_TYPE = 'jupiterx-fonts';

	const FONTS_CUSTOM_GROUP = 'jx-custom-fonts';

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_filter( 'wp_check_filetype_and_ext', [ $this, 'allow_ttf' ], 10, 5 );
		add_filter( 'upload_mimes', [ $this, 'upload_mimes' ] );
		add_filter( 'elementor/fonts/groups', [ $this, 'register_fonts_groups' ] );
		add_filter( 'elementor/fonts/additional_fonts', [ $this, 'register_fonts_in_control' ] );
		add_action( 'elementor/css-file/post/parse', [ $this, 'enqueue_fonts' ], 10, 1 );
		add_action( 'elementor/css-file/global/parse', [ $this, 'enqueue_fonts' ], 10, 1 );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'get_all_font_faces' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'get_all_font_faces' ] );
		add_action( 'elementor/css-file/global/enqueue', [ $this, 'get_all_font_faces' ] );
	}

	/**
	 * Add TTF, WOFF, WOFF2, SVG to allowed media types.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function upload_mimes( $mime_types ) {
		foreach ( $this->get_file_types() as $type => $mime ) {
			if ( ! isset( $mime_types[ $type ] ) ) {
				$mime_types[ $type ] = $mime;
			}
		}

		return $mime_types;
	}

	/**
	 * Returns the list of all font types and the mime types.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	private function get_file_types() {
		return [
			'woff'  => 'application/font-woff',
			'woff2' => 'application/font-woff2',
			'ttf'   => 'application/x-font-ttf',
			'svg'   => 'image/svg+xml',
		];
	}

	/**
	 * Allow TTF By checking the mime type and extension.
	 *
	 * @since 2.5.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function allow_ttf( $data, $file, $filename, $mimes, $real_mime ) {
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}

		$wp_file_type = wp_check_filetype( $filename, $mimes );

		if ( 'ttf' === $wp_file_type['ext'] ) {
			$data['ext']  = 'ttf';
			$data['type'] = 'application/x-font-ttf';
		}

		return $data;
	}

	/**
	 * Creates a new font group in elementor typography control called 'Jupiter X Fonts'.
	 *
	 * @param $fonts
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public function register_fonts_groups( $font_groups ) {
		$new_groups = [ self::FONTS_CUSTOM_GROUP => esc_html__( 'Jupiter X Fonts', 'jupiterx-core' ) ];

		return array_merge( $new_groups, $font_groups );
	}

	/**
	 * Returns the list of jupiterx-fonts to show in the typography controls of elementor.
	 *
	 * @param $fonts
	 *
	 * @return array
	 * @since 2.5.0
	 */
	public function register_fonts_in_control( $fonts ) {
		$jupiterx_fonts = get_posts( [
			'post_type'   => self::POST_TYPE,
			'numberposts' => - 1,
		] );

		$custom_fonts = [];

		foreach ( $jupiterx_fonts as $font ) {
			$custom_fonts[ $font->post_title ] = self::FONTS_CUSTOM_GROUP;
		}

		return array_merge( $custom_fonts, $fonts );
	}

	/**
	 * Enqueue fonts css.
	 *
	 * @param $post_css
	 *
	 * @since 2.5.0
	 */
	public function enqueue_fonts( $post_css ) {
		$used_fonts = $post_css->get_fonts();

		foreach ( $used_fonts as $font_family ) {
			$font_face = get_post_meta( $this->get_font_id_by_title( $font_family ), 'jupiterx_font_face', true );
			$this->enqueue_font( $font_face, $post_css );
		}
	}

	/**
	 * Get fonts array form the database or generate a new list if $force is set to true.
	 *
	 * @param bool $force
	 *
	 * @return array|bool|mixed
	 * @since 2.5.0
	 */
	public function get_fonts() {
		static $fonts = false;

		if ( false !== $fonts ) {
			return $fonts;
		}

		return $this->get_fonts_list();
	}

	/**
	 * Gets a list of all Font Manager fonts and stores it in the options table.
	 *
	 * @return array
	 * @since 2.5.0
	 */
	private function get_fonts_list() {
		$fonts = new WP_Query( [
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => - 1,
		] );

		$new_fonts = [];
		foreach ( $fonts->posts as $font ) {
			$new_fonts[] = [ $font->ID, $font->post_title ];
		}

		return $new_fonts;
	}

	/**
	 * Add a comment before and after the font face and add the css to the related post stylesheet.
	 *
	 * @param $font_face
	 * @param $post_css
	 *
	 * @return void
	 */
	public function enqueue_font( $font_face, $post_css ) {
		// Add a css comment.
		$custom_css = PHP_EOL . '/* Start JX Custom Fonts CSS */' . $font_face . '/* End JX Custom Fonts CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

	/**
	 * Gets font ID by font title.
	 *
	 * @param $font_title
	 *
	 * @return int
	 * @since 2.5.0
	 */
	private function get_font_id_by_title( $font_title ) {
		$query_args = [
			'post_type'      => self::POST_TYPE,
			's'              => $font_title,
			'posts_per_page' => 1,
		];

		$fonts_query      = new WP_Query( $query_args );
		$current_font_obj = ( is_array( $fonts_query->get_posts() ) && count( $fonts_query->get_posts() ) > 0 ) ? (object) $fonts_query->get_posts()[0] : false;

		if ( empty( $current_font_obj ) || false === $current_font_obj ) {
			return null;
		}

		return $current_font_obj->ID;
	}

	/**
	 * Gets all the font faces in the post meta table to load them in editor preview.
	 *
	 * @return void
	 * @since 2.5.0
	 */
	public function get_all_font_faces() {
		//phpcs:disable
		global $wpdb;
		$all_font_faces = $wpdb->get_results( "SELECT meta_value as font_face FROM $wpdb->postmeta WHERE meta_key = 'jupiterx_font_face'" );
		//phpcs:enable

		if ( empty( $all_font_faces ) ) {
			return null;
		}
		$font_faces = '';
		foreach ( $all_font_faces as $font_face ) {
			$font_faces .= PHP_EOL . $font_face->font_face . PHP_EOL;
		}

		echo '<style id="jupiterx-editor-custom-font-faces">' . $font_faces . '</style>';
	}
}

JupiterX_Custom_Fonts::get_instance();
