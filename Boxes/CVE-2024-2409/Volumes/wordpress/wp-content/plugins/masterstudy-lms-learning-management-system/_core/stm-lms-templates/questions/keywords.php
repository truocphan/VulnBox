<?php
/**
 * @var string $type
 * @var array $answers
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 */
$question_id = get_the_ID();

$answers_for = (!empty($answers)) ? wp_list_pluck($answers, 'text') : array();

$uniq_id = uniqid('quiz_');
$uniq_id_script = "var " . $uniq_id . " = " . json_encode(array_map('strtolower', $answers_for));

stm_lms_register_style('keywords_question');
stm_lms_register_script('keywords_question', array(), false, $uniq_id_script);

if (!empty($answers)): ?>

    <div class="stm_lms_question_item_keywords" data-quiz="<?php echo esc_attr($uniq_id); ?>">

        <input type="text"
               class="enter_keyword_to_fill"
               placeholder="<?php esc_attr_e('Enter keyword', 'masterstudy-lms-learning-management-system'); ?>"/>
        <span class="flying_word"></span>
        <input type="text" class="stm_lms_question_item_keywords__input" name="<?php echo esc_attr($question_id); ?>"/>

        <div class="stm_lms_question_item_keywords__answers">
            <?php foreach ($answers as $k => $answer): ?>
                <div class="stm_lms_question_item_keywords__answer stm_lms_question_item_keywords__answer_<?php echo esc_attr($k); ?>">
                    <h5 class="label_keyword"><?php printf(esc_html__('Keyword #%s', 'masterstudy-lms-learning-management-system'), $k + 1); ?></h5>
                    <div class="value"></div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?php endif; ?>