<?php
/**
 * GamiPress rules engine for Masteriyo.
 *
 * @since 1.6.15
 */

namespace Masteriyo\Addons\GamiPressIntegration;

/**
 * GamiPress rules engine for Masteriyo.
 *
 * @since 1.6.15
 */
class RulesEngine {

	/**
	 * Initialize.
	 *
	 * @since 1.6.15
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.6.15
	 */
	protected function init_hooks() {
		add_filter( 'user_has_access_to_achievement', array( $this, 'check_if_user_has_access_to_achievement' ), 10, 6 );
	}

	/**
	 * Checks if an user is allowed to work on a given requirement.
	 *
	 * @since 1.6.15
	 *
	 * @param boolean $return The default return value.
	 * @param integer $user_id The given user's ID.
	 * @param integer $requirement_id The given requirement's post ID.
	 * @param string $trigger The trigger triggered.
	 * @param integer $site_id The site id.
	 * @param array $args Arguments of this trigger.
	 *
	 * @return boolean True if user has access to the requirement, false otherwise.
	 */
	public function check_if_user_has_access_to_achievement( $return = false, $user_id = 0, $requirement_id = 0, $trigger = '', $site_id = 0, $args = array() ) {
		if ( ! function_exists( 'gamipress_get_requirement_types_slugs' ) ) {
			return $return;
		}

		// If we're not working with a requirement, bail here.
		if ( ! in_array( get_post_type( $requirement_id ), gamipress_get_requirement_types_slugs(), true ) ) {
			return $return;
		}

		// Check if user has access to the achievement ($return will be false if user has exceed the limit or achievement is not published yet)
		if ( ! $return ) {
			return $return;
		}

		// If it's category trigger, rules engine needs to check if field name and values matches required ones.
		if (
			in_array(
				$trigger,
				array(
					'masteriyo_gamipress_complete_quiz_course_category',
					'masteriyo_gamipress_pass_quiz_course_category',
					'masteriyo_gamipress_fail_quiz_course_category',
					'masteriyo_gamipress_complete_lesson_course_category',
					'masteriyo_gamipress_complete_course_category',
					'masteriyo_gamipress_enroll_course_category',
				),
				true
			)
		) {
			$course_category = '';

			if (
				in_array(
					$trigger,
					array(
						'masteriyo_gamipress_pass_quiz_course_category',
						'masteriyo_gamipress_fail_quiz_course_category',
					),
					true
				)
			) {
				$course_category = $args[4];
			} elseif (
				in_array(
					$trigger,
					array(
						'masteriyo_gamipress_complete_lesson_course_category',
						'masteriyo_gamipress_complete_quiz_course_category',
					),
					true
				)
			) {
				$course_category = $args[3];
			} elseif (
				in_array(
					$trigger,
					array(
						'masteriyo_gamipress_complete_course_category',
						'masteriyo_gamipress_enroll_course_category',
					),
					true
				)
			) {
				$course_category = $args[2];
			}

			$required_course_category = get_post_meta( $requirement_id, '_masteriyo_category', true );

			// Check if category matches the required one.
			if ( is_array( $course_category ) ) {
				$return = in_array( $required_course_category, $course_category, true );
			} else {
				$return = $course_category === $required_course_category;
			}
		}

		return $return;
	}
}
