<?php
namespace JupiterX_Core\Raven\Modules\Post_Meta;

defined( 'ABSPATH' ) || die();

use JupiterX_Core\Raven\Base\Module_base;

class Module extends Module_Base {
	public function __construct() {
		parent::__construct();
		add_action( 'wp_insert_post', [ $this, 'save_post_reading_time_meta' ], 10, 3 );
		add_action( 'elementor/editor/after_save', [ $this, 'save_post_reading_time_meta_in_editor' ], 10, 2 );
	}

	/**
	 * Register module widgets.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_widgets() {
		return [ 'post-meta' ];
	}

	/**
	 * Save post reading time in post meta when WordPress editor is updated.
	 *
	 * @param $post_id int Post ID.
	 * @param $post \WP_Post Post Object.
	 * @param $update bool Whether this is an existing post being updated.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @since 2.6.4
	 */
	public function save_post_reading_time_meta( $post_id, $post, $update ) {
		$content = get_the_content( $post_id );

		if ( get_post_type( $post_id ) === 'product' ) {
			return;
		};

		if ( empty( $content ) ) {
			return;
		}

		$read_time = $this->get_read_time( $content );

		update_post_meta( $post_id, 'jupiterx_reading_time', $read_time );
	}

	/**
	 * Save post reading time in post meta when elementor editor is updated.
	 *
	 * @param $post_id int Post ID.
	 * @param $data array The editor data.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @since 2.6.4
	 */
	public function save_post_reading_time_meta_in_editor( $post_id, $data ) {
		$content = get_the_content();

		if ( get_post_type( $post_id ) === 'product' ) {
			return;
		};

		if ( empty( $content ) ) {
			return;
		}

		$read_time = $this->get_read_time( $content );

		update_post_meta( $post_id, 'jupiterx_reading_time', $read_time );
	}

	/**
	 * Calculates the reading time with the speed of $wpm (200 words per minute).
	 *
	 * @param $content string The Post Content.
	 *
	 * @return string
	 */
	public function get_read_time( $content ) {
		$wpm = 200;

		// Strip tags to avoid calculating tags as words.
		$escaped_content = wp_strip_all_tags( $content );
		$total_words     = str_word_count( $escaped_content );
		$hours           = 0;
		$minutes         = floor( $total_words / $wpm );

		if ( $minutes >= 60 ) {
			$hours   = floor( $minutes / 60 );
			$minutes = $minutes - ( $hours * 60 );
		}

		$read_time = '';

		if ( $hours > 0 ) {
			$read_time .= sprintf(
				'%d %s',
				$hours,
				_n( 'hour', 'hours', $hours, 'jupiterx-core' )
			);
		}

		if ( $minutes > 0 ) {
			$read_time .= sprintf(
				'%s %d %s',
				$hours > 0 ? esc_html__( ' and', 'jupiterx-core' ) : '',
				$minutes,
				_n( 'minute', 'minutes', $minutes, 'jupiterx-core' )
			);
		}

		// If the words count are so small (less than $wpm), then we show 1 minute as the reading time.
		if ( '' === $read_time ) {
			$read_time = esc_html__( '1 minute', 'jupiterx-core' );
		}

		return $read_time;
	}
}
