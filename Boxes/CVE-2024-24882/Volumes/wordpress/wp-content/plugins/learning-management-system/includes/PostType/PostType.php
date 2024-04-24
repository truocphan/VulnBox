<?php
/**
 * Abstract post type class.
 */

namespace Masteriyo\PostType;

class PostType {
	/**
	 * Course post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const COURSE = 'mto-course';

	/**
	 * Section post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const SECTION = 'mto-section';

	/**
	 * Lesson post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const LESSON = 'mto-lesson';

	/**
	 * Quiz post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const QUIZ = 'mto-quiz';

	/**
	 * Question post type.
	 *
	 * @since 1.5.15
	 * @var string
	 */
	const QUESTION = 'mto-question';

	/**
	 * Order post type.
	 *
	 * @since 1.5.20
	 *
	 * @var string
	 */
	const ORDER = 'mto-order';

	/**
	 * Webhook post type.
	 *
	 * @since 1.6.9
	 *
	 * @var string
	 */
	const WEBHOOK = 'mto-webhook';

	/**
	 * Earning post type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	const EARNING = 'mto-earning';

	/**
	 * Withdraw post type.
	 *
	 * @since 1.6.14
	 *
	 * @var string
	 */
	const WITHDRAW = 'mto-withdraw';

	/**
	 * Course announcement post type.
	 *
	 * @since 1.6.16
	 *
	 * @var string
	 */
	const COURSEANNOUNCEMENT = 'mto-announcement';


	/**
	 * Post slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * An array of labels for this post type. If not set, post labels are inherited for non-hierarchical types and page labels for hierarchical ones.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $labels = array();

	/**
	 * Array or string of arguments for registering a post type.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * Register post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		register_post_type( $this->slug, $this->args );
	}

	/**
	 * Get label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 *
	 * @return mixed|null
	 */
	public function get_label( $label ) {
		if ( isset( $this->labels[ $label ] ) ) {
			return $this->labels[ $label ];
		}

		return null;
	}

	/**
	 * Get label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
	 *
	 * @return mixed|null
	 */
	public function get_arg( $arg ) {
		if ( isset( $this->args[ $arg ] ) ) {
			return $this->args[ $arg ];
		}

		return null;
	}

	/**
	 * Set label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 * @param string $value Label text/value.
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_label( $label, $value ) {
		$this->labels[ $label ] = $value;
		$this->args['labels']   = $this->labels;
		return $this;
	}

	/**
	 * Set arg.
	 *
	 * @since 1.0.0
	 *
	 * @param string $arg Arguments. (e.g. label, supports, menu_position, etc )
	 * @param string $value Arguments value.
	 *
	 * @return Masteriyo\Masteriyo\PostType
	 */
	public function set_arg( $arg, $value ) {
		$this->args[ $arg ] = $value;
		return $this;
	}

	/**
	 * Get labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_labels() {
		return $this->labels;
	}

	/**
	 * Get args.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_args() {
		return $this->args;
	}

	/**
	 * Return all post types.
	 *
	 * @since 1.5.15
	 *
	 * @return string[]
	 */
	public function all() {
		/**
		 * Filter post types register in masteriyo.
		 *
		 * @since 1.5.15
		 */
		$post_types = apply_filters(
			'masteriyo_post_types',
			array(
				self::COURSE,
				self::SECTION,
				self::LESSON,
				self::QUIZ,
				self::QUESTION,
				self::ORDER,
				self::WEBHOOK,
			)
		);

		return array_unique( $post_types );
	}
}
