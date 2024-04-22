<?php
/**
 * @var string $type
 * @var array $answers
 * @var array $user_answer
 * @var string $question
 * @var string $question_explanation
 * @var string $question_hint
 * @var string $item_id
 */
$question_id = get_the_ID();
$show_correct_answer = get_post_meta($item_id, 'correct_answer', true);
$answers_for = (!empty($answers)) ? wp_list_pluck($answers, 'text') : array();

$uniq_id = uniqid('quiz_');
$uniq_id_script = "var " . $uniq_id . " = " . json_encode(array_map('strtolower', $answers_for));

stm_lms_register_style('keywords_question');

$user_answers = array();
if (!empty($user_answer['user_answer'])) {
    $user_answers = explode('[stm_lms_sep]', str_replace('[stm_lms_keywords]', '', $user_answer['user_answer']));
}

if (!empty($answers)): ?>

    <div class="stm_lms_question_item_keywords" data-quiz="<?php echo esc_attr($uniq_id); ?>">

        <div class="stm_lms_question_item_keywords__answers">
            <?php foreach ($answers as $i => $correct_answer):
                $is_correct = (!empty($user_answers[$i]) && strtolower($user_answers[$i]) === strtolower($correct_answer['text'])) ? 'correct' : 'incorrect';
                ?>
                <div class="stm_lms_question_item_keywords__answer stm_lms_question_item_keywords__answer_<?php echo esc_attr($i); ?> <?php echo esc_attr($is_correct); ?>">
                    <h5 class="label_keyword">
                        <?php printf(esc_html__('Keyword #%s', 'masterstudy-lms-learning-management-system'), $i + 1); ?>
                    </h5>
                    <div class="value">
                        <span><?php
                            if($show_correct_answer){
                                echo esc_html($correct_answer['text']);
                            }
                            elseif(!empty($user_answers[$i])) {
                                echo esc_html($user_answers[$i]);
                            }
                            ?></span>
                        <?php if (!empty($correct_answer['explain'])): ?>
                            <div class="stm-lms-single-answer__hint">
                                <i class="fa fa-info"></i>
                                <div class="stm-lms-single-answer__hint_text">
                                    <div class="inner">
                                        <?php echo wp_kses_post($correct_answer['explain']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?php endif; ?>