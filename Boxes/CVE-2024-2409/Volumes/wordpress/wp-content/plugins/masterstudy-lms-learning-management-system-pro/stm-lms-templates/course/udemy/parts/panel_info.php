<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="single_product_after_title">

	<?php STM_LMS_Templates::show_lms_template( 'course/udemy/parts/panel_info/teacher', array( 'udemy_meta' => $udemy_meta ) ); ?>

	<?php STM_LMS_Templates::show_lms_template( 'course/udemy/parts/panel_info/enrolled', array( 'udemy_meta' => $udemy_meta ) ); ?>

	<?php STM_LMS_Templates::show_lms_template( 'course/udemy/parts/panel_info/languages', array( 'udemy_meta' => $udemy_meta ) ); ?>

</div>
