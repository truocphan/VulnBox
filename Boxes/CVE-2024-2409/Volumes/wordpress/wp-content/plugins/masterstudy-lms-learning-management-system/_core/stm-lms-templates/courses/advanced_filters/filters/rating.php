<?php

$value = (!empty($_GET['rating'])) ? floatval($_GET['rating']) : '';

$ratings = array(
    array(
        'rate' => 3,
        'label' => esc_html__('3.0 & up', 'masterstudy-lms-learning-management-system')
    ),
    array(
        'rate' => 3.5,
        'label' => esc_html__('3.5 & up', 'masterstudy-lms-learning-management-system')
    ),
    array(
        'rate' => 4,
        'label' => esc_html__('4.0 & up', 'masterstudy-lms-learning-management-system')
    ),
    array(
        'rate' => 4.5,
        'label' => esc_html__('4.5 & up', 'masterstudy-lms-learning-management-system')
    ),
);

?>

<div class="stm_lms_courses__filter stm_lms_courses__rating">

    <div class="stm_lms_courses__filter_heading">
        <h3><?php esc_html_e('Rating', 'masterstudy-lms-learning-management-system'); ?></h3>
        <div class="toggler"></div>
    </div>

    <div class="stm_lms_courses__filter_content" style="display: none;">

        <?php foreach (array_reverse($ratings) as $rating): ?>
            <label>

                <span class="wpcfto_radio">
                    <input type="radio"
                           value="<?php echo floatval($rating['rate']); ?>"
                           <?php checked($value, $rating['rate']); ?>
                           name="rating">
                    <span class="wpcfto_radio__fake"></span>
                </span>

                <div class="wpcfto_radio__rating">
                    <div class="star-rating star-rating__big">
                        <span style="width: <?php echo round($rating['rate'] * 100 / 5, 2) ?>%">
                            <strong class="rating"><?php echo sanitize_text_field($rating['label']); ?></strong>
                        </span>
                    </div>
                    <span class="label--rating"><?php echo wp_kses_post($rating['label']); ?></span>
                </div>

            </label>
        <?php endforeach; ?>

    </div>

</div>
