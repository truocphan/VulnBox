<?php
/**
 * @var $post_id
 * @var $item_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

stm_lms_register_style( 'assignment' );
stm_lms_register_script( 'assignment', array( 'vue.js', 'vue-resource.js' ) );

if ( ! empty( $item_id ) && class_exists( 'STM_LMS_Assignments' ) ) :

	/**
	 * 1 Assignment not started
	 * 2 Assignment in progress
	 * 3 Assignment on review
	 * 4 Assignment not passed
	 * 5 Assignment passed
	 */

	$number_of_assignments = STM_LMS_Assignments::number_of_assignments( $item_id );
	$passed                = STM_LMS_Assignments::has_passed_assignment( $item_id );
	$unpassed              = STM_LMS_Assignments::has_unpassed_assignment( $item_id );
	$reviewing             = STM_LMS_Assignments::has_reviewing_assignment( $item_id );
	$draft                 = STM_LMS_Assignments::has_draft_assignment( $item_id );


	if ( $passed ) {
		/*----5----*/
		STM_LMS_Templates::show_lms_template( 'course/parts/student_assignments/passed', compact( 'post_id', 'item_id', 'passed' ) );
	} elseif ( $reviewing ) {
		/*----3----*/
		STM_LMS_Templates::show_lms_template( 'course/parts/student_assignments/on_review', compact( 'post_id', 'item_id', 'reviewing' ) );
	} elseif ( $draft ) {
		/*----2----*/
		STM_LMS_Templates::show_lms_template( 'course/parts/student_assignments/progress', compact( 'post_id', 'item_id', 'draft' ) );
	} elseif ( $unpassed ) {
		/*----4----*/
		STM_LMS_Templates::show_lms_template( 'course/parts/student_assignments/unpassed', compact( 'post_id', 'item_id', 'unpassed' ) );
	} else {
		/*----1----*/
		STM_LMS_Templates::show_lms_template( 'course/parts/student_assignments/new', compact( 'post_id', 'item_id' ) );
	}
	?>

	<?php wp_reset_postdata(); ?>
	<?php
endif;
