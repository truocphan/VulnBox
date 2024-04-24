<?php
/**
 * GamiPress Integration addon main class.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration;

use Masteriyo\Addons\GamiPressIntegration\Listeners\CourseCompletedListener;
use Masteriyo\Addons\GamiPressIntegration\Listeners\LessonCompletedListener;
use Masteriyo\Addons\GamiPressIntegration\Listeners\NewEnrollmentListener;
use Masteriyo\Addons\GamiPressIntegration\Listeners\QuizAttemptStatusChangeListener;
use Masteriyo\Addons\GamiPressIntegration\Listeners\QuizCompletedListener;
use Masteriyo\Taxonomy\Taxonomy;

/**
 * GamiPress Integration addon main class.
 *
 * @since 1.6.15
 */
class GamiPressIntegrationAddon {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		$this->init_hooks();
		$this->init_event_listeners();

		( new GlobalSetting() )->init();
		( new Triggers() )->init();
		( new RulesEngine() )->init();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.6.15
	 */
	public function init_hooks() {
		add_action( 'gamipress_requirement_ui_html_after_achievement_post', array( $this, 'requirement_ui_fields' ), 10, 2 );
		add_action( 'gamipress_ajax_update_requirement', array( $this, 'ajax_update_requirement' ), 10, 2 );
		add_filter( 'gamipress_requirement_object', array( $this, 'requirement_object' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_styles' ) );
		add_filter( 'masteriyo_localized_public_scripts', array( $this, 'localize_public_scripts' ) );
		add_filter( 'masteriyo_localized_admin_scripts', array( $this, 'localize_admin_scripts' ) );
	}

	/**
	 * Initialize event listeners.
	 *
	 * @since 1.6.15
	 */
	public function init_event_listeners() {
		( new QuizCompletedListener() )->init();
		( new QuizAttemptStatusChangeListener() )->init();
		( new CourseCompletedListener() )->init();
		( new LessonCompletedListener() )->init();
		( new NewEnrollmentListener() )->init();
	}

	/**
	 * Localize public scripts.
	 *
	 * @since 1.6.15
	 *
	 * @param array $scripts Array of scripts.
	 *
	 * @return array
	 */
	public function localize_public_scripts( $scripts ) {
		$point_types       = Helper::get_point_types();
		$rank_types        = Helper::get_rank_types();
		$achievement_types = Helper::get_achievement_types();
		$settings          = new GlobalSetting();
		$ui_placements     = (array) $settings->get( 'ui_placements' );

		$scripts['account']['data']['gamipress'] = array(
			'point_types'       => $point_types,
			'rank_types'        => $rank_types,
			'achievement_types' => $achievement_types,
			'ui_placements'     => $ui_placements,
		);
		$scripts['learn']['data']['gamipress']   = array(
			'point_types'       => $point_types,
			'rank_types'        => $rank_types,
			'achievement_types' => $achievement_types,
			'ui_placements'     => $ui_placements,
		);

		return $scripts;
	}

	/**
	 * Localize admin scripts.
	 *
	 * @since 1.6.15
	 *
	 * @param array $scripts Array of scripts.
	 *
	 * @return array
	 */
	public function localize_admin_scripts( $scripts ) {
		$point_types       = Helper::get_point_types();
		$rank_types        = Helper::get_rank_types();
		$achievement_types = Helper::get_achievement_types();

		$scripts['backend']['data']['gamipress'] = array(
			'point_types'       => $point_types,
			'rank_types'        => $rank_types,
			'achievement_types' => $achievement_types,
		);

		return $scripts;
	}

	/**
	 * Custom field on requirements UI.
	 *
	 * @since 1.6.15
	 *
	 * @param mixed $requirement_id
	 * @param integer $post_id
	 */
	public function requirement_ui_fields( $requirement_id, $post_id ) {
		$selected = get_post_meta( $requirement_id, '_masteriyo_category', true );
		$terms    = get_terms(
			array(
				'taxonomy'   => Taxonomy::COURSE_CATEGORY,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			$terms = array();
		}

		?>
		<select class="select-masteriyo-category">
			<?php foreach ( $terms as $term ) : ?>
				<option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $selected, $term->term_id ); ?>>
					<?php echo esc_html( $term->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Save custom field values from requirements UI.
	 *
	 * @since 1.6.15
	 *
	 * @param mixed $requirement_id
	 * @param array $requirement
	 */
	public function ajax_update_requirement( $requirement_id, $requirement ) {
		$trigger_type                = isset( $requirement['trigger_type'] ) ? $requirement['trigger_type'] : '';
		$trigger_types_with_category = array(
			'masteriyo_gamipress_complete_quiz_course_category',
			'masteriyo_gamipress_pass_quiz_course_category',
			'masteriyo_gamipress_fail_quiz_course_category',
			'masteriyo_gamipress_complete_lesson_course_category',
			'masteriyo_gamipress_complete_course_category',
			'masteriyo_gamipress_enroll_course_category',
		);

		if ( in_array( $trigger_type, $trigger_types_with_category, true ) ) {
			update_post_meta( $requirement_id, '_masteriyo_category', $requirement['masteriyo_category'] );
		}
	}

	/**
	 * Add custom field values to the requirement object.
	 *
	 * @since 1.6.15
	 *
	 * @param array $requirement
	 * @param mixed $requirement_id
	 *
	 * @return array
	 */
	public function requirement_object( $requirement, $requirement_id ) {
		$trigger_type                = isset( $requirement['trigger_type'] ) ? $requirement['trigger_type'] : '';
		$trigger_types_with_category = array(
			'masteriyo_gamipress_complete_quiz_course_category',
			'masteriyo_gamipress_pass_quiz_course_category',
			'masteriyo_gamipress_fail_quiz_course_category',
			'masteriyo_gamipress_complete_lesson_course_category',
			'masteriyo_gamipress_complete_course_category',
			'masteriyo_gamipress_enroll_course_category',
		);

		if ( in_array( $trigger_type, $trigger_types_with_category, true ) ) {
			$requirement['masteriyo_category'] = get_post_meta( $requirement_id, '_masteriyo_category', true );
		}

		return $requirement;
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.6.15
	 */
	public function admin_enqueue_scripts_styles() {
		global $post_type;

		if ( ! function_exists( 'gamipress_get_achievement_types_slugs' ) ) {
			return;
		}

		$reward_post_types = array_merge(
			array( 'points-type' ),
			gamipress_get_achievement_types_slugs(),
			gamipress_get_rank_types_slugs()
		);

		wp_register_script(
			'masteriyo-gamipress-rewards-admin',
			plugins_url( 'js/rewards-admin.js', MASTERIYO_GAMIPRESS_INTEGRATION_FILE ),
			array( 'jquery' ),
			MASTERIYO_VERSION,
			true
		);

		if ( in_array( $post_type, $reward_post_types, true ) ) {
			wp_enqueue_script( 'masteriyo-gamipress-rewards-admin' );
		}
	}
}
