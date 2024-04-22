<?php

$values = (!empty($_GET['subcategory'])) ? $_GET['subcategory'] : array();
$parents = get_transient('stm_lms_parent_categories');

if (!empty($parents)) : ?>

    <div class="stm_lms_courses__filter stm_lms_courses__subcategory" style="display: none;">

        <div class="stm_lms_courses__filter_heading">
            <h3><?php esc_html_e('Subcategory', 'masterstudy-lms-learning-management-system'); ?></h3>
            <div class="toggler"></div>
        </div>

        <div class="stm_lms_courses__filter_content" style="display: none;">

            <?php foreach ($parents as $parent):

                $terms = get_terms(
                    "stm_lms_course_taxonomy",
                    array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'child_of' => $parent
                    )
                );

                ?>

                <div class="stm_lms_courses__subcategory_item stm_lms_courses__subcategory_<?php echo esc_attr($parent) ?>" style="display: none;">

                    <h5 style="margin-top: 10px;"><?php echo esc_html(get_term($parent)->name); ?></h5>

                    <?php foreach ($terms as $term): ?>

                        <div class="stm_lms_courses__filter_category">
                            <label class="stm_lms_styled_checkbox">
                                <span class="stm_lms_styled_checkbox__inner">
                                    <input type="checkbox"
                                           <?php if (in_array(intval($term->term_id), $values)) echo 'checked="checked"'; ?>
                                           value="<?php echo intval($term->term_id); ?>"
                                           name="subcategory[]"/>
                                    <span><i class="fa fa-check"></i> </span>
                                </span>
                                <span><?php echo esc_html($term->name . ' (' . $term->count . ')'); ?></span>
                            </label>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif;