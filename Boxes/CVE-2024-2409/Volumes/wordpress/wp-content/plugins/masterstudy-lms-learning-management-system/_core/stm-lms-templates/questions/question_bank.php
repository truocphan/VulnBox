<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $last_answers
 * @var string $item_id
 * @var string $question_id
 */

$bank_question_id = $question_id;

if (!empty($answers[0]) && !empty($answers[0]['categories']) && !empty($answers[0]['number'])) :?>
    <div class="stm_lms_question_bank">
        <?php $number = $answers[0]['number'];
        $categories = wp_list_pluck($answers[0]['categories'], 'slug');
        $questions = get_post_meta($item_id, 'questions', true);
        $questions = (!empty($questions)) ? explode(',', $questions) : array();

        $args = array(
            'post_type' => 'stm-questions',
            'posts_per_page' => $number,
            'post__not_in' => $questions,
            'meta_query' => array(
                array(
                    'key' => 'type',
                    'value' => 'question_bank',
                    'compare' => '!=',
                ),
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'stm_lms_question_taxonomy',
                    'field' => 'slug',
                    'terms' => $categories,
                ),
            )
        );
        $random = get_post_meta($item_id, 'random_questions', true);

        if(!empty($random) and $random === 'on') {
            $args['orderby'] = 'rand';
        }

        $q = new WP_Query($args);

        if ($q->have_posts()) {
            $bank_category = wp_list_pluck($answers[0]['categories'], 'term_id');
            $bank_category = array_values($bank_category);

            while ($q->have_posts()) {
                $q->the_post();

                $question_id = get_the_ID();
                $question_itself = get_the_title();
                $question = STM_LMS_Helpers::parse_meta_field($question_id);
                ?>
                <input type="hidden" name="questions_sequency[<?php echo esc_attr($bank_question_id); ?>][]" value="<?php echo esc_attr($question_id); ?>" />
                <?php
                $number = $q->found_posts - 1;

                if (empty($question['type'])) $question['type'] = 'single_choice';
                $question['user_answer'] = (!empty($last_answers[$question_id])) ? $last_answers[$question_id] : array();
                if (!empty($question['type']) and !empty($question['answers']) and !empty($question_itself)) {
                    STM_LMS_Templates::show_lms_template('questions/wrapper', array_merge($question, compact('item_id', 'last_answers', 'number')));
                }

            }
        }

        wp_reset_postdata();

        ?>
    </div>


<?php endif;