<?php
/**
 * Masteriyo - Instructors List Shortcode.
 *
 * This file defines the InstructorsListShortcode class, which handles the
 * rendering and functionality of the instructors list shortcode.
 *
 * @package   Masteriyo\Shortcodes
 * @since     1.7.0
 */

namespace Masteriyo\Shortcodes;

use Masteriyo\Abstracts\Shortcode;
use Masteriyo\Enums\UserStatus;
use Masteriyo\Query\WPUserQuery;
use Masteriyo\Roles;

defined( 'ABSPATH' ) || exit;

/**
 * InstructorsListShortcode Class.
 *
 * This class is responsible for rendering the list of instructors
 * and handling the shortcode attributes.
 *
 * @since 1.6.16
 */
class InstructorsListShortcode extends Shortcode {

	/**
	 * The shortcode tag.
	 *
	 * @since 1.6.16
	 *
	 * @var   string
	 */
	protected $tag = 'masteriyo_instructors_list';

	/**
	 * The maximum number of pages for pagination.
	 *
	 * @since 1.6.16
	 *
	 * @var   int  $max_num_pages The maximum number of pages.
	 */
	protected $max_num_pages = 0;

	/**
	 * The current page for pagination.
	 *
	 * @since 1.6.16
	 *
	 * @var   int  $current_page The current page.
	 */
	protected $current_page = 1;

	/**
	 * Default attributes for the shortcode.
	 *
	 * @since 1.6.16
	 * @var   array
	 */
	protected $default_attributes = array(
		'count'       => -1,
		'columns'     => 4,
		'ids'         => array(),
		'exclude_ids' => array(),
		'order'       => 'ASC',
		'orderby'     => 'name',
		'paginate'    => true,
		'per_page'    => 10,
	);

	/**
	 * Fetch and display the content for the shortcode.
	 *
	 * @since  1.7.0
	 *
	 * @return string The rendered HTML content.
	 */
	public function get_content() {
		$instructors = $this->get_instructors();
		$attrs       = $this->get_attributes();
		$columns     = absint( $attrs['columns'] );

		// Column Validation.
		$attrs['columns']     = ( $columns >= 1 && $columns <= 4 ) ? $columns : 4;
		$attrs['instructors'] = $instructors;

		ob_start();

		/**
		 * Action hook: masteriyo_template_shortcode_instructors_list
		 *
		 * This hook allows developers to render custom templates for the
		 * instructors list shortcode.
		 *
		 * @since 1.6.16
		 *
		 * @param array $attrs The shortcode attributes.
		 */
		do_action( 'masteriyo_template_shortcode_instructors_list', $attrs );

		if ( $attrs['paginate'] ) {
			echo wp_kses(
				paginate_links(
					array(
						'type'      => 'list',
						'prev_text' => masteriyo_get_svg( 'left-arrow' ),
						'next_text' => masteriyo_get_svg( 'right-arrow' ),
						'total'     => $this->max_num_pages,
						'current'   => $this->current_page,
					)
				),
				'masteriyo_pagination'
			);
		}

		return ob_get_clean();
	}

	/**
	 * Fetch the list of instructors based on shortcode attributes.
	 *
	 * @since 1.6.16
	 *
	 * @return array Array of WP_User objects representing instructors.
	 */
	protected function get_instructors() {
		$attr = $this->get_attributes();
		$args = $this->prepare_query_args( $attr );
		$args = $this->handle_pagination( $args, $attr );

		$args['fields']      = 'ID';
		$args['user_status'] = UserStatus::ACTIVE;

		$user_query = new WPUserQuery( $args );

		$total_users         = $user_query->total_users;
		$total_pages         = (int) ceil( $total_users / (int) $attr['per_page'] );
		$this->max_num_pages = $total_pages;

		$instructor_ids = $user_query->get_results();

		$instructors = $this->fetch_instructors_data( $instructor_ids );

		/**
		 * Filter: masteriyo_shortcode_instructors
		 *
		 * This filter allows developers to modify the list of instructors
		 * that will be displayed in the shortcode.
		 *
		 * @since 1.6.16
		 *
		 * @param \Masteriyo\Models\User[]      The array of masteriyo instructors.
		 * @param array      $args        The query args.
		 * @param Shortcode  $this        The current shortcode object.
		 */
		return apply_filters( 'masteriyo_shortcode_instructors', $instructors, $args, $this );
	}

