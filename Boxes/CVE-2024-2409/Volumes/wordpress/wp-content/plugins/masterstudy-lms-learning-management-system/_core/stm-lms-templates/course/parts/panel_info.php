<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php $number = (!empty($number)) ? $number : ''; ?>

<div class="single_product_after_title">
    <div class="clearfix">

        <div class="pull-left meta_pull">

            <?php STM_LMS_Templates::show_lms_template('course/parts/panel_info/teacher'); ?>

            <?php STM_LMS_Templates::show_lms_template('course/parts/panel_info/categories', array('number' => $number)); ?>

        </div>


        <div class="pull-right xs-comments-left">
            <div class="stm_lms_course__panel_rate">
				<?php STM_LMS_Templates::show_lms_template('course/parts/panel_info/rate'); ?>
            </div>
        </div>


    </div>

</div>
