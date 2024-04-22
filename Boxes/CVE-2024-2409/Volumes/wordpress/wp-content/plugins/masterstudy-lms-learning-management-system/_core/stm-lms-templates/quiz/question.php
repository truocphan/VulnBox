<?php
/**
 * @var $item_id
 * @var $last_answers
 * @var $question_index
 * @var $show_answers
 */
$question_id = get_the_ID();
$question_itself = get_the_title();
$question = STM_LMS_Helpers::parse_meta_field($question_id);
if (empty($question['type'])) $question['type'] = 'single_choice';
$question['user_answer'] = (!empty($last_answers[$question_id])) ? $last_answers[$question_id] : array();
$show_answers = (!empty($show_answers)) ? $show_answers : false;

if (!empty($question['type']) and !empty($question['answers']) and !empty($question_itself)) {
    STM_LMS_Templates::show_lms_template(
        'questions/wrapper',
        array_merge($question, compact('item_id', 'last_answers', 'question_index', 'show_answers', 'question_id')));
}