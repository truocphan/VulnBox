<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'awpa_Google_Fonts' ) ) {
    class awpa_Google_Fonts {

        function __construct() {
            add_action( 'wp_head', array( $this, 'enqueue_frontend_block_fonts' ), 100 );
		}

		public function enqueue_frontend_block_fonts() {
            
   
			if ( ! apply_filters( 'awpa_enqueue_fonts', true ) ) {
				return;
			}

			if ( is_single() || is_page() || is_404() ) {
				global $post;
				if ( is_object( $post ) && property_exists( $post, 'post_content' ) ) {
					$this->_enqueue_frontend_block_fonts( $post->post_content );
				}
			} elseif ( is_archive() || is_home() || is_search() ) {
				global $wp_query;
				foreach ( $wp_query as $post ) {
					if ( is_object( $post ) && property_exists( $post, 'post_content' ) ) {
						$this->_enqueue_frontend_block_fonts( $post->post_content );
					}
				}
			}
		}

		public function _enqueue_frontend_block_fonts( $content ) {
			$blocks = parse_blocks( $content );
			$google_fonts = $this->gather_google_fonts( $blocks );
			$this->enqueue_google_fonts( $google_fonts );
		}

		public function is_web_font( $font_name ) {
			return ! in_array( strtolower( $font_name ), [ 'serif', 'sans-serif', 'monospace', 'serif-alt' ] );
		}

		public function is_awpa_block( $block_name ) {
			return strpos( $block_name, 'awpa/' ) === 0;
		}

		public function gather_google_fonts( $blocks ) {

			$google_fonts = array();
			foreach ( $blocks as $block ) {

				// Gather all "fontFamily" attribute values
				if ( $this->is_awpa_block( $block['blockName'] ) ) {
					foreach ( $block['attrs'] as $attr_name => $font_name ) {
						if ( preg_match( '/fontFamily$/i', $attr_name ) ) {
							if ( ! $this->is_web_font( $font_name ) ) {
								continue;
							}
							if ( ! in_array( $font_name, $google_fonts ) ) {
								$google_fonts[] = $font_name;
							}
						}
					}
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$google_fonts = array_unique( array_merge( $google_fonts, $this->gather_google_fonts( $block['innerBlocks'] ) ) );
				}
			}

			return $google_fonts;
		}

		/**
		 * Based on: https://github.com/elementor/elementor/blob/bc251b81afb626c4c47029aea8a762566524a811/includes/frontend.php#L647
		 */
		public function enqueue_google_fonts( $google_fonts ) {
			if ( ! count( $google_fonts) ) {
				return;
			}

			foreach ( $google_fonts as &$font ) {
				$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
			}

			$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts ) );

			$subsets = [
				'ru_RU' => 'cyrillic',
				'bg_BG' => 'cyrillic',
				'he_IL' => 'hebrew',
				'el' => 'greek',
				'vi' => 'vietnamese',
				'uk' => 'cyrillic',
				'cs_CZ' => 'latin-ext',
				'ro_RO' => 'latin-ext',
				'pl_PL' => 'latin-ext',
			];

			$locale = get_locale();
			if ( isset( $subsets[ $locale ] ) ) {
				$fonts_url .= '&subset=' . $subsets[ $locale ];
			}

			wp_enqueue_style( 'awpa-google-fonts', $fonts_url ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}

	}

	new awpa_Google_Fonts();
}
