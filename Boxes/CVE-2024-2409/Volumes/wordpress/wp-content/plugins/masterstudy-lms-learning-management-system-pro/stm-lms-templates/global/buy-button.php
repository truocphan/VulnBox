<?php
/**
 * @var $course_id
 * @var $item_id
 */

$item_id = ( isset( $item_id ) ) ? $item_id : 0;

STM_LMS_Templates::show_lms_template( 'global/buy-button/mixed', compact( 'course_id', 'item_id' ) );