	/**
	 * Prepare query arguments for fetching instructors.
	 *
	 * @since 1.6.16
	 *
	 * @param array $attr Shortcode attributes.
	 *
	 * @return array Prepared query arguments.
	 */
	protected function prepare_query_args( array $attr ) {
		$number = -1 === $attr['count'] ? -1 : absint( $attr['count'] );

		return array(
			'role'    => Roles::INSTRUCTOR,
			'order'   => sanitize_text_field( $attr['order'] ),
			'orderby' => sanitize_text_field( $attr['orderby'] ),
			'include' => $this->parse_values_attribute( $attr['ids'], ',', 'absint' ),
			'exclude' => $this->parse_values_attribute( $attr['exclude_ids'], ',', 'absint' ),
			'number'  => $number,
		);
	}

	/**
	 * Handle pagination settings for query arguments.
	 *
	 * @since 1.6.16
	 *
	 * @param array $args Existing query arguments.
	 * @param array $attr Shortcode attributes.
	 *
	 * @return array Modified query arguments.
	 */
	protected function handle_pagination( array $args, array $attr ) {
		if ( isset( $attr['paginate'] ) && true === masteriyo_string_to_bool( $attr['paginate'] ) ) {
			$number = absint( $attr['per_page'] );
			$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

			$this->current_page = $paged;

			$args['number'] = $number ?? get_option( 'posts_per_page' );
			$args['offset'] = ( $paged - 1 ) * $number;
		}

		return $args;
	}

	/**
	 * Fetch and format instructor data.
	 *
	 * @since 1.6.16
	 *
	 * @param array $instructor_ids List of instructor user IDs.
	 *
	 * @return \Masteriyo\Models\User[]  List of instructor data arrays.
	 */
	protected function fetch_instructors_data( array $instructor_ids ) {
		$instructors = array();

		if ( ! is_wp_error( $instructor_ids ) && ! empty( $instructor_ids ) ) {
			foreach ( $instructor_ids as $id ) {
					$masteriyo_user = masteriyo_get_user( $id );

				if ( ! is_wp_error( $masteriyo_user ) && ! is_null( $masteriyo_user ) ) {
					$instructors[] = array(
						'full_name'         => $this->get_full_name( $masteriyo_user ),
						'username'          => $masteriyo_user->get_username(),
						'email'             => $masteriyo_user->get_email(),
						'url'               => $masteriyo_user->get_url(),
						'date_created'      => gmdate( 'F j, Y', strtotime( $masteriyo_user->get_date_created() ) ),
						'description'       => $masteriyo_user->get_description(),
						'profile_image_url' => $masteriyo_user->profile_image_url(),
						'courses_count'     => masteriyo_count_user_courses( $masteriyo_user ),
						'students_count'    => masteriyo_count_all_enrolled_users( $masteriyo_user ),
					);
				}
			}
		}

		return $instructors;
	}

	/**
	 * Get the user's full name.
	 *
	 * @since 1.6.16
	 *
	 * @param Masteriyo\Models\User $user The masteriyo user.
	 *
	 * @return string The full name of the user.
	 */
	private function get_full_name( $user ) {
		if ( is_wp_error( $user ) || is_null( $user ) ) {
			return '';
		}

		if ( ! empty( $user->get_first_name() ) && ! empty( $user->get_last_name() ) ) {
			return $user->get_first_name() . ' ' . $user->get_last_name();
		}

		return $user->get_display_name();
	}
}
