<?php
/**
 * @var $id
 */

if (STM_LMS_Options::get_option('enable_sticky_price', false)):
    $price = get_post_meta($id, 'price', true);
    $sale_price = STM_LMS_Course::get_sale_price($id);

    if (empty($price) and !empty($sale_price)) {
        $price = $sale_price;
        $sale_price = '';
    }
    ?>

    <div class="stm_lms_course_sticky_panel__price">
        <h6><?php esc_html_e('Price:', 'masterstudy-lms-learning-management-system'); ?></h6>
        <?php STM_LMS_Templates::show_lms_template('global/price', compact('price', 'sale_price')); ?>
    </div>

<?php endif;