<?php
/**
 * Abstract taxonomy class.
 */

namespace Masteriyo\Taxonomy;

abstract class Taxonomy {

	/**
	 * Course category.
	 *
	 * @since 1.5.19
	 * @var string
	 */
	const COURSE_CATEGORY = 'course_cat';

	/**
	 * Course difficulty.
	 *
	 * @since 1.5.19
	 * @var string
	 */
	const COURSE_DIFFICULTY = 'course_difficulty';

	/**
	 * Course tag.
	 *
	 * @since 1.5.19
	 * @var string
	 */
	const COURSE_TAG = 'course_tag';

	/**
	 * Course visibility.
	 *
	 * @since 1.5.20
	 * @var string
	 */
	const COURSE_VISIBILITY = 'course_visibility';

	/**
	 * Taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $taxonomy;

	/**
	 * Post type the taxonomy belongs to.
	 *
	 * @since 1.0.0
	 */
	protected $post_type = 'post';

	/**
	 * Register taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		register_taxonomy(
			$this->taxonomy,
			/**
			 * Filters post types belonging to a taxonomy.
			 *
			 * @since 1.0.0
			 *
			 * @param string[] $post_types The post types belonging to a taxonomy.
			 */
			apply_filters( "masteriyo_taxonomy_objects_{$this->taxonomy}", array( $this->post_type ) ),
			$this->get_args()
		);
	}

	/**
	 * Get all labels.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_labels() {
		$args = $this->get_args();

		$labels = isset( $args['labels'] ) ? $args['labels'] : array();

		/**
		 * Filters labels of a taxonomy.
		 *
		 * @since 1.0.0
		 *
		 * @param array $labels The labels of a taxonomy.
		 */
		return apply_filters( "masteriyo_taxonomy_{$this->taxonomy}_labels", $labels );
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
		$labels = $this->get_labels();

		$value = isset( $labels[ $label ] ) ? $value = $labels[ $label ] : '';

		/**
		 * Filters a label of a taxonomy.
		 *
		 * @since 1.0.0
		 *
		 * @param string $value The label.
		 * @param string $label The label name.
		 */
		return apply_filters( "masteriyo_taxonomy_{$this->taxonomy}_label", $value, $label );
	}

	/**
	 * Set label.
	 *
	 * @since 1.0.0
	 *
	 * @param string $label Label. (e.g. name, singular_name, menu_name, etc )
	 * @param string $value Label text/value.
	 * @param bool $strict  Strict check the label.(Default: true)
	 *
	 * @return Masteriyo\Masteriyo\Taxonomy
	 */
	public function set_label( $label, $value, $strict = true ) {
		$labels = $this->get_labels();

		if ( $strict && ! isset( $labels[ $label ] ) ) {
			throw new \Exception( 'Invalid label name.' );
		}

		$labels[ $label ] = $value;
		$args['labels']   = $labels;
	}



	/**
	 * Get arg.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Arg name. (e.g. label, args, menu_position, etc )
	 *
	 * @return mixed|null
	 */
	public function get_arg( $name ) {
		$args = $this->get_args();

		$value = ( isset( $args[ $name ] ) ) ? $args[ $name ] : null;

		/**
		 * Filters a taxonomy argument.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value The taxonomy argument.
		 * @param string $name The argument name.
		 */
		return apply_filters( "masteriyo_taxonomy_{$this->taxonomy}_arg", $value, $name );
	}


	/**
	 * Get taxonomy args which includes labels and other args.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_args() {
		return new \WP_Error(
			'invalid-method',
			// translators: %s: Class method name.
			sprintf( __( "Method '%s' not implemented. Must be overridden in subclass.", 'masteriyo' ), __METHOD__ ),
			array( 'status' => 405 )
		);
	}

	/**
	 * Return all taxonomies.
	 *
	 * @since 1.5.19
	 *
	 * @static
	 *
	 * @return string[]
	 */
	public static function all() {
		/**
		 * Filter taxonomies register in masteriyo.
		 *
		 * @since 1.5.19
		 */
		$post_types = apply_filters(
			'masteriyo_taxonomies',
			array(
				self::COURSE_CATEGORY,
				self::COURSE_DIFFICULTY,
				self::COURSE_TAG,
				self::COURSE_VISIBILITY,
			)
		);

		return array_unique( $post_types );
	}
}
