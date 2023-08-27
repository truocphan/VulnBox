<?php

namespace JupiterX_Core\Raven\Core\Dynamic_Tags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag;
use Elementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_Title extends Tag {
	public function get_name() {
		return 'archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive Title', 'jupiterx-core' );
	}

	public function get_group() {
		return 'archive';
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$include_context = 'yes' === $this->get_settings( 'include_context' );
		$page_title      = self::get_page_title( $include_context );

		echo wp_kses_post( $page_title );
	}

	/**
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public static function get_page_title( $include_context = true ) {
		$title = '';

		if ( is_singular() ) {
			/* translators: %s: Search term. */
			$title = get_the_title();

			if ( $include_context ) {
				$post_type_obj = get_post_type_object( get_post_type() );
				$title         = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
			}
		} elseif ( is_search() ) {
			/* translators: %s: Search term. */
			$title = sprintf( esc_html__( 'Search Results for: %s', 'jupiterx-core' ), get_search_query() );

			if ( get_query_var( 'paged' ) ) {
				/* translators: %s is the page number. */
				$title .= sprintf( esc_html__( '&nbsp;&ndash; Page %s', 'jupiterx-core' ), get_query_var( 'paged' ) );
			}
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );

			if ( $include_context ) {
				/* translators: Category archive title. 1: Category name */
				$title = sprintf( esc_html__( 'Category: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
			if ( $include_context ) {
				/* translators: Tag archive title. 1: Tag name */
				$title = sprintf( esc_html__( 'Tag: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';

			if ( $include_context ) {
				/* translators: Author archive title. 1: Author name */
				$title = sprintf( esc_html__( 'Author: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'jupiterx-core' ) );

			if ( $include_context ) {
				/* translators: Yearly archive title. 1: Year */
				$title = sprintf( esc_html__( 'Year: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'jupiterx-core' ) );

			if ( $include_context ) {
				/* translators: Monthly archive title. 1: Month name and year */
				$title = sprintf( esc_html__( 'Month: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_day() ) {
			$title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'jupiterx-core' ) );

			if ( $include_context ) {
				/* translators: Daily archive title. 1: Date */
				$title = sprintf( esc_html__( 'Day: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'jupiterx-core' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'jupiterx-core' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );

			if ( $include_context ) {
				/* translators: Post type archive title. 1: Post type name */
				$title = sprintf( esc_html__( 'Archives: %s', 'jupiterx-core' ), $title );
			}
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );

			if ( $include_context ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
				$title = sprintf( esc_html__( '%1$s: %2$s', 'jupiterx-core' ), $tax->labels->singular_name, $title );
			}
		} elseif ( is_archive() ) {
			$title = esc_html__( 'Archives', 'jupiterx-core' );
		} elseif ( is_404() ) {
			$title = esc_html__( 'Page Not Found', 'jupiterx-core' );
		}

		/**
		 * The archive title.
		 * Filters the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'elementor/utils/get_the_archive_title', $title );
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__( 'Include Context', 'jupiterx-core' ),
				'type' => 'switcher',
				'default' => 'yes',
			]
		);
	}
}
